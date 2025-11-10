<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        header('Location: index.php?message=Error: Usuario y contraseña no pueden estar vacíos.');
        exit();
    }

    // VULNERABILIDAD: No hay protección contra fuerza bruta (rate limiting, bloqueo de cuentas).
    // Las contraseñas se almacenan y comparan en texto plano (o un hash débil sin sal).
    $stmt = $pdo->prepare("SELECT * FROM users_auth WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php');
    } else {
        header('Location: index.php?message=Login fallido. Usuario o contraseña incorrectos.');
    }
    exit();
}
