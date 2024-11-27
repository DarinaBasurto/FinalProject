<?php
session_start();

// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Cambiar según tu configuración
$password = ""; // Cambiar según tu configuración
$database = "TIENDA"; // Cambiar por el nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    // Si no hay sesión iniciada, redirigir a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['id_usuario'];

// Consultar el valor del atributo admin del usuario
$sql = "SELECT admin FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->bind_result($admin);
    $stmt->fetch();
    $stmt->close();

    // Verificar si el usuario es administrador
    if ($is_admin != 0) {
        // Si no es administrador, bloquear el acceso
        echo "<h1>Acceso denegado</h1>";
        echo "<p>No tienes los permisos necesarios para acceder a esta página.</p>";
        exit(); // Finaliza la ejecución del script
    }
} else {
    echo "Error al preparar la consulta: " . $conn->error;
    exit();
}

// Si es administrador, el resto del código de la página se ejecutará aquí
echo "<h1>Bienvenido a la página de administración</h1>";

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Eliminar producto si se recibe el ID
if (isset($_GET['eliminar_id'])) {
    $id_producto = (int)$_GET['eliminar_id'];
    $sql = "DELETE FROM productos WHERE id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_producto);

    if ($stmt->execute()) {
        echo "<script>alert('Producto eliminado correctamente.');</script>";
    } else {
        echo "<script>alert('Error al eliminar el producto.');</script>";
    }
    $stmt->close();
}

// Consulta para obtener los productos
$sql = "SELECT id_producto, nombre, descripcion, precio, stock, fabricante, origen FROM productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="main.html">ÉCLAT</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link"  href="tienda.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="galeria.php">Galería</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contacto.html">Contacto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="carrito.php">Carrito</a> 
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Cerrar sesión</a> 
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="eliminarproducto.php">¿Eres admin?</a> 
                </li>
            </ul>
        </div>
    </div>
</nav>
    <div class="container my-5">
        <h1 class="mb-4">Lista de Productos</h1>

        <?php if ($result->num_rows > 0) { ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Fabricante</th>
                        <th>Origen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id_producto']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                            <td><?php echo "$" . number_format($row['precio'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['stock']); ?></td>
                            <td><?php echo htmlspecialchars($row['fabricante']); ?></td>
                            <td><?php echo htmlspecialchars($row['origen']); ?></td>
                            <td>
                                <a href="?eliminar_id=<?php echo $row['id_producto']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="text-center">No hay productos disponibles.</p>
        <?php } ?>

        <a href="nuevoproducto.php" class="btn btn-primary">Agregar Nuevo Producto</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
