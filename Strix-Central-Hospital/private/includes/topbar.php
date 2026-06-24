<?php
// Garantir que as variáveis existem para evitar erros caso a sessão falhe
$userName = $_SESSION['nome'] ?? $_SESSION['name'] ?? 'Utilizador'; 
$roleCode = $_SESSION['role'] ?? 'staff';

// Traduzir o código da role para um título legível
$displayRole = 'Staff';
switch ($roleCode) {
    case 'tech': $displayRole = 'Técnico'; break;
    case 'ceo':  $displayRole = 'Diretor Executivo'; break;
    case 'admin':$displayRole = 'Administrador'; break;
}
?>

<nav class="top-navbar d-flex justify-content-between align-items-center">
    <div class="input-group d-none d-md-flex" style="width: 300px;">
        <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
        <input type="text" class="form-control bg-light border-0" placeholder="Search equipment...">
    </div>
    <div class="d-flex align-items-center">
        <i class="bi bi-bell text-secondary fs-5 me-4"></i>
        <i class="bi bi-moon text-secondary fs-5 me-4"></i>
        <div class="d-flex align-items-center">
            <div class="text-end me-2">
                <p class="mb-0 small fw-bold"><?= htmlspecialchars($userName) ?></p>
                <p class="mb-0 text-muted small"><?= htmlspecialchars($displayRole) ?></p>
            </div>
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($userName) ?>&background=random" class="rounded-circle" width="35" height="35" alt="Avatar">
        </div>
    </div>
</nav>