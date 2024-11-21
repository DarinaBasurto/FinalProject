<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);


$host = "localhost";
$user = "root";
$password = "";
$dbname = "TIENDA";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM usuarios WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la consulta: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();



        if ($password === $user['password']) { 
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['nombre_usuario'] = $user['nombre_usuario'];
            header("Location: tienda.php"); 
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Correo no encontrado.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Éclat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: black;
            color: white;
        }
        .login-container {
            max-width: 400px;
            margin: 5% auto;
            padding: 2rem;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center">Iniciar Sesión</h2>
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="nombre@ejemplo.com" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Tu contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
            <?php if (isset($error)) { ?>
                <div class="text-danger mt-2 text-center"><?= $error ?></div>
            <?php } ?>
        </form>
        <div class="text-center mt-3">
            ¿No tienes una cuenta? <a href="signup.php" class="text-white">Regístrate</a>
        </div>
    </div>
</body>
</html>
