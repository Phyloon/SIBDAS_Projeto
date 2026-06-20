<?php
require_once '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/inventory-manage.php');
    exit;
}

// Collect inputs
$nome            = trim($_POST['nome']           ?? '');
$modelo          = trim($_POST['modelo']         ?? '');
$marca           = trim($_POST['marca']          ?? '');
$serial          = trim($_POST['serial']         ?? '');
$estado          = trim($_POST['estado']         ?? 'Disponivel');
$criticidade     = trim($_POST['criticidade']    ?? '');
$fornecedor_id   = intval($_POST['fornecedor_id']?? 0);
$location_wing   = trim($_POST['location_wing']  ?? '');
$location_floor  = trim($_POST['location_floor'] ?? '');
$location_room   = trim($_POST['location_room']  ?? '');
$departamento    = trim($_POST['departamento']   ?? '');
$grupo           = trim($_POST['grupo']          ?? '');
$tipo_aquisicao  = trim($_POST['tipo_aquisicao'] ?? '');
$data_aquisicao  = trim($_POST['data_aquisicao'] ?? null);
$custo_aquisicao = floatval($_POST['custo_aquisicao'] ?? 0);
$ano_fabrico     = intval($_POST['ano_fabrico']  ?? 0);
$imagem          = '../assets/images/Placeholder.jpg';

// Build location vector
$location_vector = $location_wing . '.' . 
                   str_pad($location_floor, 2, '0', STR_PAD_LEFT) . '.' . 
                   str_pad($location_room,  3, '0', STR_PAD_LEFT);

// Validate required fields
if (empty($nome) || empty($serial)) {
    $_SESSION['error'] = 'Nome e Serial são obrigatórios.';
    header('Location: ../views/inventory-manage.php');
    exit;
}

try {
    // Insert equipment
    $stmt = $pdo->prepare("
        INSERT INTO equipamentos 
            (nome, modelo, marca, serial, estado, criticidade,
            location_vector, location_wing, location_floor, location_room,
            departamento, grupo, tipo_aquisicao, custo_aquisicao, ano_fabrico, data_aquisicao, imagem)
        VALUES 
            (:nome, :modelo, :marca, :serial, :estado, :criticidade,
            :location_vector, :location_wing, :location_floor, :location_room,
            :departamento, :grupo, :tipo_aquisicao, :custo_aquisicao, :ano_fabrico, :data_aquisicao, :imagem)
    ");

    $stmt->execute([
        ':nome'            => $nome,
        ':modelo'          => $modelo,
        ':marca'           => $marca,
        ':serial'          => $serial,
        ':estado'          => $estado,
        ':criticidade'     => $criticidade,
        ':location_vector' => $location_vector,
        ':location_wing'   => $location_wing,
        ':location_floor'  => $location_floor,
        ':location_room'   => $location_room,
        ':departamento'    => $departamento,
        ':grupo'           => $grupo,
        ':tipo_aquisicao'  => $tipo_aquisicao,
        ':custo_aquisicao' => $custo_aquisicao,
        ':ano_fabrico'     => $ano_fabrico,
        ':data_aquisicao'  => !empty($data_aquisicao) ? $data_aquisicao : null, // Envia null se estiver vazio
        ':imagem'          => $imagem,
    ]);

    // Get the new equipment ID
    $equipamento_id = $pdo->lastInsertId();

    // Insert into junction table if supplier was selected
    if ($fornecedor_id > 0) {
        $stmtRel = $pdo->prepare("
            INSERT INTO equipamento_fornecedor (equipamento_id, fornecedor_id)
            VALUES (:equipamento_id, :fornecedor_id)
        ");
        $stmtRel->execute([
            ':equipamento_id' => $equipamento_id,
            ':fornecedor_id'  => $fornecedor_id,
        ]);
    }

    $_SESSION['success'] = 'Equipamento adicionado com sucesso!';

} catch (PDOException $e) {
    $_SESSION['error'] = 'Erro ao adicionar: ' . $e->getMessage();
}

header('Location: ../views/inventory-manage.php');
exit;
?>