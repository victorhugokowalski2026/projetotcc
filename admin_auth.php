<?php
/**
 * includes/admin_auth.php
 * Login simples do painel administrativo (usuário/senha fixos + sessão).
 *
 * IMPORTANTE: troque USUARIO/SENHA abaixo antes de usar o site fora
 * do ambiente de estudo/teste. Isso é uma autenticação básica, só
 * pra não deixar a área de admin 100% aberta pra qualquer visitante.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ADMIN_USUARIO', 'admin');
define('ADMIN_SENHA', 'construlink123');

/**
 * true se o admin já fez login nessa sessão.
 */
function adminEstaLogado() {
    return !empty($_SESSION['admin_logado']);
}

/**
 * Chama no topo de toda página do admin (exceto login.html).
 * Se não estiver logado, manda pro login e encerra a execução.
 */
function exigirLoginAdmin() {
    if (!adminEstaLogado()) {
        header('Location: login.html');
        exit;
    }
}

/**
 * Confere usuário/senha. Se bater, marca a sessão como logada.
 */
function autenticarAdmin($usuario, $senha) {
    if ($usuario === ADMIN_USUARIO && $senha === ADMIN_SENHA) {
        $_SESSION['admin_logado'] = true;
        return true;
    }
    return false;
}

function sairAdmin() {
    unset($_SESSION['admin_logado']);
}
