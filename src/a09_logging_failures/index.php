<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A09: Security Logging and Monitoring Failures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="../index.php">← Volver al menú</a>
        <h1 class="mt-3">A09: Security Logging and Monitoring Failures</h1>
        <p class="lead">Este laboratorio demuestra fallos en el registro y monitoreo de seguridad.</p>

        <div class="list-group">
            <div class="list-group-item flex-column align-items-start">
                <h5 class="mb-1">1. Acción Crítica sin Registro Suficiente</h5>
                <p class="mb-1">Simula una acción importante (ej. transferencia de dinero) que debería ser registrada con detalle, pero no lo es o le faltan datos clave.</p>
                <a href="perform_action.php" class="btn btn-warning mt-2">Realizar Acción Crítica</a>
            </div>

            <div class="list-group-item flex-column align-items-start mt-4">
                <h5 class="mb-1">2. Logs Accesibles y Modificables</h5>
                <p class="mb-1">El archivo de log es directamente accesible y no está protegido, permitiendo a un atacante leerlo y modificarlo para encubrir sus huellas.</p>
                <a href="view_logs.php" class="btn btn-info mt-2">Ver Registros</a>
                <a href="log.txt" class="btn btn-secondary mt-2" target="_blank">Acceder a log.txt directamente</a>
            </div>
        </div>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-info mt-4">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <div class="alert alert-info mt-4">
            <h5 class="alert-heading">🎯 Escenario de Pentesting</h5>
            <ol>
                <li>Haz clic en "Realizar Acción Crítica" varias veces. Luego, ve a "Ver Registros" o accede a <code>log.txt</code> directamente. ¿Se registra la acción? ¿Qué información falta (usuario, IP, hora exacta)?</li>
                <li>Intenta modificar el archivo <code>log.txt</code> directamente (si tu navegador lo permite o usando herramientas de proxy/editor de texto). ¿Puedes borrar tus huellas?</li>
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
