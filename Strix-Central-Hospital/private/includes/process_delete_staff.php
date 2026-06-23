<?php
require_once '../../config/config.php';
session_start();

// Ensure the request is POST and the required ID is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['staff_id'])) {
    
    // Use a prepared statement to safely update the row
    // We set 'deleted_at' to the current time to hide it from the UI
    $stmt = $pdo->prepare("UPDATE staff SET deleted_at = NOW() WHERE staff_id = :staff_id");
    
    try {
        $stmt->execute([':staff_id' => $_POST['staff_id']]);
        // Redirect with success message
        header('Location: ../views/staff.php?status=success');
    } catch (PDOException $e) {
        // Redirect with error message
        header('Location: ../views/staff.php?status=error');
    }
    exit;
} else {
    // If someone tries to access this file directly, redirect them away
    header('Location: ../views/staff.php');
    exit;
}
?>