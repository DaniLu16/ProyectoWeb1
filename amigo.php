<body class="signup-background2">
<?php
session_start(); // Asegúrate de iniciar la sesión
require('includes/header_amigo.php');

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['user_name'])) {
    $nombre = $_SESSION['user_name']; // Obtiene el nombre de la sesión
    // Mostrar el mensaje de bienvenida con una clase CSS
    echo '<div class="welcome-message">Bienvenido Amigo, ' . htmlspecialchars($nombre) . '!</div>';
} else {
    echo '<div class="welcome-message">Acceso denegado. Por favor, inicie sesión.</div>';
}
?>
</body>
