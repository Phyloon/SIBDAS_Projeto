<?php
// Configurações globais da aplicação
define('BASE_URL', '/Strixhaven');

define('MYSQL_HOST', 'localhost');
define('MYSQL_DATABASE', 'strix_data');
define('MYSQL_USERNAME', 'root');
define('MYSQL_PASSWORD', '');
define('MYSQL_AES_KEY', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD');

try {
    $pdo = new PDO(
        "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DATABASE . ";charset=utf8", MYSQL_USERNAME, MYSQL_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

define('APP_NAME', 'Strixhaven');
define('APP_VERSION', '1.0.0');
define('APP_COPYRIGHT', '© 2026 ISEP');