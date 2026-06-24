<?php /** @var array $fornecedores */ // List of all suppliers for dropdowns ?>
<?php /** @var array $allEquipments */ // List of all equipment for selection dropdown ?>

<!-- Modal for modifying an existing equipment item -->
<div class="modal fade" id="modifyEquipmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            
            <!-- Form submits to process_modify_item.php, updates equipment data -->
            <form action="../includes/process_modify_item.php" method="post" id="modifyEquipmentForm">
                
                <!-- Hidden field: equipment ID (populated by JavaScript when selecting equipment) -->
                <input type="hidden" name="id" id="mod_id">
                
                <!-- Modal header -->
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Modify Equipment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body pt-3">

                    <!-- Decorative icon and helper text -->
                    <div class="d-flex flex-column align-items-center mb-4">
                        <div class="qr-placeholder" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; background: #fffbeb; border-radius: 50%;">
                            <i class="bi bi-pencil-square" style="font-size: 2rem; color: #d97706;"></i>
                        </div>
                        <small class="text-muted mt-2">Update existing inventory item</small>
                    </div>

                    <!-- Equipment selection dropdown (the item to modify) -->
                    <div class="row g-3 mb-4 pb-4" style="border-bottom: 1px dashed #cbd5e1;">
                        <div class="col-12">
                            <label class="form-label fw-bold" style="color: #0f172a;">Select Equipment</label>
                            <select name="id" id="equipmentSelect" class="form-select border-warning" style="border-radius:8px;" required>
                                <option value="">Choose an item from inventory...</option>
                                <?php if(!empty($allEquipments)): ?>
                                    <?php foreach($allEquipments as $eq): ?>
                                        <option value="<?= $eq['id'] ?>">
                                            <?= htmlspecialchars($eq['nome']) ?> (SN: <?= htmlspecialchars($eq['serial']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Editable fields for equipment properties -->
                    <div class="row g-3">
                        <!-- Hidden field: estado (default set by JS) -->
                        <input type="hidden" name="estado" id="mod_estado" value="Disponivel">

                        <!-- Basic info: Name, Model -->
                        <div class="col-6">
                            <label class="form-label">Nome</label>
                            <input type="text" name="nome" id="mod_nome" class="form-control" placeholder="e.g. Defibrillator" style="border-radius:8px;">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Modelo</label>
                            <input type="text" name="modelo" id="mod_modelo" class="form-control" placeholder="e.g. R Series" style="border-radius:8px;">
                        </div>

                        <!-- Status and Criticality dropdowns -->
                        <div class="col-6">
                            <label class="form-label">Estado (Status)</label>
                            <select class="form-select" name="estado" style="border-radius:8px;" required>
                                <option value="Disponivel">Disponível (Free)</option>
                                <option value="Em Uso">Em Uso (In Use)</option>
                                <option value="Em Manutencao">Em Manutenção (Under Repair)</option>
                                <option value="Inativo">Inativo (Inactive)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Criticality</label>
                            <select class="form-select" name="criticidade" style="border-radius:8px;" required>
                                <option value="">Select...</option>
                                <option value="Sporte de Vida">Suporte de Vida (High)</option>
                                <option value="Alta">Alta (High)</option>
                                <option value="Média">Média (Medium)</option>
                                <option value="Baixa">Baixa (Low)</option>
                            </select>
                        </div>
                        
                        <!-- Serial, Brand, Year of Manufacture -->
                        <div class="col-4">
                            <label class="form-label">Serial</label>
                            <input type="text" name="serial" id="mod_serial" class="form-control" placeholder="e.g. 04.324.00" style="border-radius:8px;">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca" id="mod_marca" class="form-control" placeholder="e.g. R Series" style="border-radius:8px;">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Year of Mfg.</label>
                            <input type="number" min="1990" max="2026" class="form-control" name="ano_fabrico" id="mod_ano_fabrico" placeholder="YYYY" style="border-radius:8px;">
                        </div>

                        <!-- Supplier associations (many-to-many via bridge table) -->
                        <!-- Fabricante (Manufacturer) -->
                        <div class="col-6">
                            <label class="form-label">Fabricante</label>
                            <select name="fornecedor_id" id="mod_fornecedor_id" class="form-select" style="border-radius:8px;">
                                <option value="">Select...</option>
                                <?php foreach($fornecedores as $f): ?>
                                    <?php if($f['tipo_fornecedor'] === 'Fabricante'): ?>
                                        <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome_empresa']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Distribuidor Comercial -->
                        <div class="col-6">
                            <label class="form-label">Distribuidor Comercial</label>
                            <select name="fornecedor_distribuidor" id="mod_fornecedor_distribuidor" class="form-select" style="border-radius:8px;">
                                <option value="">Select...</option>
                                <?php foreach($fornecedores as $f): ?>
                                    <?php if($f['tipo_fornecedor'] === 'Distribuidor' || $f['tipo_fornecedor'] === 'Representante'): ?>
                                        <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome_empresa']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Assistência Técnica -->
                        <div class="col-6">
                            <label class="form-label">Assistência Técnica</label>
                            <select name="fornecedor_manutencao" id="mod_fornecedor_manutencao" class="form-select" style="border-radius:8px;">
                                <option value="">Select...</option>
                                <?php foreach($fornecedores as $f): ?>
                                    <?php if($f['tipo_fornecedor'] === 'Manutencao'): ?>
                                        <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome_empresa']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Fornecedor de Consumíveis -->
                        <div class="col-6">
                            <label class="form-label">Fornecedor de Consumíveis</label>
                            <select name="fornecedor_consumiveis" id="mod_fornecedor_consumiveis" class="form-select" style="border-radius:8px;">
                                <option value="">Select...</option>
                                <?php foreach($fornecedores as $f): ?>
                                    <?php if($f['tipo_fornecedor'] === 'Consumiveis'): ?>
                                        <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome_empresa']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Location: Wing, Floor, Room -->
                        <div class="col-4">
                            <label class="form-label">Wing (Ala)</label>
                            <select class="form-select" name="location_wing" id="mod_location_wing" style="border-radius:8px;">
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
                            <input type="text" class="form-control" name="location_floor" id="mod_location_floor" placeholder="e.g. 02" style="border-radius:8px;">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Room</label>
                            <input type="text" class="form-control" name="location_room" id="mod_location_room" placeholder="e.g. 201" style="border-radius:8px;">
                        </div>

                        <!-- Department and Group -->
                        <div class="col-6">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="departamento" id="mod_departamento" style="border-radius:8px;">
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
                            <select class="form-select" name="grupo" id="mod_grupo" style="border-radius:8px;">
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

                        <!-- Acquisition info: type, cost, date -->
                        <div class="col-4">
                            <label class="form-label">Acquisition Type</label>
                            <select class="form-select" name="tipo_aquisicao" id="mod_tipo_aquisicao" style="border-radius:8px;">
                                <option value="">Select...</option>
                                <option>Compra</option>
                                <option>Leasing</option>
                                <option>Doação</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Cost (€)</label>
                            <input type="number" step="0.01" class="form-control" name="custo_aquisicao" id="mod_custo_aquisicao" placeholder="0.00" style="border-radius:8px;">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Date of aquisition</label>
                            <input type="date" class="form-control" name="data_aquisicao" id="mod_data_aquisicao" style="border-radius:8px;">
                        </div>

                        <!-- Optional description/notes -->
                        <div class="col-12">
                            <label class="form-label">Description <span class="text-muted fw-normal">(optional)</span></label>
                            <textarea name="descricao" id="mod_descricao" class="form-control" rows="2" placeholder="Brief technical notes" style="border-radius:8px;"></textarea>
                        </div>
                    </div>
                    
                </div>
                <!-- Modal footer: cancel and save buttons -->
                <div class="modal-footer border-0 pt-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:8px;">Cancel</button>
                    <button type="submit" class="btn btn-warning" style="border-radius:8px;">
                        <i class="bi bi-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>