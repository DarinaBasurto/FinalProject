<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php"); // Redirigir al inicio de sesión
    exit();
}

// Obtener el ID del usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];

$mysqli = new mysqli("localhost", "root", "", "TIENDA"); // Ajusta el nombre de la base de datos

// Verificar la conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Verificar si el usuario ya tiene un carrito activo
$carrito_query = "SELECT id_carrito FROM CARRITO WHERE id_usuario = ?";
$carrito_stmt = $mysqli->prepare($carrito_query);
$carrito_stmt->bind_param("i", $id_usuario);
$carrito_stmt->execute();
$carrito_result = $carrito_stmt->get_result();

if ($carrito_result->num_rows > 0) {
    // Si el carrito ya existe, obtener el ID del carrito
    $carrito_row = $carrito_result->fetch_assoc();
    $id_carrito = $carrito_row['id_carrito'];
} else {
    // Si el carrito no existe, crear uno nuevo
    $create_carrito_query = "INSERT INTO CARRITO (id_usuario) VALUES (?)";
    $create_carrito_stmt = $mysqli->prepare($create_carrito_query);
    $create_carrito_stmt->bind_param("i", $id_usuario);
    $create_carrito_stmt->execute();
    $id_carrito = $mysqli->insert_id; // Obtener el ID del nuevo carrito
    $create_carrito_stmt->close();
}

$carrito_stmt->close();

// Agregar un producto al carrito
if (isset($_GET['id_producto'])) {
    $id_producto = (int)$_GET['id_producto'];

    // Verificar si el producto ya está en el carrito del usuario
    $query = "SELECT cantidad FROM carrito_productos WHERE id_carrito = ? AND id_producto = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $id_carrito, $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si el producto ya existe en el carrito, incrementar la cantidad
        $row = $result->fetch_assoc();
        $cantidad = $row['cantidad'] + 1;

        $update_query = "UPDATE carrito_productos SET cantidad = ? WHERE id_carrito = ? AND id_producto = ?";
        $update_stmt = $mysqli->prepare($update_query);
        $update_stmt->bind_param("iii", $cantidad, $id_carrito, $id_producto);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // Si no existe, insertar el producto en el carrito
        $insert_query = "INSERT INTO carrito_productos (id_carrito, id_producto, cantidad) VALUES (?, ?, 1)";
        $insert_stmt = $mysqli->prepare($insert_query);
        $insert_stmt->bind_param("ii", $id_carrito, $id_producto);
        $insert_stmt->execute();
        $insert_stmt->close();
    }

    $stmt->close();

    // Redirigir para evitar reenvíos del formulario
    header("Location: carrito.php");
    exit();
}

// Eliminar un producto del carrito
if (isset($_GET['eliminar_id'])) {
    $id_producto = (int)$_GET['eliminar_id'];

    $delete_query = "DELETE FROM carrito_productos WHERE id_carrito = ? AND id_producto = ?";
    $delete_stmt = $mysqli->prepare($delete_query);
    $delete_stmt->bind_param("ii", $id_carrito, $id_producto);
    $delete_stmt->execute();
    $delete_stmt->close();

    // Redirigir para evitar reenvíos del formulario
    header("Location: carrito.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Tu Carrito</h1>

    <?php
    $cart_query = "
        SELECT cp.id_producto, cp.cantidad, p.nombre 
        FROM carrito_productos cp 
        INNER JOIN productos p ON cp.id_producto = p.id_producto 
        WHERE cp.id_carrito = ?";
    $cart_stmt = $mysqli->prepare($cart_query);
    $cart_stmt->bind_param("i", $id_carrito);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();

    if ($cart_result->num_rows == 0): ?>
        <p>Tu carrito está vacío.</p>
        <a href="galeria.php" class="btn btn-primary">Volver a Productos</a>
    <?php else: ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Producto</th>
                <th>Nombre del Producto</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $cart_result->fetch_assoc()): ?>
         <tr>
            <td><?= htmlspecialchars($row['id_producto']); ?></td>
            <td><?= htmlspecialchars($row['nombre']); ?></td>
            <td><?= htmlspecialchars($row['cantidad']); ?></td>
            <td>
                <a href="carrito.php?eliminar_id=<?= $row['id_producto']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
            </td>
         </tr>
    <?php endwhile; ?>
</tbody>
</table>
        <a href="galeria.php" class="btn btn-primary">Seguir Comprando</a>
        <div class="mt-3">
            <a href="pago.php" class="btn btn-success">Proceder al pago</a>
        </div>
    <?php endif;

    $cart_stmt->close();
    $mysqli->close();
    ?>
</div>
</body>
</html>
