<?php
session_start();

// Verifica si el usuario ha iniciado sesión y si es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 1) { 
    // Redirigir al usuario a una página de acceso denegado
    header('Location: acceso_denegado.php'); 
    exit();
}

require('includes/header_admin.php');
$nombre = $_SESSION['user_name'];
?>

<body class="signup-background2">
    <div class="welcome-message">Bienvenido Administrador, <?php echo htmlspecialchars($nombre); ?>!</div>
    
    <!-- Contenedor para el contenido cargado -->
    <div id="content-container"></div>

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
