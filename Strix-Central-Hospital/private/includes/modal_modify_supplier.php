<div class="modal fade" id="modifySupplierModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px; border: none;">

            <form action="../includes/process_modify_supplier.php" method="post">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Add New Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">

                    <div class="d-flex flex-column align-items-center mb-4">
                        <div class="qr-placeholder" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; background: #f1f5f9; border-radius: 50%;">
                            <i class="bi bi-building" style="font-size: 2rem; color: #64748b;"></i>
                        </div>
                        <small class="text-muted mt-2">Modify a supplier</small>
                    </div>

                    <div class="row g-3 mb-4 pb-4" style="border-bottom: 1px dashed #cbd5e1;">
                        <div class="col-12">
                            <label class="form-label fw-bold" style="color: #0f172a;">Select Equipment</label>
                            <select name="id" id="supplierSelect" class="form-select border-warning" style="border-radius:8px;" required>
                                <option value="">Choose an item from inventory...</option>
                                <?php if(!empty($fornecedores)): ?>
                                    <?php foreach($fornecedores as $fo): ?>
                                        <option value="<?= $fo['id'] ?>">
                                            <?= htmlspecialchars($fo['nome_empresa']) ?> (Email: <?= htmlspecialchars($fo['email']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Nome da Empresa</label>
                            <input type="text" name="nome_empresa" class="form-control" placeholder="e.g. Medtronic" style="border-radius:8px;">
                        </div>
                        
                        <div class="col-6">
                            <label class="form-label">Tipo de fornecedor </label>
                            <select class="form-select" name="tipo_fornecedor" style="border-radius:8px;">
                                <option value="">Select category...</option>
                                <option value="Fabricante">Fabricante</option>
                                <option value="Distribuidor">Distribuidor ou Fornecedor Comercial</option>
                                <option value="Assistencia">Empresa de assistencia tecnica</option>
                                <option value="Consumiveis">Fornecedor de Consumiveis e acessorios</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Contact Email</label>
                            <input type="email" name="email" class="form-control" placeholder="contact@supplier.com" style="border-radius:8px;">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Website</label>
                            <input type="text" name="website" class="form-control" placeholder="www.empresa.com" style="border-radius:8px;">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Endereco</label>
                            <input type="text" name="endereco" class="form-control" placeholder="Rua XXXX, 123, Porto Portugal" style="border-radius:8px;">
                        </div>

                        <div class="col-6">
                            <label class="form-label">nif</label>
                            <input type="text" name="nif" class="form-control" placeholder="123 456 789" style="border-radius:8px;">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Contact Telefone</label>
                            <input type="tel" name="telefone" class="form-control" placeholder="+351 911 871 461" style="border-radius:8px;">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Pessoa Contacto (PC)</label>
                            <input type="text" name="pessoa_contacto" class="form-control" placeholder="Joana Teixeira" style="border-radius:8px;">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Telefone da (PC)</label>
                            <input type="tel" name="telefone_contacto" class="form-control" placeholder="+351 911 871 461" style="border-radius:8px;">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description <span class="text-muted fw-normal">(optional)</span></label>
                            <textarea class="form-control" rows="2" placeholder="Brief info about the supplier" style="border-radius:8px;"></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0 pt-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:8px;">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-plus-lg me-1"></i> Add Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>