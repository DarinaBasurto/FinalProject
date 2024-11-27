<?php

session_start();


$servername = "localhost";
$username = "root"; 
$password = ""; 
$database = "TIENDA"; 

$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}


$sql = "SELECT id_compra, id_usuario, id_producto FROM HISTORIAL";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4">Historial de Compras</h1>

    <?php if ($result->num_rows > 0) { ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID de Compra</th>
                    <th>ID de Usuario</th>
                    <th>ID de Producto</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_compra']); ?></td>
                        <td><?php echo htmlspecialchars($row['id_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($row['id_producto']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No hay compras registradas en el historial.</p>
    <?php } ?>

</div>
<a href="eliminarproducto.php" class="btn btn-custom">Regresar</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
