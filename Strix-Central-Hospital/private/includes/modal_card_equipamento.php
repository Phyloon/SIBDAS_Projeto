<!--IT SAYS ERROR BUT IT WORKS ANYWAY, WHEN IT IS INCLUDED $allEquipments IS DEFINED B4HAND, SO IT WORKS-->

<!--remover aviso de erro-->
<?php /** @var array $allEquipments */ ?>

<?php foreach ($allEquipments as $eq): ?>
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
                            <div class="card px-3 py-2">
                                <small class="text-muted">ID</small>
                                <span><i class="bi bi-hash me-1"></i><?= htmlspecialchars($eq['serial']) ?></span>
                            </div>
                            
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

                    <hr>
                    <a href="#dates-<?= $eq['id'] ?>" data-bs-toggle="collapse" role="button" class="d-flex justify-content-between align-items-center text-decoration-none text-dark fw-bold mb-2">
                        <span>
                            <i class="bi bi-calendar-event me-2"></i> Datas Importantes
                            <span class="badge rounded-pill bg-danger ms-1">3</span>
                        </span>
                        <i class="bi bi-chevron-down small"></i>
                    </a>

                    <div id="dates-<?= $eq['id'] ?>" class="collapse mb-2">
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
                    <a href="#dados_tecnicos-<?= $eq['id'] ?>" data-bs-toggle="collapse" role="button" class="d-flex justify-content-between align-items-center text-decoration-none text-dark fw-bold mb-2">
                        <span><i class="bi bi-cpu me-2"></i> Dados técnicos</span>
                        <i class="bi bi-chevron-down small"></i>
                    </a>

                    <div id="dados_tecnicos-<?= $eq['id'] ?>" class="collapse">
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
                    <a href="#components-<?= $eq['id'] ?>" data-bs-toggle="collapse" role="button" class="d-flex justify-content-between align-items-center text-decoration-none text-dark fw-bold mb-2">
                        <span><i class="bi bi-gear me-2"></i> Componentes Associados</span>
                        <i class="bi bi-chevron-down small"></i>
                    </a>

                    <div id="components-<?= $eq['id'] ?>" class="collapse">
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