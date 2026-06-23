<?php
// process_modify_supplier.php
require_once '../../config/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/fornecedores.php');
    exit;
}

// 1. Collect and Sanitize Inputs
$id                = intval($_POST['id'] ?? 0);
$nome_empresa      = trim($_POST['nome_empresa'] ?? '');
$tipo_fornecedor   = trim($_POST['tipo_fornecedor'] ?? '');
$email             = trim($_POST['email'] ?? '');
$website           = trim($_POST['website'] ?? '');
$endereco          = trim($_POST['endereco'] ?? '');
$nif               = trim($_POST['nif'] ?? '');
$telefone          = trim($_POST['telefone'] ?? '');
$pessoa_contacto   = trim($_POST['pessoa_contacto'] ?? '');
$telefone_contacto = trim($_POST['telefone_contacto'] ?? '');

if ($id <= 0) {
    $_SESSION['error'] = 'Invalid Supplier ID.';
    header('Location: ../views/fornecedores.php');
    exit;
}

try {
    // 2. Perform the Update
    // Note: We only touch the 'fornecedores' table.
    // The 'equipamento_fornecedor' junction table remains untouched, 
    // keeping all relationships intact.
    $stmt = $pdo->prepare("
        UPDATE fornecedores SET 
            nome_empresa = :nome,
            tipo_fornecedor = :tipo,
            email = :email,
            website = :website,
            endereco = :endereco,
            nif = :nif,
            telefone = :telefone,
            pessoa_contacto = :pc,
            telefone_contacto = :tpc
        WHERE id = :id
    ");

    $stmt->execute([
        ':nome' => $nome_empresa,
        ':tipo' => $tipo_fornecedor,
        ':email' => $email,
        ':website' => $website,
        ':endereco' => $endereco,
        ':nif' => $nif,
        ':telefone' => $telefone,
        ':pc' => $pessoa_contacto,
        ':tpc' => $telefone_contacto,
        ':id' => $id
    ]);

    $_SESSION['success'] = 'Supplier details updated successfully!';

} catch (PDOException $e) {
    error_log("Update Error: " . $e->getMessage());
    $_SESSION['error'] = 'Database error: Could not update supplier.';
}

header('Location: ../views/fornecedores.php');
exit;