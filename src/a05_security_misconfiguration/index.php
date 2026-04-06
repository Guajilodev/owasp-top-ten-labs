<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A05: Security Misconfiguration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="../index.php">← Volver al menú</a>
        <h1 class="mt-3">A05: Security Misconfiguration</h1>
        <p class="lead">Este laboratorio demuestra varios fallos de configuración de seguridad comunes.</p>

        <div class="list-group">
            <div class="list-group-item flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">1. Mensajes de Error Detallados</h5>
                </div>
                <p class="mb-1">Las aplicaciones no deben filtrar información detallada de errores en producción, ya que puede revelar detalles de implementación, versiones de software o consultas de base de datos.</p>
                <a href="db_error.php" class="btn btn-warning mt-2">Provocar Error de BD</a>
            </div>

            <div class="list-group-item flex-column align-items-start mt-4">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">2. Listado de Directorios y Archivos Sensibles Expuestos</h5>
                </div>
                <p class="mb-1">El servidor web no debería estar configurado para mostrar el contenido de los directorios si no hay una página de índice. Esto puede exponer archivos que no estaban destinados a ser públicos.</p>
                <p class="mb-1">El siguiente enlace lleva a un directorio <code>/uploads</code> que no tiene un archivo de índice, exponiendo su contenido.</p>
                <a href="uploads/" class="btn btn-warning mt-2">Ver Directorio sin Índice</a>
            </div>

            <div class="list-group-item flex-column align-items-start mt-4">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">3. Subida de Archivos con Blacklist Mal Configurada</h5>
                </div>
                <p class="mb-1">El componente de subida de archivos usa una blacklist de extensiones para bloquear archivos peligrosos. Una configuración correcta usaría una whitelist de extensiones permitidas.</p>
                <a href="upload_form.php" class="btn btn-warning mt-2">Ir al Formulario de Subida</a>
            </div>
        </div>

        <div class="alert alert-info mt-4">
            <h5 class="alert-heading">🎯 Escenario de Pentesting</h5>
            <ol>
                <li>Haz clic en "Provocar Error de BD" y analiza la información que se filtra. ¿Qué puedes averiguar sobre la base de datos y el servidor?</li>
                <li>Haz clic en "Ver Directorio sin Índice". ¿Qué archivos encuentras? ¿Contienen información sensible? En un pentest real, los atacantes usan escáneres automáticos para encontrar estos directorios expuestos.</li>
                <li>Haz clic en "Ir al Formulario de Subida" e intenta subir un archivo PHP usando doble extensión (<code>shell.php.jpg</code>) o manipulando el MIME type.</li>
            </ol>
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
