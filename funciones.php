<?php
// Función para establecer la conexión a la base de datos
function getConnection() {
    $connection = mysqli_connect('localhost', 'root', '', 'mytrees');
    
    // Verificar si la conexión fue exitosa
    if (!$connection) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    return $connection;
}

function validarLogin($email, $password) {
    $conexion = getConnection(); // Cambiado a getConnection()

    // Escapar datos para evitar inyecciones SQL
    $email = $conexion->real_escape_string($email);
    $password = $conexion->real_escape_string($password);

    // Consultar el usuario
    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    $resultado = $conexion->query($query);

    if ($resultado && $resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verificar la contraseña
        if ($usuario['contraseña'] === $password) {
            // Iniciar sesión
            session_start();
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol_id'] = $usuario['rol_id'];

            $conexion->close(); // Cerrar conexión aquí
            return $usuario['rol_id']; // Devolver el rol del usuario
        } else {
            $conexion->close(); // Cerrar conexión en caso de contraseña incorrecta
            return false; // Contraseña incorrecta
        }
    } else {
        $conexion->close(); // Cerrar conexión si no se encontró el usuario
        return false; // Usuario no encontrado
    }
}

function mostrarMensajeBienvenida($rol_id) {
    if ($rol_id == 1) { // Suponiendo que 1 es el rol de administrador
        return "Bienvenido Administrador";
    } else {
        return "Bienvenido Amigo";
    }
}

// Función para generar el script del modal
function renderModalScript($error_msg = '') {
    ?>
    <script>
        // Función para abrir el modal
        function openModal() {
            const modal = document.getElementById("loginModal");
            if (modal) {
                modal.style.display = "block";
            }
        }

        // Función para cerrar el modal
        function closeModal() {
            const modal = document.getElementById("loginModal");
            if (modal) {
                modal.style.display = "none";
            }
        }

        // Cierra el modal si se hace clic fuera de él
        window.onclick = function(event) {
            const modal = document.getElementById("loginModal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };

        // Mostrar el modal automáticamente si hay un error
        <?php if (!empty($error_msg)): ?>
            window.onload = function() {
                openModal(); // Abre el modal si hay un error
            };
        <?php endif; ?>
    </script>
    <?php
}
?>
