<?php 
// 1. LOAD CONFIG & START SESSION
require_once '../../config/config.php';
session_start();

//2. DEFINE ALLOWED DOCUMENT TYPES,Used for tabs and filtering

$docTypes = [
    "Manual de Utilizador", 
    "Manual de Servico", 
    "Certificado de Calibracao", 
    "Faturas/Guia de Aquisicao", 
    "Declaracao de Conformidade", 
    "Relatorio Tecnico"
];

// 3. FETCH ALL DOCUMENTS WITH EQUIPMENT & SUPPLIER DATA
//    - Join with equipamentos to get names/serials
//    - Left join with fornecedores to show supplier name (if any)
//    - Order by newest upload first

$stmt = $pdo->query("
    SELECT d.*, 
           e.nome as nome_equipamento, 
           e.serial as serial_equipamento, 
           f.nome_empresa 
    FROM documentos_equipamento d 
    JOIN equipamentos e ON d.equipamento_id = e.id 
    LEFT JOIN fornecedores f ON d.fornecedor_id = f.id 
    ORDER BY d.data_upload DESC
");
$allDocuments = $stmt->fetchAll(PDO::FETCH_ASSOC);


// 4. FETCH ALL EQUIPMENT FOR DROPDOWN (upload modal)
$stmtEq = $pdo->query("SELECT id, nome, serial FROM equipamentos ORDER BY nome");
$allEquipments = $stmtEq->fetchAll(PDO::FETCH_ASSOC);


// 5. FETCH ALL SUPPLIERS FOR DROPDOWN (upload modal)
$stmtFo = $pdo->query("SELECT id, nome_empresa FROM fornecedores ORDER BY nome_empresa");
$fornecedores = $stmtFo->fetchAll(PDO::FETCH_ASSOC);


// 6. HELPER FUNCTION: Filter documents by type
//    Used to populate each tab with its own docs
function getDocsByType($docs, $type) {
    return array_filter($docs, function($d) use ($type) {
        return $d['tipo_documento'] === $type;
    });
}
?>


<!-- 7. INCLUDE HEADER (starts HTML, top nav, etc.) -->
<?php include '../includes/header.php'?>

<div class="d-flex">
    
    <!-- 8. SIDEBAR NAVIGATION -->
    <?php include '../includes/nav.php'?>

    <!-- 9. MAIN CONTENT AREA -->
    <div id="content">

        <!-- 10. TOP BAR (user info, notifications, etc.) -->
        <?php include '../includes/topbar.php'?>

        <div class="p-4">
            <!-- 11. PAGE HEADER with title and upload button -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold text-dark mb-1">
                        <i class="bi bi-folder2-open text-primary me-2"></i>Gestão de Documentos
                    </h5>
                    <p class="text-muted small mb-0">
                        Centralized documentation and manuals for all TrueTech medical assets.
                    </p>
                </div>
                <button class="btn btn-primary-custom btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#uploadDocModal" 
                        style="border-radius: 8px;">
                    <i class="bi bi-cloud-arrow-up me-2"></i> Upload Document
                </button>
            </div>

            <!-- 12. CARD CONTAINING TABS + TABLE -->
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                
                <!-- 12a. TABS (one per document type) -->
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0 px-3">
                    <ul class="nav nav-tabs border-bottom-0" id="documentTabs" role="tablist">
                        <?php foreach($docTypes as $index => $type): ?>
                            <li class="nav-item">
                                <button class="nav-link <?= $index === 0 ? 'active' : '' ?>" 
                                        data-bs-toggle="tab" 
                                        data-bs-target="#tab-<?= $index ?>" 
                                        type="button">
                                    <?= $type ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- 12b. TAB CONTENT (one panel per type) -->
                <div class="tab-content mt-3">
                    <?php foreach($docTypes as $index => $type): 
                        // Only show documents of this type in this tab
                        $filteredDocs = getDocsByType($allDocuments, $type);
                    ?>
                        <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" 
                             id="tab-<?= $index ?>">
                            
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <!-- Table headers -->
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Nome do Equipamento</th>
                                            <th>Serial</th>
                                            <th>Fornecedor</th>
                                            <th>Validade</th>
                                            <th>Nome do Ficheiro</th>
                                            <th>Data de Upload</th>
                                            <th class="text-end pe-4">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- If no documents, show empty message -->
                                        <?php if (empty($filteredDocs)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">
                                                    No documents found for this category.
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <!-- Loop through each document -->
                                            <?php foreach($filteredDocs as $doc): ?>
                                                <tr>
                                                    <!-- Equipment name -->
                                                    <td class="ps-4">
                                                        <?= htmlspecialchars($doc['nome_equipamento']) ?>
                                                    </td>
                                                    
                                                    <!-- Serial number -->
                                                    <td><?= htmlspecialchars($doc['serial_equipamento']) ?></td>
                                                    
                                                    <!-- Supplier (or N/A) -->
                                                    <td>
                                                        <?= !empty($doc['nome_empresa']) 
                                                            ? htmlspecialchars($doc['nome_empresa']) 
                                                            : '<span class="text-muted">N/A</span>' ?>
                                                    </td>
                                                    
                                                    <!-- Validity badge: shows date and expiry status -->
                                                    <td>
                                                        <?php if (!empty($doc['data_validade'])): 
                                                            $isExpired = (new DateTime($doc['data_validade'])) < (new DateTime());
                                                        ?>
                                                            <span class="badge <?= $isExpired ? 'bg-danger' : 'bg-success bg-opacity-10 text-success' ?>">
                                                                <?= $isExpired ? 'Expirado a ' : 'Válido até ' ?>
                                                                <?= date('d/m/Y', strtotime($doc['data_validade'])) ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted small">N/A</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    
                                                    <!-- Filename (truncated if long) -->
                                                    <td class="text-truncate" style="max-width: 200px;">
                                                        <?= basename($doc['caminho_ficheiro']) ?>
                                                    </td>
                                                    
                                                    <!-- Upload date -->
                                                    <td><?= date('d M Y', strtotime($doc['data_upload'])) ?></td>
                                                    
                                                    <!-- Action buttons: View & Download -->
                                                    <td class="text-end pe-4">
                                                        <a href="<?= htmlspecialchars($doc['caminho_ficheiro']) ?>" 
                                                           target="_blank" 
                                                           class="btn btn-sm btn-light border">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="<?= htmlspecialchars($doc['caminho_ficheiro']) ?>" 
                                                           download 
                                                           class="btn btn-sm btn-primary-custom">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- 13. INCLUDE UPLOAD MODAL (form to add new documents) -->
            <?php include '../includes/newDocModal.php' ?>
        </div>
    </div>
</div>

<!-- 14. INCLUDE FOOTER (closing HTML tags, scripts, etc.) -->
<?php include "../includes/footer.php"?>