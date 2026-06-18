<?php
// api/equipment_status.php
header('Content-Type: application/json');
require_once '../../config/config.php';

try {
    $equip_stmt = $pdo->query("
        SELECT estado, COUNT(*) as total 
        FROM equipamentos 
        GROUP BY estado
    ");
    $status_data = $equip_stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // Format the response for Chart.js
    $response = [
        'Used'        => (int)($status_data['Em Uso'] ?? 0),
        'Broken'      => (int)($status_data['Fora de Servico'] ?? 0),
        'Maintenance' => (int)($status_data['Em Manutenção'] ?? 0),
        'Available'   => (int)($status_data['Disponivel'] ?? 0)
        
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error' . $e->getMessage()]);
}