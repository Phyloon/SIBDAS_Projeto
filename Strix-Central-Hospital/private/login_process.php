<?php
// Load configuration and helper functions
require_once '../config/config.php';
require_once 'includes/funcoes.php';

// Start session for user data and error messages
session_start();

// 1. Block direct URL access (only allow POST requests)
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../public/login.php');
    return;
}

// 2. Fetch and sanitize form inputs
$username = isset($_POST['text_username']) ? trim($_POST['text_username']) : '';
$password = isset($_POST['text_password']) ? $_POST['text_password'] : '';

// 3. Validate user inputs
$validation_errors = [];

// Username must be a valid email
if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $validation_errors[] = 'O username tem que ser um email válido.';
}
// Username length check (5-50 characters)
if (strlen($username) < 5 || strlen($username) > 50) {
    $validation_errors[] = 'O username deve ter entre 5 e 50 caracteres.';
}
// Password length check (6-12 characters)
if (strlen($password) < 6 || strlen($password) > 12) {
    $validation_errors[] = 'A password deve ter entre 6 e 12 caracteres.';
}

// If validation fails, redirect back to login with errors
if (!empty($validation_errors)) {
    $_SESSION['validation_errors'] = $validation_errors;
    header('Location: login.php');
    return;
}

// 4. Query database for the user by email
$stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE email = :email LIMIT 1");
$stmt->execute([':email' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// 5. Verify password against stored hash
if ($user && password_verify($password, $user['password'])) {
    // Login successful: store user data in session
    $_SESSION['utilizador'] = $user['email'];
    $_SESSION['nome_user']  = $user['nome'];
    $_SESSION['role']       = $user['role']; // Role: enfermeiro, doctor, tech, or ceo
    // Redirect to the dashboard
    header('Location: ../private/dashboard.php');
    exit;
} else {
    // Login failed: show generic error message
    $_SESSION['server_error'] = 'Login inválido. Verifique as suas credenciais.';
    header('Location: ../public/login.php');
    return;
}
?>