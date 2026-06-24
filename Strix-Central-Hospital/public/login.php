<?php
// Turn on error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start session to store login status and error messages
session_start();

// If user is already logged in, redirect to dashboard (skip login page)
if (isset($_SESSION['utilizador'])) {
    header("Location: ../private/dashboard.php"); 
    exit;
}

// Retrieve any validation errors from session (from previous failed attempts)
$validation_errors = !empty($_SESSION['validation_errors']) ? $_SESSION['validation_errors'] : [];
unset($_SESSION['validation_errors']); // Clear after reading

// Retrieve any server-side error (e.g., invalid credentials)
$server_error = !empty($_SESSION['server_error']) ? $_SESSION['server_error'] : '';
unset($_SESSION['server_error']); // Clear after reading
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login - TrueTech</title>
    <!-- Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<!-- Center the login card vertically and horizontally -->
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

<!-- Login card container -->
<div class="card shadow-sm border-0" style="width: 100%; max-width: 400px; border-radius: 12px;">
    <div class="card-body p-4">
        <!-- Page title -->
        <h4 class="text-center mb-4 fw-bold text-primary">TrueTech Login</h4>

        <!-- Display validation errors (e.g., invalid email format, length issues) -->
        <?php if (!empty($validation_errors)): ?>
            <div class="alert alert-danger p-2 text-center small">
                <?php foreach ($validation_errors as $error): ?>
                    <div><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Display server-side error (e.g., wrong credentials) -->
        <?php if (!empty($server_error)): ?>
            <div class="alert alert-danger p-2 text-center small">
                <div><?= htmlspecialchars($server_error) ?></div>
            </div>
        <?php endif; ?>

        <!-- Login form – submits to login_process.php -->
        <form action="../private/login_process.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Utilizador (Email)</label>
                <input type="email" name="text_username" class="form-control" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="text_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold">Entrar</button>
        </form>
    </div>
</div>

</body>
</html>