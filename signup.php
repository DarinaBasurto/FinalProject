<?php

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

    // Verificar si el mail ya está en la base de datos.
    $sql_check = "SELECT * FROM usuarios WHERE email = ? LIMIT 1";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $error = "El correo ya está registrado. Intenta con otro.";
    } else {
        // Insert de el nuevo usuario a la base de datos.
        $sql = "INSERT INTO usuarios (nombre_usuario, email, password, fecha_nacimiento, num_tarjeta, codigo_postal)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $nombre_usuario, $email, $password, $fecha_nacimiento, $num_tarjeta, $codigo_postal);

        if ($stmt->execute()) {
            // Redirigir a iniciar sesión. 
            header("Location: login.html");
            exit();
        } else {
            $error = "Ocurrió un error al registrar el usuario. Intenta nuevamente.";
        }
    }
}
$conn->close();
?>
