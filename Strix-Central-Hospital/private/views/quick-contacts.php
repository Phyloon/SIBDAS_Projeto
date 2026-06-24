<?php 
// Load database configuration and start session for flash messages
require_once '../../config/config.php';
session_start();

// 1. FETCH ALL PAGER CODES FROM DATABASE
//    Ordered alphabetically by hospital role
$stmt = $pdo->query("SELECT * FROM pager_codes ORDER BY funcao_hospitalar ASC");
$pagerCodes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. GROUP CODES BY HOSPITAL ROLE
//    Creates an array where each role holds all its codes
$grouped = [];
foreach ($pagerCodes as $code) {
    $grouped[$code['funcao_hospitalar']][] = $code;
}
?>

<!-- Include header (HTML start, styles, etc.) -->
<?php include '../includes/header.php'?>

<div class="d-flex">

    <!-- Sidebar navigation -->
    <?php include '../includes/nav.php'?>

    <div id="content">

        <!-- Top bar (user info, notifications, etc.) -->
        <?php include '../includes/topbar.php'?>

        <div class="container-fluid p-4">
            <!-- Page header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">Quick Contacts</h3>
            </div>

            <!-- Button to open the "Add New Code" modal -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_new_pager_code">
                <i class="bi bi-plus-circle"></i> Add New Code
            </button>

            <!-- ACCORDION: One section per hospital role -->
            <!-- Each section shows the pager codes for that role -->
            <div class="accordion mt-4" id="ticketsAccordion">
                <?php $i = 0; foreach ($grouped as $role => $items): $i++; ?>
                    <div class="accordion-item">
                        <!-- Accordion header with role name -->
                        <h2 class="accordion-header" id="heading<?= $i ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>">
                                Receiving role: <?= htmlspecialchars($role) ?>
                            </button>
                        </h2>
                        <!-- Accordion body containing the list of codes -->
                        <div id="collapse<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#ticketsAccordion">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <?php foreach ($items as $item): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <!-- Pager code (bold) -->
                                                <strong><?= htmlspecialchars($item['codigo_pager']) ?></strong>
                                                <!-- Description (small, muted) -->
                                                <br><small class="text-muted"><?= htmlspecialchars($item['descricao']) ?></small>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Include the "Add New Pager Code" modal -->
            <?php include '../includes/modal_new_pager_code.php'?>
        </div>
    </div>
</div>

<!-- Include footer (closing HTML tags and scripts) -->
<?php include "../includes/footer.php"?>