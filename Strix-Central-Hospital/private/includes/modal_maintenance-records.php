<!-- REMOVE ERROR NOTICE: These variables are defined in the parent page before this file is included -->
<?php /** @var array $allEquipments */ ?>
<?php /** @var array $fornecedores */ ?>

<!-- ============================================================ -->
<!-- MODAL BODY with two tabs: Garantia (Warranty) and Contrato (Contract) -->
<!-- ============================================================ -->
<div class="modal-body">
    <div class="tab-content">
        
        <!-- ========================================================== -->
        <!-- TAB 1: WARRANTY (Garantia)                                   -->
        <!-- ========================================================== -->
        <div class="tab-pane fade show active" id="garantia" role="tabpanel">
            <!-- Form submits to process_new_maintenance.php, includes file upload -->
            <form action="../includes/process_new_maintenance.php" method="post" enctype="multipart/form-data">
                <!-- Hidden field identifies this as a warranty record -->
                <input type="hidden" name="tipo_registo" value="garantia">
                
                <!-- Equipment and Supplier dropdowns -->
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

                <!-- Warranty start and end dates -->
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

                <!-- File upload for warranty document -->
                <div class="mb-3">
                    <label class="form-label">Documento da Garantia (PDF, Imagem)</label>
                    <input type="file" name="ficheiro_documento" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <!-- Optional observations -->
                <div class="mb-3">
                    <label class="form-label">Observações</label>
                    <textarea name="observacoes" class="form-control" rows="2" placeholder="Detalhes adicionais..."></textarea>
                </div>
                
                <!-- Form action buttons -->
                <div class="text-end mt-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Garantia</button>
                </div>
            </form>
        </div>

        <!-- ========================================================== -->
        <!-- TAB 2: MAINTENANCE CONTRACT (Contrato)                       -->
        <!-- ========================================================== -->
        <div class="tab-pane fade" id="contrato" role="tabpanel">
            <!-- Form submits to same processor, with hidden tipo_registo = 'contrato' -->
            <form action="../includes/process_new_maintenance.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="tipo_registo" value="contrato">
                
                <!-- Equipment and Supplier dropdowns -->
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

                <!-- Contract start and end dates -->
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

                <!-- Contract type and frequency -->
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

                <!-- File upload for contract document (PDF) -->
                <div class="mb-3">
                    <label class="form-label">Ficheiro do Contrato (PDF)</label>
                    <input type="file" name="ficheiro_documento" class="form-control" accept=".pdf">
                </div>

                <!-- Optional observations -->
                <div class="mb-3">
                    <label class="form-label">Observações</label>
                    <textarea name="observacoes" class="form-control" rows="2" placeholder="Detalhes adicionais..."></textarea>
                </div>

                <!-- Form action buttons -->
                <div class="text-end mt-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Contrato</button>
                </div>
            </form>
        </div>

    </div>
</div>