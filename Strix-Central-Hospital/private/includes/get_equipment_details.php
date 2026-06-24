<?php
// Load database configuration (provides $pdo connection)
require_once '../../config/config.php';

// Get equipment ID from GET request, default to 0 if not set
$id = intval($_GET['id'] ?? 0);

// Validate ID: must be a positive integer
if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid ID']);
    exit;
}

try {
    // 1. Fetch equipment data from the 'equipamentos' table
    $stmt = $pdo->prepare("SELECT * FROM equipamentos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $eq = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no equipment found, return error
    if (!$eq) {
        echo json_encode(['success' => false, 'error' => 'Equipment not found']);
        exit;
    }

    // 2. Fetch linked suppliers for this equipment via the bridge table
    $stmtSup = $pdo->prepare("
        SELECT f.id, f.tipo_fornecedor 
        FROM fornecedores f
        JOIN equipamento_fornecedor ef ON f.id = ef.fornecedor_id
        WHERE ef.equipamento_id = :id
    ");
    $stmtSup->execute([':id' => $id]);
    $suppliers = $stmtSup->fetchAll(PDO::FETCH_ASSOC);

    // 3. Build a map of supplier type => supplier ID
    //    This makes it easy for the frontend to populate multiple dropdowns
    $supplierMap = [];
    foreach ($suppliers as $s) {
        $supplierMap[$s['tipo_fornecedor']] = $s['id'];
    }

    // 4. Attach supplier IDs to the equipment object by type
    //    (fallback to empty string if not present)
    $eq['fornecedor_fabricante']    = $supplierMap['Fabricante']   ?? '';
    $eq['fornecedor_distribuidor']  = $supplierMap['Distribuidor'] ?? '';
    $eq['fornecedor_manutencao']    = $supplierMap['Manutencao']   ?? '';
    $eq['fornecedor_consumiveis']   = $supplierMap['Consumiveis']  ?? '';

    // 5. Return success response with equipment data
    echo json_encode(['success' => true, 'equipment' => $eq]);

} catch (PDOException $e) {
    // On database error, return error message
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>