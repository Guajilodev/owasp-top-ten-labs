<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $_SESSION['login_message'] = 'Error: El usuario y la contraseña no pueden estar vacíos.';
        header('Location: index.php');
        exit();
    }

    // VULNERABILIDAD: Se compara el hash MD5 de la contraseña introducida directamente con el de la BD.
    // Si un atacante obtiene los hashes, puede usar tablas rainbow o fuerza bruta para encontrar la contraseña original.
    $md5_password = md5($password);

    $stmt = $pdo->prepare("SELECT * FROM users_crypto WHERE username = ? AND md5_password = ?");
    $stmt->execute([$username, $md5_password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['login_message'] = "¡Login exitoso! Bienvenido, {$username}. (Contraseña hasheada con MD5: {$md5_password})";
    } else {
        $_SESSION['login_message'] = "Login fallido. Usuario o contraseña incorrectos.";
    }

    header('Location: index.php');
    exit();
}
