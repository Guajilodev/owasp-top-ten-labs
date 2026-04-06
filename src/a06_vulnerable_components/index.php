<?php
require_once 'LegacyHtmlSanitizer.php';

$input = '';
$output = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['content'] ?? '';

    // VULNERABILIDAD: Se usa una librería desactualizada (v1.0.0) que solo filtra <script>.
    // Vectores como <img onerror=...>, <svg onload=...>, etc. pasan sin problema.
    $output = LegacyHtmlSanitizer::clean($input);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A06: Vulnerable and Outdated Components</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="../index.php">&larr; Volver al men&uacute;</a>
        <h1 class="mt-3">A06: Vulnerable and Outdated Components</h1>
        <p class="lead">Esta p&aacute;gina usa <code>LegacyHtmlSanitizer v<?php echo LegacyHtmlSanitizer::VERSION; ?></code>, una librer&iacute;a de sanitizaci&oacute;n desactualizada con bypasses conocidos.</p>

        <div class="card mb-4">
            <div class="card-header">Previsualizador de HTML "seguro"</div>
            <div class="card-body">
                <form method="POST" action="index.php">
                    <div class="mb-3">
                        <label for="content" class="form-label">Ingres&aacute; contenido HTML:</label>
                        <textarea class="form-control" id="content" name="content" rows="4" placeholder="<b>Texto en negrita</b>"><?php echo htmlspecialchars($input); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Previsualizar</button>
                </form>
            </div>
        </div>

        <?php if ($output !== ''): ?>
            <div class="card mb-4">
                <div class="card-header">Vista previa (sanitizada con LegacyHtmlSanitizer v<?php echo LegacyHtmlSanitizer::VERSION; ?>)</div>
                <div class="card-body">
                    <!-- VULNERABILIDAD: El output se renderiza como HTML después de pasar
                         por el sanitizer incompleto. Vectores que no sean <script> se ejecutan. -->
                    <?php echo $output; ?>
                </div>
            </div>

            <div class="card mb-4 border-secondary">
                <div class="card-header bg-secondary text-white">C&oacute;digo fuente del sanitizer (v<?php echo LegacyHtmlSanitizer::VERSION; ?>)</div>
                <div class="card-body">
<pre><code>class LegacyHtmlSanitizer {
    public static function clean($input) {
        // Solo elimina &lt;script&gt;...&lt;/script&gt;
        $cleaned = preg_replace('/&lt;script\b[^&gt;]*&gt;(.*?)&lt;\/script&gt;/is', '', $input);
        $cleaned = preg_replace('/&lt;script\b[^&gt;]*\/?&gt;/i', '', $cleaned);
        return $cleaned;
    }
}</code></pre>
                    <p class="text-muted mb-0"><small>La v2.0.0 usa una whitelist de etiquetas y atributos permitidos. Este proyecto nunca actualiz&oacute;.</small></p>
                </div>
            </div>
        <?php endif; ?>

        <div class="alert alert-info mt-4">
            <h5 class="alert-heading">&#127919; Escenario de Pentesting</h5>
            <p>La aplicaci&oacute;n usa <code>LegacyHtmlSanitizer v1.0.0</code> que solo filtra etiquetas <code>&lt;script&gt;</code>. Exist&iacute;an bypasses conocidos que se corrigieron en v2.0.0, pero este proyecto nunca actualiz&oacute; la dependencia.</p>
            <p>Intenta estos vectores que evitan el filtro:</p>
            <ul>
                <li><code>&lt;img src=x onerror=alert('XSS')&gt;</code></li>
                <li><code>&lt;svg onload=alert('XSS')&gt;</code></li>
                <li><code>&lt;body onload=alert('XSS')&gt;</code></li>
                <li><code>&lt;input onfocus=alert('XSS') autofocus&gt;</code></li>
            </ul>
            <p>Si ves un alert de JavaScript, confirmaste que el componente desactualizado tiene una vulnerabilidad conocida. En un proyecto real, herramientas como <code>composer audit</code>, <code>npm audit</code> o <strong>Snyk</strong> detectan estas dependencias vulnerables.</p>
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
