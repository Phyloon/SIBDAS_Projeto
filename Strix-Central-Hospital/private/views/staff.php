<?php include '../includes/header.php'?>

    <div class="d-flex">

        <?php include '../includes/nav.php'?>

        <div id="content">

            <?php include '../includes/topbar.php'?>

            <div class="container-fluid p-4">
 
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-1">Staff</h3>
                        <p class="text-muted small mb-0">All hospital staff and their current availability</p>
                    </div>
                    <button class="btn btn-primary-custom">
                        <i class="bi bi-plus-lg me-2"></i> Add Staff Member
                    </button>
                </div>
    
                <!-- Stat cards -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
                            <div class="stat-icon"><i class="bi bi-people"></i></div>
                            <div>
                                <div class="stat-value">42</div>
                                <div class="stat-label">Total Staff</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                            <div>
                                <div class="stat-value">28</div>
                                <div class="stat-label">On Shift</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <div class="stat-icon"><i class="bi bi-telephone"></i></div>
                            <div>
                                <div class="stat-value">8</div>
                                <div class="stat-label">On Call</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #94a3b8, #64748b);">
                            <div class="stat-icon"><i class="bi bi-moon"></i></div>
                            <div>
                                <div class="stat-value">6</div>
                                <div class="stat-label">Off Duty</div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Filter bar -->
                <div class="filter-bar mb-4">
                    <i class="bi bi-funnel text-muted"></i>
                    <select data-filter="role">
                        <option>All Roles</option>
                        <option>Doctor</option>
                        <option>Nurse</option>
                        <option>Biomedical Technician</option>
                        <option>Radiologist</option>
                        <option>Surgeon</option>
                    </select>
                    <select data-filter="department">
                        <option>All Departments</option>
                        <option>Cardiology</option>
                        <option>Radiology</option>
                        <option>Neurology</option>
                        <option>ICU</option>
                        <option>Surgery</option>
                    </select>
                    <select data-filter="availability">
                        <option>All Availability</option>
                        <option>On Shift</option>
                        <option>On Call</option>
                        <option>Off Duty</option>
                    </select>
                    
                    <input type="text" placeholder="Search by name or ID...">
                </div>
    
                <!-- Staff table -->
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table staff-table mb-0">
                            <thead>
                                <tr>
                                    <th>Staff Member</th>
                                    <th>Role</th>
                                    <th>Department</th>
                                    <th>Availability</th>
                                    <th>Personal Contact</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-role="Doctor" data-availability="On Shift" data-department="Cardiology">
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="https://ui-avatars.com/api/?name=Maria+Santos&background=0ea5e9&color=fff" class="staff-avatar">
                                            <div>
                                                <div class="staff-name">Dr. Maria Santos</div>
                                                <div class="staff-id">#ST-00021</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="role-badge" style="background:#dbeafe; color:#1d4ed8;">Doctor</span></td>
                                    <td>Cardiology</td>
                                    <td>
                                        <span class="availability-dot" style="background:#10b981;"></span>
                                        <span class="availability-label" style="color:#10b981;">On Shift</span>
                                    </td>
                                    <td><span class="assignment-pill"><i class="bi bi-phone"></i> +351 911 871 461</span></td>
                                </tr>
                                <tr data-role="Biomedical Technician" data-availability="On Shift" data-department="Radiology">
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="https://ui-avatars.com/api/?name=John+Doe&background=f97316&color=fff" class="staff-avatar">
                                            <div>
                                                <div class="staff-name">John Doe</div>
                                                <div class="staff-id">#ST-00001</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="role-badge" style="background:#ffedd5; color:#c2410c;">Biomedical Tech.</span></td>
                                    <td>Radiology</td>
                                    <td>
                                        <span class="availability-dot" style="background:#10b981;"></span>
                                        <span class="availability-label" style="color:#10b981;">On Shift</span>
                                    </td>
                                    <td><span class="assignment-pill"><i class="bi bi-phone"></i> +351 913 522 453</span></td>
                                </tr>
                                <tr data-role="Nurse" data-availability="On Call" data-department="ICU">
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="https://ui-avatars.com/api/?name=Ana+Costa&background=10b981&color=fff" class="staff-avatar">
                                            <div>
                                                <div class="staff-name">Ana Costa</div>
                                                <div class="staff-id">#ST-00034</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="role-badge" style="background:#d1fae5; color:#065f46;">Nurse</span></td>
                                    <td>ICU</td>
                                    <td>
                                        <span class="availability-dot" style="background:#f59e0b;"></span>
                                        <span class="availability-label" style="color:#f59e0b;">On Call</span>
                                    </td>
                                    <td><span class="assignment-pill"><i class="bi bi-phone"></i> +351 918 452 633</span></td>
                                </tr>
                                <tr data-role="Radiologist" data-availability="On Shift" data-department="Radiology">
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="https://ui-avatars.com/api/?name=Carlos+Lima&background=8b5cf6&color=fff" class="staff-avatar">
                                            <div>
                                                <div class="staff-name">Dr. Carlos Lima</div>
                                                <div class="staff-id">#ST-00009</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="role-badge" style="background:#ede9fe; color:#6d28d9;">Radiologist</span></td>
                                    <td>Radiology</td>
                                    <td>
                                        <span class="availability-dot" style="background:#10b981;"></span>
                                        <span class="availability-label" style="color:#10b981;">On Shift</span>
                                    </td>
                                    <td><span class="assignment-pill"><i class="bi bi-phone"></i> +351 914 522 782</span></td>
                                </tr>
                                <tr data-role="Surgeon" data-availability="Off Duty" data-department="Surgery">
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="https://ui-avatars.com/api/?name=Pedro+Alves&background=64748b&color=fff" class="staff-avatar">
                                            <div>
                                                <div class="staff-name">Dr. Pedro Alves</div>
                                                <div class="staff-id">#ST-00015</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="role-badge" style="background:#f1f5f9; color:#475569;">Surgeon</span></td>
                                    <td>Surgery</td>
                                    <td>
                                        <span class="availability-dot" style="background:#94a3b8;"></span>
                                        <span class="availability-label" style="color:#94a3b8;">Off Duty</span>
                                    </td>
                                    <td><span class="assignment-pill"><i class="bi bi-phone"></i> +351 913 522 453</span></td>
                                </tr>
                                <tr data-role="Nurse" data-availability="On Shift" data-department="Neurology">
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="https://ui-avatars.com/api/?name=Sofia+Ferreira&background=10b981&color=fff" class="staff-avatar">
                                            <div>
                                                <div class="staff-name">Sofia Ferreira</div>
                                                <div class="staff-id">#ST-00041</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="role-badge" style="background:#d1fae5; color:#065f46;">Nurse</span></td>
                                    <td>Neurology</td>
                                    <td>
                                        <span class="availability-dot" style="background:#10b981;"></span>
                                        <span class="availability-label" style="color:#10b981;">On Shift</span>
                                    </td>
                                    <td><span class="assignment-pill"><i class="bi bi-phone"></i> +351 917 145 221</span></td>
                                </tr>
                                <tr data-role="Biomedical Technician" data-availability="On Call" data-department="ICU">
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="https://ui-avatars.com/api/?name=Rui+Mendes&background=f97316&color=fff" class="staff-avatar">
                                            <div>
                                                <div class="staff-name">Rui Mendes</div>
                                                <div class="staff-id">#ST-00007</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="role-badge" style="background:#ffedd5; color:#c2410c;">Biomedical Tech.</span></td>
                                    <td>ICU</td>
                                    <td>
                                        <span class="availability-dot" style="background:#f59e0b;"></span>
                                        <span class="availability-label" style="color:#f59e0b;">On Call</span>
                                    </td>
                                    <td><span class="assignment-pill"><i class="bi bi-phone"></i> +351 914 735 086</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<?php include "../includes/footer.php"?>