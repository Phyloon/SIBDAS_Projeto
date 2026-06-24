<?php
require_once '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get common fields
    $tipo_registo   = $_POST['tipo_registo'] ?? '';
    $equipamento_id = intval($_POST['equipamento_id'] ?? 0);
    $fornecedor_id  = !empty($_POST['fornecedor_id']) ? intval($_POST['fornecedor_id']) : null;
    $data_inicio    = $_POST['data_inicio'] ?? null;
    $data_fim       = $_POST['data_fim'] ?? null;

    // Validate required fields
    if (!$equipamento_id || !$data_inicio || !$data_fim) {
        $_SESSION['error'] = 'Preencha todos os campos obrigatórios.';
        header('Location: ../views/maintenance-records.php');
        exit;
    }

    // Map tipo_registo to tipo_documento
    $tipo_documento = ($tipo_registo === 'garantia') ? 'Garantia' : 'Contrato';

    // For contracts, get extra fields
    $tipo_contrato = null;
    $periodicidade = null;
    if ($tipo_registo === 'contrato') {
        $tipo_contrato = $_POST['tipo_contrato'] ?? null;
        $periodicidade = $_POST['periodicidade'] ?? null;
    }

    // Handle file upload
    $caminho_ficheiro = '';
    $nome_ficheiro = '';
    if (isset($_FILES['ficheiro_documento']) && $_FILES['ficheiro_documento']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['ficheiro_documento']['tmp_name'];
        $originalName = $_FILES['ficheiro_documento']['name'];
        $fileExt = pathinfo($originalName, PATHINFO_EXTENSION);
        
        // Generate a unique filename
        $newFileName = 'doc_' . uniqid() . '.' . $fileExt;
        
        // Define upload directory (absolute path relative to this script)
        // This script is in /private/includes/, so we save to /private/includes/documents/
        $uploadDir = __DIR__ . '/documents/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $destPath = $uploadDir . $newFileName;
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Store the web-accessible path (absolute from web root)
            $caminho_ficheiro = '/private/includes/documents/' . $newFileName;
            $nome_ficheiro = $originalName;
        } else {
            $_SESSION['error'] = 'Erro ao fazer upload do ficheiro.';
            header('Location: ../views/maintenance-records.php');
            exit;
        }
    }

    // Insert into documentos_equipamento
    try {
        $stmt = $pdo->prepare("
            INSERT INTO documentos_equipamento 
            (equipamento_id, fornecedor_id, tipo_documento, tipo_contrato, periodicidade,
             data_inicio_garantia, data_fim_garantia, caminho_ficheiro, nome_ficheiro) 
            VALUES 
            (:equip, :forn, :tipo_doc, :tipo_contr, :period,
             :inicio, :fim, :caminho, :nome)
        ");

        $stmt->execute([
            ':equip'     => $equipamento_id,
            ':forn'      => $fornecedor_id,
            ':tipo_doc'  => $tipo_documento,
            ':tipo_contr'=> $tipo_contrato,
            ':period'    => $periodicidade,
            ':inicio'    => $data_inicio,
            ':fim'       => $data_fim,
            ':caminho'   => $caminho_ficheiro,
            ':nome'      => $nome_ficheiro
        ]);

        $_SESSION['success'] = 'Registo adicionado com sucesso!';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Erro ao guardar: ' . $e->getMessage();
    }

    header('Location: ../views/maintenance-records.php');
    exit;
}