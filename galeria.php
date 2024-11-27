<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "TIENDA"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los productos
$sql = "SELECT id_producto, nombre, descripcion, fotos, precio, stock, fabricante, origen FROM productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Productos</title>
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

    <section class="py-5">
        <div class="container">
            <h1 class="text-center mb-4">Galería de Productos</h1>
            <div class="row">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <!-- Mostrar la imagen del producto -->
                                <?php if (!empty($row['fotos'])) { ?>
                                    <img src="data:image/jpeg;base64,<?= base64_encode($row['fotos']); ?>" class="card-img-top" alt="<?= htmlspecialchars($row['nombre']); ?>">
                                <?php } else { ?>
                                    <img src="placeholder.jpg" class="card-img-top" alt="Imagen no disponible">
                                <?php } ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($row['nombre']); ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($row['descripcion']); ?></p>
                                    <p class="card-text"><strong>Precio: $<?= number_format($row['precio'], 2); ?></strong></p>
                                    <p class="card-text"><small>Fabricante: <?= htmlspecialchars($row['fabricante']); ?></small></p>
                                    <p class="card-text"><small>Origen: <?= htmlspecialchars($row['origen']); ?></small></p>
                                    <?php if ($row['stock'] > 0) { ?>
                                    <a href="carrito.php?id_producto=<?= $row['id_producto']; ?>" class="btn btn-primary">Agregar al Carrito</a>
                                    <?php } else { ?>
                                      <button class="btn btn-secondary" disabled>Agotado</button>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='text-center'>No hay productos disponibles en este momento.</p>";
                }
                ?>
            </div>
        </div>
    </section>

    <footer class="bg-light py-4">
        <div class="container text-center">
            <p>&copy; 2024 Tienda de Arte. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
