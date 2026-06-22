<?php
require_once '../config/config.php';
require_once 'includes/funcoes.php';

session_start();

// 1. Block direct URL access
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../public/login.php');
    return;
}

// 2. Fetch inputs
$username = isset($_POST['text_username']) ? trim($_POST['text_username']) : '';
$password = isset($_POST['text_password']) ? $_POST['text_password'] : '';

// 3. Validation Logic (from Worksheet)
$validation_errors = [];

if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $validation_errors[] = 'O username tem que ser um email válido.';
}
if (strlen($username) < 5 || strlen($username) > 50) {
    $validation_errors[] = 'O username deve ter entre 5 e 50 caracteres.';
}
if (strlen($password) < 6 || strlen($password) > 12) {
    $validation_errors[] = 'A password deve ter entre 6 e 12 caracteres.';
}

// If validation fails, bounce them back to login
if (!empty($validation_errors)) {
    $_SESSION['validation_errors'] = $validation_errors;
    header('Location: login.php');
    return;
}

// 4. Database Check
// Find the user by email
$stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE email = :email LIMIT 1");
$stmt->execute([':email' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// password_verify checks the raw typed password against the hashed DB password
if ($user && password_verify($password, $user['password'])) {
    
    // SUCCESS: Store identifiers AND the role in the session
    $_SESSION['utilizador'] = $user['email'];
    $_SESSION['nome_user']  = $user['nome'];
    $_SESSION['role']       = $user['role']; // 'enfermeiro', 'doctor', 'tech', or 'ceo'

    header('Location: ../private/dashboard.php');
    exit;

} else {
    // FAIL: Invalid credentials
    $_SESSION['server_error'] = 'Login inválido. Verifique as suas credenciais.';
    header('Location: ../public/login.php');
    return;
}
?>