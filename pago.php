<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "TIENDA");

if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

$id_usuario = $_SESSION['id_usuario'];

$query = "SELECT cp.id_producto, cp.cantidad, p.nombre, p.precio 
          FROM carrito_productos cp 
          INNER JOIN productos p ON cp.id_producto = p.id_producto 
          WHERE cp.id_carrito = (SELECT id_carrito FROM CARRITO WHERE id_usuario = ?)";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
    $total += $row['precio'] * $row['cantidad'];
}
$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceso de Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #222;
            color: white;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .btn-custom {
            background-color: #4CAF50;
            color: white;
        }
        .btn-custom:hover {
            background-color: #45a049;
        }
        .total {
            font-size: 1.5em;
            font-weight: bold;
        }
        .summary {
            background-color: #333;
            padding: 15px;
            margin-bottom: 30px;
        }
        .summary ul {
            list-style-type: none;
            padding: 0;
        }
        .summary li {
            margin-bottom: 10px;
        }
        .summary li span {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Detalles del Pago</h2>
            <p>Por favor, completa los siguientes datos para proceder con el pago.</p>
        </div>

        <div class="summary">
            <h4>Resumen de tu Carrito</h4>
            <ul>
                <?php foreach ($productos as $producto): ?>
                    <li><span><?= htmlspecialchars($producto['nombre']); ?>:</span> $<?= number_format($producto['precio'], 2); ?> x <?= $producto['cantidad']; ?></li>
                <?php endforeach; ?>
                <li class="total"><span>Total:</span> $<?= number_format($total, 2); ?></li>
            </ul>
        </div>


        <form action="procesar_pago.php" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre Completo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección de Envío</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
            <div class="mb-3">
                <label for="num_tarjeta" class="form-label">Número de Tarjeta</label>
                <input type="text" class="form-control" id="num_tarjeta" name="num_tarjeta" required>
            </div>
            <div class="mb-3">
                <label for="fecha_expiracion" class="form-label">Fecha de Expiración</label>
                <input type="month" class="form-control" id="fecha_expiracion" name="fecha_expiracion" required>
            </div>
            <div class="mb-3">
                <label for="codigo_seguridad" class="form-label">Código de Seguridad (CVV)</label>
                <input type="text" class="form-control" id="codigo_seguridad" name="codigo_seguridad" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-custom">Proceder al Pago</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
