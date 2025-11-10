<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A06: Vulnerable Components (File Upload)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="../index.php">← Volver al menú</a>
        <h1 class="mt-3">A06: Vulnerable and Outdated Components</h1>
        <p class="lead">Este laboratorio demuestra cómo un componente de subida de archivos desactualizado o mal configurado puede ser vulnerable a la carga de archivos maliciosos.</p>

        <div class="card mb-4">
            <div class="card-header">Subir Archivo</div>
            <div class="card-body">
                <form action="upload.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="fileToUpload" class="form-label">Selecciona un archivo para subir:</label>
                        <input type="file" class="form-control" id="fileToUpload" name="fileToUpload" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Subir Archivo</button>
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
            <p>Este componente de subida intenta bloquear archivos peligrosos (como <code>.php</code>), pero su lógica es vulnerable.</p>
            <p>Intenta subir un archivo PHP malicioso (una "web shell") que te permita ejecutar comandos en el servidor. Puedes probar técnicas como:</p>
            <ul>
                <li>**Doble extensión:** <code>shell.php.jpg</code></li>
                <li>**MIME Type Bypass:** Manipular el tipo MIME en la petición HTTP.</li>
                <li>**Caracteres nulos:** <code>shell.php%00.jpg</code> (puede que no funcione en todas las configuraciones de PHP/Apache modernas, pero es una técnica histórica).</li>
            </ul>
            <p>Si logras subir un archivo PHP y ejecutarlo, habrás explotado la vulnerabilidad.</p>
        </div>

        <h2 class="mt-5">Archivos Subidos</h2>
        <div class="list-group">
            <?php
            $uploaded_files = glob('uploads/*');
            if (empty($uploaded_files)) {
                echo '<p>No hay archivos subidos aún.</p>';
            } else {
                foreach ($uploaded_files as $file) {
                    echo '<a href="' . htmlspecialchars($file) . '" class="list-group-item list-group-item-action">' . htmlspecialchars(basename($file)) . '</a>';
                }
            }
            ?>
        </div>

    </div>
</body>
</html>
