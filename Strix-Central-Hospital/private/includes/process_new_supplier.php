<?php
require_once '../../config/config.php';

// Ensure the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/fornecedores.php');
    exit;
}

// Collect inputs using the updated name attributes
$nome_empresa      = trim($_POST['nome_empresa'] ?? '');
$tipo_fornecedor   = trim($_POST['tipo_fornecedor'] ?? '');
$email             = trim($_POST['email'] ?? '');
$website           = trim($_POST['website'] ?? '');
$endereco          = trim($_POST['endereco'] ?? '');
$nif               = trim($_POST['nif'] ?? '');
$telefone          = trim($_POST['telefone'] ?? '');
$pessoa_contacto   = trim($_POST['pessoa_contacto'] ?? '');
$telefone_contacto = trim($_POST['telefone_contacto'] ?? '');

// Validate required fields (Company name is the bare minimum)
if (empty($nome_empresa)) {
    $_SESSION['error'] = 'O Nome da Empresa é obrigatório.';
    header('Location: ../views/fornecedores.php');
    exit;
}

try {
    // Insert new supplier into the database
    // Ensure these column names match exactly what you have in phpMyAdmin
    $stmt = $pdo->prepare("
        INSERT INTO fornecedores 
            (nome_empresa, nif, telefone, email, endereco, website, pessoa_contacto, telefone_contacto, tipo_fornecedor)
        VALUES 
            (:nome_empresa, :nif, :telefone, :email, :endereco, :website, :pessoa_contacto, :telefone_contacto, :tipo_fornecedor)
    ");

    $stmt->execute([
        ':nome_empresa'      => $nome_empresa,
        ':nif'               => $nif,
        ':telefone'          => $telefone,
        ':email'             => $email,
        ':endereco'          => $endereco,
        ':website'           => $website,
        ':pessoa_contacto'   => $pessoa_contacto,
        ':telefone_contacto' => $telefone_contacto,
        ':tipo_fornecedor'   => $tipo_fornecedor,
    ]);

    $_SESSION['success'] = 'Fornecedor adicionado com sucesso!';

} catch (PDOException $e) {
    $_SESSION['error'] = 'Erro ao adicionar fornecedor: ' . $e->getMessage();
}

// Redirect back to the suppliers dashboard
header('Location: ../views/fornecedores.php');
exit;
?>