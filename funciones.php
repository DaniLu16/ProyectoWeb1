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
    $connection = getConnection(); // Conexión a la base de datos

    $sql = "UPDATE amigos SET 
                nombre = ?, 
                apellidos = ?, 
                telefono = ?, 
                email = ?, 
                direccion = ?, 
                pais = ?, 
                estado_id = ?, 
                rol_id = ? 
            WHERE id = ?";

    $stmt = mysqli_prepare($connection, $sql);
    if (!$stmt) {
        echo "Error al preparar la consulta: " . mysqli_error($connection);
        return false;
    }

    // Asociar parámetros y ejecutar la consulta
    mysqli_stmt_bind_param($stmt, 'ssssssiii', 
        $nombre, $apellidos, $telefono, $email, $direccion, $pais, $estado_id, $rol_id, $id);

    $success = mysqli_stmt_execute($stmt);

    if (!$success) {
        echo "Error al ejecutar la consulta: " . mysqli_error($connection);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    return $success;
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

function registrarArbol($nombreComercial, $nombreCientifico, $file) {
    // Verificar si el archivo fue subido correctamente
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    // Directorio de destino para las imágenes
    $directorioDestino = "../arboles/";

    // Crear el directorio si no existe
    if (!is_dir($directorioDestino)) {
        mkdir($directorioDestino, 0777, true);
    }

    // Generar un nombre único para la imagen
    $nombreImagen = uniqid() . "-" . basename($file['name']);
    $rutaImagen = $directorioDestino . $nombreImagen;

    // Mover la imagen al directorio de destino
    if (!move_uploaded_file($file['tmp_name'], $rutaImagen)) {
        return false;
    }

    // Guardar los datos en la base de datos con estado activo por defecto
    $connection = getConnection();
    $query = "INSERT INTO arboles (nombre_comercial, nombre_cientifico, imagen, estado_id) VALUES (?, ?, ?, 1)";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sss', $nombreComercial, $nombreCientifico, $nombreImagen);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($connection);

        return $resultado; // Retornar true si el registro es exitoso
    }

    return false; // Retornar false si hay un error
}



function cargarArboles() {
    $connection = getConnection(); // Conectar a la base de datos

    // Consulta para obtener todos los árboles, incluyendo la imagen
    $query = "SELECT id, nombre_comercial, nombre_cientifico, imagen FROM arboles";
    $result = mysqli_query($connection, $query);

    // Verificar si la consulta fue exitosa
    if (!$result) {
        die("Error al cargar árboles: " . mysqli_error($connection));
    }

    return $result; // Retornar el resultado de la consulta
}


function editarArbol($id, $nombreComercial, $nombreCientifico, $file = null) {
    $connection = getConnection();
    $query = "UPDATE arboles SET nombre_comercial = ?, nombre_cientifico = ?";

    $params = [$nombreComercial, $nombreCientifico];
    $paramTypes = 'ss';

    // Verificar si se está subiendo una nueva imagen
    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        $directorioDestino = "../arboles/";

        // Crear el directorio si no existe
        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        // Generar un nombre único para la imagen
        $nombreImagen = uniqid() . "-" . basename($file['name']);
        $rutaImagen = $directorioDestino . $nombreImagen;

        // Mover la imagen al directorio de destino
        if (move_uploaded_file($file['tmp_name'], $rutaImagen)) {
            $query .= ", imagen = ?";
            $params[] = $nombreImagen;
            $paramTypes .= 's';
        } else {
            return [
                "success" => false,
                "message" => "Error al subir la imagen."
            ];
        }
    }

    $query .= " WHERE id = ?";
    $params[] = $id;
    $paramTypes .= 'i';

    $stmt = mysqli_prepare($connection, $query);

    // Verificar la preparación de la consulta
    if (!$stmt) {
        return [
            "success" => false,
            "message" => "Error al preparar la consulta."
        ];
    }

    mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);
    $resultado = mysqli_stmt_execute($stmt);

    if ($resultado) {
        $mensaje = "Árbol actualizado correctamente.";
        
    } else {
        $mensaje = "Error al actualizar el árbol: " . mysqli_error($connection);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    return [
        "success" => $resultado,
        "message" => $mensaje
    ];
}




function eliminarArbol($id) {
    $connection = getConnection();

    // Obtener la imagen asociada para eliminarla del servidor
    $queryImagen = "SELECT imagen FROM arboles WHERE id = ?";
    $stmtImagen = mysqli_prepare($connection, $queryImagen);
    mysqli_stmt_bind_param($stmtImagen, 'i', $id);
    mysqli_stmt_execute($stmtImagen);
    mysqli_stmt_bind_result($stmtImagen, $imagen);
    mysqli_stmt_fetch($stmtImagen);
    mysqli_stmt_close($stmtImagen);

    // Eliminar la imagen del servidor
    if ($imagen && file_exists("../arboles/" . $imagen)) {
        unlink("../arboles/" . $imagen);
    }

    // Eliminar el registro de la base de datos
    $query = "DELETE FROM arboles WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);

    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    if ($resultado) {
        // Redirigir a la página de administración de árboles
        header("Location: adm_trees.php");
        exit();
    } else {
        return json_encode([
            "success" => false,
            "message" => "Error al eliminar el árbol."
        ]);
    }
}




?>

<?php
