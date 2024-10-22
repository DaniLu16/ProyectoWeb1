<?php
// Aquí puedes agregar el código para cerrar la sesión, por ejemplo:
session_destroy(); // Destruye la sesión actual

// Redirigir a index.php
header("Location: index.php");
exit(); // Asegúrate de detener la ejecución del script después de la redirección
?>
