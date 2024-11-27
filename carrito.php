<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); 

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php"); 
    exit();
}


$id_usuario = $_SESSION['id_usuario'];

$mysqli = new mysqli("localhost", "root", "", "TIENDA"); 


if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}


$carrito_query = "SELECT id_carrito FROM CARRITO WHERE id_usuario = ?";
$carrito_stmt = $mysqli->prepare($carrito_query);
$carrito_stmt->bind_param("i", $id_usuario);
$carrito_stmt->execute();
$carrito_result = $carrito_stmt->get_result();

if ($carrito_result->num_rows > 0) {
 
    $carrito_row = $carrito_result->fetch_assoc();
    $id_carrito = $carrito_row['id_carrito'];
} else {

    $create_carrito_query = "INSERT INTO CARRITO (id_usuario) VALUES (?)";
    $create_carrito_stmt = $mysqli->prepare($create_carrito_query);
    $create_carrito_stmt->bind_param("i", $id_usuario);
    $create_carrito_stmt->execute();
    $id_carrito = $mysqli->insert_id; 
    $create_carrito_stmt->close();
}

$carrito_stmt->close();

if (isset($_POST['vaciar_carrito'])) {
    $_SESSION['carrito'] = array(); 
    echo "<script>alert('El carrito ha sido vaciado.');</script>";
}


if (isset($_GET['id_producto'])) {
    $id_producto = (int)$_GET['id_producto'];


    $stock_query = "SELECT stock FROM productos WHERE id_producto = ?";
    $stock_stmt = $mysqli->prepare($stock_query);
    $stock_stmt->bind_param("i", $id_producto);
    $stock_stmt->execute();
    $stock_result = $stock_stmt->get_result();
    
    if ($stock_result->num_rows > 0) {
        $stock_row = $stock_result->fetch_assoc();
        $stock_disponible = $stock_row['stock'];

        // Verificar si el producto ya está en el carrito
        $query = "SELECT cantidad FROM carrito_productos WHERE id_carrito = ? AND id_producto = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ii", $id_carrito, $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $cantidad_actual = $row['cantidad'];

            if ($cantidad_actual < $stock_disponible) {
                $cantidad_nueva = $cantidad_actual + 1;
                $update_query = "UPDATE carrito_productos SET cantidad = ? WHERE id_carrito = ? AND id_producto = ?";
                $update_stmt = $mysqli->prepare($update_query);
                $update_stmt->bind_param("iii", $cantidad_nueva, $id_carrito, $id_producto);
                $update_stmt->execute();
                $update_stmt->close();
            } else {
                echo "<script>alert('No hay suficiente stock para agregar más unidades.');</script>";
            }
        } else {

            if ($stock_disponible > 0) {
                $insert_query = "INSERT INTO carrito_productos (id_carrito, id_producto, cantidad) VALUES (?, ?, 1)";
                $insert_stmt = $mysqli->prepare($insert_query);
                $insert_stmt->bind_param("ii", $id_carrito, $id_producto);
                $insert_stmt->execute();
                $insert_stmt->close();
            } else {
                echo "<script>alert('El producto no está disponible en stock.');</script>";
            }
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error: el producto no existe o ha sido eliminado.');</script>";
    }


    header("Location: carrito.php");
    exit();
}


if (isset($_GET['eliminar_id_producto'])) {
    $id_producto = (int)$_GET['eliminar_id_producto'];

    $delete_query = "DELETE FROM carrito_productos WHERE id_carrito = ? AND id_producto = ?";
    $delete_stmt = $mysqli->prepare($delete_query);
    $delete_stmt->bind_param("ii", $id_carrito, $id_producto);
    $delete_stmt->execute();
    $delete_stmt->close();


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
        SELECT cp.id_producto, cp.cantidad, p.nombre, p.precio, p.stock 
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
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $cart_result->fetch_assoc()): ?>
         <tr>
            <td><?= htmlspecialchars($row['id_producto']); ?></td>
            <td><?= htmlspecialchars($row['nombre']); ?></td>
            <td>$<?= number_format($row['precio'], 2); ?></td>
            <td>
                <div class="d-flex">
                    <a href="carrito.php?accion=disminuir&id_producto=<?= $row['id_producto']; ?>" class="btn btn-warning btn-sm">-</a>
                    <input type="text" class="form-control text-center" value="<?= $row['cantidad']; ?>" readonly style="width: 50px;">
                    <a href="carrito.php?accion=incrementar&id_producto=<?= $row['id_producto']; ?>" class="btn btn-success btn-sm">+</a>
                </div>
            </td>
            <td><a href="carrito.php?eliminar_id_producto=<?= $row['id_producto']; ?>" class="btn btn-danger btn-sm">Eliminar</a></td>
         </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="galeria.php" class="btn btn-primary">Volver a Productos</a>
    <a href="pago.php" class="btn btn-primary">Proceder al pago</a>
    <?php endif; ?>
    <form method="post" action="">
        </form>
</div>
</body>
</html>
