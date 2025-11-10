<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A07: Identification and Authentication Failures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="../index.php">← Volver al menú</a>
        <h1 class="mt-3">A07: Identification and Authentication Failures</h1>
        <p class="lead">Este laboratorio demuestra la falta de protección contra ataques de fuerza bruta y el uso de contraseñas débiles.</p>

        <div class="card">
            <div class="card-header"><h4>Iniciar Sesión</h4></div>
            <div class="card-body">
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
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
            <p>Este formulario de login no tiene ninguna protección contra ataques de fuerza bruta (rate limiting, bloqueo de cuentas). Además, las contraseñas de los usuarios son muy débiles.</p>
            <p>Intenta adivinar las credenciales de los usuarios de prueba:</p>
            <ul>
                <li><code>user1</code> / <code>password</code></li>
                <li><code>admin</code> / <code>123456</code></li>
                <li><code>test</code> / <code>test</code></li>
            </ul>
            <p>Puedes usar herramientas como Burp Suite Intruder o Hydra para automatizar el ataque de fuerza bruta.</p>
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
