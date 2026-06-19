<?php require_once '../includes/header.php';
//get all suppliers
$stmt = $pdo->query("SELECT * FROM fornecedores ORDER BY nome_empresa");
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

//get the join between supplier id's on mixed table and the equipment id's associated to them
$stmtEq = $pdo->query("SELECT ef.fornecedor_id, e.* FROM equipamento_fornecedor ef INNER JOIN equipamentos e ON ef.equipamento_id = e.id ORDER BY e.nome");
$allEquipments = $stmtEq->fetchAll(PDO::FETCH_ASSOC);

//agrupar equipamento por fornecedor
$eqBySupplier = [];
foreach($allEquipments as $eq) {
    // second one shows desconheido if an equipment doesnt have a supplier
    $supplierID = !empty($eq['fornecedor_id']) ? $eq['fornecedor_id'] : 'Desconhecido';
    $eqBySupplier[$supplierID][] = $eq;
}

?>



    <div class="d-flex">

        <?php include '../includes/nav.php'?>

        <div id="content">
            
            <!-- Top Navigation Bar -->
            <?php include '../includes/topbar.php'?>

            <!--main page content-->
            <div class="p-4">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><i class="bi bi-truck text-primary me-2"></i>Fornecedores & Equipamentos Associados</h5>
                        <p class="text-muted small mb-0">Consulte e gira o seu inventário através da rede de fornecedores da TrueTech.</p>
                    </div>

                    <!--adicionar fornecedor/item-->
                    <div class="dropdown align-items-center">
                        <button class="btn btn-primary-custom btn-sm" data-bs-toggle="dropdown" data-bs-target="#addDropdown" style="border-radius: 8px;">
                            <i class="bi bi-chevron-down me-2"></i> Adicionar
                        </button>
                        <div class="dropdown-menu" id="addDropdown">
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                                <i class="bi bi-building me-2"></i> Adicionar Fornecedor
                            </button>
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                                <i class="bi bi-wrench me-2"></i> Adicionar Equipamento
                            </button>
                        </div>
                    </div>
                    
                </div>

                <!--main page content-->
                <div class="accordion" id="suppliers-accordion">
                    
                    <!--information tab-->
                    <div class="accordion" id="supplierInfoAccordion">

                        
                        <div class="accordion-item border-0 shadow-sm mb-3" style="border-radius: 12px; overflow: hidden;" >
                            <h2 class="accordion-header" id="moreinfo">
                                <button class="accordion-button collapsed fw-bold d-flexR" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInfo"  style="background-color: #fff; color: #333;">
                                    <div class="bg-light p-2 rounded text-primary">
                                        <i class="bi bi-plus fs-5"></i>
                                    </div>
                                    <div class="d-flex flex-column flex-grow-1 text-start">
                                        <span class="fs-6">More Information:</span>
                                        <span class="text-muted small">Open to see contacts, NIF, and other details of all suppliers.</span>
                                    </div>  
                                </button>
                                
                            </h2>
                            <div id="collapseInfo" class="accordion-collapse collapse ">
                                <div class="accordion-body bg-light border-top p-4">
                                    <div class="row g-4">
                                        <div class="card border-0 shadow-sm mb-4">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="fw-bold m-0"><i class="bi bi-funnel me-2"></i>Filtrar Fornecedores</h6>
                                                    
                                                    <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters" aria-expanded="false" aria-controls="advancedFilters">
                                                        <i class="bi bi-chevron-down"></i> More filters
                                                    </button>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label small">Nome da empresa</label>
                                                        <input type="text" class="form-control form-control-sm" placeholder="Nome...">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small">Nif</label>
                                                        <input type="text" class="form-control form-control-sm" placeholder="NIF...">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small">Telefone</label>
                                                        <input type="text" class="form-control form-control-sm" placeholder="Telefone...">
                                                    </div>
                                                </div>

                                                <div class="collapse" id="advancedFilters">
                                                    <div class="row g-3 pt-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label small">Email</label>
                                                            <input type="text" class="form-control form-control-sm" placeholder="Email...">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label small">Endereço</label>
                                                            <input type="text" class="form-control form-control-sm" placeholder="Endereço...">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label small">Website</label>
                                                            <input type="text" class="form-control form-control-sm" placeholder="Website...">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label small">Pessoa de Contacto</label>
                                                            <input type="text" class="form-control form-control-sm" placeholder="Nome da pessoa...">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label small">Telefone da P.C.</label>
                                                            <input type="text" class="form-control form-control-sm" placeholder="Telefone...">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label small">Tipo de Fornecedor</label>
                                                            <input type="text" class="form-control form-control-sm" placeholder="Tipo...">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nome da empresa</th>
                                                    <th>Nif</th>
                                                    <th>Telefone</th>
                                                    <th>Email</th>
                                                    <th>Endereço</th>
                                                    <th>Website</th>
                                                    <th>Pessoa de Contacto</th>
                                                    <th>Telefone da P.C.</th>
                                                    <th>Tipo de Fornecedor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($fornecedores as $f): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($f['nome_empresa']) ?></td>
                                                        <td><?= htmlspecialchars($f['nif']) ?></td>
                                                        <td><?= htmlspecialchars($f['telefone']) ?></td>

                                                        <td>
                                                            <a href="mailto:<?= htmlspecialchars($f['email']) ?>">
                                                                <?= htmlspecialchars($f['email']) ?>
                                                            </a>
                                                        </td>

                                                        <td><?= htmlspecialchars($f['endereco']) ?></td>

                                                        <td>
                                                            <a href="<?= htmlspecialchars($f['website']) ?>" target="_blank">
                                                                Website
                                                            </a>
                                                        </td>

                                                        <td><?= htmlspecialchars($f['pessoa_contacto']) ?></td>

                                                        <td><?= htmlspecialchars($f['telefone_contacto']) ?></td>

                                                        <td><?= htmlspecialchars($f['tipo_fornecedor']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!--Fornecedores e equipamentos correspondentes-->
                    <?php foreach($fornecedores as $f): ?>
                        <?php
                            $supplierId = $f['id'];
                            // Get equipment for this specific supplier, empty array if none exist yet
                            $equipamentosMarca = $eqBySupplier[$supplierId] ?? []; 
                            $totalEq = count($equipamentosMarca);
                            
                            // Using the numeric ID makes it safe for Bootstrap targets
                            $safeId = $supplierId;
                        ?>
                        <div class="accordion-item border-0 shadow-sm mb-3" style="border-radius: 12px; overflow: hidden;">
                            <h2 class="accordion-header" id="heading<?= $safeId ?>">
                                <button class="accordion-button collapsed fw-bold d-flex align-items-center gap-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $safeId ?>" aria-expanded="false" style="background-color: #fff; color: #333;">
                                    <div class="bg-light p-2 rounded text-primary">
                                        <i class="bi bi-building fs-5"></i>
                                    </div>
                                    <div class="d-flex flex-column flex-grow-1 text-start">
                                        <span class="fs-6"><?= htmlspecialchars($f['nome_empresa']) ?></span>
                                        <span class="text-muted small fw-normal">Equipamentos associados</span>
                                    </div>
                                    
                                    <span class="badge bg-light text-dark border px-3 py-2 me-3 fw-normal" style="border-radius: 6px;">
                                        Total Equipments: <?= $totalEq ?>
                                    </span>
                                </button>
                            </h2>

                            <div id="collapse<?= $safeId ?>" class="accordion-collapse collapse" data-bs-parent="#suppliers-accordion">
                                <div class="accordion-body bg-light border-top p-4">
                                    <div class="row g-4">
                                        
                                        <?php if (empty($equipamentosMarca)): ?>
                                            <div class="col-12 text-center text-muted py-3">
                                                <i class="bi bi-box-seam fs-4 d-block mb-2"></i>
                                                Nenhum equipamento associado a este fornecedor.
                                            </div>
                                        <?php else: ?>
                                            <?php foreach($equipamentosMarca as $eq): 
                                                $estado = $eq['estado'];
                                                $bClass = 'secondary';
                                                if ($estado === 'Disponivel') $bClass = 'success';
                                                elseif ($estado === 'Em Uso') $bClass = 'primary';
                                                elseif ($estado === 'Em Manutenção') $bClass = 'warning text-dark';
                                                elseif ($estado === 'Fora de Servico') $bClass = 'danger';
                                            ?>
                                        <div class="col-3">
                                            <div class="card border-0 shadow-sm h-100" style="cursor: pointer;">
                                                <div style="padding: 15px;">   
                                                    <img src="<?= htmlspecialchars($eq['imagem']) ?>" class="card-img-top" alt="..." style="border-radius: 10px; height: 140px; object-fit: cover;">
                                                </div>
                                                <div class="card-body py-2">
                                                    <h6 class="card-title text-center fw-bold mb-3"><?= htmlspecialchars($eq['nome']) ?></h6>
                                                </div>
                                                <ul class="list-group list-group-flush text-center small">
                                                    <li class="list-group-item text-muted"><?= htmlspecialchars($eq['serial']) ?></li>
                                                    <li class="list-group-item"><?= htmlspecialchars($eq['modelo']) ?></li>
                                                    <li class="list-group-item">
                                                        <span class="badge bg-<?= $bClass ?> bg-opacity-10 text-<?= str_replace(' text-dark', '', $bClass) ?> border border-<?= str_replace(' text-dark', '', $bClass) ?> border-opacity-25 rounded-pill">
                                                            <?= htmlspecialchars($eq['estado']) ?>
                                                        </span>
                                                    </li>
                                                    <li class="list-group-item text-muted">
                                                        <?= htmlspecialchars($eq['departamento'] ?? 'N/A') ?> <i class="bi bi-dot"></i> <?= htmlspecialchars($eq['local_equipamento'] ?? 'N/A') ?>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    <?php endforeach; ?>
                </div>

                <!--modals adicionar fornecedor-->
                <div class="modal fade" id="addSupplierModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content" style="border-radius: 16px; border: none;">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold">Add New Supplier</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body pt-3">

                                <div class="d-flex flex-column align-items-center mb-4">
                                    <div class="qr-placeholder" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; background: #f1f5f9; border-radius: 50%;">
                                        <i class="bi bi-building" style="font-size: 2rem; color: #64748b;"></i>
                                    </div>
                                    <small class="text-muted mt-2">Upload or assign a supplier logo</small>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Supplier Name</label>
                                        <input type="text" class="form-control" placeholder="e.g. Medtronic" style="border-radius:8px;">
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label">Category</label>
                                        <select class="form-select" style="border-radius:8px;">
                                            <option value="">Select category...</option>
                                            <option>Life Support</option>
                                            <option>Imaging & Diagnostics</option>
                                            <option>Patient Monitoring</option>
                                            <option>Surgical Equipment</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Contact Email</label>
                                        <input type="email" class="form-control" placeholder="contact@supplier.com" style="border-radius:8px;">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Description <span class="text-muted fw-normal">(optional)</span></label>
                                        <textarea class="form-control" rows="2" placeholder="Brief info about the supplier" style="border-radius:8px;"></textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer border-0 pt-3">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:8px;">Cancel</button>
                                <button type="button" class="btn btn-primary-custom">
                                    <i class="bi bi-plus-lg me-1"></i> Add Supplier
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                 <!--modal adicionar equipamento-->
                <div class="modal fade" id="addEquipmentModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content" style="border-radius: 16px; border: none;">
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
                                    <div class="col-6">
                                        <label class="form-label">Equipment Name</label>
                                        <input type="text" class="form-control" placeholder="e.g. Defibrillator" style="border-radius:8px;">
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Equipment Model</label>
                                        <input type="text" class="form-control" placeholder="e.g. R Series" style="border-radius:8px;">
                                    </div>
                                    
                                    <div class="col-6">
                                        <label class="form-label">Equipment ID</label>
                                        <input type="text" class="form-control" placeholder="e.g. 04.324.00" style="border-radius:8px;">
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Criticality</label>
                                        <select class="form-select" style="border-radius:8px;">
                                            <option value="">Select criticality...</option>
                                            <option value="low">Low</option>
                                            <option value="medium">Medium</option>
                                            <option value="high">High</option>
                                            <option value="life support">Life Support</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Supplier</label>
                                        <select class="form-select" style="border-radius:8px;">
                                            <option value="">Select supplier...</option>
                                                <?php foreach($fornecedores as $f): ?>
                                                    <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome_empresa']) ?></option>
                                                <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Description <span class="text-muted fw-normal">(optional)</span></label>
                                        <textarea class="form-control" rows="2" placeholder="Brief technical notes" style="border-radius:8px;"></textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer border-0 pt-3">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:8px;">Cancel</button>
                                <button type="button" class="btn btn-primary-custom">
                                    <i class="bi bi-plus-lg me-1"></i> Add Equipment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include "../includes/footer.php"?>   