<?php
require_once '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo_pager'];
    $funcao = $_POST['funcao_hospitalar'];
    $desc   = $_POST['descricao'];

    $sql = "INSERT INTO pager_codes (codigo_pager, funcao_hospitalar, descricao) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([$codigo, $funcao, $desc]);
        header("Location: ../views/quick-contacts.php?status=success");
    } catch (PDOException $e) {
        header("Location: ../views/quick-contacts.php?status=error");
    }
}