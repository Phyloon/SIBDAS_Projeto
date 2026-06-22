<?php
require_once '../config/config.php';
require_once 'funcoes.php';

start_session();

// STRICT SECURITY CHECK
$userRole = $_SESSION['role'] ?? '';
if ($userRole !== 'tech' && $userRole !== 'ceo') {
    header("Location: ../private/home.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];
    $role  = $_POST['role'];

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['server_error'] = "O email fornecido não é válido.";
        header("Location: ../private/registar_utilizador.php");
        exit;
    }

    if (strlen($pass) < 6 || strlen($pass) > 12) {
        $_SESSION['server_error'] = "A password deve ter entre 6 e 12 caracteres.";
        header("Location: ../private/registar_utilizador.php");
        exit;
    }

    // Hash the password securely
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO utilizadores (nome, email, password, role) VALUES (:nome, :email, :password, :role)");
        $stmt->execute([
            ':nome'     => $nome,
            ':email'    => $email,
            ':password' => $hashed_password,
            ':role'     => $role
        ]);

        $_SESSION['success_message'] = "Utilizador '$nome' registado com sucesso!";
        header("Location: ../private/registar_utilizador.php");
        exit;

    } catch (PDOException $e) {
        // Error code 23000 usually means the email already exists (UNIQUE constraint)
        if ($e->getCode() == 23000) {
            $_SESSION['server_error'] = "Este email já está registado no sistema.";
        } else {
            $_SESSION['server_error'] = "Erro ao registar utilizador: " . $e->getMessage();
        }
        header("Location: ../private/registar_utilizador.php");
        exit;
    }
}
?>