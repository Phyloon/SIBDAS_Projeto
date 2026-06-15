<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Ligar à Base de Dados (ajusta o caminho se o teu config estiver noutro sítio)
    require_once '../../config/config.php'; 

    // 2. Recolher e higienizar os dados do POST
    $nome            = trim($_POST['nome']);
    $role            = trim($_POST['role']);
    $departamento    = trim($_POST['departamento']);
    $disponibilidade = trim($_POST['disponibilidade']);
    $contacto        = trim($_POST['contacto']);
    $staff_id        = trim($_POST['staff_id']);

    // 3. Gerar o link automático de imagem
    $avatar_name = urlencode($nome);
    $imagem = "https://ui-avatars.com/api/?name={$avatar_name}&background=0ea5e9&color=fff";

    try {
        // 4. Preparar a Query SQL
        $sql = "INSERT INTO staff (nome, role, departamento, disponibilidade, contacto, staff_id, imagem) 
                VALUES (:nome, :role, :departamento, :disponibilidade, :contacto, :staff_id, :imagem)";
        
        $stmt = $pdo->prepare($sql);
        
        // 5. Executar
        $stmt->execute([
            ':nome'            => $nome,
            ':role'            => $role,
            ':departamento'    => $departamento,
            ':disponibilidade' => $disponibilidade,
            ':contacto'        => $contacto,
            ':staff_id'        => $staff_id,
            ':imagem'          => $imagem
        ]);

        // CORREÇÃO: Redirecionar para staff.php (e não staff_page.php)
        header("Location: ../views/staff.php?status=success");
        exit();

    } catch (PDOException $e) {
        // Em caso de erro, volta com a mensagem de erro para debug
        header("Location: staff.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: staff.php");
    exit();
}