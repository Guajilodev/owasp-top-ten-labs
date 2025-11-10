<?php
session_start();
require_once 'UserPreference.php';

$current_username = $_SESSION['username'] ?? 'guest';
$current_is_admin = false;

// Intentar cargar preferencias de la cookie
if (isset($_COOKIE['user_prefs'])) {
    // VULNERABILIDAD: Deserialización insegura de datos controlados por el usuario.
    // No hay validación ni sanitización antes de deserializar.
    // Un atacante puede manipular la cookie para inyectar un objeto serializado malicioso.
    $user_prefs_serialized = base64_decode($_COOKIE['user_prefs']);
    try {
        $user_prefs = unserialize($user_prefs_serialized);
        if ($user_prefs instanceof UserPreference) {
            $current_username = $user_prefs->username;
            $current_is_admin = $user_prefs->is_admin;
        }
    } catch (Exception $e) {
        // Ignorar errores de deserialización para no romper la página
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A08: Software and Data Integrity Failures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="../index.php">← Volver al menú</a>
        <h1 class="mt-3">A08: Software and Data Integrity Failures</h1>
        <p class="lead">Este laboratorio demuestra la deserialización insegura de objetos PHP.</p>

        <div class="card mb-4">
            <div class="card-header"><h4>Tus Preferencias Actuales</h4></div>
            <div class="card-body">
                <p><strong>Usuario:</strong> <?php echo htmlspecialchars($current_username); ?></p>
                <p><strong>¿Es Administrador?:</strong> <?php echo $current_is_admin ? 'Sí' : 'No'; ?></p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h4>Establecer Nuevas Preferencias</h4></div>
            <div class="card-body">
                <form action="save_preference.php" method="POST">
                    <div class="mb-3">
                        <label for="username_pref" class="form-label">Nombre de Usuario Preferido</label>
                        <input type="text" class="form-control" id="username_pref" name="username_pref" value="<?php echo htmlspecialchars($current_username); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Preferencias</button>
                </form>
            </div>
        </div>

        <div class="alert alert-info mt-4">
            <h5 class="alert-heading">🎯 Escenario de Pentesting</h5>
            <p>La aplicación guarda tus preferencias en una cookie serializada. Intenta manipular esta cookie para cambiar la propiedad <code>is_admin</code> a <code>true</code>.</p>
            <p>Pasos:</p>
            <ol>
                <li>Establece un nombre de usuario y guarda las preferencias.</li>
                <li>Inspecciona la cookie <code>user_prefs</code> en tu navegador. Descodifica el valor de Base64.</li>
                <li>Verás un objeto PHP serializado (ej. <code>O:13:"UserPreference":2:{s:8:"username";s:5:"guest";s:8:"is_admin";b:0;}</code>).</li>
                <li>Modifica la cadena serializada para cambiar <code>b:0;</code> (false) a <code>b:1;</code> (true) para la propiedad <code>is_admin</code>.</li>
                <li>Codifica la cadena modificada de nuevo a Base64 y reemplaza el valor de la cookie en tu navegador.</li>
                <li>Recarga la página. Si ves "¿Es Administrador?: Sí", has explotado la vulnerabilidad.</li>
            </ol>
            <p>En escenarios más complejos, la deserialización insegura puede llevar a la ejecución remota de código (RCE) si las clases tienen métodos mágicos vulnerables.</p>
        </div>

    </div>
</body>
</html>
