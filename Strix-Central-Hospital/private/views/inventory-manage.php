<?php 
require_once '../../config/config.php';
$stmt = $pdo->query("SELECT * FROM equipamentos");
$equipamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// stats for progress bar
$total = count($equipamentos);
$disponivel   = count(array_filter($equipamentos, fn($e) => $e['estado'] === 'Disponivel'));
$em_uso       = count(array_filter($equipamentos, fn($e) => $e['estado'] === 'Em Uso'));
$manutencao   = count(array_filter($equipamentos, fn($e) => $e['estado'] === 'Em Manutenção'));
$fora         = count(array_filter($equipamentos, fn($e) => $e['estado'] === 'Fora de Servico'));

$pct_disp = $total ? round($disponivel / $total * 100) : 0;
$pct_uso  = $total ? round($em_uso    / $total * 100) : 0;
$pct_man  = $total ? round($manutencao/ $total * 100) : 0;
$pct_fora = $total ? round($fora      / $total * 100) : 0;
?>

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
                                        <option>Em uso</option>
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
                <?php foreach ($equipamentos as $eq): ?>
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

            <!--Modais dos cards-->
            <?php foreach ($equipamentos as $eq): ?>
            <div class="modal fade" id="modal_<?= $eq['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="border-radius: 16px; border: none;">
                        <div class="modal-header border-0">
                            <h5 class="modal-title fw-bold"><?= htmlspecialchars($eq['nome']) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" style="overflow-y: auto; max-height: 75vh;">
                            <div class="row mb-4">
                                <div class="col-7">
                                    <img src="<?= htmlspecialchars($eq['imagem']) ?>" alt="..." class="img-fluid">
                                    
                                </div>
                                
                                <div class="col-5 d-flex flex-column justify-content-center gap-2">
                                    <!-- ID -->
                                    <div class="card px-3 py-2">
                                        <small class="text-muted">ID</small>
                                        <span><i class="bi bi-hash me-1"></i><?= htmlspecialchars($eq['serial']) ?></span>
                                    </div>
                                    
                                    <!-- State -->
                                    <div class="card px-3 py-2">
                                        <small class="text-muted">Estado</small>
                                        <span><i class="bi bi-circle-fill me-1" style="text-decoration: none;" ><?= htmlspecialchars($eq['estado']) ?></i></span> 
                                    </div>
                                    
                                    <div class="card px-3 py-2">
                                        <small class="text-muted">Criticidade</small>
                                        <small><i class="bi bi-exclamation-triangle-fill me-1"></i> <?= htmlspecialchars($eq['criticidade']) ?> </small>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0"><i class="bi bi-question-circle me-2 text-decoration-none"></i> Caracterizacao:</h6>
                            </div> 
                            <div class="me-2 p-2">
                                dadadad jhjhjh jhjhjh jh jhjh jhj h jhjhj hjhjh jhj hh
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0"><i class="bi bi-map-fill me-2 text-decoration-none"></i> Localização:</h6>
                                <span class="me-2"><?= htmlspecialchars(("Wing: " . $eq['location_wing'] ?? '') . " || Floor: " . ($eq['location_floor'] ?? '') . " || Room: " . ($eq['location_room'] ?? '')) ?></span>
                                
                            </div>  

                             
                            <!--IMPORTANT DATES-->
                            <hr> <!--cabeca do dropdown-->
                            <a href="#dates-1" data-bs-toggle="collapse" role="button" 
                               class="d-flex justify-content-between align-items-center text-decoration-none text-dark fw-bold mb-2">
                                <span>
                                    <i class="bi bi-calendar-event me-2"></i> Datas Importantes
                                    <span class="badge rounded-pill bg-danger ms-1">3</span>
                                </span>
                                <i class="bi bi-chevron-down small"></i>
                            </a>

                                <!--items do dropdown-->
                                <div id="dates-1" class="collapse mb-2">
                                    <ul class="list-group list-group-flush p-3">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-semibold">Próxima Calibração</div> 
                                                <div class="small text-muted">15 Jan 2026</div>   
                                            </div>
                                            <span class="badge bg-warning text-dark rounded-pill">Em breve</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-semibold">Manutenção Preventiva</div>
                                                <div class="small text-muted">02 Feb 2026</div>
                                            </div>
                                            <span class="badge bg-secondary rounded-pill">Agendado</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-semibold">Validade da Garantia</div>
                                                <div class="small text-muted">30 Jun 2026</div>
                                            </div>
                                            <span class="badge bg-secondary rounded-pill">Agendado</span>
                                        </li>
                                    </ul>
                                </div>

                            <hr>
                            <a href="#dados_tecnicos-1" data-bs-toggle="collapse" role="button" class="d-flex justify-content-between align-items-center text-decoration-none text-dark fw-bold mb-2">
                                <span><i class="bi bi-cpu me-2"></i> Dados técnicos</span>
                                <i class="bi bi-chevron-down small"></i>
                            </a>

                            <div id="dados_tecnicos-1" class="collapse">
                                <ul class="list-group list-group-flush p-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">Número de Série</div>
                                            <div class="small text-muted"><?= htmlspecialchars($eq['serial']) ?></div>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">Modelo</div>
                                            <div class="small text-muted"><?= htmlspecialchars($eq['modelo']) ?></div>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">Aquisição</div>
                                            <div class="small text-muted">
                                                <?= htmlspecialchars($eq['data_aquisicao']) ?> — 
                                                <?= htmlspecialchars($eq['tipo_aquisicao']) ?> — 
                                                <?= number_format($eq['custo_aquisicao'], 2) ?>€
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">Ano de Fabrico</div>
                                            <div class="small text-muted"><?= htmlspecialchars($eq['ano_fabrico']) ?></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <hr>
                            <a href="#components-1" data-bs-toggle="collapse" role="button" class="d-flex justify-content-between align-items-center text-decoration-none text-dark fw-bold mb-2">
                                <span><i class="bi bi-gear me-2"></i> Componentes Associados</span>
                                <i class="bi bi-chevron-down small"></i>
                            </a>

                            <div id="components-1" class="collapse">
                                <ul class="list-group list-group-flush p-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">Sensor de Ritmo Cardíaco</div>
                                            <div class="small text-muted">ID: 00-0000-01</div>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">Cabo de Eletrodos</div>
                                            <div class="small text-muted">ID: 00-0000-02</div>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">Bateria Principal</div>
                                            <div class="small text-muted">ID: 00-0000-03</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>


                            <hr>
                            <h6 class="fw-bold mb-3"><i class="bi bi-file-earmark-pdf me-2 text-danger"></i>Documentação</h6>
                            <div class="d-flex flex-column gap-2">
                                <a href="#" class="btn btn-outline-secondary btn-sm text-start">
                                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Manual do Utilizador.pdf
                                </a>
                                <a href="#" class="btn btn-outline-secondary btn-sm text-start">
                                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Ficha Técnica.pdf
                                </a>
                                <a href="#" class="btn btn-outline-secondary btn-sm text-start">
                                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Certificado de Calibração.pdf
                                </a>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0"><i class="bi bi-exclamation-circle me-2 text-decoration-none"></i> Observações:</h6>
                            </div> 
                            <div class="me-2 p-2">
                                dadadad jhjhjh jhjhjh jh jhjh jhj h jhjhj hjhjh jhj hh
                            </div> 
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <!--modal adicionar equipamento-->
            <?php include '../includes/new_equipment.php'?>

        </div>
    </div>
<?php include "../includes/footer.php"?>