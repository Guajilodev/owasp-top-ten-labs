<?php
require_once '../config/db.php';

echo "<div class='container mt-4'>";
echo "<h1>Demostración de Error Detallado</h1>";
echo "<p>Intentando ejecutar una consulta SQL inválida...</p>";
echo "<hr>";

try {
    // VULNERABILIDAD: Se intenta consultar una tabla que no existe.
    // En un entorno de producción mal configurado (display_errors=On),
    // esto revelará información sensible como el stack trace, la consulta SQL, 
    // y detalles de la conexión a la base de datos.
    $stmt = $pdo->query("SELECT * FROM tabla_inexistente");
    echo "<div class='alert alert-success'>La consulta se ejecutó sin errores (esto no debería pasar).</div>";

} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>";
    echo "<h4>¡Error de Base de Datos Expuesto!</h4>";
    echo "<p>La aplicación ha filtrado el siguiente mensaje de error detallado:</p>";
    echo "<pre><code>";
    print_r($e);
    echo "</code></pre>";
    echo "</div>";
}

echo "</div>";

// Añadir Bootstrap para un formato más limpio
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
