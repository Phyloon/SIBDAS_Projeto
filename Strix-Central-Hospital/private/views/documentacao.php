<?php 
require_once '../../config/config.php';
session_start();
//specify each type of foc possible
$docTypes = [
    "Manual de Utilizador", "Manual de Servico", "Certificado de Calibracao", "Faturas/Guia de Aquisicao", 
    "Declaracao de Conformidade", "Relatorio Tecnico"
];

//get all documents joined w equip
$stmt = $pdo->query("SELECT d.*, e.nome as nome_equipamento, e.serial as serial_equipamento, f.nome_empresa FROM documentos_equipamento d JOIN equipamentos e ON d.equipamento_id = e.id LEFT JOIN fornecedores f ON d.fornecedor_id = f.id ORDER BY d.data_upload DESC ");
$allDocuments = $stmt->fetchAll(PDO::FETCH_ASSOC);


// all equip
$stmtEq = $pdo->query("SELECT id, nome, serial FROM equipamentos ORDER BY nome");
$allEquipments = $stmtEq->fetchAll(PDO::FETCH_ASSOC);

// all equip
$stmtFo = $pdo->query("SELECT id, nome_empresa FROM fornecedores ORDER BY nome_empresa  ");
$fornecedores = $stmtFo->fetchAll(PDO::FETCH_ASSOC);



// 3. Helper function to filter docs
function getDocsByType($docs, $type) {
    return array_filter($docs, function($d) use ($type) {
        return $d['tipo_documento'] === $type;
    });
}
?>

<?php include '../includes/header.php'?>

    <div class="d-flex">
        
        <?php include '../includes/nav.php'?>

        <!--Page content-->
        <div id="content">

            <!-- Top Navigation Bar -->
            <?php include '../includes/topbar.php'?>

            <div class="p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><i class="bi bi-folder2-open text-primary me-2"></i>Gestão de Documentos</h5>
                        <p class="text-muted small mb-0">Centralized documentation and manuals for all TrueTech medical assets.</p>
                    </div>
                    <button class="btn btn-primary-custom btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocModal" style="border-radius: 8px;">
                        <i class="bi bi-cloud-arrow-up me-2"></i> Upload Document
                    </button>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                    
                    <div class="card-header bg-white border-bottom-0 pt-3 pb-0 px-3">
                        <ul class="nav nav-tabs border-bottom-0" id="documentTabs" role="tablist">
                            <?php foreach($docTypes as $index => $type): ?>
                                <li class="nav-item">
                                    <button class="nav-link <?= $index === 0 ? 'active' : '' ?>" 
                                            data-bs-toggle="tab" data-bs-target="#tab-<?= $index ?>" type="button">
                                        <?= $type ?>
                                    </button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <!--CONTENT TABLE-->
                    <div class="tab-content mt-3">
                        <?php foreach($docTypes as $index => $type): 
                            $filteredDocs = getDocsByType($allDocuments, $type);
                        ?>
                            <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" id="tab-<?= $index ?>">
                                <div class="table-responsive">
                                    <table class="table table-hover">
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
                                            <?php if (empty($filteredDocs)): ?>
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">No documents found for this category.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach($filteredDocs as $doc): ?>
                                                    <tr>
                                                        <td class="ps-4"><?= htmlspecialchars($doc['nome_equipamento']) ?></td>
                                                        <td><?= htmlspecialchars($doc['serial_equipamento']) ?></td>
                                                        <td><?= !empty($doc['nome_empresa']) ? htmlspecialchars($doc['nome_empresa']) : '<span class="text-muted">N/A</span>' ?></td>
                                                        <td>
                                                            <?php if (!empty($doc['data_validade'])): 
                                                                $isExpired = (new DateTime($doc['data_validade'])) < (new DateTime());
                                                            ?>
                                                                <span class="badge <?= $isExpired ? 'bg-danger' : 'bg-success bg-opacity-10 text-success' ?>">
                                                                    <?= $isExpired ? 'Expirado a ' : 'Válido até ' ?><?= date('d/m/Y', strtotime($doc['data_validade'])) ?>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="text-muted small">N/A</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-truncate" style="max-width: 200px;"><?= basename($doc['caminho_ficheiro']) ?></td>
                                                        <td><?= date('d M Y', strtotime($doc['data_upload'])) ?></td>
                                                        <td class="text-end pe-4">
                                                            <a href="<?= htmlspecialchars($doc['caminho_ficheiro']) ?>" target="_blank" class="btn btn-sm btn-light border"><i class="bi bi-eye"></i></a>
                                                            <a href="<?= htmlspecialchars($doc['caminho_ficheiro']) ?>" download class="btn btn-sm btn-primary-custom"><i class="bi bi-download"></i></a>
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
                <?php include '../includes/newDocModal.php' ?>
            </div>
        </div>
    </div>
<?php include "../includes/footer.php"?>