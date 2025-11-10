<?php
session_start();
require_once 'UserPreference.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_pref = $_POST['username_pref'] ?? 'guest';

    // Crear un nuevo objeto de preferencia de usuario
    $user_prefs = new UserPreference($username_pref);
    // Por defecto, is_admin es false. El atacante intentará cambiar esto.

    // Serializar el objeto y codificarlo en Base64 para almacenarlo en una cookie
    $serialized_prefs = base64_encode(serialize($user_prefs));

    // Establecer la cookie (sin HttpOnly para facilitar la manipulación en el navegador)
    setcookie('user_prefs', $serialized_prefs, [
        'expires' => time() + (86400 * 30), // 30 días
        'path' => '/',
        'httponly' => false, // ¡VULNERABLE! Permite JS acceder a la cookie
        'samesite' => 'Lax'
    ]);

    header('Location: index.php');
    exit();
}
