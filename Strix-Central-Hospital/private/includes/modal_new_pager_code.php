<!-- Modal for adding a new pager code (emergency contact number) -->
<div class="modal fade" id="modal_new_pager_code" tabindex="-1">
    <div class="modal-dialog">
        <!-- Form submits to process_new_pager_code.php via POST -->
        <form action="../includes/process_new_pager_code.php" method="POST" class="modal-content">
            
            <!-- Modal header: title and close button -->
            <div class="modal-header">
                <h5 class="modal-title">New Pager Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- Modal body with input fields -->
            <div class="modal-body">
                <!-- Pager code (e.g., #1234) -->
                <div class="mb-3">
                    <label>Pager Code</label>
                    <input type="text" name="codigo_pager" class="form-control" required>
                </div>
                
                <!-- Hospital role that receives this pager code -->
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
                
                <!-- Description of the pager code's purpose -->
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="descricao" class="form-control" rows="3" required></textarea>
                </div>
            </div>
            
            <!-- Modal footer: submit button only -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save Code</button>
            </div>
        </form>
    </div>
</div>