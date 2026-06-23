<?php /** @var array $allEquipments */ ?>

<div class="modal fade" id="deleteEquipmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">Remove Equipment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="../includes/process_delete_equipment.php" method="POST">
                <div class="modal-body pt-3">

                    <div class="d-flex flex-column align-items-center mb-4">
                        <div class="qr-placeholder" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; background: #fee2e2; border-radius: 50%;">
                            <i class="bi bi-trash3" style="font-size: 2rem; color: #dc3545;"></i>
                        </div>
                        <small class="text-muted mt-2">Select an item to remove from the inventory</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Equipment (Name & ID)</label>
                            <select name="id" class="form-select" style="border-radius:8px;" required>
                                <option value="" selected disabled>Select equipment to delete...</option>
                                <?php foreach ($allEquipments as $eq): ?>
                                    <option value="<?= htmlspecialchars($eq['id']) ?>">
                                        <?= htmlspecialchars($eq['nome']) ?> (ID: <?= htmlspecialchars($eq['id']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 mt-2">
                            <div class="form-check bg-light p-3" style="border-radius: 8px;">
                                <input class="form-check-input ms-1" type="checkbox" id="confirmDeleteEquip" required>
                                <label class="form-check-label ms-2 text-danger small fw-semibold" for="confirmDeleteEquip">
                                    I confirm I want to remove this item. This action cannot be undone.
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
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