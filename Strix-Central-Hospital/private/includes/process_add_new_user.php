<?php
// Load database configuration and helper functions
require_once '../../config/config.php';
require_once 'funcoes.php';

// Start session to manage user login state
start_session();

// Only users with role 'tech' or 'ceo' can access this page
$userRole = $_SESSION['role'] ?? '';
if ($userRole !== 'tech' && $userRole !== 'ceo') {
    header("Location: ../../public/index.php");
    exit;
}

// Process form submission (adding a new user)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];
    $role  = $_POST['role'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['server_error'] = "O email fornecido não é válido.";
        header("Location: ../views/user-add.php");
        exit;
    }

    // Validate password length (6-12 characters)
    if (strlen($pass) < 6 || strlen($pass) > 12) {
        $_SESSION['server_error'] = "A password deve ter entre 6 e 12 caracteres.";
        header("Location: ../views/user-add.php");
        exit;
    }

    // Hash password for secure storage
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    try {
        // Insert the new user into the database
        $stmt = $pdo->prepare("INSERT INTO utilizadores (nome, email, password, role) VALUES (:nome, :email, :password, :role)");
        $stmt->execute([
            ':nome'     => $nome,
            ':email'    => $email,
            ':password' => $hashed_password,
            ':role'     => $role
        ]);

        // Success message and redirect
        $_SESSION['success_message'] = "Utilizador '$nome' registado com sucesso!";
        header("Location: ../views/user-add.php");
        exit;

    } catch (PDOException $e) {
        // Check for duplicate email (SQL error 23000)
        if ($e->getCode() == 23000) {
            $_SESSION['server_error'] = "Este email já está registado no sistema.";
        } else {
            $_SESSION['server_error'] = "Erro ao registar utilizador: " . $e->getMessage();
        }
        // Redirect back to form with error
        header("Location: ../views/user-add.php");
        exit;
    }
}
?>