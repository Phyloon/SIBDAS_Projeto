<!--remover aviso de erro-->
<?php /** @var array $fornecedores */ ?>

<div class="modal fade" id="addEquipmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <form action="../includes/process_new_item.php" method="post">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Add New Equipment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">

                    <div class="d-flex flex-column align-items-center mb-4">
                        <div class="qr-placeholder" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; background: #f1f5f9; border-radius: 50%;">
                            <i class="bi bi-box-seam" style="font-size: 2rem; color: #64748b;"></i>
                        </div>
                        <small class="text-muted mt-2">Register new inventory item</small>
                    </div>

                    
                    <div class="row g-3">
                        <!--defaults to Disponivel-->
                        <input type="hidden" name="estado" value="Disponivel">

                        <div class="col-6">
                            <label class="form-label">Nome</label>
                            <input type="text" name="nome" class="form-control" placeholder="e.g. Defibrillator" style="border-radius:8px;">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Modelo</label>
                            <input type="text" name="modelo" class="form-control" placeholder="e.g. R Series" style="border-radius:8px;">
                        </div>
                        
                        <div class="col-4">
                            <label class="form-label">Serial</label>
                            <input type="text" name="serial" class="form-control" placeholder="e.g. 04.324.00" style="border-radius:8px;">
                        </div>

                        <div class="col-4">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca" class="form-control" placeholder="e.g. R Series" style="border-radius:8px;">
                        </div>

                        <div class="col-4">
                            <label class="form-label">Year of Mfg.</label>
                            <input type="number" min="1990" max="2026" class="form-control" name="ano_fabrico" placeholder="YYYY" style="border-radius:8px;">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Fabricante</label>
                            <select name="fornecedor_id" class="form-select" style="border-radius:8px;">
                                <option value="">Select...</option>
                                <?php foreach($fornecedores as $f): ?>
                                    <?php if($f['tipo_fornecedor'] === 'Fabricante'): ?>
                                        <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome_empresa']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-6">
                            <label class="form-label">Distribuidor Comercial</label>
                            <select name="fornecedor_distribuidor" class="form-select" style="border-radius:8px;">
                                <option value="">Select...</option>
                                <?php foreach($fornecedores as $f): ?>
                                    <?php if($f['tipo_fornecedor'] === 'Distribuidor' || $f['tipo_fornecedor'] === 'Representante'): ?>
                                        <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome_empresa']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-6">
                            <label class="form-label">Assistência Técnica</label>
                            <select name="fornecedor_manutencao" class="form-select" style="border-radius:8px;">
                                <option value="">Select...</option>
                                <?php foreach($fornecedores as $f): ?>
                                    <?php if($f['tipo_fornecedor'] === 'Manutencao'): ?>
                                        <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome_empresa']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-6">
                            <label class="form-label">Fornecedor de Consumíveis</label>
                            <select name="fornecedor_consumiveis" class="form-select" style="border-radius:8px;">
                                <option value="">Select...</option>
                                <?php foreach($fornecedores as $f): ?>
                                    <?php if($f['tipo_fornecedor'] === 'Consumiveis'): ?>
                                        <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome_empresa']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        

                        

                        <div class="col-4">
                            <label class="form-label">Wing (Ala)</label>
                            <select class="form-select" name="location_wing" style="border-radius:8px;">
                                <option value="">Select...</option>
                                <option value="WA">Wing A</option>
                                <option value="WB">Wing B</option>
                                <option value="WC">Wing C</option>
                                <option value="WD">Wing D</option>
                                <option value="WE">Wing E</option>
                            </select>
                        </div>

                        <div class="col-4">
                            <label class="form-label">Floor</label>
                            <input type="text" class="form-control" name="location_floor" placeholder="e.g. 02" style="border-radius:8px;">
                        </div>

                        <div class="col-4">
                            <label class="form-label">Room</label>
                            <input type="text" class="form-control" name="location_room" placeholder="e.g. 201" style="border-radius:8px;">
                        </div>

                        <div class="col-6">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="departamento" style="border-radius:8px;">
                                <option value="">Select...</option>
                                <option>Cardiology</option>
                                <option>ICU</option>
                                <option>Neurology</option>
                                <option>Radiology</option>
                                <option>Surgery</option>
                            </select>
                        </div>

                        <div class="col-6">
                            <label class="form-label">Group</label>
                            <select class="form-select" name="grupo" style="border-radius:8px;">
                                <option value="">Select...</option>
                                <option>Grupo 1- Monitorizacao</option>
                                <option>Grupo 2- Suporte de Vida</option>
                                <option>Grupo 3- Terapia</option>
                                <option>Grupo 4- Diagnostico</option>
                                <option>Grupo 5- Laboratorio</option>
                                <option>Grupo 6- Esterilizacao</option>
                                <option>Grupo 7- Reabilitacao</option>
                            </select>
                        </div>

                        <div class="col-4">
                            <label class="form-label">Acquisition Type</label>
                            <select class="form-select" name="tipo_aquisicao" style="border-radius:8px;">
                                <option value="">Select...</option>
                                <option>Compra</option>
                                <option>Leasing</option>
                                <option>Doação</option>
                            </select>
                        </div>

                        <div class="col-4">
                            <label class="form-label">Cost (€)</label>
                            <input type="number" step="0.01" class="form-control" name="custo_aquisicao" placeholder="0.00" style="border-radius:8px;">
                        </div>

                        <div class="col-4">
                            <label class="form-label">Date of aquisition </label>
                            <input type="date" class="form-control" name="data_aquisicao" style="border-radius:8px;">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description <span class="text-muted fw-normal">(optional)</span></label>
                            <textarea name="descricao" class="form-control" rows="2" placeholder="Brief technical notes" style="border-radius:8px;"></textarea>
                        </div>
                    </div>
                    
                    
                </div>
                <div class="modal-footer border-0 pt-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:8px;">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-plus-lg me-1"></i> Add Equipment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>