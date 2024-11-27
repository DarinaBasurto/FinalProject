<?php 
session_start();

// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TIENDA";
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consultar los primeros 3 productos
$sql = "SELECT id_producto, nombre, descripcion, fotos, precio FROM productos LIMIT 3";
$result = $conn->query($sql);
//session_start(); 


//if (!isset($_SESSION['id_usuario'])) {
//    header("Location: login.php"); 
//    exit();
//}
?> 

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ÉCLAT</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: url('FondoMain.jpg') no-repeat center center/cover;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }
        .hero-section .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .hero-section .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
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
                    <?php if (isset($_SESSION['id_usuario'])): ?>
                        <a class="nav-link" href="logout.php">Cerrar sesión</a>
                    <?php else: ?>
                        <a class="nav-link" href="login.php">Iniciar sesión</a>
                    <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="eliminarproducto.php">¿Eres admin?</a> 
                </li>
            </ul>
        </div>
    </div>
</nav>


<section class="py-5 text-center bg-light">
    <div class="container">
        <h1 class="display-4">Bienvenido a Éclat</h1>
        <p class="lead">Descubre y compra obras de arte únicas de artistas talentosos.</p>
        <a href="galeria.php" class="btn btn-primary">Explorar Galería.</a>
    </div>
</section>


<footer class="bg-light py-4">
    <div class="container text-center">
        <p>&copy; 2024 Éclat. Todos los derechos reservados.</p>
        <p>Síguenos en:
            <a href="#" class="text-primary">Facebook</a> |
            <a href="#" class="text-primary">Instagram</a> |
            <a href="#" class="text-primary">Twitter</a>
        </p>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
