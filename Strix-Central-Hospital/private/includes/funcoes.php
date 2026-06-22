<?php require_once __DIR__ . '../../../config/config.php'?>

<?php
// Inicia a sessão se ainda não estiver iniciada
function start_session() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// Verifica se a sessão do utilizador está ativa
function check_session() {
    return isset($_SESSION['utilizador']);
}

// Redireciona automaticamente se não houver sessão iniciada
function redirect_if_not_logged($redirect_to = '../public/login.php') {
    start_session();
    if (!check_session()) {
        header("Location: $redirect_to");
        exit;
    }
}
?>