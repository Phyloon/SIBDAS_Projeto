<?php
require_once '../config/config.php';
require_once '../includes/funcoes.php';

session_start();
redirect_if_not_logged();

// STRICT SECURITY: Kick out anyone who isn't a Tech or CEO
$userRole = $_SESSION['role'] ?? '';
if ($userRole !== 'tech' && $userRole !== 'ceo') {
    $_SESSION['server_error'] = "Acesso negado. Não tem permissões para ver esta página.";
    header("Location: home.php");
    exit;
}
?>

<?php include '../includes/header.php' ?>
<div class="d-flex">
    <?php include '../includes/nav.php' ?>
    
    <div id="content" class="w-100">
        <?php include '../includes/topbar.php' ?>
        
        <div class="p-4">
            <h5 class="fw-bold text-dark mb-4"><i class="bi bi-person-plus-fill text-primary me-2"></i>Registar Novo Utilizador</h5>
            
            <?php if (!empty($_SESSION['success_message'])): ?>
                <div class="alert alert-success p-2">
                    <?= htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['server_error'])): ?>
                <div class="alert alert-danger p-2">
                    <?= htmlspecialchars($_SESSION['server_error']); unset($_SESSION['server_error']); ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0" style="max-width: 600px; border-radius: 12px;">
                <div class="card-body p-4">
                    <form action="../includes/process_add_user.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nome Completo</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Password <span class="text-muted small">(6-12 char)</span></label>
                                <input type="password" name="password" class="form-control" minlength="6" maxlength="12" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cargo (Role)</label>
                                <select name="role" class="form-select" required>
                                    <option value="">Selecione o cargo...</option>
                                    <option value="enfermeiro">Enfermeiro(a)</option>
                                    <option value="doctor">Médico(a)</option>
                                    <option value="tech">Técnico(a) de Manutenção</option>
                                    <option value="ceo">Diretor(a) / CEO</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary-custom px-4 fw-bold">Registar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php' ?>