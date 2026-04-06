<?php
require_once '../config/db.php';

// Obtener productos de la base de datos
$stmt = $pdo->query("SELECT id, name, description, price, stock FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Procesar "compra" si se envió el formulario
$purchase = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? '';
    $product_name = $_POST['product_name'] ?? '';

    // VULNERABILIDAD: El precio se toma del formulario (lado del cliente) en vez de consultarlo
    // en la base de datos. El diseño confía en datos que el usuario puede manipular.
    // Un diseño seguro SIEMPRE obtendría el precio del servidor, nunca del cliente.
    $price = $_POST['price'] ?? 0;
    $quantity = (int)($_POST['quantity'] ?? 1);

    $total = $price * $quantity;

    $purchase = [
        'product' => $product_name,
        'price' => $price,
        'quantity' => $quantity,
        'total' => $total
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A04: Insecure Design</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="../index.php">&larr; Volver al men&uacute;</a>
        <h1 class="mt-3">A04: Insecure Design</h1>
        <p class="lead">Tienda online con un fallo de dise&ntilde;o: el precio del producto se env&iacute;a desde el cliente en vez de validarse en el servidor.</p>

        <?php if ($purchase): ?>
            <div class="alert alert-success">
                <h5>&#9989; Compra procesada</h5>
                <p><strong>Producto:</strong> <?php echo htmlspecialchars($purchase['product']); ?></p>
                <p><strong>Precio unitario:</strong> $<?php echo htmlspecialchars($purchase['price']); ?></p>
                <p><strong>Cantidad:</strong> <?php echo $purchase['quantity']; ?></p>
                <p><strong>Total cobrado:</strong> $<?php echo htmlspecialchars($purchase['total']); ?></p>
            </div>
        <?php endif; ?>

        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="card-text"><strong>$<?php echo number_format($product['price'], 2); ?></strong></p>
                            <p class="card-text"><small class="text-muted">Stock: <?php echo $product['stock']; ?> unidades</small></p>
                            <form method="POST" action="index.php">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                                <!-- VULNERABILIDAD: El precio viaja como campo oculto del formulario.
                                     El usuario puede modificarlo con DevTools antes de enviar. -->
                                <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Cantidad</span>
                                    <input type="number" class="form-control" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>">
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Comprar</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="alert alert-info mt-4">
            <h5 class="alert-heading">&#127919; Escenario de Pentesting</h5>
            <p>Esta tienda tiene un fallo de <strong>dise&ntilde;o</strong>: el precio se env&iacute;a como campo oculto del formulario HTML en vez de obtenerse de la base de datos al procesar la compra.</p>
            <p>Intenta lo siguiente:</p>
            <ol>
                <li>Abr&iacute; las DevTools del navegador (F12)</li>
                <li>Inspeccion&aacute; el formulario de cualquier producto</li>
                <li>Busc&aacute; el <code>input type="hidden" name="price"</code></li>
                <li>Cambi&aacute; el valor a <code>0.01</code></li>
                <li>Hac&eacute; clic en "Comprar"</li>
            </ol>
            <p>Si la compra se procesa con el precio que vos pusiste, confirmaste el fallo de dise&ntilde;o. Un sistema bien dise&ntilde;ado NUNCA conf&iacute;a en datos del cliente para l&oacute;gica de negocio cr&iacute;tica.</p>
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
