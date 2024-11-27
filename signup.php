<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "localhost"; 
$user = "root"; 
$password = ""; 
$dbname = "TIENDA"; 

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $num_tarjeta = $_POST['num_tarjeta'];
    $codigo_postal = $_POST['codigo_postal'];
    $admin = isset($_POST['admin']) ? 1 : 0;

    // Verificar si el mail ya está en la base de datos.
    $sql_check = "SELECT * FROM usuarios WHERE email = ? LIMIT 1";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $error = "El correo ya está registrado. Intenta con otro.";
    } else {
        // Insertar el nuevo usuario a la base de datos.
        $sql = "INSERT INTO usuarios (nombre_usuario, email, password, fecha_nacimiento, num_tarjeta, codigo_postal, admin)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiii", $nombre_usuario, $email, $password, $fecha_nacimiento, $num_tarjeta, $codigo_postal, $admin);

        if ($stmt->execute()) {
            // Redirigir a iniciar sesión.
            header("Location: login.php");
            exit();
        } else {
            $error = "Ocurrió un error al registrar el usuario. Intenta nuevamente.";
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | Éclat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: black;
            color: white;
        }
        .signup-container {
            max-width: 500px;
            margin: 5% auto;
            padding: 2rem;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2 class="text-center">Crear Cuenta</h2>
        <form action="signup.php" method="POST">
            <div class="mb-3">
                <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </div>
            <div class="mb-3">
                <label for="num_tarjeta" class="form-label">Número de Tarjeta</label>
                <input type="number" class="form-control" id="num_tarjeta" name="num_tarjeta" required>
            </div>
            <div class="mb-3">
                <label for="codigo_postal" class="form-label">Código Postal</label>
                <input type="number" class="form-control" id="codigo_postal" name="codigo_postal" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="admin" name="admin">
                <label class="form-check-label" for="admin">Es Administrador</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Crear Cuenta</button>
            <?php if (isset($error)) { ?>
                <div class="text-danger mt-2 text-center"><?= $error ?></div>
            <?php } ?>
        </form>
        <div class="text-center mt-3">
            ¿Ya tienes una cuenta? <a href="login.php" class="text-white">Inicia sesión</a>
        </div>
    </div>
</body>
</html>
