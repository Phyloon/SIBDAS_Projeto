<?php
// API endpoint: returns equipment status counts in JSON format for Chart.js

// Set response header to JSON
header('Content-Type: application/json');

// Load database configuration (provides $pdo connection)
require_once '../../config/config.php';

try {
    // Query: count equipment by status (group by estado)
    $equip_stmt = $pdo->query("
        SELECT estado, COUNT(*) as total 
        FROM equipamentos 
        GROUP BY estado
    ");
    // Fetch as key-value pair (status => count)
    $status_data = $equip_stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // Build a normalized response array with the keys expected by the frontend chart
    // Each value defaults to 0 if the status is not found
    $response = [
        'Used'        => (int)($status_data['Em Uso'] ?? 0),
        'Broken'      => (int)($status_data['Fora de Servico'] ?? 0),
        'Maintenance' => (int)($status_data['Em Manutenção'] ?? 0),
        'Available'   => (int)($status_data['Disponivel'] ?? 0)
    ];

    // Send JSON response
    echo json_encode($response);

} catch (PDOException $e) {
    // On database error, return an error JSON message
    echo json_encode(['error' => 'Database error' . $e->getMessage()]);
}