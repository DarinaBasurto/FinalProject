<?php
session_start(); // Inicia la sesión

// Configuración de la conexión a la base de datos
$host = "localhost"; // Cambiar si tu servidor tiene otro nombre
$user = "root"; // Usuario de MySQL
$password = ""; // Contraseña de MySQL
$dbname = "TIENDA"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta para verificar usuario
    $sql = "SELECT * FROM usuarios WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Validar contraseña
        $user = $result->fetch_assoc();
        if ($password === $user['password']) { // Usar hash para producción
            // Credenciales válidas
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['nombre_usuario'] = $user['nombre_usuario'];
            header("Location: tienda.php"); // Redirigir al usuario
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "No se encontró una cuenta con ese correo.";
    }
}
$conn->close();
?>
