<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Registros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="index.php">← Volver al Laboratorio A09</a>
        <h1 class="mt-3">Contenido del Log</h1>
        <pre class="bg-light p-3 border rounded"><code><?php
            $log_file = 'log.txt';
            if (file_exists($log_file)) {
                echo htmlspecialchars(file_get_contents($log_file));
            } else {
                echo "El archivo de log no existe.";
            }
        ?></code></pre>
    </div>
</body>
</html>
