<body class="signup-background">
<?php
session_start(); // Asegúrate de iniciar la sesión
require('includes/header_us.php'); 

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['user_name'])) {
    $nombre = $_SESSION['user_name']; // Obtiene el nombre de la sesión
    // Mostrar el mensaje de bienvenida con una clase CSS
    echo '<div class="welcome-message">Bienvenido Amigo, ' . htmlspecialchars($nombre) . '!</div>';

    // Crear la barra de navegación
    echo '<nav class="options-navbar">';
    echo '<ul>';
    echo '<li><a href="dashboard.php">Dashboard</a></li>';
    echo '<li><a href="administrar_especie.php">Especies</a></li>';
    echo '<li><a href="amigos.php">Amigos</a></li>';
    echo '<li><a href="arboles.php">Árboles</a></li>';
    echo '</ul>';
    echo '</nav>';
} else {
    echo '<div class="welcome-message">Acceso denegado. Por favor, inicie sesión.</div>';
}
?>
</body>
