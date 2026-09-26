<?php
/**
 * includes/admin_header.php
 * Cabeçalho do painel administrativo. Só é incluído em páginas que já
 * chamaram exigirLoginAdmin() antes.
 *
 * Variável esperada: $tituloPagina
 */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title><?php echo htmlspecialchars($tituloPagina ?? 'Admin - Construlink'); ?></title>
</head>
<body>
    <header>
        <div class="logo">
            <h1>Construlink Admin</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.html">Produtos</a></li>
                <li><a href="produto-form.html">+ Novo produto</a></li>
                <li><a href="../index.html">Ver site</a></li>
                <li><a href="logout.html">Sair</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="painel-admin">
