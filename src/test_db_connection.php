<?php
$host = getenv('DB_HOST') ?: 'db';
$dbname = getenv('DB_NAME') ?: 'owasp_labs';
$user = getenv('DB_USER') ?: 'owasp_user';
$pass = getenv('DB_PASSWORD') ?: '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->exec("SET NAMES utf8mb4");
    echo 'Connection successful!\n';
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage() . "\n";
}
?>
