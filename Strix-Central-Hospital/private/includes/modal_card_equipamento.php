<!--
  ERROR NOTICE: This modal code works despite occasional IDE warnings about undefined variables.
  The variables $allEquipments, $maintenanceData, and $docsByEquipment are defined elsewhere
  before this file is included.
-->

<?php /** @var array $allEquipments */ // Type hint for IDEs: $allEquipments is an array of equipment data ?>

<?php
// ------------------------------------------------------------------
// 1. FETCH MAINTENANCE DATA ONCE (per page load)
//    This block queries the documentos_equipamento table and builds
//    a map (equipment_id => contract data) for fast access later.
// ------------------------------------------------------------------
if (!isset($maintenanceData)) {
    // Run query only if not already fetched
    $stmt = $pdo->query("SELECT * FROM documentos_equipamento");
    $rawContracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Initialize the map
    $maintenanceData = [];
    foreach ($rawContracts as $contract) {
        // Store the original contract data under its equipment ID
        $maintenanceData[$contract['equipamento_id']] = $contract;
        
        // If there's a start date and a periodicidade, calculate the next maintenance date
        if (!empty($contract['data_inicio_garantia'])) {
            $proxima = new DateTime($contract['data_inicio_garantia']);
            // Add the interval based on the periodicidade
            switch ($contract['periodicidade']) {
                case '3 meses':  $proxima->modify('+3 months');  break;
                case '6 meses':  $proxima->modify('+6 months');  break;
                case '12 meses': $proxima->modify('+12 months'); break;
                default:         break; // fallback - leave unchanged
            }
            // Add the calculated date to the contract array
            $contract['proxima_manutencao'] = $proxima->format('Y-m-d');
        } else {
            $contract['proxima_manutencao'] = null;
        }
        // Store the updated contract back in the map
        $maintenanceData[$contract['equipamento_id']] = $contract;
    }
}

