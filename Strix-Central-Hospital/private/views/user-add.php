<?php 
require_once '../../config/config.php';
session_start();
// Add logic here to ensure the current user is an Admin
?>

<?php include '../includes/header.php' ?>
<div class="d-flex">
    <?php include '../includes/nav.php' ?>
    <div id="content" class="w-100">
        <?php include '../includes/topbar.php' ?>
        <div class="p-4">
            <div class="card shadow-sm border-0 col-md-6 mx-auto">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Adicionar Novo Utilizador</h5>


                    <?php if (!empty($_SESSION['success_message'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['server_error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['server_error'] ?></div>
                        <?php unset($_SESSION['server_error']); ?>
                    <?php endif; ?>

                    
                    <form action="../includes/process_add_new_user.php" method="post">
                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-Mail</label>
                            <input type="text" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
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
                        <button type="submit" class="btn btn-primary w-100">Criar Utilizador</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php' ?>