<?php
$message = $_GET['message'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A05: File Upload Misconfiguration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="index.php">&larr; Volver a A05</a>
        <h1 class="mt-3">Subida de Archivos Mal Configurada</h1>
        <p class="lead">Componente de subida que usa una blacklist de extensiones f&aacute;cilmente evitable.</p>

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

        <?php if ($message !== ''): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <h2 class="mt-4">Archivos Subidos</h2>
        <div class="list-group">
            <?php
            $uploaded_files = glob('file_uploads/*');
            if (empty($uploaded_files)) {
                echo '<p>No hay archivos subidos a&uacute;n.</p>';
            } else {
                foreach ($uploaded_files as $file) {
                    echo '<a href="' . htmlspecialchars($file) . '" class="list-group-item list-group-item-action">' . htmlspecialchars(basename($file)) . '</a>';
                }
            }
            ?>
        </div>

        <div class="alert alert-info mt-4">
            <h5 class="alert-heading">&#127919; Escenario de Pentesting</h5>
            <p>La validaci&oacute;n usa una <strong>blacklist</strong> de extensiones, que es una misconfiguraci&oacute;n cl&aacute;sica. Intenta:</p>
            <ul>
                <li><strong>Doble extensi&oacute;n:</strong> <code>shell.php.jpg</code></li>
                <li><strong>MIME Type Bypass:</strong> Manipular el Content-Type en la petici&oacute;n HTTP</li>
            </ul>
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
