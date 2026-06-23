<?php
require_once '../../config/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        // Soft delete: Assuming you have a 'deleted_at' column in 'equipamentos'
        $stmt = $pdo->prepare("UPDATE equipamentos SET deleted_at = NOW() WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error removing equipment: " . $e->getMessage();
    }
    
    header('Location: ../views/inventory-manage.php');
    exit;
}
?>