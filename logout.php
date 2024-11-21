<?php
// Iniciar la sesión
session_start();

// Destruir la sesión y limpiar los datos
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión

// Redirigir a la página principal
header("Location: main.html");
exit();
?>
