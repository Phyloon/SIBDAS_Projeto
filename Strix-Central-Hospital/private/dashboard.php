<?php 
require_once 'includes/funcoes.php';
session_start();
redirect_if_not_logged(); 

if ($_SESSION['role'] !== 'tech' && $_SESSION['role'] !== 'ceo') {
    header('Location: ../private/views/staff.php'); 
    exit;
}

// Get upcoming maintenance and warranty alerts (within 7 days)
$today   = new DateTime();
$in7days = (new DateTime())->modify('+7 days');

$stmt = $pdo->query("
    SELECT d.*, e.nome as equipamento_nome, e.location_vector
    FROM documentos_equipamento d
    JOIN equipamentos e ON d.equipamento_id = e.id
    WHERE e.deleted_at IS NULL
");
$rawContracts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$upcomingMaintenance = [];
$upcomingWarranty    = [];

foreach ($rawContracts as $contract) {

    // --- Próxima manutenção ---
    if (!empty($contract['data_inicio_garantia']) && !empty($contract['periodicidade'])) {
        $proxima = new DateTime($contract['data_inicio_garantia']);

        // Keep adding the interval until we find the next future date
        while ($proxima <= $today) {
            switch ($contract['periodicidade']) {
                case '3 meses':  $proxima->modify('+3 months');  break;
                case '6 meses':  $proxima->modify('+6 months');  break;
                case '12 meses': $proxima->modify('+12 months'); break;
                default:         $proxima->modify('+1 year');    break;
            }
        }

        $contract['proxima_manutencao'] = $proxima->format('Y-m-d');

        // Only add if within 7 days
        if ($proxima <= $in7days) {
            $upcomingMaintenance[] = $contract;
        }
    }

    // --- Fim de garantia ---
    if (!empty($contract['data_fim_garantia'])) {
        $fimGarantia = new DateTime($contract['data_fim_garantia']);
        if ($fimGarantia >= $today && $fimGarantia <= $in7days) {
            $upcomingWarranty[] = $contract;
        }
    }


    // A. Equipamentos por Localização
    $stmtLoc = $pdo->query("
        SELECT location_wing, COUNT(*) as total 
        FROM equipamentos 
        WHERE deleted_at IS NULL 
        GROUP BY location_wing 
        ORDER BY total DESC
    ");
    $locData = $stmtLoc->fetchAll(PDO::FETCH_ASSOC);
    $locLabels = json_encode(array_column($locData, 'location_wing'));
    $locCounts = json_encode(array_column($locData, 'total'));

    // B. Tipos de Documentação
    $stmtDocs = $pdo->query("
        SELECT d.tipo_documento, COUNT(*) as total 
        FROM documentos_equipamento d
        JOIN equipamentos e ON d.equipamento_id = e.id
        WHERE e.deleted_at IS NULL
        GROUP BY d.tipo_documento
    ");
    $docData = $stmtDocs->fetchAll(PDO::FETCH_ASSOC);
    $docLabels = json_encode(array_column($docData, 'tipo_documento'));
    $docCounts = json_encode(array_column($docData, 'total'));

    // C. Top Fornecedores (Usando a tabela de relação Many-to-Many `equipamento_fornecedor`)
    $stmtForn = $pdo->query("
        SELECT f.nome_empresa, COUNT(ef.equipamento_id) AS total_equipamentos
        FROM fornecedores f
        JOIN equipamento_fornecedor ef ON f.id = ef.fornecedor_id
        JOIN equipamentos e ON ef.equipamento_id = e.id
        WHERE e.deleted_at IS NULL
        GROUP BY f.id, f.nome_empresa
        ORDER BY total_equipamentos DESC
        LIMIT 5
    ");
    $fornData = $stmtForn->fetchAll(PDO::FETCH_ASSOC);
    $fornLabels = json_encode(array_column($fornData, 'nome_empresa'));
    $fornCounts = json_encode(array_column($fornData, 'total_equipamentos'));  
    }

    // D. Contagem de Equipamentos sem qualquer documentação associada
    $stmtNoDocs = $pdo->query("
        SELECT COUNT(e.id) as total 
        FROM equipamentos e
        LEFT JOIN documentos_equipamento d ON e.id = d.equipamento_id
        WHERE e.deleted_at IS NULL AND d.equipamento_id IS NULL
    ");
    $noDocsCount = $stmtNoDocs->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    
?>

<?php include 'includes/header.php'?>

    <div class="d-flex">
        
    <?php include 'includes/nav.php'?>

        <div id="content">
            
        <?php include 'includes/topbar.php'?>

            <div class="container-fluid p-4">
                <div class="row g-4">
                    <div class="col-xl-7">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-4">Upcoming Appointments</h5>
                                <div class="appointment-list">
                                    <?php if (empty($upcomingMaintenance) && empty($upcomingWarranty)): ?>
                                        <p class="text-muted small">No upcoming alerts in the next 7 days.</p>
                                    <?php endif; ?>

                                    <?php foreach ($upcomingMaintenance as $item): ?>
                                        <div class="appointment-list-item d-flex align-items-center">
                                            <div class="date-badge me-3">
                                                <div class="small text-muted"><?= (new DateTime($item['proxima_manutencao']))->format('M') ?></div>
                                                <div class="fw-bold"><?= (new DateTime($item['proxima_manutencao']))->format('d') ?></div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold"><?= htmlspecialchars($item['equipamento_nome']) ?> — Manutenção</div>
                                                <div class="small text-muted">
                                                    <?= htmlspecialchars($item['location_vector'] ?? 'Unknown location') ?> 
                                                    • Periodicidade: <?= htmlspecialchars($item['periodicidade']) ?>
                                                </div>
                                            </div>
                                            <span class="status-badge bg-warning text-dark">Manutenção</span>
                                        </div>
                                    <?php endforeach; ?>

                                    <?php foreach ($upcomingWarranty as $item): ?>
                                        <div class="appointment-list-item d-flex align-items-center">
                                            <div class="date-badge me-3">
                                                <div class="small text-muted"><?= (new DateTime($item['data_fim_garantia']))->format('M') ?></div>
                                                <div class="fw-bold"><?= (new DateTime($item['data_fim_garantia']))->format('d') ?></div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold"><?= htmlspecialchars($item['equipamento_nome']) ?> — Fim de Garantia</div>
                                                <div class="small text-muted">
                                                    <?= htmlspecialchars($item['location_vector'] ?? 'Unknown location') ?>
                                                </div>
                                            </div>
                                            <span class="status-badge bg-danger text-white">Garantia</span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 d-flex flex-column gap-3">
                        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-4">Equipment Status Overview</h5>
                                <div style="height: 250px;">
                                    <canvas id="statusChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="card p-3 border-0 shadow-sm bg-white" style="border-radius: 16px;">
                            <div class="card-body d-flex align-items-center justify-content-between p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="bi bi-file-earmark-x fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">Falta de Documentação</h6>
                                        <small class="text-muted">Equipamentos sem manuais ou faturas.</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="fs-2 fw-bold text-danger"><?= $noDocsCount ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-lg-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h6 class="fw-bold text-muted mb-3">Equipamentos por Ala</h6>
                                    <div style="height: 200px;">
                                        <canvas id="locationChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h6 class="fw-bold text-muted mb-3">Volume de Documentação</h6>
                                    <div style="height: 200px;">
                                        <canvas id="docsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <h6 class="fw-bold text-muted mb-3">Top Fornecedores (Equipamentos)</h6>
                                    <div style="height: 200px;">
                                        <canvas id="suppliersChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include 'includes/new_equipment.php'?>
                <?php include 'includes/modal_supplier.php'?>
                <?php include 'includes/new_staff.php'?>
                <?php include 'includes/newDocModal.php'?>
            </div>
        </div>
    </div>
    
<script>
    const dashboardData = {
        locLabels: <?= $locLabels ?? '[]' ?>,
        locCounts: <?= $locCounts ?? '[]' ?>,
        docLabels: <?= $docLabels ?? '[]' ?>,
        docCounts: <?= $docCounts ?? '[]' ?>,
        fornLabels: <?= $fornLabels ?? '[]' ?>,
        fornCounts: <?= $fornCounts ?? '[]' ?>
    };
</script>
<?php include "includes/footer.php"?>