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

    // VULNERABILIDAD: Se hashea la contraseña con MD5, un algoritmo débil y sin salt.
    $md5_password = md5($password);

    try {
        $stmt = $pdo->prepare("INSERT INTO users_crypto (username, md5_password) VALUES (?, ?)");
        $stmt->execute([$username, $md5_password]);
        $_SESSION['login_message'] = "Usuario '{$username}' registrado con éxito. Ahora puedes iniciar sesión.";
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) { // Código de error para entrada duplicada
            $_SESSION['login_message'] = "Error: El usuario '{$username}' ya existe.";
        } else {
            $_SESSION['login_message'] = "Error en la base de datos: " . $e->getMessage();
        }
    }

    header('Location: index.php');
    exit();
}