// ------------------------------------------------------------------
// 2. FETCH AND GROUP DOCUMENTS BY EQUIPMENT ID (once per page load)
//    Queries all documents and groups them into an associative array
//    where the key is equipment_id and the value is an array of documents.
// ------------------------------------------------------------------
if (!isset($docsByEquipment)) {
    $stmtDocs = $pdo->query("
        SELECT id, equipamento_id, tipo_documento, nome_ficheiro, caminho_ficheiro, data_upload, data_validade 
        FROM documentos_equipamento 
        ORDER BY data_upload DESC
    ");
    $rawDocs = $stmtDocs->fetchAll(PDO::FETCH_ASSOC);

    $docsByEquipment = [];
    foreach ($rawDocs as $doc) {
        // Group by equipment_id
        $docsByEquipment[$doc['equipamento_id']][] = $doc;
    }
}
?>

<!-- ============================================================== -->
<!-- LOOP OVER EQUIPMENT AND BUILD A MODAL FOR EACH ONE             -->
<!-- ============================================================== -->
<?php foreach ($allEquipments as $eq): ?>
    <!-- Modal: unique ID per equipment (modal_<id>) -->
    <div class="modal fade" id="modal_<?= $eq['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                
                <!-- Modal header: equipment name and close button -->
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold"><?= htmlspecialchars($eq['nome']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <!-- Modal body with scrollable content -->
                <div class="modal-body" style="overflow-y: auto; max-height: 75vh;">
                    
                    <!-- Row: image (left) and quick info cards (right) -->
                    <div class="row mb-4">
                        <div class="col-7">
                            <img src="<?= htmlspecialchars($eq['imagem']) ?>" alt="..." class="img-fluid">
                        </div>
                        <div class="col-5 d-flex flex-column justify-content-center gap-2">
                            <!-- Serial number -->
                            <div class="card px-3 py-2">
                                <small class="text-muted">ID</small>
                                <span><i class="bi bi-hash me-1"></i><?= htmlspecialchars($eq['serial']) ?></span>
                            </div>
                            <!-- Status -->
                            <div class="card px-3 py-2">
                                <small class="text-muted">Estado</small>
                                <span><i class="bi bi-circle-fill me-1" style="text-decoration: none;" ><?= htmlspecialchars($eq['estado']) ?></i></span> 
                            </div>
                            <!-- Criticality -->
                            <div class="card px-3 py-2">
                                <small class="text-muted">Criticidade</small>
                                <small><i class="bi bi-exclamation-triangle-fill me-1"></i> <?= htmlspecialchars($eq['criticidade']) ?> </small>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <!-- Location display -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0"><i class="bi bi-map-fill me-2 text-decoration-none"></i> Localização:</h6>
                        <span class="me-2"><?= htmlspecialchars(("Wing: " . $eq['location_wing'] ?? '') . " || Floor: " . ($eq['location_floor'] ?? '') . " || Room: " . ($eq['location_room'] ?? '')) ?></span>
                    </div>  

                    <hr>

                    <!-- =========================================================== -->
                    <!-- SECTION: Important Dates (collapsible)                      -->
                    <!-- =========================================================== -->
                    <a href="#dates-<?= $eq['id'] ?>" data-bs-toggle="collapse" role="button" class="d-flex justify-content-between align-items-center text-decoration-none text-dark fw-bold mb-2">
                        <span>
                            <i class="bi bi-calendar-event me-2"></i> Datas Importantes
                        </span>
                        <i class="bi bi-chevron-down small"></i>
                    </a>

                    <div id="dates-<?= $eq['id'] ?>" class="collapse mb-2">
                        <?php $data = $maintenanceData[$eq['id']] ?? null; ?>
                        <ul class="list-group list-group-flush p-3">
                            <!-- Next maintenance -->
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">Próxima Manutenção</div>
                                    <div class="small text-muted">
                                        <?= !empty($data['proxima_manutencao'])
                                            ? date('d M Y', strtotime($data['proxima_manutencao']))
                                            : 'Sem dados' ?>
                                    </div>
                                </div>
                                <span class="badge <?= $data ? 'bg-warning text-dark' : 'bg-secondary' ?> rounded-pill">
                                    <?= $data ? 'Agendado' : 'N/A' ?>
                                </span>
                            </li>
                            <!-- Warranty end -->
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">Fim da Garantia</div>
                                    <div class="small text-muted">
                                        <?= $data ? date('d M Y', strtotime($data['data_fim_garantia'])) : 'Sem dados' ?>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <hr>
                    <!-- =========================================================== -->
                    <!-- SECTION: Technical Data (collapsible)                      -->
                    <!-- =========================================================== -->
                    <a href="#dados_tecnicos-<?= $eq['id'] ?>" data-bs-toggle="collapse" role="button" class="d-flex justify-content-between align-items-center text-decoration-none text-dark fw-bold mb-2">
                        <span><i class="bi bi-cpu me-2"></i> Dados técnicos</span>
                        <i class="bi bi-chevron-down small"></i>
                    </a>

                    <div id="dados_tecnicos-<?= $eq['id'] ?>" class="collapse">
                        <ul class="list-group list-group-flush p-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">Número de Série</div>
                                    <div class="small text-muted"><?= htmlspecialchars($eq['serial']) ?></div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">Modelo</div>
                                    <div class="small text-muted"><?= htmlspecialchars($eq['modelo']) ?></div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">Aquisição</div>
                                    <div class="small text-muted">
                                        <?= htmlspecialchars($eq['data_aquisicao']) ?> — 
                                        <?= htmlspecialchars($eq['tipo_aquisicao']) ?> — 
                                        <?= number_format($eq['custo_aquisicao'], 2) ?>€
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">Ano de Fabrico</div>
                                    <div class="small text-muted"><?= htmlspecialchars($eq['ano_fabrico']) ?></div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <hr>

                    <!-- =========================================================== -->
                    <!-- SECTION: Associated Documents (table)                      -->
                    <!-- =========================================================== -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Documentos Associados</h5>
                        </div>
                        <div class="card-body">
                            <?php $documentos = $docsByEquipment[$eq['id']] ?? [];?>

                            <?php if (count($documentos) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Tipo</th>
                                                <th>Nome do Ficheiro</th>
                                                <th>Data Upload</th>
                                                <th>Validade</th>
                                                <th>Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($documentos as $doc): ?>
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            <?= htmlspecialchars($doc['tipo_documento']) ?>
                                                        </span>
                                                    </td>
                                                    <td><?= htmlspecialchars($doc['nome_ficheiro']) ?></td>
                                                    <td><?= date('d/m/Y', strtotime($doc['data_upload'])) ?></td>
                                                    <td>
                                                        <?= $doc['data_validade'] ? date('d/m/Y', strtotime($doc['data_validade'])) : '<span class="text-muted">N/A</span>' ?>
                                                    </td>
                                                    <td>
                                                        <!-- View and Download buttons -->
                                                        <a href="../private/<?= htmlspecialchars($doc['caminho_ficheiro']) ?>" 
                                                        target="_blank"  class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-eye "></i> Ver
                                                        </a>
                                                        
                                                        <a href="../private/<?= htmlspecialchars($doc['caminho_ficheiro']) ?>" 
                                                        download="<?= htmlspecialchars($doc['nome_ficheiro']) ?>" 
                                                        class="btn btn-sm btn-primary-custom">
                                                        <i class="bi bi-download"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted mb-0">Não existem documentos associados a este equipamento.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <hr>
                    
                    <!-- =========================================================== -->
                    <!-- SECTION: Observations (free text)                           -->
                    <!-- =========================================================== -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-exclamation-circle me-2 text-decoration-none"></i> Observações:
                        </h6>
                    </div> 

                    <div class="me-2 p-2 bg-light rounded" style="font-size: 0.9rem; color: #475569;">
                        <?php if (!empty($eq['observacao'])): ?>
                            <?= nl2br(htmlspecialchars($eq['observacao'])) ?>
                        <?php else: ?>
                            <span class="text-muted fst-italic">Sem observações registradas.</span>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Modal footer: close button -->
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>