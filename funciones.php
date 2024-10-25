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

function registrarUsuario($nombre, $apellidos, $telefono, $email, $direccion, $pais, $password) {
    $connection = getConnection();

    // Encriptar la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Obtener la fecha y hora actual para último inicio de sesión
    $ultimo_inicio_sesion = date('Y-m-d H:i:s');

    // Obtener rol_id de la tabla roles
    $queryRole = "SELECT id FROM roles WHERE tipo = 'amigo'"; // Cambia 'amigo' a 'administrador' si es necesario
    $resultRole = mysqli_query($connection, $queryRole);

    if ($resultRole && mysqli_num_rows($resultRole) > 0) {
        $rol = mysqli_fetch_assoc($resultRole);
        $rol_id = $rol['id']; // Asignar el rol_id correspondiente
    } else {
        die("Error al obtener el rol: " . mysqli_error($connection));
    }

    // Obtener estado_id de la tabla estados (suponiendo que quieres que sea "activo" por defecto)
    $queryEstado = "SELECT id FROM estado WHERE estado = 'activo'"; // Puedes cambiar esto si es necesario
    $resultEstado = mysqli_query($connection, $queryEstado);

    if ($resultEstado && mysqli_num_rows($resultEstado) > 0) {
        $estado = mysqli_fetch_assoc($resultEstado);
        $estado_id = $estado['id']; // Asignar el estado_id correspondiente
    } else {
        die("Error al obtener el estado: " . mysqli_error($connection));
    }

    $query = "INSERT INTO amigos (nombre, apellidos, telefono, email, direccion, pais, contraseña, rol_id, estado_id, ultimo_inicio_sesion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $query);

    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        die("Error al preparar la consulta: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, 'sssssssiis', $nombre, $apellidos, $telefono, $email, $direccion, $pais, $hashedPassword, $rol_id, $estado_id, $ultimo_inicio_sesion);

    if (mysqli_stmt_execute($stmt)) {
        echo "Usuario registrado con éxito.";
    } else {
        echo "Error al registrar usuario: " . mysqli_error($connection);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}

function cargarUsuarios() {
    // Establecer conexión a la base de datos
    $connection = getConnection();

    // Consulta para obtener todos los usuarios de la tabla amigos
    $query = "SELECT id, nombre, apellidos, telefono, email, direccion, pais, rol_id, estado_id, ultimo_inicio_sesion FROM amigos";
    $result = mysqli_query($connection, $query);

    // Verificar si la consulta fue exitosa
    if (!$result) {
        die("Error al cargar usuarios: " . mysqli_error($connection));
    }

    // Crear un array para almacenar los usuarios
    $usuarios = [];

    // Recorrer los resultados y agregar cada usuario al array
    while ($usuario = mysqli_fetch_assoc($result)) {
        $usuarios[] = $usuario;
    }

    // Cerrar la conexión
    mysqli_close($connection);

    // Retornar el array de usuarios
    return $usuarios;
}
function editarUsuario($id, $nombre, $apellidos, $telefono, $email, $direccion, $pais, $estado_id, $rol_id) {
    // Establecer conexión a la base de datos
    $connection = getConnection();

    // Verificar si la conexión es válida
    if ($connection->connect_error) {
        die("Error de conexión: " . $connection->connect_error);
    }

    // Preparar la consulta SQL de actualización
    $sql = "UPDATE amigos 
            SET nombre = ?, apellidos = ?, telefono = ?, email = ?, direccion = ?, pais = ?, estado_id = ?, rol_id = ? 
            WHERE id = ?";
    
    $stmt = $connection->prepare($sql);

    // Verificar si la preparación fue exitosa
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $connection->error);
    }

    // Vincular los parámetros a la consulta SQL
    if (!$stmt->bind_param("sssssissi", $nombre, $apellidos, $telefono, $email, $direccion, $pais, $estado_id, $rol_id, $id)) {
        die("Error al vincular parámetros: " . $stmt->error);
    }

    // Ejecutar la consulta y manejar el resultado
    if ($stmt->execute()) {
        echo "Usuario actualizado con éxito.";
    } else {
        echo "Error al actualizar el usuario: " . $stmt->error;
    }

    // Cerrar la sentencia preparada
    $stmt->close();
    
    // Cerrar la conexión
    $connection->close();

    return $stmt->affected_rows > 0; // Devuelve verdadero si se actualizó al menos un registro
}



function loginUsuario($email, $password) {
    $connection = getConnection();

    // Preparar la consulta para obtener el usuario por email
    $query = "SELECT id, nombre, rol_id, contraseña FROM amigos WHERE email = ?";
    $stmt = mysqli_prepare($connection, $query);

    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        die("Error al preparar la consulta: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $nombre, $rol_id, $hashedPassword);
    mysqli_stmt_fetch($stmt);

    // Verificar la contraseña
    if (password_verify($password, $hashedPassword)) {
        // Iniciar sesión
        session_start();
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $nombre;
        $_SESSION['rol_id'] = $rol_id;

        // Redireccionar según el rol
        if ($rol_id == 1) {
            header("Location: admin.php"); // Cambia esto a la página del administrador
        } elseif ($rol_id == 2) {
            header("Location: amigo.php"); // Cambia esto a la página del amigo
        }
        exit();
    } else {
        echo "Credenciales incorrectas.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}
// Función para generar el script del modal
function renderModalScript($error_msg = '') {
    ?>
    <script>
        // Función para abrir el modal
        function openModal() {
            const modal = document.getElementById("loginModal");
            const loginForm = document.getElementById("loginForm"); // Obtiene el formulario
            if (modal) {
                modal.style.display = "block";
                if (loginForm) {
                    loginForm.reset(); // Limpiar los campos del formulario
                }
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
                closeModal();
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


// Función para cargar los países desde la API
function cargarPaises() {
    ?>
    <script>
        fetch('https://restcountries.com/v3.1/all')
            .then(response => response.json())
            .then(data => {
                const paisSelect = document.getElementById('pais');
                data.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.name.common;
                    option.textContent = country.name.common;
                    paisSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error al cargar países:', error));
    </script>
    <?php
}



function obtenerOpciones($tipo) {
    // Inicializar un array para almacenar las opciones
    $opciones = [];
    $connection = getConnection();

    // Preparar la consulta SQL para obtener los nombres comerciales o científicos
    $sql = "SELECT $tipo FROM tipos_arboles";
    $result = mysqli_query($connection, $sql);

    // Verificar si hay resultados y agregar las opciones al array
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $opciones[] = $row[$tipo];
        }
    }

    mysqli_close($connection);
    return $opciones;
}

// Registrar árbol en la base de datos
function registrarArbol($nombreComercial, $nombreCientifico) {
    $connection = getConnection();
    $query = "INSERT INTO arboles_nuevos (nombre_comercial, nombre_cientifico) VALUES (?, ?)";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ss', $nombreComercial, $nombreCientifico);
        if (mysqli_stmt_execute($stmt)) {
            echo "Árbol registrado con éxito.";
        } else {
            echo "Error al registrar el árbol: " . mysqli_error($connection);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error al preparar la consulta: " . mysqli_error($connection);
    }
    mysqli_close($connection);
}
?>
?>
<?php
