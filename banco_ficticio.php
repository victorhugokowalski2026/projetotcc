<?php
/**
 * banco_ficticio.php
 * "Banco de dados" fictício da Construlink.
 * - Catálogo de produtos (com estoque) fica em produtos.json
 * - Carrinho de compras fica na sessão ($_SESSION['carrinho'])
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = []; // formato: [ produto_id => quantidade ]
}

define('ARQUIVO_PRODUTOS', __DIR__ . '/../produtos.json');

/**
 * Lê o catálogo do produtos.json e devolve indexado por id.
 * Usa flock() para não ler no meio de uma escrita de outro processo.
 */
function obterCatalogoProdutos() {
    if (!file_exists(ARQUIVO_PRODUTOS)) {
        return [];
    }

    $fp = fopen(ARQUIVO_PRODUTOS, 'r');
    if (!$fp) {
        return [];
    }

    $catalogo = [];
    if (flock($fp, LOCK_SH)) {
        $conteudo = stream_get_contents($fp);
        $lista = json_decode($conteudo, true);
        if (is_array($lista)) {
            foreach ($lista as $produto) {
                $catalogo[$produto['id']] = $produto;
            }
        }
        flock($fp, LOCK_UN);
    }
    fclose($fp);

    return $catalogo;
}

/**
 * Regrava o produtos.json inteiro (lock exclusivo, evita corrida entre pedidos).
 */
function salvarCatalogoProdutos($catalogo) {
    $fp = fopen(ARQUIVO_PRODUTOS, 'c+');
    if (!$fp) {
        return false;
    }

    $ok = false;
    if (flock($fp, LOCK_EX)) {
        ftruncate($fp, 0);
        rewind($fp);
        $lista = array_values($catalogo);
        fwrite($fp, json_encode($lista, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        fflush($fp);
        flock($fp, LOCK_UN);
        $ok = true;
    }
    fclose($fp);

    return $ok;
}

/**
 * Retorna apenas os produtos ativos (para exibir nas listagens).
 */
function listarProdutosAtivos() {
    $catalogo = obterCatalogoProdutos();
    return array_filter($catalogo, function ($p) {
        return !empty($p['ativo']);
    });
}

/**
 * Busca um produto pelo id. Retorna null se não existir ou estiver inativo.
 */
function buscarProdutoPorId($id) {
    $catalogo = obterCatalogoProdutos();
    if (isset($catalogo[$id]) && !empty($catalogo[$id]['ativo'])) {
        return $catalogo[$id];
    }
    return null;
}

/**
 * Quantidade em estoque de um produto (0 se não existir).
 */
function estoqueDisponivel($idProduto) {
    $produto = buscarProdutoPorId($idProduto);
    return $produto ? (int) ($produto['estoque'] ?? 0) : 0;
}

/**
 * Adiciona um produto ao carrinho, respeitando o estoque disponível.
 * Retorna true se conseguiu adicionar a quantidade pedida (ou o máximo possível),
 * false se o produto está esgotado.
 */
function adicionarAoCarrinho($idProduto, $quantidade = 1) {
    if ($quantidade < 1) {
        $quantidade = 1;
    }
    if (buscarProdutoPorId($idProduto) === null) {
        return false;
    }

    $disponivel = estoqueDisponivel($idProduto);
    $noCarrinho = $_SESSION['carrinho'][$idProduto] ?? 0;

    if ($disponivel <= $noCarrinho) {
        return false; // já está no limite do estoque
    }

    // não deixa passar do estoque disponível
    $novaQuantidade = min($noCarrinho + $quantidade, $disponivel);
    $_SESSION['carrinho'][$idProduto] = $novaQuantidade;

    return true;
}

/**
 * Remove um produto do carrinho.
 */
function removerDoCarrinho($idProduto) {
    unset($_SESSION['carrinho'][$idProduto]);
}

/**
 * Atualiza a quantidade de um item já existente no carrinho,
 * sem deixar passar do estoque disponível.
 */
function atualizarQuantidadeCarrinho($idProduto, $quantidade) {
    if ($quantidade < 1) {
        removerDoCarrinho($idProduto);
        return;
    }
    if (isset($_SESSION['carrinho'][$idProduto])) {
        $disponivel = estoqueDisponivel($idProduto);
        $_SESSION['carrinho'][$idProduto] = min($quantidade, $disponivel);
    }
}

/**
 * Soma total de itens no carrinho (para o contador no menu).
 */
function contarItensCarrinho() {
    if (empty($_SESSION['carrinho'])) {
        return 0;
    }
    return array_sum($_SESSION['carrinho']);
}

/**
 * Retorna os itens do carrinho já com dados do produto (nome, imagem, preço,
 * estoque disponível) e o subtotal calculado, prontos para exibir em carrinho.php.
 */
function obterItensCarrinhoDetalhados() {
    $itens = [];
    if (empty($_SESSION['carrinho'])) {
        return $itens;
    }

    foreach ($_SESSION['carrinho'] as $idProduto => $quantidade) {
        $produto = buscarProdutoPorId($idProduto);
        if ($produto === null) {
            continue; // produto pode ter sido removido do catálogo
        }
        $itens[] = [
            "id"          => $produto['id'],
            "nome"        => $produto['nome'],
            "imagem"      => $produto['imagem'],
            "preco"       => $produto['preco'],
            "quantidade"  => $quantidade,
            "subtotal"    => $produto['preco'] * $quantidade,
            "estoque"     => (int) ($produto['estoque'] ?? 0),
        ];
    }

    return $itens;
}

/**
 * Confirma a compra: baixa o estoque de cada item do carrinho e grava no
 * produtos.json. Retorna true se conseguiu finalizar, false se algum item
 * não tinha mais estoque suficiente (nesse caso nada é alterado).
 */
function finalizarCompra() {
    if (empty($_SESSION['carrinho'])) {
        return false;
    }

    $catalogo = obterCatalogoProdutos();

    // valida tudo antes de mexer em qualquer coisa
    foreach ($_SESSION['carrinho'] as $idProduto => $quantidade) {
        if (!isset($catalogo[$idProduto]) || ($catalogo[$idProduto]['estoque'] ?? 0) < $quantidade) {
            return false;
        }
    }

    // baixa o estoque
    foreach ($_SESSION['carrinho'] as $idProduto => $quantidade) {
        $catalogo[$idProduto]['estoque'] -= $quantidade;
    }

    salvarCatalogoProdutos($catalogo);
    $_SESSION['carrinho'] = [];

    return true;
}
