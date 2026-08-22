<?php
// Recebe os dados do formulário
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$telefone = $_POST['telefone'] ?? '';

// Monta o array com os dados
$novoUsuario = [
    "nome" => $nome,
    "email" => $email,
    "telefone" => $telefone
];

// Lê o arquivo JSON existente
$arquivo = 'usuarios.json';
if (file_exists($arquivo)) {
    $dados = json_decode(file_get_contents($arquivo), true);
} else {
    $dados = [];
}

// Adiciona o novo usuário
$dados[] = $novoUsuario;

// Salva de volta no JSON
file_put_contents($arquivo, json_encode($dados, JSON_PRETTY_PRINT));
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Cadastro realizado - Construlink</title>
</head>
<body>
    <header>
        <div class="logo">
            <h1>Construlink</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.html">Início</a></li>
                <li><a href="produtos.html">Produtos</a></li>
                <li><a href="categorias.html">Categorias</a></li>
                <li><a href="promocoes.html">Promoções</a></li>
                <li><a href="sobre.html">Sobre</a></li>
                <li><a href="contato.html" class="ativo">Contato</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="contato">
            <h2>Cadastro realizado com sucesso!</h2>
            <p>Nome: <?php echo htmlspecialchars($nome); ?></p>
            <p>Email: <?php echo htmlspecialchars($email); ?></p>
            <p>Telefone: <?php echo htmlspecialchars($telefone); ?></p>
            <p><a href="contato.html">Voltar</a></p>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Construlink - Todos os direitos reservados.</p>
    </footer>
</body>
</html>
