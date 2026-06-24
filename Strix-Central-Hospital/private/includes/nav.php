<nav id="sidebar" class="sidebar">
    <div class="sidebar-header d-flex">
        <div class="logo-icon me-2"></div>
        <div>
            <span class="logo-text fw-bold fs-4">True<span class="text-primary">Tech</span></span>
            <small class="text-secondary">Strixhaven</small>
        </div>
    </div>

    <?php
    // Define who gets full access
    $userRole = $_SESSION['role'] ?? '';
    $isFullAccess = ($userRole === 'tech' || $userRole === 'ceo');
    $isCEO = ($userRole === 'ceo');
    ?>

    <ul class="nav flex-column mt-2">
        <?php if ($isFullAccess): ?>
        <li class="nav-item"><a href="/private/dashboard.php" class="nav-link"><i class="bi bi-calendar-check"></i> Dashboard</a></li>
        <?php endif; ?>

        <li class="nav-item"><a href="/private/views/staff.php" class="nav-link"><i class="bi bi-tools"></i> Staff</a></li>

        <?php if ($isFullAccess): ?>
        <li class="nav-item"><a href="/private/views/maintenance-records.php" class="nav-link"><i class="bi bi-clipboard-data"></i> Maintenance Records</a></li>
        <?php endif; ?>

        <li class="nav-item">
            <a href="#inventory-accordion" data-bs-toggle="collapse" role="button" class="nav-link d-flex justify-content-between align-items-center">
                <span><i class="bi bi-box-seam"></i> Inventory</span>
                <i class="bi bi-chevron-down submenu-arrow small"></i>
            </a>

            <div id="inventory-accordion" class="collapse">
                <ul class="nav flex-column ms-4 border-start border-secondary ">
                    <li class="nav-item">
                        <a href="/private/views/inventory-manage.php" class="nav-link small">
                            <i class="bi bi-layers me-1"></i> Equipamentos
                        </a>
                    </li>

                    <?php if ($isFullAccess): ?>
                    <li class="nav-item">
                        <a href="/private/views/fornecedores.php" class="nav-link small">
                            <i class="bi bi-truck me-1"></i> Fornecedores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/private/views/documentacao.php" class="nav-link small">
                            <i class="bi bi-file-earmark-text me-1"></i> Documentação
                        </a>
                    </li>
                    <?php endif; ?>

                </ul>
            </div>
            
        </li>

        <li class="nav-item"><a href="/private/views/location.php" class="nav-link"><i class="bi bi-pin-map"></i> Location</a></li>

        <li class="nav-item"><a href="/private/views/quick-contacts.php" class="nav-link"><i class="bi bi-phone-vibrate"></i> Quick Contacts</a></li>
        
        <?php if ($isCEO): ?>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-gear"></i> Frontpage edit </a></li>
        <li class="nav-item"><a href="/private/views/user-add.php" class="nav-link"><i class="bi bi-gear"></i> Adicionar Utilizador </a></li>
        <?php endif; ?>
    </ul>

    <div class="mt-auto p-3">
        <a href="../../public/logout.php" class="nav-link text-danger border-top pt-3">
            <i class="bi bi-box-arrow-right"></i> Terminar Sessão
        </a>
    </div>

</nav>