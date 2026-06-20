<?php
// update_location.php
require_once '../../config/config.php'; // Your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $equipamento_id = intval($_POST['equipment_id'] ?? '');
    $location_wing = $_POST['location_wing'] ?? '';
    $location_floor = intval($_POST['location_floor'] ?? '');
    $location_room = $_POST['location_room'] ?? '';
    
    try {
        $stmt = $pdo->prepare("
            UPDATE equipamentos 
            SET 
                location_wing = :wing,
                location_floor = :floor,
                location_room = :room,
                last_scanned = NOW()
            WHERE id = :id
        ");
        $stmt->execute([
            ':wing' => $location_wing,
            ':floor' => $location_floor,
            ':room' => $location_room,
            ':id' => $equipamento_id
        ]);

        $_SESSION['success'] = 'Location updated successfully!';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Erro: ' . $e->getMessage();
    }
    
    
}
// Redirect back to location.php
    header("Location: ../views/location.php");
    exit();
?>