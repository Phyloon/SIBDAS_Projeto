<?php 
// Load database configuration and start session for flash messages
require_once '../../config/config.php';
session_start();

// 1. FETCH ALL ACTIVE STAFF (not deleted)
//    Ordered by default (no specific order)
$stmt = $pdo->query("SELECT * FROM staff WHERE deleted_at IS NULL");
$staff = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. CALCULATE STAFF STATISTICS
//    - Total active staff
//    - Count by availability status: On Shift, On Call, Off Duty
$total      = count($staff);
$on_shift   = count(array_filter($staff, fn($s) => $s['disponibilidade'] === 'On Shift'));
$on_call    = count(array_filter($staff, fn($s) => $s['disponibilidade'] === 'On Call'));
$off_duty   = count(array_filter($staff, fn($s) => $s['disponibilidade'] === 'Off Duty'));
?>

<!-- Include header (HTML start, styles, etc.) -->
<?php include '../includes/header.php'?>

<div class="d-flex">

    <!-- Sidebar navigation -->
    <?php include '../includes/nav.php'?>

    <div id="content">

        <!-- Top bar (user info, notifications, etc.) -->
        <?php include '../includes/topbar.php'?>

        <div class="container-fluid p-4">
 
            <!-- PAGE HEADER with title and action dropdown -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Staff</h3>
                    <p class="text-muted small mb-0">All hospital staff and their current availability</p>
                </div>

                <!-- Dropdown with staff management actions -->
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

                <!-- Include staff modals (add and delete) -->
                <?php include '../includes/new_staff.php'?>
                <?php include '../includes/delete_staff.php'?>
            </div>
    
            <!-- STATISTICS CARDS: Total, On Shift, On Call, Off Duty -->
            <div class="row g-3 mb-4">
                <!-- Total staff -->
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
                        <div class="stat-icon"><i class="bi bi-people"></i></div>
                        <div>
                            <div class="stat-value"><?= $total ?></div>
                            <div class="stat-label">Total Staff</div>
                        </div>
                    </div>
                </div>
                <!-- Currently on shift -->
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                        <div>
                            <div class="stat-value"><?= $on_shift ?></div>
                            <div class="stat-label">On Shift</div>
                        </div>
                    </div>
                </div>
                <!-- On call (available if needed) -->
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <div class="stat-icon"><i class="bi bi-telephone"></i></div>
                        <div>
                            <div class="stat-value"><?= $on_call ?></div>
                            <div class="stat-label">On Call</div>
                        </div>
                    </div>
                </div>
                <!-- Off duty / not available -->
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
    
            <!-- FILTER BAR: Role, Department, Availability -->
            <!-- Used for filtering the staff table via JavaScript -->
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
    
            
            <!-- Shows all staff with their info -->
            <!-- Each row has data attributes for filtering -->
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
                        <tbody>
                            <?php foreach ($staff as $s): ?>
                                <!-- Data attributes used for filtering by role, availability, and department -->
                                <tr data-role="<?= htmlspecialchars($s['role']) ?>" 
                                    data-availability="<?= htmlspecialchars($s['disponibilidade']) ?>" 
                                    data-department="<?= htmlspecialchars($s['departamento']) ?>">
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <!-- Staff avatar image -->
                                            <img src="<?= htmlspecialchars($s['imagem']) ?>" class="staff-avatar">
                                            <div>
                                                <!-- Staff name and ID -->
                                                <div class="staff-name"><?= htmlspecialchars($s['nome']) ?></div>
                                                <div class="staff-id"><?= htmlspecialchars($s['staff_id']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Role badge -->
                                    <td><span class="role-badge"><?= htmlspecialchars($s['role']) ?></span></td>
                                    <!-- Department -->
                                    <td><?= htmlspecialchars($s['departamento']) ?></td>
                                    <!-- Contact info with phone icon -->
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
    
<!-- Include footer (closing HTML tags and scripts) -->
<?php include "../includes/footer.php"?>