<?php
require_once '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['documentos'])) {
    $equipamento_id = intval($_POST['equipamento_id']);
    $tipo = $_POST['tipo_documento'];
    $files = $_FILES['documentos'];

    foreach ($files['name'] as $index => $name) {
        $tmpName = $files['tmp_name'][$index];

        //time() is the number of seconds since 1970, unix timestamp, and it ensures a unique id/name
        $newName = $equipamento_id . '_' . time() . '_' . basename($name);
        $path = '../includes/documents/' . $newName;

        if (move_uploaded_file($tmpName, $path)) {
            $stmt = $pdo->prepare("INSERT INTO documentos_equipamento (equipamento_id, tipo_documento, nome_ficheiro, caminho_ficheiro) VALUES (?, ?, ?, ?)");
            $stmt->execute([$equipamento_id, $tipo, $name, $path]);
        }
    }
    $_SESSION['success'] = "Documents uploaded successfully!";
}
header('Location: ../views/documentacao.php');