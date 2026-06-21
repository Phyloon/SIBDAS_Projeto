<!--remover aviso de erro-->
<?php /** @var array $allEquipments */ ?>
<?php /** @var array $fornecedores */ ?>

<div class="modal fade" id="uploadDocModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Documents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="../includes/process_new_doc.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <label>Select Equipment</label>
                    <select name="equipamento_id" class="form-select mb-3" required>
                        <?php foreach($allEquipments as $eq): ?>
                            <option value="<?= $eq['id'] ?>"><?= $eq['nome'] ?> (<?= $eq['serial'] ?>)</option>
                        <?php endforeach; ?>
                    </select>

                    <div class="mb-3">
                        label>Document Type</label>
                        <select name="tipo_documento" class="form-select mb-3" required>
                            <option value="Manual de Utilizador">Manual de Utilizador</option>
                            <option value="Manual de Servico">Manual de Servico</option>
                            <option value="Certificado de Calibracao">Certificado de Calibracao</option>
                            <option value="Contrato de Manutencao">Contrato de Manutencao</option>
                            <option value="Faturas/Guia de Aquisicao">Faturas/Guia de Aquisicao</option>
                            <option value="Declaracao de Conformidade">Declaracao de Conformidade</option>
                            <option value="Relatorio Tecnico">Relatorio Tecnico</option>
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Data de Validade <span class="text-muted small">(opcional)</span></label>
                            <input type="date" name="data_validade" class="form-control" style="border-radius:8px;">
                        </div>
                        
                        <div class="col-6">
                            <label class="form-label">Fornecedor <span class="text-muted small">(opcional)</span></label>
                            <select name="fornecedor_id" class="form-select" style="border-radius:8px;">
                                <option value="">Nenhum / N/A</option>
                                <?php foreach($fornecedores as $f): ?>
                                    <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome_empresa']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Upload Files</label>
                        <input type="file" name="documentos[]" class="form-control" multiple required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>