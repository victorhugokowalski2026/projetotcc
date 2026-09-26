<?php
/**
 * includes/header.php
 * Cabeçalho e menu, reaproveitados em todas as páginas.
 *
 * Variáveis esperadas (definidas pela página antes do require):
 *   $tituloPagina -> título da aba do navegador
 *   $paginaAtiva  -> nome da página atual, para marcar o menu (ex: 'produtos')
 *   $totalItens   -> quantidade de itens no carrinho
 *
 * >>> Equipe de HTML/CSS: só mexer daqui pra baixo, na parte visual.
 *     Não é necessário entender o que tem em includes/banco_ficticio.php.
 */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?php echo htmlspecialchars($tituloPagina ?? 'Construlink'); ?></title>
</head>
<body>
    <header>
        <div class="logo">
            <h1>Construlink</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.html" class="<?php echo ($paginaAtiva ?? '') === 'index' ? 'ativo' : ''; ?>">Início</a></li>
                <li><a href="produtos.html" class="<?php echo ($paginaAtiva ?? '') === 'produtos' ? 'ativo' : ''; ?>">Produtos</a></li>
                <li><a href="categorias.html" class="<?php echo ($paginaAtiva ?? '') === 'categorias' ? 'ativo' : ''; ?>">Categorias</a></li>
                <li><a href="promocoes.html" class="<?php echo ($paginaAtiva ?? '') === 'promocoes' ? 'ativo' : ''; ?>">Promoções</a></li>
                <li><a href="sobre.html" class="<?php echo ($paginaAtiva ?? '') === 'sobre' ? 'ativo' : ''; ?>">Sobre</a></li>
                <li><a href="contato.html" class="<?php echo ($paginaAtiva ?? '') === 'contato' ? 'ativo' : ''; ?>">Contato</a></li>
                <li><a href="carrinho.html" class="<?php echo ($paginaAtiva ?? '') === 'carrinho' ? 'ativo' : ''; ?>">Carrinho (<?php echo (int) ($totalItens ?? 0); ?>)</a></li>
            </ul>
        </nav>
    </header>

    <main>
