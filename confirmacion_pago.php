<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
    <title>Confirmación de Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #222;
            color: white;
            font-family: 'Arial', sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        .btn-custom {
            background-color: #4CAF50;
            color: white;
            margin-top: 20px;
        }
        .btn-custom:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>¡Gracias por tu compra!</h2>
        <p>Tu pedido llegará en 3 días hábiles.</p>
        <a href="logout.php" class="btn btn-custom">Volver a la tienda</a>
    </div>
</body>
</html>
