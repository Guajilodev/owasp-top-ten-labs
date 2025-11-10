<?php
require_once '../config/db.php';

// Obtener todos los comentarios de la base de datos
$stmt = $pdo->query("SELECT author, content, created_at FROM comments ORDER BY created_at DESC");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A03: Injection (XSS)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="../index.php">← Volver al menú</a>
        <h1 class="mt-3">A03: Injection (Stored XSS)</h1>
        <p class="lead">Libro de visitas vulnerable a Cross-Site Scripting Almacenado.</p>

        <!-- Formulario para añadir comentarios -->
        <div class="card mb-4">
            <div class="card-header">Deja tu comentario</div>
            <div class="card-body">
                <form action="add_comment.php" method="POST">
                    <div class="mb-3">
                        <label for="author" class="form-label">Tu Nombre</label>
                        <input type="text" class="form-control" id="author" name="author" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Comentario</label>
                        <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Publicar</button>
                </form>
            </div>
        </div>

        <!-- Sección de comentarios -->
        <h2 class="mb-3">Comentarios</h2>
        <?php foreach ($comments as $comment): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $comment['author']; // Sin sanitizar, pero menos probable que sea un vector aquí ?></h5>
                    <p class="card-text">
                        <?php
                        // VULNERABILIDAD: El contenido se imprime directamente en el HTML.
                        // Un atacante puede insertar scripts (ej. <script>alert('XSS')</script>)
                        // que se ejecutarán en el navegador de cualquier usuario que vea la página.
                        echo $comment['content'];
                        ?>
                    </p>
                    <p class="card-text"><small class="text-muted">Publicado el: <?php echo $comment['created_at']; ?></small></p>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="alert alert-info mt-4">
            <h5 class="alert-heading">🎯 Escenario de Pentesting</h5>
            <p>Intenta publicar un comentario que contenga un script de JavaScript. Por ejemplo:</p>
            <code>&lt;script&gt;alert('XSS_DEMO');&lt;/script&gt;</code>
            <p class="mt-2">Si el script se ejecuta cuando la página se recarga, has confirmado la vulnerabilidad de Stored XSS. Cualquier usuario que visite esta página ejecutará tu script.</p>
        </div>

    </div>
</body>
</html>
