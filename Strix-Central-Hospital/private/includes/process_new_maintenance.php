<?php
require_once '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_registo   = $_POST['tipo_registo']; // 'garantia' or 'contrato'
    $equipamento_id = $_POST['equipamento_id'];
    $fornecedor_id  = !empty($_POST['fornecedor_id']) ? $_POST['fornecedor_id'] : null;
    $data_inicio    = !empty($_POST['data_inicio']) ? $_POST['data_inicio'] : null;
    $data_fim       = !empty($_POST['data_fim']) ? $_POST['data_fim'] : null;
    $observacoes    = $_POST['observacoes'];

    // Specific variables depending on type
    $tipo_contrato  = ($tipo_registo === 'contrato') ? $_POST['tipo_contrato'] : 'Garantia';
    $periodicidade  = ($tipo_registo === 'contrato') ? $_POST['periodicidade'] : null;

    try {
        $pdo->beginTransaction();

        // 1. Insert into contratos_manutencao table
        $stmt = $pdo->prepare("
            INSERT INTO contratos_manutencao 
            (equipamento_id, fornecedor_id, data_inicio_garantia, data_fim_garantia, tipo_contrato, periodicidade, observacoes)
            VALUES (:equip_id, :forn_id, :inicio, :fim, :tipo, :periodo, :obs)
        ");

        $stmt->execute([
            ':equip_id' => $equipamento_id,
            ':forn_id'  => $fornecedor_id,
            ':inicio'   => $data_inicio,
            ':fim'      => $data_fim,
            ':tipo'     => $tipo_contrato,
            ':periodo'  => $periodicidade,
            ':obs'      => $observacoes
        ]);

        // 2. Handle File Upload (If a file was attached)
        if (isset($_FILES['ficheiro_documento']) && $_FILES['ficheiro_documento']['error'] === UPLOAD_ERR_OK) {
            
            $fileTmpPath = $_FILES['ficheiro_documento']['tmp_name'];
            $fileName = $_FILES['ficheiro_documento']['name'];
            
            // Clean filename and add timestamp to prevent overwriting
            $cleanFileName = time() . '_' . preg_replace("/[^a-zA-Z0-9.-]/", "_", $fileName);
            
            // Define your upload directory (adjust path if needed)
            $uploadFileDir = '/private/includes/documents';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $dest_path = $uploadFileDir . $cleanFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Determine document type for the database
                $tipo_doc_db = ($tipo_registo === 'garantia') ? 'Faturas/Guia de Aquisicao' : 'Contrato de Manutenção';

                // Insert into your documents table so it shows up in your docs page!
                $stmtDoc = $pdo->prepare("
                    INSERT INTO documentos_equipamento 
                    (equipamento_id, fornecedor_id, tipo_documento, nome_ficheiro, caminho_ficheiro, data_validade) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                
                // Adjust the path to be relative to the web root if necessary 
                // e.g., '../uploads/documents/filename.pdf'
                $webPath = '../uploads/documents/' . $cleanFileName;

                $stmtDoc->execute([
                    $equipamento_id,
                    $fornecedor_id,
                    $tipo_doc_db,
                    $fileName, // Original name
                    $webPath,  // Path to access it via web
                    $data_fim  // Document expires when the contract/warranty ends
                ]);
            }
        }

        $pdo->commit();
        header('Location: ../views/maintenance-records.php?success=1');
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        // Log the error or handle it
        die("Error saving record: " . $e->getMessage());
    }
}
?>