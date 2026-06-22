<?php
require_once '../../config/config.php';
session_start();

// Fetch the maintenance/warranty data
$stmt = $pdo->query("
    SELECT 
        e.nome AS equipamento, 
        c.data_inicio_garantia, 
        c.data_fim_garantia, 
        c.tipo_contrato, 
        c.periodicidade, 
        c.observacoes,
        f.nome_empresa AS entidade_responsavel
    FROM equipamentos e
    LEFT JOIN contratos_manutencao c ON e.id = c.equipamento_id
    LEFT JOIN fornecedores f ON c.fornecedor_id = f.id
    ORDER BY e.nome
");
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Fetch data for the Modal (The missing part)
$stmtEq = $pdo->query("SELECT id, nome, serial FROM equipamentos ORDER BY nome");
$allEquipments = $stmtEq->fetchAll(PDO::FETCH_ASSOC);

$stmtFo = $pdo->query("SELECT id, nome_empresa FROM fornecedores ORDER BY nome_empresa");
$fornecedores = $stmtFo->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php' ?>
<div class="d-flex">
    <?php include '../includes/nav.php' ?>
    <div id="content" class="w-100">
        <?php include '../includes/topbar.php' ?>
        
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark"><i class="bi bi-shield-check text-primary me-2"></i>Garantias e Contratos</h5>
                <button class="btn btn-primary-custom " data-bs-toggle="modal" data-bs-target="#newContractModal">
                    <i class="bi bi-plus-lg me-1"></i> Novo Registo
                </button>
            </div>

            <div class="card shadow-sm border-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Equipamento</th>
                                <th>Garantia (Início/Fim)</th>
                                <th>Contrato</th>
                                <th>Entidade</th>
                                <th>Periodicidade</th>
                                <th>Obs.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($records as $r): ?>
                            <tr>
                                <td class="fw-bold"><?= htmlspecialchars($r['equipamento']) ?></td>
                                <td>
                                    <?php if($r['data_inicio_garantia']): ?>
                                        <div class="small">Início: <?= $r['data_inicio_garantia'] ?></div>
                                        <div class="small text-danger fw-bold">Fim: <?= $r['data_fim_garantia'] ?></div>
                                    <?php else: ?>
                                        <span class="text-muted small">Sem garantia</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($r['tipo_contrato'] ?: 'N/A') ?></td>
                                <td><?= htmlspecialchars($r['entidade_responsavel'] ?: 'N/A') ?></td>
                                <td><?= htmlspecialchars($r['periodicidade'] ?: 'N/A') ?></td>
                                <td><?= htmlspecialchars($r['observacoes'] ?: '-') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!--modal add registry-->
            <div class="modal fade" id="newContractModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title">Novo Registo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        
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

                        <div class="modal-body">
                            <div class="tab-content">
                                
                                <div class="tab-pane fade show active" id="garantia" role="tabpanel">
                                    <form action="../includes/process_new_maintenance.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="tipo_registo" value="garantia">
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Equipamento</label>
                                                <select name="equipamento_id" class="form-select" required>
                                                    <option value="">Selecione o equipamento...</option>
                                                    <?php foreach($allEquipments as $eq): ?>
                                                        <option value="<?= $eq['id'] ?>"><?= htmlspecialchars($eq['nome']) ?> (<?= htmlspecialchars($eq['serial']) ?>)</option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Entidade Responsável (Fornecedor)</label>
                                                <select name="fornecedor_id" class="form-select">
                                                    <option value="">Sem fornecedor associado</option>
                                                    <?php foreach($fornecedores as $f): ?>
                                                        <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome_empresa']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Início da Garantia</label>
                                                <input type="date" name="data_inicio" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Fim da Garantia (Validade)</label>
                                                <input type="date" name="data_fim" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Documento da Garantia (PDF, Imagem)</label>
                                            <input type="file" name="ficheiro_documento" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Observações</label>
                                            <textarea name="observacoes" class="form-control" rows="2" placeholder="Detalhes adicionais..."></textarea>
                                        </div>
                                        
                                        <div class="text-end mt-4">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Guardar Garantia</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="contrato" role="tabpanel">
                                    <form action="../includes/process_new_maintenance.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="tipo_registo" value="contrato">
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Equipamento</label>
                                                <select name="equipamento_id" class="form-select" required>
                                                    <option value="">Selecione o equipamento...</option>
                                                    <?php foreach($allEquipments as $eq): ?>
                                                        <option value="<?= $eq['id'] ?>"><?= htmlspecialchars($eq['nome']) ?> (<?= htmlspecialchars($eq['serial']) ?>)</option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Entidade Responsável</label>
                                                <select name="fornecedor_id" class="form-select">
                                                    <option value="">Sem entidade associada</option>
                                                    <?php foreach($fornecedores as $f): ?>
                                                        <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome_empresa']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Início do Contrato</label>
                                                <input type="date" name="data_inicio" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Fim do Contrato (Validade)</label>
                                                <input type="date" name="data_fim" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Tipo de Contrato</label>
                                                <select name="tipo_contrato" class="form-select" required>
                                                    <option value="Preventiva">Manutenção Preventiva</option>
                                                    <option value="Corretiva">Manutenção Corretiva</option>
                                                    <option value="Total">Contrato Total</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Periodicidade</label>
                                                <select name="periodicidade" class="form-select" required>
                                                    <option value="Mensal">Mensal</option>
                                                    <option value="Trimestral">Trimestral</option>
                                                    <option value="Semestral">Semestral</option>
                                                    <option value="Anual">Anual</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Ficheiro do Contrato (PDF)</label>
                                            <input type="file" name="ficheiro_documento" class="form-control" accept=".pdf">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Observações</label>
                                            <textarea name="observacoes" class="form-control" rows="2" placeholder="Detalhes adicionais..."></textarea>
                                        </div>

                                        <div class="text-end mt-4">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Guardar Contrato</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php' ?>