<?php
// process_modify_item.php
require_once '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/inventory-manage.php');
    exit;
}

// Collect inputs (including the item ID to update)
$equipamento_id  = intval($_POST['id']           ?? 0);
$nome            = trim($_POST['nome']           ?? '');
$modelo          = trim($_POST['modelo']         ?? '');
$marca           = trim($_POST['marca']          ?? '');
$serial          = trim($_POST['serial']         ?? '');
$estado          = trim($_POST['estado']         ?? 'Disponivel');
$criticidade     = trim($_POST['criticidade']    ?? '');
// Collect all four supplier IDs
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

// Build location vector exactly like the insertion step
$location_vector = $location_wing . '.' . 
                   str_pad($location_floor, 2, '0', STR_PAD_LEFT) . '.' . 
                   str_pad($location_room,  3, '0', STR_PAD_LEFT);

// Validate required fields
if (empty($nome) || empty($serial) || $equipamento_id <= 0) {
    $_SESSION['error'] = 'Nome, Serial e ID do equipamento são obrigatórios.';
    header('Location: ../views/inventory-manage.php');
    exit;
}

try {
    // Update existing equipment entry
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

    // Update the junction table relationship: 
    // 1. Remove any old supplier link for this asset
    $stmtDel = $pdo->prepare("DELETE FROM equipamento_fornecedor WHERE equipamento_id = :equipamento_id");
    $stmtDel->execute([':equipamento_id' => $equipamento_id]);

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
    echo "SQL Error: " . $e->getMessage(); 
    exit;
}

header('Location: ../views/inventory-manage.php');
exit;
?>