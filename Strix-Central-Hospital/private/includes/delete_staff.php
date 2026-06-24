<?php /** @var array $staff */ // Type hint for IDE: $staff is an array of staff members from the database ?>

<!-- Modal for deleting a staff member -->
<div class="modal fade" id="deleteStaffModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            
            <!-- Modal header: title and close button -->
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">Remove Staff Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- Form: submits to process_delete_staff.php via POST -->
            <form action="../includes/process_delete_staff.php" method="POST">
                <div class="modal-body pt-3">

                    <!-- Decorative icon and helper text -->
                    <div class="d-flex flex-column align-items-center mb-4">
                        <div class="qr-placeholder" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; background: #fee2e2; border-radius: 50%;">
                            <i class="bi bi-person-x" style="font-size: 2rem; color: #dc3545;"></i>
                        </div>
                        <small class="text-muted mt-2">Select a staff member to remove from the dashboard</small>
                    </div>

                    <!-- Dropdown: list all active staff members -->
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Staff Member (Name & Code)</label>
                            <select name="staff_id" class="form-select" style="border-radius:8px;" required>
                                <option value="" selected disabled>Select staff member to delete...</option>
                                <?php foreach ($staff as $s): ?>
                                    <option value="<?= htmlspecialchars($s['staff_id']) ?>">
                                        <?= htmlspecialchars($s['nome']) ?> - (ID: <?= htmlspecialchars($s['staff_id']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Confirmation checkbox: user must confirm before submission -->
                        <div class="col-12 mt-2">
                            <div class="form-check bg-light p-3" style="border-radius: 8px;">
                                <input class="form-check-input ms-1" type="checkbox" id="confirmDelete" required>
                                <label class="form-check-label ms-2 text-danger small fw-semibold" for="confirmDelete">
                                    I confirm I want to remove this staff member. This action cannot be undone.
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Modal footer: cancel and submit buttons -->
                <div class="modal-footer border-0 pt-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:8px;">Cancel</button>
                    <button type="submit" class="btn btn-danger" style="border-radius:8px;">
                        <i class="bi bi-trash3 me-1"></i> Confirm Deletion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>