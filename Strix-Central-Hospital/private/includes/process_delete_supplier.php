<?php
// process_delete_supplier.php
require_once '../../config/config.php';
session_start();

// Ensure it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/inventory-manage.php');
    exit;
}

$fornecedor_id = intval($_POST['id'] ?? 0);

if ($fornecedor_id <= 0) {
    $_SESSION['error'] = 'Invalid supplier ID selected.';
    header('Location: ../views/inventory-manage.php');
    exit;
}

try {
    // We update the 'deleted_at' column to the current timestamp 
    // instead of running a physical DELETE command.
    $stmt = $pdo->prepare("
        UPDATE fornecedores 
        SET deleted_at = NOW() 
        WHERE id = :id AND deleted_at IS NULL
    ");
    
    $stmt->execute([':id' => $fornecedor_id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = 'Supplier soft-deleted successfully!';
    } else {
        $_SESSION['error'] = 'Supplier not found or already deleted.';
    }

} catch (PDOException $e) {
    error_log("Soft Delete Error: " . $e->getMessage());
    $_SESSION['error'] = 'An error occurred while attempting to remove the supplier.';
}

header('Location: ../views/fornecedores.php');
exit;