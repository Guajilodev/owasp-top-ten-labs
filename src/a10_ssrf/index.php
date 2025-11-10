<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A10: Server-Side Request Forgery (SSRF)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="../index.php">← Volver al menú</a>
        <h1 class="mt-3">A10: Server-Side Request Forgery (SSRF)</h1>
        <p class="lead">Este laboratorio demuestra cómo un atacante puede forzar al servidor a realizar peticiones a recursos internos o externos no deseados.</p>

        <div class="card mb-4">
            <div class="card-header"><h4>Obtener Contenido de URL</h4></div>
            <div class="card-body">
                <form action="fetch.php" method="POST">
                    <div class="mb-3">
                        <label for="url" class="form-label">URL a obtener:</label>
                        <input type="text" class="form-control" id="url" name="url" value="http://example.com" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Obtener Contenido</button>
                </form>
            </div>
        </div>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-info mt-4">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <div class="alert alert-info mt-4">
            <h5 class="alert-heading">🎯 Escenario de Pentesting</h5>
            <p>La aplicación permite al usuario especificar una URL para que el servidor obtenga su contenido. No hay validación de la URL, lo que permite al atacante forzar al servidor a realizar peticiones a recursos internos.</p>
            <p>Intenta obtener contenido de:</p>
            <ul>
                <li>**Recursos internos:** <code>http://localhost/</code> (verás el menú principal de la aplicación), <code>http://db/</code> (intentará conectar al servicio de base de datos).</li>
                <li>**Servicios de metadatos en la nube (si aplicable):** <code>http://169.254.169.254/latest/meta-data/</code> (en entornos AWS).</li>
                <li>**Escaneo de puertos internos:** Prueba diferentes puertos en <code>localhost</code> (ej. <code>http://localhost:22</code>, <code>http://localhost:8081</code>).</li>
            </ul>
            <p>Si el servidor devuelve el contenido de estos recursos internos, has explotado la vulnerabilidad SSRF.</p>
        </div>

    </div>
    
    <footer class="text-center mt-5">
        <p class="mt-3">
            <small>
                Created by <a href="https://x.com/guajilodev" target="_blank">@guajilodev</a> | 
                <a href="https://github.com/Guajilodev/owasp-top-ten-labs" target="_blank">GitHub Repository</a>
            </small>
        </p>
    </footer>
</body>
</html>
