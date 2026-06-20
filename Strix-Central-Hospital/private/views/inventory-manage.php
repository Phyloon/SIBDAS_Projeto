<?php 
require_once '../../config/config.php';
$stmt = $pdo->query("SELECT * FROM equipamentos");
$allEquipments = $stmt->fetchAll(PDO::FETCH_ASSOC);

//get all suppliers
$stmt = $pdo->query("SELECT * FROM fornecedores ORDER BY nome_empresa");
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);


// stats for progress bar
$total = count($allEquipments);
$disponivel   = count(array_filter($allEquipments, fn($e) => $e['estado'] === 'Disponivel'));
$em_uso       = count(array_filter($allEquipments, fn($e) => $e['estado'] === 'Em Uso'));
$manutencao   = count(array_filter($allEquipments, fn($e) => $e['estado'] === 'Em Manutenção'));
$fora         = count(array_filter($allEquipments, fn($e) => $e['estado'] === 'Fora de Servico'));

$pct_disp = $total ? round($disponivel / $total * 100) : 0;
$pct_uso  = $total ? round($em_uso    / $total * 100) : 0;
$pct_man  = $total ? round($manutencao/ $total * 100) : 0;
$pct_fora = $total ? round($fora      / $total * 100) : 0;
?>




<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>




<?php include '../includes/header.php'?>

    <div class="d-flex">
        
        <?php include '../includes/nav.php'?>

        <!--Page content-->
        <div id="content">
            
            <!-- Top Navigation Bar -->
            <?php include '../includes/topbar.php'?>

            <!-- progress bars and filters -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <div class="card-body p-4">
                    
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0 text-secondary">
                            <i class="bi bi-pie-chart-fill me-2"></i> Estado Geral do Inventário
                        </h6>
                        <div class="d-flex gap-3 small fw-semibold flex-wrap">
                            <span><i class="bi bi-circle-fill text-success me-1"></i> Disponíveis (<?= $pct_disp ?>%)</span>
                            <span><i class="bi bi-circle-fill text-primary me-1"></i> Em Uso (<?= $pct_uso ?>%)</span>
                            <span><i class="bi bi-circle-fill text-warning me-1"></i> Em Manutenção (<?= $pct_man ?>%)</span>
                            <span><i class="bi bi-circle-fill text-danger me-1"></i> Fora de Serviço (<?= $pct_fora ?>%)</span>
                        </div>
                    </div>

                    <div class="progress mb-4" style="height: 12px; border-radius: 6px;">
                        <div class="progress-bar bg-success" style="width: <?= $pct_disp ?>%"></div>
                        <div class="progress-bar bg-primary" style="width: <?= $pct_uso ?>%"></div>
                        <div class="progress-bar bg-warning" style="width: <?= $pct_man ?>%"></div>
                        <div class="progress-bar bg-danger" style="width: <?= $pct_fora ?>%"></div>
                    </div>


                    <!-- filtros e search bar -->
                    <div class="d-flex flex-wrap align-items-center gap-3 filter-bar-modal">
                        <div class="flex-grow-1 position-relative">
                            <input type="text" placeholder="Search by room, equipment name or ID..." class="form-control form-control-sm text-center" style="border-radius: 8px;">
                        </div>

                        <div class="dropdown align-items-center">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" style="border-radius: 8px;">
                                <i class="bi bi-funnel-fill me-1"></i> Filtros:
                            </button>

                            <div class="dropdown-menu dropdown-menu-end p-3 shadow" style="min-width: 260px; border-radius: 12px; border: none;">
                                <h6 class="dropdown-header fw-bold text-dark mb-3">Opcoes de Filtragem</h6>

                                <div class="mb-3 align-items-center">
                                    <select class="form-select form-select-sm text-center" data-filter="group" style="border-radius: 8px;">
                                        <option>Grupo</option>
                                        <option>Grupo 1- Monitorizacao</option>
                                        <option>Grupo 2- Suporte de Vida</option>
                                        <option>Grupo 3- Terapia</option>
                                        <option>Grupo 4- Diagnostico</option>
                                        <option>Grupo 5- Laboratorio</option>
                                        <option>Grupo 6- Esterilizacao</option>
                                        <option>Grupo 7- Reabilitacao</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3 align-items-center">
                                    <select class="form-select form-select-sm text-center" data-filter="availability" style="border-radius: 8px;">
                                        <option>Disponibilidade</option>
                                        <option>Disponivel</option>
                                        <option>Em Uso</option>
                                        <option>Em Manutenção</option>
                                        <option>Fora de Servico</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3 align-items-center">
                                    <select class="form-select form-select-sm text-center" data-filter="department" style="border-radius: 8px;">
                                        <option>Departamento</option>
                                        <option>Cardiology</option>
                                        <option>Radiology</option>
                                        <option>Neurology</option>
                                        <option>ICU</option>
                                        <option>Unknown</option>
                                    </select>
                                </div>

                                <div class="mb-3 align-items-center">
                                    <select class="form-select form-select-sm text-center" data-filter="criticality" style="border-radius: 8px;">
                                        <option>Criticidade</option>
                                        <option>Suporte de Vida</option>
                                        <option>Alta</option>
                                        <option>Média</option>
                                        <option>Baixa</option>
                                    </select>
                                </div>

                                <button class="btn btn-sm btn-light w-100 text-danger fw-bold mt-2" id="clearFiltersBtn" style="border-radius: 6px;">
                                    <i class="bi bi-x-circle me-1"></i> Limpar Filtros
                                </button>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                                <i class="bi bi-plus-lg me-2"></i> Add Inventory
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            
            <!--cards-->
            <div class="row mb-4">
                <?php foreach ($allEquipments as $eq): ?>
                <div class="col-3 mb-4" data-group="<?= htmlspecialchars($eq['grupo']) ?>" data-availability="<?= htmlspecialchars($eq['estado']) ?>" data-department="<?= htmlspecialchars($eq['departamento']) ?>" data-criticality="<?= htmlspecialchars($eq['criticidade']) ?>">
                    <div class="card h-100" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modal_<?= $eq['id'] ?>">
                        <div style="padding: 15px;">
                            <img src="<?= htmlspecialchars($eq['imagem']) ?>" class="card-img-top" style="border-radius: 15px;">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-center"><?= htmlspecialchars($eq['nome']) ?></h5>
                        </div>
                        <ul class="list-group list-group-flush text-center">
                            <li class="list-group-item">Marca: <?= htmlspecialchars($eq['marca']) ?></li>
                            <li class="list-group-item">Modelo: <?= htmlspecialchars($eq['modelo']) ?></li>
                            <li class="list-group-item"><?= htmlspecialchars($eq['serial']) ?></li>
                            <li class="list-group-item"><?= htmlspecialchars($eq['estado']) ?></li>
                            <li class="list-group-item"><?= htmlspecialchars(("Wing: " . $eq['location_wing'] ?? '') . " || Floor: " . ($eq['location_floor'] ?? '') . " || Room: " . ($eq['location_room'] ?? '')) ?></li>
                        </ul>
                    </div>
                </div>
                <?php endforeach; ?> 
            </div> 

            <!--modal equip-->
            <?php include '../includes/modal_card_equipamento.php'; ?>
            
            <!--modal adicionar equipamento-->
            <?php include '../includes/new_equipment.php'?>

        </div>
    </div>
<?php include "../includes/footer.php"?>