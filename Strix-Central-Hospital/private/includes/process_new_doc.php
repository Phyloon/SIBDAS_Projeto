<?php
require_once '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['documentos'])) {
    $equipamento_id = intval($_POST['equipamento_id']);
    $tipo = $_POST['tipo_documento'];
    $files = $_FILES['documentos'];

    // Catch the optional fields: if empty, send null to DB
    $data_validade = !empty($_POST['data_validade']) ? $_POST['data_validade'] : null;
    $fornecedor_id = !empty($_POST['fornecedor_id']) ? intval($_POST['fornecedor_id']) : null;

    // Turn spaces into underscores for a clean folder name (e.g., "Manual_de_Servico")
    $safeFolderName = str_replace(' ', '_', $tipo);
    $uploadDir = '../includes/documents/' . $safeFolderName . '/';

    // Create the category folder if it doesn't exist yet
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    foreach ($files['name'] as $index => $name) {
        $tmpName = $files['tmp_name'][$index];
        $newName = $equipamento_id . '_' . time() . '_' . basename($name);

        // Final path inside the specific category folder
        $path = $uploadDir . $newName;

        if (move_uploaded_file($tmpName, $path)) {
            $stmt = $pdo->prepare("INSERT INTO documentos_equipamento (equipamento_id, tipo_documento, nome_ficheiro, caminho_ficheiro, data_validade, fornecedor_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$equipamento_id, $tipo, $name, $path, $data_validade, $fornecedor_id]);
        }
    }
    $_SESSION['success'] = "Documents uploaded successfully!";
}
header('Location: ../views/documentacao.php');
exit;