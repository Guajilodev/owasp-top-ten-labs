<?php
// Simulación de un usuario autenticado
// En un sistema real, esto vendría de una sesión segura.
session_start();

// Por defecto, simulamos que el usuario 'alice' (ID 1) ha iniciado sesión.
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'alice';
}

require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Obtener las notas del usuario actual
$stmt = $pdo->prepare("SELECT id, title FROM notes WHERE user_id = ?");
$stmt->execute([$user_id]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A01: Broken Access Control</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="../index.php">← Volver al menú</a>
        <h1 class="mt-3">A01: Broken Access Control (IDOR)</h1>
        <p class="lead">Estás viendo las notas como el usuario: <strong><?php echo htmlspecialchars($username); ?></strong></p>

        <div class="card">
            <div class="card-header">
                Mis Notas
            </div>
            <ul class="list-group list-group-flush">
                <?php if (empty($notes)): ?>
                    <li class="list-group-item">No tienes notas.</li>
                <?php else: ?>
                    <?php foreach ($notes as $note): ?>
                        <li class="list-group-item">
                            <a href="view_note.php?note_id=<?php echo $note['id']; ?>">
                                <?php echo htmlspecialchars($note['title']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

        <div class="alert alert-info mt-4">
            <h5 class="alert-heading">🎯 Escenario de Pentesting</h5>
            <p>Como usuario 'alice', solo deberías poder ver tus propias notas. Intenta acceder a las notas de otros usuarios (por ejemplo, las notas con ID 3, 4 o 5) cambiando el parámetro <code>note_id</code> en la URL de la página de visualización.</p>
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
