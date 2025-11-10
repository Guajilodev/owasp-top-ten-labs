<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?message=Acceso denegado. Por favor, inicia sesión.');
    exit();
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="index.php">← Volver al Login</a>
        <h1 class="mt-3">Bienvenido, <?php echo htmlspecialchars($username); ?>!</h1>
        <p class="lead">Has iniciado sesión con éxito. Esta es tu página de dashboard.</p>

        <div class="alert alert-success">
            <p>¡Felicidades! Has logrado iniciar sesión.</p>
            <p>En un escenario real, esta página contendría información o funcionalidades sensibles.</p>
        </div>

        <form action="logout.php" method="POST">
            <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
        </form>

    </div>
</body>
</html>
