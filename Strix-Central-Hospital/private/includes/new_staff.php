<div class="modal fade" id="addStaffModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <form action="../includes/process_new_staff.php" method="POST">
                
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Add New Staff Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body pt-3">
                    <div class="d-flex flex-column align-items-center mb-4">
                        <div class="qr-placeholder" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; background: #f1f5f9; border-radius: 50%;">
                            <i class="bi bi-person-plus" style="font-size: 2rem; color: #64748b;"></i>
                        </div>
                        <small class="text-muted mt-2">Register new hospital personnel</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="nome" class="form-control" placeholder="e.g. Dr. Alexandre Silva" style="border-radius:8px;" required>
                        </div>

                        <div class="col-6">
                            <label class="form-label">Role</label>
                            <input type="text" name="role" class="form-control" placeholder="e.g. Doctor, Nurse, Tech" style="border-radius:8px;" required>
                        </div>

                        <div class="col-6">
                            <label class="form-label">Staff ID</label>
                            <input type="text" name="staff_id" class="form-control" placeholder="e.g. #ST-00099" style="border-radius:8px;" required>
                        </div>

                        <div class="col-6">
                            <label class="form-label">Department</label>
                            <select name="departamento" class="form-select" style="border-radius:8px;" required>
                                <option value="">Select...</option>
                                <option value="Cardiology">Cardiology</option>
                                <option value="Radiology">Radiology</option>
                                <option value="ICU">ICU</option>
                                <option value="Surgery">Surgery</option>
                                <option value="Neurology">Neurology</option>
                            </select>
                        </div>

                        <div class="col-6">
                            <label class="form-label">Status</label>
                            <select name="disponibilidade" class="form-select" style="border-radius:8px;" required>
                                <option value="On Shift">On Shift</option>
                                <option value="On Call">On Call</option>
                                <option value="Off Duty">Off Duty</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contacto" class="form-control" placeholder="e.g. +351 9xx xxx xxx" style="border-radius:8px;" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius:8px;">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom" style="border-radius:8px;">
                        <i class="bi bi-plus-lg me-1"></i> Add Staff Member
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>