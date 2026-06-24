<div class="modal fade" id="modal_new_pager_code" tabindex="-1">
    <div class="modal-dialog">
        <form action="../includes/process_new_pager_code.php" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Pager Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Pager Code</label>
                    <input type="text" name="codigo_pager" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Receiving Role</label>
                    <select name="funcao_hospitalar" class="form-select" required>
                        <option value="Nurse">Nurse</option>
                        <option value="Doctor">Doctor</option>
                        <option value="Biomedical Technician">Biomedical Technician</option>
                        <option value="Radiologist">Radiologist</option>
                        <option value="Surgeon">Surgeon</option>
                        <option value="Tech">Tech</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="descricao" class="form-control" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save Code</button>
            </div>
        </form>
    </div>
</div>