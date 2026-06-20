<?php
require_once '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipamento_id = intval($_POST['equipamento_id']);
    $fornecedor_id  = intval($_POST['fornecedor_id']);

    try {
        // Insert or Update the link
        // Using REPLACE or ON DUPLICATE KEY UPDATE ensures you don't get duplicate errors
        $stmt = $pdo->prepare("
            INSERT INTO equipamento_fornecedor (equipamento_id, fornecedor_id) 
            VALUES (:eq_id, :for_id)
            ON DUPLICATE KEY UPDATE fornecedor_id = :for_id
        ");
        $stmt->execute([':eq_id' => $equipamento_id, ':for_id' => $fornecedor_id]);

        $_SESSION['success'] = 'Equipamento associado com sucesso!';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Erro: ' . $e->getMessage();
    }
}
header('Location: ../views/fornecedores.php');
exit;