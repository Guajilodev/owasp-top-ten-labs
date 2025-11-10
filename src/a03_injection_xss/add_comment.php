<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author = $_POST['author'] ?? 'Anónimo';
    $content = $_POST['content'] ?? '';

    if (!empty($content)) {
        // VULNERABILIDAD: No se sanitiza la entrada del usuario antes de guardarla en la BD.
        // Esto permite que código malicioso (HTML/JS) se almacene y luego se ejecute en la página principal.
        $stmt = $pdo->prepare("INSERT INTO comments (author, content) VALUES (?, ?)");
        $stmt->execute([$author, $content]);
    }

    // Redirigir de vuelta al libro de visitas
    header('Location: index.php');
    exit();
}
