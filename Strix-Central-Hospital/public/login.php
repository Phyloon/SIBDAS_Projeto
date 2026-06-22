<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// If already logged in, skip login page
if (isset($_SESSION['utilizador'])) {
    header("Location: ../private/dashboard.php"); 
    exit;
}

$validation_errors = !empty($_SESSION['validation_errors']) ? $_SESSION['validation_errors'] : [];
unset($_SESSION['validation_errors']);

$server_error = !empty($_SESSION['server_error']) ? $_SESSION['server_error'] : '';
unset($_SESSION['server_error']);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login - TrueTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

<div class="card shadow-sm border-0" style="width: 100%; max-width: 400px; border-radius: 12px;">
    <div class="card-body p-4">
        <h4 class="text-center mb-4 fw-bold text-primary">TrueTech Login</h4>

        <?php if (!empty($validation_errors)): ?>
            <div class="alert alert-danger p-2 text-center small">
                <?php foreach ($validation_errors as $error): ?>
                    <div><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($server_error)): ?>
            <div class="alert alert-danger p-2 text-center small">
                <div><?= htmlspecialchars($server_error) ?></div>
            </div>
        <?php endif; ?>

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