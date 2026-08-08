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

echo "<h2>Cadastro realizado com sucesso!</h2>";
echo "<p>Nome: $nome</p>";
echo "<p>Email: $email</p>";
echo "<p>Telefone: $telefone</p>";
?>
<p><a href="javascript:history.go(-1)">Voltar para página anterior</a></p>
    
    
</body>
</html>