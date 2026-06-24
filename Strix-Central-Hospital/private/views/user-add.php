<?php 
// 1. LOAD CONFIGURATION AND START SESSION
require_once '../../config/config.php';
session_start();

// Restrict access to ceo only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'ceo') {
    header('Location: ../views/dashboard.php');
    exit;
}
?>

<!-- Include header (HTML start, styles, etc.) -->
<?php include '../includes/header.php' ?>
<div class="d-flex">
    <!-- Sidebar navigation -->
    <?php include '../includes/nav.php' ?>
    <div id="content" class="w-100">
        <!-- Top bar with user info and notifications -->
        <?php include '../includes/topbar.php' ?>
        <div class="p-4">
            
            <!-- Add New User Form-->
            <div class="card shadow-sm border-0 col-md-6 mx-auto">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Adicionar Novo Utilizador</h5>

                    <!-- Display success message from session (if any) -->
                    <?php if (!empty($_SESSION['success_message'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>

                    <!-- Display error message from session (if any) -->
                    <?php if (!empty($_SESSION['server_error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['server_error'] ?></div>
                        <?php unset($_SESSION['server_error']); ?>
                    <?php endif; ?>

                    <!-- ============================================================
                         Submits to process_add_new_user.php for processing
                         Fields: Name, Email, Password, Role
                    ============================================================ -->
                    <form action="../includes/process_add_new_user.php" method="post">
                        <!-- Name field -->
                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        
                        <!-- Email field -->
                        <div class="mb-3">
                            <label class="form-label">E-Mail</label>
                            <input type="text" name="email" class="form-control" required>
                        </div>
                        
                        <!-- Password field (plain text - should be hashed on the processing page) -->
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        
                        <!-- Role dropdown (select user's permission level) -->
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="#">Selecione a função...</option>
                                <option value="tech">Técnico</option>
                                <option value="ceo">CEO</option>
                                <option value="enfermeiro">Enfermeiro</option>
                                <option value="doctor">Doutor</option>
                            </select>
                        </div>
                        
                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary w-100">Criar Utilizador</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php' ?>