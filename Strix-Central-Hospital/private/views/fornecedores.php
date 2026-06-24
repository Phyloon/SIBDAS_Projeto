<?php 
// Load header first (includes DB connection and HTML start)
require_once '../includes/header.php';
session_start();

// 1. FETCH ALL SUPPLIERS (not deleted)
//    Used for the accordion headers and modal dropdowns
$stmt = $pdo->query("SELECT * FROM fornecedores WHERE deleted_at IS NULL ORDER BY nome_empresa");
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. FETCH EQUIPMENT WITH THEIR SUPPLIER LINKS
//    - Joins equipamento_fornecedor (bridge table) to get supplier IDs
//    - Includes equipment details and supplier type
//    - Only shows equipment that is not deleted
$stmtEq = $pdo->query("
    SELECT ef.fornecedor_id, f.tipo_fornecedor, e.* 
    FROM equipamento_fornecedor ef 
    INNER JOIN equipamentos e ON ef.equipamento_id = e.id 
    LEFT JOIN fornecedores f ON ef.fornecedor_id = f.id 
    WHERE e.deleted_at IS NULL 
    ORDER BY e.nome
");
$allEquipments = $stmtEq->fetchAll(PDO::FETCH_ASSOC);

// 3. GROUP EQUIPMENT BY SUPPLIER ID
//    - Creates an array where each supplier ID holds a list of its equipment
//    - 'Desconhecido' is used as fallback for equipment without a supplier
$eqBySupplier = [];
foreach($allEquipments as $eq) {
    $supplierID = !empty($eq['fornecedor_id']) ? $eq['fornecedor_id'] : 'Desconhecido';
    $eqBySupplier[$supplierID][] = $eq;
}
?>

<!-- 4. START MAIN LAYOUT (sidebar + content area) -->
<div class="d-flex">

    <?php include '../includes/nav.php'?>

    <div id="content">
        
        <!-- Top Navigation Bar (user, notifications, etc.) -->
        <?php include '../includes/topbar.php'?>

       <!-- 5. PAGE HEADER with title, search/filter, and actions -->
        <div class="p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold text-dark mb-1">
                        <i class="bi bi-truck text-primary me-2"></i>Fornecedores & Equipamentos Associados
                    </h5>
                    <p class="text-muted small mb-0">Consulte e gira o seu inventário através da rede de fornecedores da TrueTech.</p>
                </div>

                <!-- Search, filter, and dropdown with all management options -->
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex gap-2 align-items-center">
                        <input type='text' id='supplierSearchInput' class="form-control form-control-sm" placeholder="Search Supplier or Equipment..." style="border-radius:8px; width:260px;">
                        <select id="supplierTypeFilter" class="form-select form-select-sm" style="border-radius:8px; width:200px;">
                            <option value="">All Types</option>
                            <option value="Fabricante">Fabricante</option>
                            <option value="Distribuidor">Distribuidor ou Fornecedor Comercial</option>
                            <option value="Assistencia">Empresa de assistencia tecnica</option>
                            <option value="Consumiveis">Fornecedor de Consumiveis e acessorios</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>

                    <!-- Dropdown with CRUD options -->
                    <div class="dropdown">
                        <button class="btn btn-primary-custom dropdown-toggle btn-sm" type="button" id="funcoesDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 8px;">
                            <i class="bi bi-gear me-2"></i> Funções
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="funcoesDropdown">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addSupplierModal"><i class="bi bi-building-add me-2 text-success"></i> Adicionar Fornecedor</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modifySupplierModal"><i class="bi bi-pencil-square me-2 text-warning"></i> Editar um Fornecedor</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deleteSupplierModal"><i class="bi bi-trash-fill me-2 text-danger"></i> Remover um Fornecedor</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addEquipmentModal"><i class="bi bi-wrench-adjustable me-2 text-primary"></i> Adicionar Equipamento</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#linkEquipmentModal"><i class="bi bi-link-45deg me-2 text-info"></i> Ligar Equipamento a Fornecedor</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 6. ACCORDION CONTAINER for all suppliers -->
            <div class="accordion" id="suppliers-accordion">
                
                <!-- 6a. "MORE INFORMATION" accordion (shows full supplier details table) -->
                <div class="accordion" id="supplierInfoAccordion">
                    <div class="accordion-item border-0 shadow-sm mb-3" style="border-radius: 12px; overflow: hidden;" >
                        <h2 class="accordion-header" id="moreinfo">
                            <button class="accordion-button collapsed fw-bold d-flexR" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInfo" style="background-color: #fff; color: #333;">
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
                                        <!-- Advanced filters (collapsed) – currently hidden -->
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
                                    <!-- Table showing all supplier details -->
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
                                                    <td><a href="mailto:<?= htmlspecialchars($f['email']) ?>"><?= htmlspecialchars($f['email']) ?></a></td>
                                                    <td><?= htmlspecialchars($f['endereco']) ?></td>
                                                    <td><a href="<?= htmlspecialchars($f['website']) ?>" target="_blank">Website</a></td>
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
                
                <!-- 6b. SUPPLIER ACCORDION ITEMS – one per supplier -->
                <?php foreach($fornecedores as $f): ?>
                    <?php
                        // Get equipment for this supplier from the grouped array
                        $equipamentosMarca = $eqBySupplier[$f['id']] ?? []; 
                        $totalEq = count($equipamentosMarca);
                        $safeId = $f['id']; // numeric ID for Bootstrap targets
                    ?>
                    <div class="accordion-item border-0 shadow-sm mb-3" style="border-radius: 12px; overflow: hidden;" data-type="<?= htmlspecialchars($f['tipo_fornecedor']) ?>">
                        <h2 class="accordion-header" id="heading<?= $safeId ?>">
                            <button class="accordion-button collapsed fw-bold d-flex align-items-center gap-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $safeId ?>" aria-expanded="false" style="background-color: #fff; color: #333;">
                                <div class="bg-light p-2 rounded text-primary">
                                    <i class="bi bi-building fs-5"></i>
                                </div>
                                <div class="d-flex flex-column flex-grow-1 text-start">
                                    <span class="fs-6"><?= htmlspecialchars($f['nome_empresa']) ?></span>
                                    <span class="fs-6 text-muted py-2"><?= htmlspecialchars($f['tipo_fornecedor']) ?></span>
                                </div>
                                <span class="badge bg-light text-dark border px-3 py-2 me-3 fw-normal" style="border-radius: 6px;">
                                    Equipamentos associados: <?= $totalEq ?>
                                </span>
                            </button>
                        </h2>

                        <!-- Collapsible body – shows equipment cards -->
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
                                            // Determine status badge color
                                            $estado = $eq['estado'];
                                            $bClass = 'secondary';
                                            if ($estado === 'Disponivel') $bClass = 'success';
                                            elseif ($estado === 'Em Uso') $bClass = 'primary';
                                            elseif ($estado === 'Em Manutenção') $bClass = 'warning text-dark';
                                            elseif ($estado === 'Fora de Servico') $bClass = 'danger';
                                        ?>
                                        <!-- Equipment card – click opens modal with more info -->
                                        <div class="col-2 col-md-3">
                                            <div class="card border-0 shadow-sm h-100" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modal_<?= $eq['id'] ?>">
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
                                                        <?= htmlspecialchars($eq['departamento'] ?? 'N/A') ?> <i class="bi bi-dot"></i> <?= htmlspecialchars($eq['location_vector'] ?? 'N/A') ?>
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

            <!-- 7. MODALS – included from separate files -->
            <!--    - Link Equipment to Supplier modal         -->
            <!--    - Add/Edit/Delete Supplier modals          -->
            <!--    - Add Equipment modal                      -->
            <!--    - Equipment detail modal (card click)     -->
            <div class="modal fade" id="linkEquipmentModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content" style="border-radius: 16px; border: none;">
                        <form action="../includes/process_link_equip_forn.php" method="post">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold">Link Equipment to Supplier</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body pt-3">
                                <div class="mb-3">
                                    <label class="form-label">Select Equipment</label>
                                    <select name="equipamento_id" class="form-select" required>
                                        <?php 
                                        $stmt = $pdo->query("SELECT id, nome, serial FROM equipamentos ORDER BY nome");
                                        foreach($stmt->fetchAll() as $eq): ?>
                                            <option value="<?= $eq['id'] ?>"><?= $eq['nome'] ?> (<?= $eq['serial'] ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Select Supplier</label>
                                    <select name="fornecedor_id" class="form-select" required>
                                        <?php 
                                        $stmt = $pdo->query("SELECT id, nome_empresa FROM fornecedores ORDER BY nome_empresa");
                                        foreach($stmt->fetchAll() as $f): ?>
                                            <option value="<?= $f['id'] ?>"><?= $f['nome_empresa'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="submit" class="btn btn-primary-custom">Link Equipment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Include all other modals from external files -->
            <?php include '../includes/modal_supplier.php'; ?>
            <?php include '../includes/modal_delete_supplier.php'; ?>
            <?php include '../includes/modal_modify_supplier.php'; ?>
            <?php include '../includes/new_equipment.php'; ?>
            <?php include '../includes/modal_card_equipamento.php'; ?>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"?>