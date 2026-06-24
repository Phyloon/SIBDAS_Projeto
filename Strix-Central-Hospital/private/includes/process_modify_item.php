<?php
// process_modify_item.php - updates an existing equipment item and its supplier links

require_once '../../config/config.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/inventory-manage.php');
    exit;
}

// Collect all form inputs
$equipamento_id  = intval($_POST['id']           ?? 0);
$nome            = trim($_POST['nome']           ?? '');
$modelo          = trim($_POST['modelo']         ?? '');
$marca           = trim($_POST['marca']          ?? '');
$serial          = trim($_POST['serial']         ?? '');
$estado          = trim($_POST['estado']         ?? 'Disponivel');
$criticidade     = trim($_POST['criticidade']    ?? '');
// Collect all supplier IDs from the four dropdowns (filter out zeros)
$fornecedores_ids = array_filter([
    intval($_POST['fornecedor_id']           ?? 0),
    intval($_POST['fornecedor_distribuidor'] ?? 0),
    intval($_POST['fornecedor_manutencao']   ?? 0),
    intval($_POST['fornecedor_consumiveis']  ?? 0),
]);
$location_wing   = trim($_POST['location_wing']  ?? '');
$location_floor  = trim($_POST['location_floor'] ?? '');
$location_room   = trim($_POST['location_room']  ?? '');
$departamento    = trim($_POST['departamento']   ?? '');
$grupo           = trim($_POST['grupo']          ?? '');
$tipo_aquisicao  = trim($_POST['tipo_aquisicao'] ?? '');
$data_aquisicao  = trim($_POST['data_aquisicao'] ?? null);
$custo_aquisicao = floatval($_POST['custo_aquisicao'] ?? 0);
$ano_fabrico     = intval($_POST['ano_fabrico']  ?? 0);

// Build the location vector (format: wing.floor.room with zero padding)
$location_vector = $location_wing . '.' . 
                   str_pad($location_floor, 2, '0', STR_PAD_LEFT) . '.' . 
                   str_pad($location_room,  3, '0', STR_PAD_LEFT);

// Validate required fields (name, serial, and valid ID)
if (empty($nome) || empty($serial) || $equipamento_id <= 0) {
    $_SESSION['error'] = 'Nome, Serial e ID do equipamento são obrigatórios.';
    header('Location: ../views/inventory-manage.php');
    exit;
}

try {
    // Update the equipment record in the 'equipamentos' table
    $stmt = $pdo->prepare("
        UPDATE equipamentos 
        SET 
            nome = :nome, 
            modelo = :modelo, 
            marca = :marca, 
            serial = :serial, 
            estado = :estado, 
            criticidade = :criticidade,
            location_vector = :location_vector, 
            location_wing = :location_wing, 
            location_floor = :location_floor, 
            location_room = :location_room,
            departamento = :departamento, 
            grupo = :grupo, 
            tipo_aquisicao = :tipo_aquisicao, 
            custo_aquisicao = :custo_aquisicao, 
            ano_fabrico = :ano_fabrico, 
            data_aquisicao = :data_aquisicao
        WHERE id = :id
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
        ':data_aquisicao'  => !empty($data_aquisicao) ? $data_aquisicao : null,
        ':id'              => $equipamento_id,
    ]);

    // Update supplier links (many-to-many relation via junction table)
    // 1. Remove all existing links for this equipment
    $stmtDel = $pdo->prepare("DELETE FROM equipamento_fornecedor WHERE equipamento_id = :equipamento_id");
    $stmtDel->execute([':equipamento_id' => $equipamento_id]);

    // 2. Insert new links for each selected supplier
    $stmtRel = $pdo->prepare("
        INSERT IGNORE INTO equipamento_fornecedor (equipamento_id, fornecedor_id)
        VALUES (:equipamento_id, :fornecedor_id)
    ");

    foreach ($fornecedores_ids as $fid) {
        if ($fid > 0) {
            $stmtRel->execute([
                ':equipamento_id' => $equipamento_id,
                ':fornecedor_id'  => $fid,
            ]);
        }
    }

    $_SESSION['success'] = 'Equipamento atualizado com sucesso!';

} catch (PDOException $e) {
    // If an error occurs, show it (for debugging) – consider logging instead in production
    echo "SQL Error: " . $e->getMessage(); 
    exit;
}

// Redirect back to the inventory management page
header('Location: ../views/inventory-manage.php');
exit;
?>