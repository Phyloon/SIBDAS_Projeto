<?php 
require_once '../../config/config.php';
$stmt = $pdo->query("SELECT * FROM staff WHERE deleted_at IS NULL");
$staff = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total      = count($staff);
$on_shift   = count(array_filter($staff, fn($s) => $s['disponibilidade'] === 'On Shift'));
$on_call    = count(array_filter($staff, fn($s) => $s['disponibilidade'] === 'On Call'));
$off_duty   = count(array_filter($staff, fn($s) => $s['disponibilidade'] === 'Off Duty'));
?>

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

                    <div class="dropdown align-items-center">
                        <button class="btn btn-primary-custom" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 8px;">
                            Actions <i class="bi bi-chevron-down ms-2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius: 8px; border: none;">
                            <li>
                                <button class="dropdown-item py-2" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                                    <i class="bi bi-person-plus me-2 text-muted"></i> Add Staff
                                </button>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <button class="dropdown-item py-2 text-danger" data-bs-toggle="modal" data-bs-target="#deleteStaffModal">
                                    <i class="bi bi-person-dash me-2"></i> Delete Staff
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!--modal add staff-->
                    <?php include '../includes/new_staff.php'?>
                    <?php include '../includes/delete_staff.php'?>
                </div>
    
                <!-- Stat cards -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
                            <div class="stat-icon"><i class="bi bi-people"></i></div>
                            <div>
                                <div class="stat-value"><?= $total ?></div>
                                <div class="stat-label">Total Staff</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                            <div>
                                <div class="stat-value"><?= $on_shift ?></div>
                                <div class="stat-label">On Shift</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <div class="stat-icon"><i class="bi bi-telephone"></i></div>
                            <div>
                                <div class="stat-value"><?= $on_call ?></div>
                                <div class="stat-label">On Call</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="stat-card" style="background: linear-gradient(135deg, #94a3b8, #64748b);">
                            <div class="stat-icon"><i class="bi bi-moon"></i></div>
                            <div>
                                <div class="stat-value"><?= $off_duty ?></div>
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
                                    <th>Personal Contact</th>
                                </tr>
                            </thead>
                                <?php foreach ($staff as $s): 
                                    // set dot colour based on availability
                                ?>
                                <tr data-role="<?= htmlspecialchars($s['role']) ?>" 
                                    data-availability="<?= htmlspecialchars($s['disponibilidade']) ?>" 
                                    data-department="<?= htmlspecialchars($s['departamento']) ?>">
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="<?= htmlspecialchars($s['imagem']) ?>" class="staff-avatar">
                                            <div>
                                                <div class="staff-name"><?= htmlspecialchars($s['nome']) ?></div>
                                                <div class="staff-id"><?= htmlspecialchars($s['staff_id']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="role-badge"><?= htmlspecialchars($s['role']) ?></span></td>
                                    <td><?= htmlspecialchars($s['departamento']) ?></td>
                                    <td><span class="assignment-pill"><i class="bi bi-phone"></i> <?= htmlspecialchars($s['contacto']) ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                
            </div> 
        </div>
    </div>
    
<?php include "../includes/footer.php"?>