<body class="signup-background">
<?php
session_start(); 
require('includes/header_us.php');

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['user_name'])) {
    $nombre = $_SESSION['user_name'];
    echo '<div class="welcome-message">Bienvenido Amigo, ' . htmlspecialchars($nombre) . '!</div>';

    // Crear la barra de navegación
    echo '<nav class="options-navbar">';
    echo '<ul>';
    echo '<li><a href="dashboard.php">Dashboard</a></li>';

    // Menú de Especies con submenú
    echo '<li class="dropdown">
            <a href="#" onclick="toggleMenu(event)">Especies</a>
            <ul class="submenu">
                <li><a href="agregar_especie.php">Nueva Especie</a></li>
                <li><a href="administrar_especie.php">Administrar Especies</a></li>
            </ul>
          </li>';

    // Menú de Amigos con submenú
    echo '<li class="dropdown">
            <a href="#" onclick="toggleMenu(event)">Amigos</a>
            <ul class="submenu">
                <li><a href="administrar_arboles.php">Ver Árboles por Amigo</a></li>
                <li><a href="editar_arboles_amigo.php">Editar los Árboles de un Amigo</a></li>
            </ul>
          </li>';

    // Menú de Árboles con submenú
    echo '<li class="dropdown">
            <a href="#" onclick="toggleMenu(event)">Árboles</a>
            <ul class="submenu">
                <li><a href="administrar_arboles.php">Nuevo árbol </a></li>
                <li><a href="administrar_arboles.php">Administrar Árboles</a></li>
            </ul>
          </li>';
    echo '</ul>';
    echo '</nav>';
} else {
    echo '<div class="welcome-message">Acceso denegado. Por favor, inicie sesión.</div>';
}
?>
<script>
    // Función para mostrar/ocultar el submenú al hacer clic
    function toggleMenu(event) {
        event.preventDefault(); // Evitar recarga de página
        const submenu = event.target.nextElementSibling;

        // Cerrar otros submenús abiertos
        document.querySelectorAll('.submenu.show').forEach(menu => {
            if (menu !== submenu) {
                menu.classList.remove('show');
            }
        });

        // Mostrar u ocultar el submenú seleccionado
        submenu.classList.toggle('show');
    }
</script>
</body>
