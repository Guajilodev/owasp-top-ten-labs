<?php
$host = 'db';
$dbname = 'owasp_labs';
$user = 'user';
$pass = 'password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->exec("SET NAMES utf8mb4");
    echo 'Connection successful!\n';
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage() . "\n";
}
?>
