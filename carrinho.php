<?php
session_start();
require_once 'includes/banco_ficticio.php';

$mensagem = '';
$erro = '';

// Adicionar produto ao carrinho (vem do botão "Comprar" das páginas de produtos)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    $idProduto = intval($_POST['produto_id']);
    $quantidade = isset($_POST['quantidade']) ? intval($_POST['quantidade']) : 1;
    $ok = adicionarAoCarrinho($idProduto, $quantidade);

    $origem = $_POST['origem'] ?? 'produtos.php';
    $parametro = $ok ? 'adicionado=1' : 'semestoque=1';
    header('Location: ' . $origem . '?' . $parametro);
    exit;
}

// Remover produto do carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'remover') {
    removerDoCarrinho(intval($_POST['produto_id']));
    header('Location: carrinho.php');
    exit;
}

// Atualizar quantidade de um item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'atualizar') {
    atualizarQuantidadeCarrinho(intval($_POST['produto_id']), intval($_POST['quantidade']));
    header('Location: carrinho.php');
    exit;
}

// Finalizar compra: agora baixa o estoque de verdade
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'finalizar') {
    if (finalizarCompra()) {
        $mensagem = 'Compra simulada finalizada com sucesso! Obrigado pela preferência.';
    } else {
        $erro = 'Não foi possível finalizar: um ou mais itens do carrinho não têm mais estoque suficiente. Ajuste as quantidades e tente novamente.';
    }
}

$itens = obterItensCarrinhoDetalhados();
$total = array_sum(array_column($itens, 'subtotal'));
$totalItens = contarItensCarrinho();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Carrinho - Construlink</title>
</head>
<body>
    <header>
        <div class="logo">
            <h1>Construlink</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="produtos.php">Produtos</a></li>
                <li><a href="categorias.php">Categorias</a></li>
                <li><a href="promocoes.php">Promoções</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                <li><a href="contato.php">Contato</a></li>
                <li><a href="carrinho.php" class="ativo">Carrinho (<?php echo $totalItens; ?>)</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="carrinho">
            <h2>Meu Carrinho</h2>

            <?php if ($mensagem): ?>
                <p class="mensagem-sucesso"><?php echo htmlspecialchars($mensagem); ?></p>
            <?php endif; ?>

            <?php if ($erro): ?>
                <p class="mensagem-erro"><?php echo htmlspecialchars($erro); ?></p>
            <?php endif; ?>

            <?php if (empty($itens)): ?>
                <p>Seu carrinho está vazio. <a href="produtos.php">Ver produtos</a></p>
            <?php else: ?>
                <table class="tabela-carrinho">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itens as $item): ?>
                            <tr>
                                <td class="produto-nome">
                                    <img src="<?php echo htmlspecialchars($item['imagem']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>">
                                    <?php echo htmlspecialchars($item['nome']); ?>
                                    <?php if ($item['quantidade'] >= $item['estoque']): ?>
                                        <span class="aviso-estoque">últimas unidades</span>
                                    <?php endif; ?>
                                </td>
                                <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                                <td>
                                    <form action="carrinho.php" method="post" class="form-quantidade">
                                        <input type="hidden" name="acao" value="atualizar">
                                        <input type="hidden" name="produto_id" value="<?php echo $item['id']; ?>">
                                        <input type="number" name="quantidade" value="<?php echo $item['quantidade']; ?>" min="1" max="<?php echo $item['estoque']; ?>" onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td>R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></td>
                                <td>
                                    <form action="carrinho.php" method="post">
                                        <input type="hidden" name="acao" value="remover">
                                        <input type="hidden" name="produto_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="btn-remover">Remover</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="carrinho-total">
                    <strong>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></strong>
                </div>

                <form action="carrinho.php" method="post">
                    <input type="hidden" name="acao" value="finalizar">
                    <button type="submit" class="oauthButton">Finalizar Compra</button>
                </form>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Construlink - Todos os direitos reservados.</p>
    </footer>
</body>
</html>
