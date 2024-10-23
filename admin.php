<body class="signup-background">
<?php
session_start(); 
require('includes/header.php');

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['user_name'])) {
    $nombre = $_SESSION['user_name'];
    echo '<div class="welcome-message">Bienvenido Amigo, ' . htmlspecialchars($nombre) . '!</div>';

    // Crear la barra de navegación
    echo '<nav class="options-navbar">';
    echo '<ul>';
    echo '<li><a href="#" onclick="loadContent(\'dashboard.php\')">Dashboard</a></li>';

    // Menú de Especies con submenú
    echo '<li class="dropdown">
            <a href="#" onclick="toggleMenu(event)">Especies</a>
            <ul class="submenu">
                <li><a href="#" onclick="loadContent(\'agregar_especie.php\')">Nueva Especie</a></li>
                <li><a href="#" onclick="loadContent(\'administrar_especie.php\')">Administrar Especies</a></li>
            </ul>
          </li>';

    // Menú de Amigos con submenú
    echo '<li class="dropdown">
            <a href="#" onclick="toggleMenu(event)">Amigos</a>
            <ul class="submenu">
                <li><a href="#" onclick="loadContent(\'administrar_arboles.php\')">Ver Árboles por Amigo</a></li>
                <li><a href="#" onclick="loadContent(\'editar_arboles_amigo.php\')">Administrar los Árboles de un Amigo</a></li>
                <li><a href="#" onclick="loadContent(\'administrador/adm_usuarios.php\')">Administrar Amigos</a></li>
            </ul>
          </li>';

    // Menú de Árboles con submenú
    echo '<li class="dropdown">
            <a href="#" onclick="toggleMenu(event)">Árboles</a>
            <ul class="submenu">
                <li><a href="#" onclick="loadContent(\'administrar_arboles.php\')">Nuevo árbol</a></li>
                <li><a href="#" onclick="loadContent(\'administrar_arboles.php\')">Administrar Árboles</a></li>
            </ul>
          </li>';
    echo '</ul>';
    echo '</nav>';
    
    // Contenedor para el contenido cargado
    echo '<div id="content-container"></div>';
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

    // Función para cargar el contenido en el contenedor
    function loadContent(page) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', page, true);
        xhr.onload = function() {
            if (this.status === 200) {
                document.getElementById('content-container').innerHTML = this.responseText;
            } else {
                document.getElementById('content-container').innerHTML = '<p>Error al cargar el contenido.</p>';
            }
        };
        xhr.send();
    }
</script>

</body>
