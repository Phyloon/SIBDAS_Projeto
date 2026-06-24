<?php
require_once '../../config/config.php'; // Adjust path if necessary

// 1. Realistic Medical Equipment Templates
$templates = [
    ['nome' => 'Monitor de Sinais Vitais', 'modelo' => 'IntelliVue MX40', 'marca' => 'Philips', 'grupo' => 'Grupo 1- Monitorizacao', 'criticidade' => 'Alta', 'dept' => 'ICU'],
    ['nome' => 'Desfibrilhador', 'modelo' => 'R Series', 'marca' => 'Zoll', 'grupo' => 'Grupo 2- Suporte de Vida', 'criticidade' => 'Sporte de Vida', 'dept' => 'Cardiology'],
    ['nome' => 'Ventilador Mecânico', 'modelo' => 'Evita V500', 'marca' => 'Dräger', 'grupo' => 'Grupo 2- Suporte de Vida', 'criticidade' => 'Sporte de Vida', 'dept' => 'ICU'],
    ['nome' => 'Bomba de Infusão Volumétrica', 'modelo' => 'Infusomat Space', 'marca' => 'B. Braun', 'grupo' => 'Grupo 3- Terapia', 'criticidade' => 'Alta', 'dept' => 'Surgery'],
    ['nome' => 'Ecoccardiógrafo', 'modelo' => 'Vivid IQ', 'marca' => 'GE Healthcare', 'grupo' => 'Grupo 4- Diagnostico', 'criticidade' => 'Média', 'dept' => 'Cardiology'],
    ['nome' => 'Raio-X Portátil', 'modelo' => 'Mobilett Elara Max', 'marca' => 'Siemens', 'grupo' => 'Grupo 4- Diagnostico', 'criticidade' => 'Alta', 'dept' => 'Radiology'],
    ['nome' => 'Analisador de Sangue', 'modelo' => 'i-STAT 1', 'marca' => 'Abbott', 'grupo' => 'Grupo 5- Laboratorio', 'criticidade' => 'Média', 'dept' => 'ICU'],
    ['nome' => 'Eletrocardiógrafo (ECG)', 'modelo' => 'MAC 2000', 'marca' => 'GE Healthcare', 'grupo' => 'Grupo 4- Diagnostico', 'criticidade' => 'Alta', 'dept' => 'Cardiology'],
    ['nome' => 'Máquina de Anestesia', 'modelo' => 'Aisys CS2', 'marca' => 'GE Healthcare', 'grupo' => 'Grupo 2- Suporte de Vida', 'criticidade' => 'Sporte de Vida', 'dept' => 'Surgery'],
    ['nome' => 'Bisturi Elétrico', 'modelo' => 'ForceTriad', 'marca' => 'Medtronic', 'grupo' => 'Grupo 3- Terapia', 'criticidade' => 'Alta', 'dept' => 'Surgery'],
    ['nome' => 'Cama Hospitalar Elétrica', 'modelo' => 'ProCuity', 'marca' => 'Stryker', 'grupo' => 'Grupo 7- Reabilitacao', 'criticidade' => 'Baixa', 'dept' => 'Neurology'],
    ['nome' => 'Tomógrafo Computadorizado', 'modelo' => 'Somatom Go', 'marca' => 'Siemens', 'grupo' => 'Grupo 4- Diagnostico', 'criticidade' => 'Alta', 'dept' => 'Radiology']
];

$estados = ['Disponivel', 'Disponivel', 'Disponivel', 'Em Uso', 'Em Uso', 'Em Manutencao', 'Fora de Servico'];
$wings = ['WA', 'WB', 'WC', 'WD', 'WE'];
$tipos_aquisicao = ['Compra', 'Compra', 'Compra', 'Leasing', 'Doação'];
$observacoes = ['', '', 'Requer calibração anual', 'Apresentou falha no display mês passado', 'Equipamento novo', 'Doação da fundação local'];

// Added 'imagem' column to the query
$sql = "INSERT INTO equipamentos (
    nome, modelo, estado, criticidade, serial, marca, ano_fabrico, 
    location_wing, location_floor, location_room, departamento, grupo, 
    tipo_aquisicao, custo_aquisicao, data_aquisicao, observacao, imagem
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$insertedCount = 0;
$imagePath = 'private/assets/images/Placeholder.jpg';

try {
    $pdo->beginTransaction();

    for ($i = 0; $i < 200; $i++) {
        $template = $templates[array_rand($templates)];
        $estado = $estados[array_rand($estados)];
        $serial = sprintf("%02d.%03d.%02d", rand(1,99), rand(100,999), rand(10,99));
        $ano_fabrico = rand(2010, 2024);
        
        $wing = $wings[array_rand($wings)];
        $floor = sprintf("%02d", rand(1, 5));
        $room = $floor . sprintf("%02d", rand(1, 20));
        
        $aquisicao = $tipos_aquisicao[array_rand($tipos_aquisicao)];
        $custo = rand(500, 50000) + (rand(0, 99) / 100);
        
        $timestamp = rand(strtotime("2015-01-01"), time());
        $data_aquisicao = date("Y-m-d", $timestamp);
        
        $obs = $observacoes[array_rand($observacoes)];

        // Executing with the image path included at the end
        $stmt->execute([
            $template['nome'],
            $template['modelo'],
            $estado,
            $template['criticidade'],
            $serial,
            $template['marca'],
            $ano_fabrico,
            $wing,
            $floor,
            $room,
            $template['dept'],
            $template['grupo'],
            $aquisicao,
            $custo,
            $data_aquisicao,
            $obs,
            $imagePath
        ]);

        $insertedCount++;
    }

    $pdo->commit();
    echo "<h3 style='color:green; font-family:sans-serif;'>Success: $insertedCount equipment records with placeholder images generated successfully!</h3>";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "<h3 style='color:red; font-family:sans-serif;'>Error generating records: " . $e->getMessage() . "</h3>";
}
?>