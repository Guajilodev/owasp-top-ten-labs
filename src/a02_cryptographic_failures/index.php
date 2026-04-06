<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A02: Cryptographic Failures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="../index.php">← Volver al menú</a>
        <h1 class="mt-3">A02: Cryptographic Failures</h1>
        <p class="lead">Uso de funciones de hash débiles (MD5) para almacenar contraseñas.</p>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h4>Iniciar Sesión</h4></div>
                    <div class="card-body">
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <label for="login-username" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="login-username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="login-password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="login-password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['login_message'])): ?>
            <div class="alert alert-info mt-4">
                <?php echo $_SESSION['login_message']; unset($_SESSION['login_message']); ?>
            </div>
        <?php endif; ?>

        <div class="alert alert-info mt-4">
            <h5 class="alert-heading">🎯 Escenario de Pentesting</h5>
            <p>La base de datos almacena las contraseñas usando MD5, un algoritmo de hash obsoleto y rápido de computar. Intenta crackear los hashes de los usuarios de ejemplo:</p>
            <ul>
                <li><b>admin:</b> <code>21232f297a57a5a743894a0e4a801fc3</code></li>
                <li><b>testuser:</b> <code>5f4dcc3b5aa765d61d8327deb882cf99</code></li>
                <li><b>guest:</b> <code>e10adc3949ba59abbe56e057f20f883e</code></li>
            </ul>
            <p>Puedes usar herramientas online como CrackStation para romper estos hashes y luego iniciar sesión.</p>
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
