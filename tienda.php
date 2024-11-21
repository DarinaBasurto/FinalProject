<?php
session_start(); 


if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ÉCLAT</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Tienda de Arte</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Galería</a>
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
            </ul>
        </div>
    </div>
</nav>


<section class="py-5 text-center bg-light">
    <div class="container">
        <h1 class="display-4">Bienvenido a Éclat</h1>
        <p class="lead">Descubre y compra obras de arte únicas de artistas talentosos.</p>
        <a href="#" class="btn btn-primary">Explorar Galería.</a>
    </div>
</section>


<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Galería</h2>
        <div class="row">
  
            <div class="col-md-4">
                <div class="card">
                    <img src="Bawl.jpg" class="card-img-top" alt="Bawl">
                    <div class="card-body">
                        <h5 class="card-title">Bawl</h5>
                        <p class="card-text">Marcie Marsh.</p>
                        <p class="card-text"><strong>$100.00</strong></p>
                        <a href="carrito.php" class="btn btn-primary">Agregar al Carrito</a> <!-- Cambié .html a .php -->
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <img src="https://via.placeholder.com/300" class="card-img-top" alt="Arte 2">
                    <div class="card-body">
                        <h5 class="card-title">Obra de Arte 2</h5>
                        <p class="card-text">Descripción breve de la obra de arte.</p>
                        <p class="card-text"><strong>$200.00</strong></p>
                        <a href="carrito.php" class="btn btn-primary">Agregar al Carrito</a> <!-- Cambié .html a .php -->
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <img src="https://via.placeholder.com/300" class="card-img-top" alt="Arte 3">
                    <div class="card-body">
                        <h5 class="card-title">Obra de Arte 3</h5>
                        <p class="card-text">Descripción breve de la obra de arte.</p>
                        <p class="card-text"><strong>$300.00</strong></p>
                        <a href="carrito.php" class="btn btn-primary">Agregar al Carrito</a> <!-- Cambié .html a .php -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<footer class="bg-light py-4">
    <div class="container text-center">
        <p>&copy; 2024 Tienda de Arte. Todos los derechos reservados.</p>
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
