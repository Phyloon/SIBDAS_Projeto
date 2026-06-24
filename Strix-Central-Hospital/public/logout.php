<?php
// Load helper functions (includes start_session() which safely starts a session)
require_once '../private/includes/funcoes.php';

// Start the session to be able to destroy it
start_session();

// Clear all session variables (remove user data)
$_SESSION = array();

// If the session uses cookies, delete the session cookie from the browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session on the server (removes session file)
session_destroy();

// Redirect the user to the login page
header("Location: /public/login.php");
exit;
?>