<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
if (!isset($_SESSION['user_id'])) {
    // Si no hay sesión, redirigir o mostrar error
    header('Location: index.php');
    exit();
}

require_once '../config/db.php';

$note_id = $_GET['note_id'] ?? 0;

// --- VULNERABILIDAD IDOR ---
// El código obtiene la nota directamente sin verificar si pertenece al usuario autenticado.
// Un atacante puede cambiar el 'note_id' en la URL para ver notas de otros usuarios.
$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ?");
$stmt->execute([$note_id]);
$note = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Nota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="index.php">← Volver a mis notas</a>
        
        <?php if ($note): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h3><?php echo htmlspecialchars($note['title']); ?></h3>
                </div>
                <div class="card-body">
                    <p><?php echo nl2br(htmlspecialchars($note['content'])); ?></p>
                </div>
                <div class="card-footer text-muted">
                    Nota creada el: <?php echo $note['created_at']; ?>
                </div>
            </div>

            <div class="alert alert-warning mt-4">
                <h5 class="alert-heading">🚨 ¡Vulnerabilidad Expuesta! 🚨</h5>
                <p>Acabas de acceder a la nota con ID <strong><?php echo $note['id']; ?></strong>. Si esta nota no te pertenece, has explotado una vulnerabilidad de tipo IDOR (Insecure Direct Object Reference).</p>
                <hr>
                <p class="mb-0"><strong>Causa:</strong> La aplicación no verifica que el usuario autenticado (<code>user_id: <?php echo $_SESSION['user_id']; ?></code>) tenga permiso para ver esta nota (<code>note_id: <?php echo $note['id']; ?></code>, perteneciente a <code>user_id: <?php echo $note['user_id']; ?></code>).</p>
            </div>

        <?php else: ?>
            <div class="alert alert-danger mt-3">
                Nota no encontrada o no tienes permiso para verla.
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
