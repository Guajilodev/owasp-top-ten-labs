<?php
require_once '../config/db.php';

$search = $_GET['q'] ?? '';
$results = [];

if ($search !== '') {
    // VULNERABILIDAD: El valor del usuario se concatena directamente en la query SQL.
    // No se usan prepared statements ni se sanitiza la entrada.
    // Un atacante puede inyectar SQL arbitrario, por ejemplo: ' OR 1=1 --
    // O extraer datos de otras tablas con UNION SELECT.
    $query = "SELECT id, name, description, price FROM products WHERE name = '" . $search . "'";

    try {
        $stmt = $pdo->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A03: Injection (SQL Injection)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="../index.php">&larr; Volver al men&uacute;</a>
        <h1 class="mt-3">A03: Injection (SQL Injection)</h1>
        <p class="lead">Buscador de productos vulnerable a inyecci&oacute;n SQL por concatenaci&oacute;n directa del input del usuario.</p>

        <div class="card mb-4">
            <div class="card-header">Buscar Productos</div>
            <div class="card-body">
                <form method="GET" action="index.php">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" placeholder="Escribe el nombre del producto..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <strong>Error SQL:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($search !== '' && !isset($error)): ?>
            <h2 class="mb-3">Resultados para "<?php echo htmlspecialchars($search); ?>"</h2>
            <?php if (empty($results)): ?>
                <div class="alert alert-warning">No se encontraron productos.</div>
            <?php else: ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <?php foreach (array_keys($results[0]) as $col): ?>
                                <th><?php echo htmlspecialchars($col); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <?php foreach ($row as $value): ?>
                                    <td><?php echo htmlspecialchars($value ?? ''); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>

        <div class="alert alert-info mt-4">
            <h5 class="alert-heading">&#127919; Escenario de Pentesting</h5>
            <p>El buscador construye la consulta SQL concatenando directamente tu input. Intenta:</p>
            <ul>
                <li><code>' OR 1=1 -- </code> &mdash; Ver todos los productos</li>
                <li><code>' UNION SELECT 1,username,md5_password,3 FROM users_crypto -- </code> &mdash; Extraer datos de otra tabla</li>
                <li><code>' UNION SELECT 1,table_name,column_name,4 FROM information_schema.columns WHERE table_schema='owasp_labs' -- </code> &mdash; Enumerar la base de datos</li>
            </ul>
            <p class="mt-2">Si logr&aacute;s ver datos que no son productos, confirmaste la vulnerabilidad de SQL Injection.</p>
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
