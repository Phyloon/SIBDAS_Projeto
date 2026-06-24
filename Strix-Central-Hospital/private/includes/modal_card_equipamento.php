<!--IT SAYS ERROR BUT IT WORKS ANYWAY, WHEN IT IS INCLUDED $allEquipments IS DEFINED B4HAND, SO IT WORKS-->

<!--remover aviso de erro-->
<?php /** @var array $allEquipments */ ?>

<?php
// 1. Only run this query once per page load
if (!isset($maintenanceData)) {
    // Assuming you have access to $pdo from your config.php
    $stmt = $pdo->query("SELECT * FROM documentos_equipamento");
    $rawContracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 2. Map data by equipment ID for fast access
    $maintenanceData = [];
    foreach ($rawContracts as $contract) {
        $maintenanceData[$contract['equipamento_id']] = $contract;
        if (!empty($contract['data_inicio_garantia'])) {

            $proxima = new DateTime($contract['data_inicio_garantia']);

            switch ($contract['periodicidade']) {
                case '3 meses':
                    $proxima->modify('+3 months');
                    break;

                case '6 meses':
                    $proxima->modify('+6 months');
                    break;

                case '12 meses':
                    $proxima->modify('+12 months');
                    break;
            }

            $contract['proxima_manutencao'] = $proxima->format('Y-m-d');
        } else {
                $contract['proxima_manutencao'] = null;
        }
        $maintenanceData[$contract['equipamento_id']] = $contract;
    }

}

// 2. NOVA LÓGICA: Carregar TODOS os documentos e agrupar por equipamento_id
if (!isset($docsByEquipment)) {
    $stmtDocs = $pdo->query("
        SELECT id, equipamento_id, tipo_documento, nome_ficheiro, caminho_ficheiro, data_upload, data_validade 
        FROM documentos_equipamento 
        ORDER BY data_upload DESC
    ");
    $rawDocs = $stmtDocs->fetchAll(PDO::FETCH_ASSOC);

    $docsByEquipment = [];
    foreach ($rawDocs as $doc) {
        // Agrupa os documentos usando o equipamento_id como chave
        $docsByEquipment[$doc['equipamento_id']][] = $doc;
    }
}
?>

<?php foreach ($allEquipments as $eq): ?>
    <div class="modal fade" id="modal_<?= $eq['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold"><?= htmlspecialchars($eq['nome']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 75vh;">
                    <div class="row mb-4">
                        <div class="col-7">
                            <img src="<?= htmlspecialchars($eq['imagem']) ?>" alt="..." class="img-fluid">
                        </div>
                        
                        <div class="col-5 d-flex flex-column justify-content-center gap-2">
                            <div class="card px-3 py-2">
                                <small class="text-muted">ID</small>
                                <span><i class="bi bi-hash me-1"></i><?= htmlspecialchars($eq['serial']) ?></span>
                            </div>
                            
                            <div class="card px-3 py-2">
                                <small class="text-muted">Estado</small>
                                <span><i class="bi bi-circle-fill me-1" style="text-decoration: none;" ><?= htmlspecialchars($eq['estado']) ?></i></span> 
                            </div>
                            
                            <div class="card px-3 py-2">
                                <small class="text-muted">Criticidade</small>
                                <small><i class="bi bi-exclamation-triangle-fill me-1"></i> <?= htmlspecialchars($eq['criticidade']) ?> </small>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0"><i class="bi bi-question-circle me-2 text-decoration-none"></i> Caracterizacao:</h6>
                    </div> 
                    <div class="me-2 p-2">
                        dadadad jhjhjh jhjhjh jh jhjh jhj h jhjhj hjhjh jhj hh
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0"><i class="bi bi-map-fill me-2 text-decoration-none"></i> Localização:</h6>
                        <span class="me-2"><?= htmlspecialchars(("Wing: " . $eq['location_wing'] ?? '') . " || Floor: " . ($eq['location_floor'] ?? '') . " || Room: " . ($eq['location_room'] ?? '')) ?></span>
                    </div>  

                    <hr>

                    
                    <a href="#dates-<?= $eq['id'] ?>" data-bs-toggle="collapse" role="button" class="d-flex justify-content-between align-items-center text-decoration-none text-dark fw-bold mb-2">
                        <span>
                            <i class="bi bi-calendar-event me-2"></i> Datas Importantes
                        </span>
                        <i class="bi bi-chevron-down small"></i>
                    </a>

                    <div id="dates-<?= $eq['id'] ?>" class="collapse mb-2">
                        <?php $data = $maintenanceData[$eq['id']] ?? null; ?>
                        <ul class="list-group list-group-flush p-3">
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
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>