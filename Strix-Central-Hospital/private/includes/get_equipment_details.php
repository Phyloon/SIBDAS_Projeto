<?php
require_once '../../config/config.php';

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid ID']);
    exit;
}

try {
    // Get equipment data
    $stmt = $pdo->prepare("SELECT * FROM equipamentos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $eq = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$eq) {
        echo json_encode(['success' => false, 'error' => 'Equipment not found']);
        exit;
    }

    // Get linked suppliers
    $stmtSup = $pdo->prepare("
        SELECT f.id, f.tipo_fornecedor 
        FROM fornecedores f
        JOIN equipamento_fornecedor ef ON f.id = ef.fornecedor_id
        WHERE ef.equipamento_id = :id
    ");
    $stmtSup->execute([':id' => $id]);
    $suppliers = $stmtSup->fetchAll(PDO::FETCH_ASSOC);

    // Map suppliers by type so JS can populate the right dropdowns
    $supplierMap = [];
    foreach ($suppliers as $s) {
        $supplierMap[$s['tipo_fornecedor']] = $s['id'];
    }

    $eq['fornecedor_fabricante']    = $supplierMap['Fabricante']   ?? '';
    $eq['fornecedor_distribuidor']  = $supplierMap['Distribuidor'] ?? '';
    $eq['fornecedor_manutencao']    = $supplierMap['Manutencao']   ?? '';
    $eq['fornecedor_consumiveis']   = $supplierMap['Consumiveis']  ?? '';

    echo json_encode(['success' => true, 'equipment' => $eq]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>