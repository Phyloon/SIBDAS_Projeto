<?php
// Configurações globais da aplicação
define('BASE_URL', '/Strixhaven');

define('MYSQL_HOST',     'vsgate-s1.dei.isep.ipp.pt');
define('MYSQL_PORT',     '10464');
define('MYSQL_DATABASE', 'db1240863');
define('MYSQL_USERNAME', '1240863');
define('MYSQL_PASSWORD', 'martins_863');

try {
    $pdo = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}