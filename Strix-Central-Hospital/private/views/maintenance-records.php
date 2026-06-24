<?php
// 1. Load configuration and start session for user messages
require_once '../../config/config.php';
session_start();

// 2. FETCH ALL RECORDS (documents) with equipment and supplier details
//    - Left join with equipamentos and fornecedores to get names
//    - Includes: equipment name, doc ID, type, contract type, dates, file path, supplier
//    - Ordered by equipment name and start date descending
$stmt = $pdo->query("
    SELECT 
        e.nome AS equipamento,
        d.id AS doc_id,
        d.tipo_documento,
        d.tipo_contrato,
        d.periodicidade,
        d.data_inicio_garantia,
        d.data_fim_garantia,
        d.caminho_ficheiro,
        f.nome_empresa AS entidade_responsavel
    FROM documentos_equipamento d
    LEFT JOIN equipamentos e ON d.equipamento_id = e.id
    LEFT JOIN fornecedores f ON d.fornecedor_id = f.id
    ORDER BY e.nome, d.data_inicio_garantia DESC
");
$allRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. DEFINE FILTERS FOR EACH TAB
//    Used to split records into categories: Garantia, Preventiva, Corretiva, Total
$tabFilters = [
    'Garantia'   => ['tipo_documento' => 'Garantia'],
    'Preventiva' => ['tipo_documento' => 'Contrato', 'tipo_contrato' => 'Preventiva'],
    'Corretiva'  => ['tipo_documento' => 'Contrato', 'tipo_contrato' => 'Corretiva'],
    'Total'      => ['tipo_documento' => 'Contrato', 'tipo_contrato' => 'Total'],
];

// 4. LABELS FOR TABS (display names)
$regTypes = [
    'Garantia'   => 'Garantia',
    'Preventiva' => 'Manutenção Preventiva',
    'Corretiva'  => 'Manutenção Corretiva',
    'Total'      => 'Contrato Total',
];

// 5. HELPER: Filter records based on tab key
//    Uses the $tabFilters array to apply conditions
function getRecordsByType($allRecords, $typeKey, $tabFilters) {
    $filter = $tabFilters[$typeKey] ?? null;
    if (!$filter) return [];
    return array_filter($allRecords, function($r) use ($filter) {
        foreach ($filter as $key => $value) {
            if (($r[$key] ?? '') !== $value) return false;
        }
        return true;
    });
}

// 6. HELPER: Render view/download buttons for documents
//    Returns HTML with icons for viewing and downloading
function renderDocumentButtons($caminho) {
    if (empty($caminho)) {
        return '<span class="text-muted small">Sem doc.</span>';
    }
    $html = '<a href="' . htmlspecialchars($caminho) . '" target="_blank" class="btn btn-sm btn-light border" title="Ver Documento">
                <i class="bi bi-eye"></i>
             </a>
             <a href="' . htmlspecialchars($caminho) . '" download class="btn btn-sm btn-primary-custom ms-1" title="Download">
                <i class="bi bi-download"></i>
             </a>';
    return $html;
}

// 7. FETCH LISTS FOR MODAL DROPDOWNS (equipments and suppliers)
//    Used when adding a new record via the modal
$stmtEq = $pdo->query("SELECT id, nome, serial FROM equipamentos ORDER BY nome");
$allEquipments = $stmtEq->fetchAll(PDO::FETCH_ASSOC);

$stmtFo = $pdo->query("SELECT id, nome_empresa FROM fornecedores ORDER BY nome_empresa");
$fornecedores = $stmtFo->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Include header (HTML start, styles) -->
<?php include '../includes/header.php' ?>
<div class="d-flex">
    <!-- Sidebar navigation -->
    <?php include '../includes/nav.php' ?>
    <div id="content" class="w-100">
        <!-- Top bar with user info -->
        <?php include '../includes/topbar.php' ?>
        
        <div class="p-4">
           <!-- PAGE HEADER with title and "New Record" button -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark"><i class="bi bi-shield-check text-primary me-2"></i>Garantias e Contratos</h5>
                <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#newContractModal">
                    <i class="bi bi-plus-lg me-1"></i> Novo Registo
                </button>
            </div>

            <!-- TABS: One tab per category (Garantia, Preventiva, Corretiva, Total) -->
            <ul class="nav nav-tabs mb-4" role="tablist">
                <?php $index = 0; foreach($regTypes as $dbValue => $displayLabel): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-dark fw-bold <?= $index === 0 ? 'active' : '' ?>" 
                                data-bs-toggle="tab" data-bs-target="#view-tab-<?= $index ?>" type="button" role="tab">
                            <?= $displayLabel ?>
                        </button>
                    </li>
                <?php $index++; endforeach; ?>
            </ul>

            <!-- TAB CONTENT: Each tab shows a filtered table -->
            <div class="tab-content">
                <?php $index = 0; foreach($regTypes as $dbValue => $displayLabel): 
                    // Get records for this specific tab using the helper
                    $filteredRecords = getRecordsByType($allRecords, $dbValue, $tabFilters);
                ?>
                    <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" id="view-tab-<?= $index ?>" role="tabpanel">
                        <div class="card shadow-sm border-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Equipamento</th>
                                            <?php if ($dbValue === 'Garantia'): ?>
                                                <!-- Columns for warranty records -->
                                                <th>Início Garantia</th>
                                                <th>Fim Garantia</th>
                                                <th>Entidade Responsável</th>
                                                <th>Documento</th>
                                            <?php else: ?>
                                                <!-- Columns for contract records (preventive, corrective, total) -->
                                                <th>Início</th>
                                                <th>Fim</th>
                                                <th>Periodicidade</th>
                                                <th>Entidade Responsável</th>
                                                <th>Documento</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($filteredRecords)): ?>
                                            <tr>
                                                <td colspan="<?= $dbValue === 'Garantia' ? 6 : 7 ?>" class="text-center text-muted py-4">
                                                    Sem registos encontrados para esta categoria.
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <!-- Loop through filtered records and display each row -->
                                            <?php foreach($filteredRecords as $r): ?>
                                            <tr>
                                                <td class="fw-bold ps-4"><?= htmlspecialchars($r['equipamento']) ?></td>
                                                <td><?= $r['data_inicio_garantia'] ? date('d/m/Y', strtotime($r['data_inicio_garantia'])) : '-' ?></td>
                                                <td><?= $r['data_fim_garantia'] ? date('d/m/Y', strtotime($r['data_fim_garantia'])) : '-' ?></td>
                                                <?php if ($dbValue !== 'Garantia'): ?>
                                                    <!-- Extra column for contract records: periodicidade -->
                                                    <td><?= htmlspecialchars($r['periodicidade'] ?: '-') ?></td>
                                                <?php endif; ?>
                                                <td><?= htmlspecialchars($r['entidade_responsavel'] ?: 'N/A') ?></td>
                                                <!-- Document view/download buttons -->
                                                <td><?= renderDocumentButtons($r['caminho_ficheiro'] ?? '') ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php $index++; endforeach; ?>
            </div>

            <!-- MODAL: Add New Record (Warranty or Maintenance Contract) -->
            <!-- The modal contains tabs inside it to switch between adding warranty or contract -->
            <div class="modal fade" id="newContractModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title">Novo Registo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        
                        <!-- Inner tabs for choosing warranty or contract -->
                        <div class="modal-header pt-2">
                            <ul class="nav nav-tabs w-100" id="registryTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active fw-bold" id="garantia-tab" data-bs-toggle="tab" data-bs-target="#garantia" type="button" role="tab">
                                        <i class="bi bi-shield-check me-1"></i> Garantia
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold" id="contrato-tab" data-bs-toggle="tab" data-bs-target="#contrato" type="button" role="tab">
                                        <i class="bi bi-file-earmark-text me-1"></i> Contrato de Manutenção
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Include the actual modal form content from an external file -->
                        <?php include '../includes/modal_maintenance-records.php' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php' ?>