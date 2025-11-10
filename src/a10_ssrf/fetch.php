<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'] ?? '';

    if (empty($url)) {
        header('Location: index.php?message=Error: La URL no puede estar vacía.');
        exit();
    }

    echo "<!DOCTYPE html>
<html lang=\"es\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Contenido Obtenido</title>
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css\" rel=\"stylesheet\">
</head>
<body>
    <div class=\"container mt-5\">
        <a href=\"index.php\">← Volver al Laboratorio A10</a>
        <h1 class=\"mt-3\">Contenido de: " . htmlspecialchars($url) . "</h1>
        <div class=\"card\">
            <div class=\"card-body\">
                <pre><code>";

    // VULNERABILIDAD: Se usa file_get_contents() directamente con la URL proporcionada por el usuario.
    // Esto permite al atacante forzar al servidor a realizar peticiones a cualquier URL.
    try {
        $content = file_get_contents($url);
        if ($content === false) {
            echo "Error: No se pudo obtener el contenido de la URL.\n";
        } else {
            echo htmlspecialchars($content); // Escapar el contenido para evitar XSS en la visualización
        }
    } catch (Exception $e) {
        echo "Error al obtener la URL: " . htmlspecialchars($e->getMessage());
    }

    echo "</code></pre>
            </div>
        </div>
    </div>
</body>
</html>";

    exit();
}

