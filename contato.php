<?php  
/**
 * ARQUIVO: includes/banco_ficticio.php
 * OBJETIVO: Centralizar o acesso aos dados do sistema.
 * No futuro, mudaremos o miolo destas funções para conectar ao MySQL.
 */

// Função auxiliar interna para ler o arquivo JSON e devolver um array
function lerBancoJson() {
    $caminho = "data/produtos.json";
    
    //Verificação de segurança caso o arquivo suma por acidente
    if (!file_exists($caminho)) {
      return[];
    }
    $conteudo = file_get_contents($caminho);
    return json_decode($conteudo, true)??[];
}
function listarProdutos() {
    return lerBancoJson();
}

//Busca e retorna apenas UM produto pelo ID' ou null se não encontrar
function buscarProdutosPorID($id) { 
    $produtos = lerBancoJson();

    foreach ($produtos as $p ) {
        if ($p['id'] == $id) {
        return $p; // Encontrou o produto, retorna ele imediatamente
        }
    }
}
?>

