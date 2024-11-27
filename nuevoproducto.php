<?php
session_start();
$servername = "localhost";
$username = "root"; // Cambiar según tu configuración
$password = ""; // Cambiar según tu configuración
$database = "TIENDA"; // Cambiar por el nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = (float) $_POST['precio'];
    $stock = (int) $_POST['stock'];
    $fabricante = trim($_POST['fabricante']);
    $origen = trim($_POST['origen']);

    // Manejar imagen subida
    if (isset($_FILES['fotos']) && $_FILES['fotos']['error'] == 0) {
        $fotos = file_get_contents($_FILES['fotos']['tmp_name']); // Obtener contenido binario del archivo
    } else {
        $fotos = null;
    }

    // Validar que los campos requeridos no estén vacíos
    if ($nombre && $descripcion && $precio && $stock && $fabricante && $origen) {
        $sql = "INSERT INTO productos (nombre, descripcion, fotos, precio, stock, fabricante, origen) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdiss", $nombre, $descripcion, $fotos, $precio, $stock, $fabricante, $origen);

        if ($stmt->execute()) {
            $mensaje = "Producto agregado correctamente.";
        } else {
            $error = "Error al agregar el producto: " . $conn->error;
        }
    } else {
        $error = "Por favor, completa todos los campos.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto | Éclat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
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
    <div class="container py-5">
        <h1 class="text-center mb-4">Agregar Nuevo Producto</h1>
        <?php if (isset($mensaje)) { ?>
            <div class="alert alert-success text-center"><?= $mensaje ?></div>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php } ?>
        <form action="" method="POST" enctype="multipart/form-data" class="p-4 bg-white shadow rounded">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="fotos" class="form-label">Foto del Producto</label>
                <input type="file" id="fotos" name="fotos" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio ($)</label>
                <input type="number" id="precio" name="precio" step="0.01" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" id="stock" name="stock" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fabricante" class="form-label">Fabricante</label>
                <input type="text" id="fabricante" name="fabricante" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="origen" class="form-label">Origen</label>
                <input type="text" id="origen" name="origen" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Agregar Producto</button>
        </form>
    </div>
</body>
</html>
