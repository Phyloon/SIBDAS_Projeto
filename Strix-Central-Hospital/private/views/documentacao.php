<?php 
require_once '../../config/config.php';

//get all equip
$stmt = $pdo->query("SELECT id, nome, serial FROM equipamentos ORDER BY nome");
$allEquipments = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-docs" type="button" role="tab" style="color: #333;">Manual de Utilizador</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-muted" id="manuals-tab" data-bs-toggle="tab" data-bs-target="#manuals" type="button" role="tab">Manual de Servico</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-muted" id="certs-tab" data-bs-toggle="tab" data-bs-target="#certs" type="button" role="tab">Certificado de Calibracao</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-muted" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices" type="button" role="tab">Contrato de Manutencao</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-muted" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices" type="button" role="tab">Faturas/Guia de Aquisicao</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-muted" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices" type="button" role="tab">Declaracao de Conformidade</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-muted" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices" type="button" role="tab">Relatorio Tecnico</button>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- TABLE OF DOCUMENTS -->
                    <div class="card-body p-0 border-top">
                        <div class="tab-content" id="documentTabsContent">
                            <div class="tab-pane fade show active p-0" id="all-docs" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" style="table-layout: fixed">
                                        <thead class="table-light text-muted small">
                                            <tr>
                                                <th class="ps-4" style="width: 22%;">Nome do Equipamento</th>
                                                <th style="width: 22%;">Serial / ID</th>
                                                <th style="width: 22%;">Nome do Ficheiro</th>
                                                <th style="width: 22%;">Data de Upload</th>
                                                <th class="text-end pe-4" style="width: 12%;">Ações</th>
                                            </tr>
                                        </thead>

                                        <?php foreach($allEquipments as $eq): ?>
                                        <tbody>
                                            <tr>
                                                <td class="ps-4 fw-semibold text-dark"><?= htmlspecialchars($eq['nome']) ?></td>
                                                <td class="text-muted small"><?= htmlspecialchars($eq['serial']) ?></td>
                                                
                                                <!-- the thing bellow truncates file name to something small enough to not break everything-->
                                                <td class="text-truncate" style="max-width: 0;"><i class="bi bi-file-earmark-pdf text-danger me-2"></i>muito_longo.pdf</td>
                                                <td class="text-muted small">20 Jun 2026</td>
                                                <td class="text-end pe-4">
                                                    <a href="#" class="btn btn-sm btn-light border me-1" title="Visualizar"><i class="bi bi-eye"></i></a>
                                                    <a href="#" class="btn btn-sm btn-primary-custom" title="Download"><i class="bi bi-download"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <?php endforeach; ?>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
<?php include "../includes/footer.php"?>