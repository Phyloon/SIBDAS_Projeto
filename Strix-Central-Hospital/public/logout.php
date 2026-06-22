<?php
// 1. Include the functions file to use start_session()
require_once '../private/includes/funcoes.php';

// 2. Start the session to be able to destroy it
start_session();

// 3. Clear all session variables
$_SESSION = array();

// 4. If there's a session cookie, delete it from the browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 5. Finally, destroy the session on the server
session_destroy();

// 6. Redirect to login
header("Location: /public/login.php");
exit;
?>