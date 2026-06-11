<?php include '../includes/header.php'?>

    <div class="d-flex">

        <?php include '../includes/nav.php'?>

        <div id="content">

            <!-- Top Navigation Bar -->
            <?php include '../includes/topbar.php'?>

            <div class="container-fluid p-4">

                <!-- Page header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-1">Equipment Location</h3>
                        <p class="text-muted small mb-0">Live tracking via QR scan — last updated 2 min ago</p>
                    </div>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#updateLocationModal">
                        <i class="bi bi-qr-code-scan me-2"></i> Log QR Scan
                    </button>
                </div>

                <!-- Stat cards -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
                            <div class="stat-icon"><i class="bi bi-boxes"></i></div>
                            <div>
                                <div class="stat-value">230</div>
                                <div class="stat-label">Total Tracked Items</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <div class="stat-icon"><i class="bi bi-geo-alt"></i></div>
                            <div>
                                <div class="stat-value">198</div>
                                <div class="stat-label">Located</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <div class="stat-icon"><i class="bi bi-question-circle"></i></div>
                            <div>
                                <div class="stat-value">24</div>
                                <div class="stat-label">Unconfirmed</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                            <div class="stat-icon"><i class="bi bi-exclamation-triangle"></i></div>
                            <div>
                                <div class="stat-value">8</div>
                                <div class="stat-label">Missing / Not Scanned</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filter-bar mb-4">
                    <i class="bi bi-funnel text-muted"></i>
                    <select data-filter="wing">
                        <option>All Wings</option>
                        <option>Wing A</option>
                        <option>Wing B</option>
                        <option>Wing C</option>
                        <option>Unknown</option>
                    </select>
                    <select data-filter="floor">
                        <option>All Floors</option>
                        <option>Floor 1</option>
                        <option>Floor 2</option>
                        <option>Floor 3</option>
                        <option>Unknown</option>
                    </select>
                    <select data-filter="department">
                        <option>All Departments</option>
                        <option>Cardiology</option>
                        <option>Radiology</option>
                        <option>Neurology</option>
                        <option>ICU</option>
                        <option>Unknown</option>
                    </select>
                    <input type="text" placeholder="Search by room, equipment name or ID..." class="col-3">
                </div>

                <!-- Location table -->
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table location-table mb-0">
                            <thead>
                                <tr>
                                    <th>Equipment and ID</th>
                                    <th>Wing</th>
                                    <th>Floor</th>
                                    <th>Room</th>
                                    <th>Department</th>
                                    <th>Last Scanned</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-wing="Wing A" data-floor="Floor 2" data-department="Cardiology">
                                    <td>
                                        <div class="equipment-name">ECG Machine</div>
                                        <div class="equipment-id">#EQ-00142</div>
                                    </td>
                                    <td><span class="location-pill"><i class="bi bi-building"></i> Wing A</span></td>
                                    <td><span class="location-pill"><i class="bi bi-layers"></i> Floor 2</span></td>
                                    <td><span class="location-pill"><i class="bi bi-door-open"></i> Room 204</span></td>
                                    <td>Cardiology</td>
                                    <td class="scan-time"><i class="bi bi-clock"></i> Today, 09:14</td>
                                    <td><span class="badge-status bg-success text-white">Located</span></td>
                                </tr>
                                <tr data-wing="Wing B" data-floor="Floor 1" data-department="ICU">
                                    <td>
                                        <div class="equipment-name">Portable Ventilator</div>
                                        <div class="equipment-id">#EQ-00089</div>
                                    </td>
                                    <td><span class="location-pill"><i class="bi bi-building"></i> Wing B</span></td>
                                    <td><span class="location-pill"><i class="bi bi-layers"></i> Floor 1</span></td>
                                    <td><span class="location-pill"><i class="bi bi-door-open"></i> ICU-03</span></td>
                                    <td>ICU</td>
                                    <td class="scan-time"><i class="bi bi-clock"></i> Today, 08:47</td>
                                    <td><span class="badge-status bg-success text-white">Located</span></td>
                                </tr>
                                <tr data-wing="Wing C" data-floor="Floor 1" data-department="Radiology">
                                    <td>
                                        <div class="equipment-name">MRI Scanner</div>
                                        <div class="equipment-id">#EQ-00011</div>
                                    </td>
                                    <td><span class="location-pill"><i class="bi bi-building"></i> Wing C</span></td>
                                    <td><span class="location-pill"><i class="bi bi-layers"></i> Floor 1</span></td>
                                    <td><span class="location-pill"><i class="bi bi-door-open"></i> Radiology-1</span></td>
                                    <td>Radiology</td>
                                    <td class="scan-time"><i class="bi bi-clock"></i> Yesterday, 17:30</td>
                                    <td><span class="badge-status bg-warning text-dark">Unconfirmed</span></td>
                                </tr>
                                <tr data-wing="Wing A" data-floor="Floor 3" data-department="Cardiology">
                                    <td>
                                        <div class="equipment-name">Defibrillator Model X</div>
                                        <div class="equipment-id">#EQ-00057</div>
                                    </td>
                                    <td><span class="location-pill"><i class="bi bi-building"></i> Wing A</span></td>
                                    <td><span class="location-pill"><i class="bi bi-layers"></i> Floor 3</span></td>
                                    <td><span class="location-pill"><i class="bi bi-door-open"></i> Room 301</span></td>
                                    <td>Cardiology</td>
                                    <td class="scan-time"><i class="bi bi-clock"></i> Today, 11:02</td>
                                    <td><span class="badge-status bg-success text-white">Located</span></td>
                                </tr>
                                <tr data-wing="Unknown" data-floor="Unknown" data-department="Unknown">
                                    <td>
                                        <div class="equipment-name">Infusion Pump</div>
                                        <div class="equipment-id">#EQ-00203</div>
                                    </td>
                                    <td><span class="location-pill text-muted" style="background:#fee2e2; color:#ef4444 !important;"><i class="bi bi-question-lg" style="color:#ef4444;"></i> Unknown</span></td>
                                    <td>—</td>
                                    <td>—</td>
                                    <td>—</td>
                                    <td class="scan-time text-danger"><i class="bi bi-clock"></i> 3 days ago</td>
                                    <td><span class="badge-status bg-danger text-white">Missing</span></td>
                                </tr>
                                <tr data-wing="Wing B" data-floor="Floor 2" data-department="Neurology">
                                    <td>
                                        <div class="equipment-name">Ultrasound Unit</div>
                                        <div class="equipment-id">#EQ-00178</div>
                                    </td>
                                    <td><span class="location-pill"><i class="bi bi-building"></i> Wing B</span></td>
                                    <td><span class="location-pill"><i class="bi bi-layers"></i> Floor 2</span></td>
                                    <td><span class="location-pill"><i class="bi bi-door-open"></i> Room 215</span></td>
                                    <td>Neurology</td>
                                    <td class="scan-time"><i class="bi bi-clock"></i> Today, 10:33</td>
                                    <td><span class="badge-status bg-success text-white">Located</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- QR Scan Modal -->
    <div class="modal fade" id="updateLocationModal" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Log Equipment Location</h5>

                    <!--CLOSE BUTTON-->
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">

                    <!-- QR placeholder -->
                    <div class="d-flex flex-column align-items-center mb-4">
                        <div class="qr-placeholder">
                            <i class="bi bi-qr-code-scan"></i>
                            <span>QR scanner goes here</span>
                        </div>
                        <small class="text-muted mt-2">Scan the QR code on the equipment, or enter the ID manually below</small>
                    </div>

                    <!-- Equipment ID -->
                    <div class="mb-3">
                        <label class="form-label">Equipment ID</label>
                        <input type="text" class="form-control" placeholder="e.g. #EQ-00142" style="border-radius:8px;">
                    </div>

                    <!-- Location fields -->
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Wing</label>
                            <select class="form-select" style="border-radius:8px;">
                                <option value="">Select wing...</option>
                                <option>Wing A</option>
                                <option>Wing B</option>
                                <option>Wing C</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Floor</label>
                            <select class="form-select" style="border-radius:8px;">
                                <option value="">Select floor...</option>
                                <option>Floor 1</option>
                                <option>Floor 2</option>
                                <option>Floor 3</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Room</label>
                            <input type="text" class="form-control" placeholder="e.g. Room 204" style="border-radius:8px;">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes <span class="text-muted fw-normal">(optional)</span></label>
                            <textarea class="form-control" rows="2" placeholder="e.g. Left next to bed 3" style="border-radius:8px;"></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:8px;">Cancel</button>
                    <button type="button" class="btn btn-primary-custom">
                        <i class="bi bi-check-lg me-1"></i> Confirm Location
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php include "../includes/footer.php"?>  