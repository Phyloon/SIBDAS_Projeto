<?php
require_once '../../config/config.php';
session_start();

// Fetch all documents with equipment and supplier info
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

// Define tab filters
$tabFilters = [
    'Garantia'   => ['tipo_documento' => 'Garantia'],
    'Preventiva' => ['tipo_documento' => 'Contrato', 'tipo_contrato' => 'Preventiva'],
    'Corretiva'  => ['tipo_documento' => 'Contrato', 'tipo_contrato' => 'Corretiva'],
    'Total'      => ['tipo_documento' => 'Contrato', 'tipo_contrato' => 'Total'],
];

// Labels for tabs
$regTypes = [
    'Garantia'   => 'Garantia',
    'Preventiva' => 'Manutenção Preventiva',
    'Corretiva'  => 'Manutenção Corretiva',
    'Total'      => 'Contrato Total',
];

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

// Helper function to render document buttons
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

// Fetch data for the Modal dropdowns
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
                <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#newContractModal">
                    <i class="bi bi-plus-lg me-1"></i> Novo Registo
                </button>
            </div>

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

            <div class="tab-content">
                <?php $index = 0; foreach($regTypes as $dbValue => $displayLabel): 
                    // Use the helper function to filter records for this specific tab
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
                                                <th>Início Garantia</th>
                                                <th>Fim Garantia</th>
                                                <th>Entidade Responsável</th>
                                                <th>Documento</th>
                                                <th class="pe-4">Observações</th>
                                            <?php else: ?>
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
                                            <?php foreach($filteredRecords as $r): ?>
                                            <tr>
                                                <td class="fw-bold ps-4"><?= htmlspecialchars($r['equipamento']) ?></td>
                                                <td><?= $r['data_inicio_garantia'] ? date('d/m/Y', strtotime($r['data_inicio_garantia'])) : '-' ?></td>
                                                <td><?= $r['data_fim_garantia'] ? date('d/m/Y', strtotime($r['data_fim_garantia'])) : '-' ?></td>
                                                <?php if ($dbValue !== 'Garantia'): ?>
                                                    <td><?= htmlspecialchars($r['periodicidade'] ?: '-') ?></td>
                                                <?php endif; ?>
                                                <td><?= htmlspecialchars($r['entidade_responsavel'] ?: 'N/A') ?></td>
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