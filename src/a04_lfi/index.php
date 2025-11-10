<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A04: Insecure Design (LFI)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="../index.php">← Volver al menú</a>
        <h1 class="mt-3">A04: Insecure Design (LFI)</h1>
        <p class="lead">Este visor de páginas es un ejemplo de un diseño inseguro que lleva a una vulnerabilidad de Inclusión Local de Archivos (LFI).</p>

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=home.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=about.php">Acerca de</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <?php
                $page = $_GET['page'] ?? 'home.php';

                // VULNERABILIDAD: Se incluye el valor del parámetro 'page' directamente.
                // No hay validación, sanitización ni control sobre el path.
                // Esto permite a un atacante "subir" en el árbol de directorios e incluir archivos sensibles.
                include('pages/' . $page);
                ?>
            </div>
        </div>

        <div class="alert alert-info mt-4">
            <h5 class="alert-heading">🎯 Escenario de Pentesting</h5>
            <p>La aplicación está diseñada para incluir solo archivos de la carpeta <code>pages/</code>. Sin embargo, debido a un diseño inseguro, no valida la entrada del usuario.</p>
            <p>Intenta leer archivos del sistema operativo usando Path Traversal. Por ejemplo:</p>
            <code>index.php?page=../../../../../../etc/passwd</code>
            <p class="mt-2">Si ves el contenido del archivo <code>/etc/passwd</code>, has explotado la vulnerabilidad LFI.</p>
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
