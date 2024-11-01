<body class="signup-background2">
<?php
session_start(); 
require('includes/header_admin.php');

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['user_name'])) {
    $nombre = $_SESSION['user_name'];
    echo '<div class="welcome-message">Bienvenido Amigo, ' . htmlspecialchars($nombre) . '!</div>';

    
    
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
