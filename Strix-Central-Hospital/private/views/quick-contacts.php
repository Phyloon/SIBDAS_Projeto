

<?php 
require_once '../../config/config.php';
session_start();
// Fetch all codes from the new table
$stmt = $pdo->query("SELECT * FROM pager_codes ORDER BY funcao_hospitalar ASC");
$pagerCodes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group by role
$grouped = [];
foreach ($pagerCodes as $code) {
    $grouped[$code['funcao_hospitalar']][] = $code;
}
?>

<?php include '../includes/header.php'?>

    <div class="d-flex">

        <?php include '../includes/nav.php'?>

        <div id="content">

            <!-- Top Navigation Bar -->
            <?php include '../includes/topbar.php'?>

            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold">Quick Contacts</h3>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_new_pager_code">
                    <i class="bi bi-plus-circle"></i> Add New Code
                </button>

                <div class="accordion mt-4" id="ticketsAccordion">
                    

                    <div class="accordion mt-4" id="ticketsAccordion">
                        <?php $i = 0; foreach ($grouped as $role => $items): $i++; ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?= $i ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>">
                                        Receiving role: <?= htmlspecialchars($role) ?>
                                    </button>
                                </h2>
                                <div id="collapse<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#ticketsAccordion">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            <?php foreach ($items as $item): ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong><?= htmlspecialchars($item['codigo_pager']) ?></strong>
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
                </div>

                <?php include '../includes/modal_new_pager_code.php'?>
            </div>
        </div>
    </div>
<?php include "../includes/footer.php"?>  