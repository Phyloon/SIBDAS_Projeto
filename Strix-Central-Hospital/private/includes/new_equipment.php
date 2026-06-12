<div class="modal fade" id="addEquipmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Add New Equipment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">

                <div class="d-flex flex-column align-items-center mb-4">
                    <div class="qr-placeholder" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; background: #f1f5f9; border-radius: 50%;">
                        <i class="bi bi-box-seam" style="font-size: 2rem; color: #64748b;"></i>
                    </div>
                    <small class="text-muted mt-2">Register new inventory item</small>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">Equipment Name</label>
                        <input type="text" class="form-control" placeholder="e.g. Defibrillator" style="border-radius:8px;">
                    </div>

                    <div class="col-6">
                        <label class="form-label">Equipment Model</label>
                        <input type="text" class="form-control" placeholder="e.g. R Series" style="border-radius:8px;">
                    </div>

                    <div class="col-6">
                        <label class="form-label">Equipment ID</label>
                        <input type="text" class="form-control" placeholder="e.g. 04.324.00" style="border-radius:8px;">
                    </div>

                    <div class="col-6">
                        <label class="form-label">Criticality</label>
                        <select class="form-select" style="border-radius:8px;">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="life support">Life Support</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Supplier</label>
                        <select class="form-select" style="border-radius:8px;">
                            <option value="">Select supplier...</option>
                            <option>Zoll Medical Corporation</option>
                            <option>Drägerwerke AG</option>
                            <option>Philips Healthcare</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description <span class="text-muted fw-normal">(optional)</span></label>
                        <textarea class="form-control" rows="2" placeholder="Brief technical notes" style="border-radius:8px;"></textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>