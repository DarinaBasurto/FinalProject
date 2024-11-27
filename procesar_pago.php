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
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$direccion = $_POST['direccion'];


$query = "SELECT cp.id_producto, cp.cantidad, p.precio 
          FROM carrito_productos cp 
          INNER JOIN productos p ON cp.id_producto = p.id_producto 
          WHERE cp.id_carrito = (SELECT id_carrito FROM CARRITO WHERE id_usuario = ?)";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $id_producto = $row['id_producto'];
    $cantidad = $row['cantidad'];
    $precio_total = $row['precio'] * $cantidad;


    $insert_historial_query = "INSERT INTO HISTORIAL (id_usuario, id_producto) VALUES (?, ?)";
    $insert_historial_stmt = $mysqli->prepare($insert_historial_query);
    $insert_historial_stmt->bind_param("ii", $id_usuario, $id_producto);
    $insert_historial_stmt->execute();
    $insert_historial_stmt->close();


    $update_stock_query = "UPDATE productos SET stock = stock - ? WHERE id_producto = ?";
    $update_stock_stmt = $mysqli->prepare($update_stock_query);
    $update_stock_stmt->bind_param("ii", $cantidad, $id_producto);
    $update_stock_stmt->execute();
    $update_stock_stmt->close();
}

$stmt->close();
$mysqli->close();


header("Location: confirmacion_pago.php");
exit();
?>
