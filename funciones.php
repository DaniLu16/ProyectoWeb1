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
        return ['success' => false, 'message' => "Error al preparar la consulta: " . mysqli_error($connection)];
    }

    // Asociar parámetros y ejecutar la consulta
    mysqli_stmt_bind_param($stmt, 'ssssssiii', 
        $nombre, $apellidos, $telefono, $email, $direccion, $pais, $estado_id, $rol_id, $id);

    $success = mysqli_stmt_execute($stmt);
    

    if (!$success) {
        return ['success' => false, 'message' => "Error al ejecutar la consulta: " . mysqli_error($connection)];
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    return ['success' => true, 'message' => 'Usuario actualizado con éxito'];
    
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

function registrarArbol($especieId, $tamano, $ubicacion, $precio, $file) {
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

    // Guardar los datos en la tabla arboles_dispo con estado disponible = 1 por defecto
    $connection = getConnection();
    $query = "INSERT INTO arboles_dispo (especie, tamano, ubicacion, estado, precio, imagen) VALUES (?, ?, ?, 1, ?, ?)";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'issds', $especieId, $tamano, $ubicacion, $precio, $nombreImagen);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($connection);

        return $resultado; // Retornar true si el registro es exitoso
    }

    return false; // Retornar false si hay un error
}


function obtenerOpcionesEspecies() {
    $connection = getConnection();
    $query = "SELECT id, nombre_comercial, nombre_cientifico FROM especies";
    $result = mysqli_query($connection, $query);
    
    $especies = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $especies[] = $row;
        }
    } else {
        die("Error al cargar especies: " . mysqli_error($connection));
    }

    mysqli_close($connection);
    return $especies;
}



function cargarArboles() {
    $connection = getConnection(); // Conectar a la base de datos

    // Consulta para obtener todos los árboles de arboles_dispo, incluyendo los datos de la especie y la imagen
    $query = "
        SELECT ad.id, e.nombre_comercial, e.nombre_cientifico, ad.ubicacion, ad.estado, ad.precio, ad.imagen 
        FROM arboles_dispo AS ad
        JOIN especies AS e ON ad.especie = e.id
    ";
    
    $result = mysqli_query($connection, $query);

    // Verificar si la consulta fue exitosa
    if (!$result) {
        die("Error al cargar árboles: " . mysqli_error($connection));
    }

    return $result; // Retornar el resultado de la consulta
}


function registrarEspecie($nombreComercial, $nombreCientifico) {
   
    $connection = getConnection();
    $query = "INSERT INTO especies (nombre_comercial, nombre_cientifico) VALUES (?, ?)";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ss', $nombreComercial, $nombreCientifico);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($connection);

        return $resultado; // Retornar true si el registro es exitoso
    }

    return false; // Retornar false si hay un error
}



function cargarEspecie() {
    $connection = getConnection(); // Conectar a la base de datos

    // Consulta para obtener todas las especies
    $query = "SELECT id, nombre_comercial, nombre_cientifico FROM especies";
    $result = mysqli_query($connection, $query);

    // Verificar si la consulta fue exitosa
    if (!$result) {
        die("Error al cargar especies: " . mysqli_error($connection));
    }

    return $result; // Retornar el resultado de la consulta
}

function editarArbol($id, $tamano, $especie, $ubicacion, $precio, $estado, $file = null) {
    $connection = getConnection();
    if (!$connection) {
        return [
            "success" => false,
            "message" => "Error de conexión: " . mysqli_connect_error()
        ];
    }

    // Ajusta la consulta con los nombres de columna correctos
    $query = "UPDATE arboles_dispo SET tamano = ?, especie = ?, ubicacion = ?, precio = ?, estado = ?";
    $params = [$tamano, $especie, $ubicacion, $precio, $estado];
    $paramTypes = 'sssdi'; // Asegúrate de que coincida con los parámetros

    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        $directorioDestino = "../arboles/";
        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        $nombreImagen = uniqid() . "-" . basename($file['name']);
        $rutaImagen = $directorioDestino . $nombreImagen;

        // Mover la imagen y verificar si se realizó correctamente
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
    if (!$stmt) {
        return [
            "success" => false,
            "message" => "Error al preparar la consulta: " . mysqli_error($connection)
        ];
    }

    // Para depuración: mostrar la consulta y los parámetros
    error_log("Consulta: " . $query);
    error_log("Parámetros: " . json_encode($params));
    
    if (!mysqli_stmt_bind_param($stmt, $paramTypes, ...$params)) {
        return [
            "success" => false,
            "message" => "Error al enlazar parámetros: " . mysqli_stmt_error($stmt)
        ];
    }
    
    if (!mysqli_stmt_execute($stmt)) {
        return [
            "success" => false,
            "message" => "Error al ejecutar la consulta: " . mysqli_stmt_error($stmt)
        ];
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
    
    return [
        "success" => true,
        "message" => "Árbol actualizado correctamente."
    ];
}


function eliminarArbol($id) {
    $connection = getConnection();

    // Primero, elimina las compras asociadas al árbol
    $queryCompras = "DELETE FROM compras WHERE arbol_id = ?";
    $stmtCompras = mysqli_prepare($connection, $queryCompras);
    mysqli_stmt_bind_param($stmtCompras, 'i', $id);
    mysqli_stmt_execute($stmtCompras);
    mysqli_stmt_close($stmtCompras);

    // Obtener la imagen asociada para eliminarla del servidor
    $queryImagen = "SELECT imagen FROM arboles_dispo WHERE id = ?";
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

    // Eliminar el registro del árbol de la base de datos
    $query = "DELETE FROM arboles_dispo WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);

    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    return $resultado; // Retornar el resultado de la eliminación
}



function eliminarEspecie($id) {
    $connection = getConnection();

    // Eliminar el registro de la base de datos
    $query = "DELETE FROM especies WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);

    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    if ($resultado) {
        // Redirigir a la página de administración de árboles
        header("Location: adm_especie.php");
        exit();
    } else {
        return json_encode([
            "success" => false,
            "message" => "Error al eliminar especie."
        ]);
    }
 
}
 
function editarEspecie($id, $nombreComercial, $nombreCientifico) {
    $connection = getConnection();
    
    // Consulta con WHERE para especificar el registro a actualizar
    $query = "UPDATE especies SET nombre_comercial = ?, nombre_cientifico = ? WHERE id = ?";
    
    // Preparación de la declaración
    $stmt = $connection->prepare($query);
    $paramTypes = 'ssi'; // 'ssi' indica los tipos: string, string, integer
    $stmt->bind_param($paramTypes, $nombreComercial, $nombreCientifico, $id);
    
    // Ejecución de la declaración
    if ($stmt->execute()) {
        $stmt->close();
        $connection->close();
        return ['success' => true, 'message' => 'Especie actualizada exitosamente.'];
    } else {
        $error = $stmt->error;
        $stmt->close();
        $connection->close();
        return ['success' => false, 'message' => "Error al actualizar la especie: $error"];
    }
}

function cargarArbolesDisponibles() {
    $connection = getConnection();

    // Consulta para obtener solo los árboles disponibles (estado = 1), incluyendo el tamaño y el ID de la especie
    $query = "
        SELECT ad.id, ad.especie, e.nombre_comercial, e.nombre_cientifico, ad.ubicacion, ad.estado, ad.precio, ad.imagen, ad.tamano
        FROM arboles_dispo AS ad
        JOIN especies AS e ON ad.especie = e.id
        WHERE ad.estado = 1
    ";

    // Ejecutar la consulta
    $result = mysqli_query($connection, $query);

    // Manejo de errores
    if (!$result) {
        die("Error al cargar árboles: " . htmlspecialchars(mysqli_error($connection)));
    }

    // Devolver el resultado
    return $result;
}


function obtenerListaAmigos() {
    $connection = getConnection();

    $query = "SELECT id, nombre, apellidos, email FROM amigos";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Error al obtener la lista de amigos: " . mysqli_error($connection));
    }

    return $result;
}

function obtenerArbolesCompradosPorAmigos() {
    $connection = getConnection();

    $query = "
        SELECT a.id AS amigo_id, a.nombre, a.apellidos, ad.id AS arbol_id, e.nombre_comercial, e.nombre_cientifico, ad.ubicacion, ad.precio, ad.imagen, c.fecha_compra
        FROM compras AS c
        JOIN arboles_dispo AS ad ON c.arbol_id = ad.id
        JOIN especies AS e ON ad.especie = e.id
        JOIN amigos AS a ON c.user_id = a.id
    ";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Error al obtener árboles comprados por amigos: " . mysqli_error($connection));
    }

    return $result;
}

function obtenerArbolesCompradosPorUsuario($user_id) {
    $connection = getConnection();

    $query = "
        SELECT ad.id, e.nombre_comercial, e.nombre_cientifico, ad.ubicacion, ad.precio, ad.imagen, ad.tamano, c.fecha_compra
        FROM compras AS c
        JOIN arboles_dispo AS ad ON c.arbol_id = ad.id
        JOIN especies AS e ON ad.especie = e.id
        WHERE c.user_id = ?
    ";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        die("Error al obtener árboles comprados: " . mysqli_error($connection));
    }

    return $result;
}

function obtenerDatosArbol($id) {
    $connection = getConnection();
    $query = "SELECT tamano, especie, ubicacion, estado FROM arboles_dispo WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $arbol = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
    return $arbol;
}

function registrarActualizacion($arbol_id, $tamano, $estado) {
    $connection = getConnection();
    $query = "INSERT INTO actualizaciones_arboles (arbol_id, tamano, estado) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'iss', $arbol_id, $tamano, $estado);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
    return $resultado;
}

function contarAmigosRegistrados() {
    $connection = getConnection();

    // Consulta para contar la cantidad de amigos registrados
    $query = "SELECT COUNT(*) AS total_amigos FROM amigos WHERE rol_id = 2"; // 2 representa el rol de 'amigo'
    $result = mysqli_query($connection, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_amigos = $row['total_amigos'];
        return $total_amigos;
    } else {
        return "Error al contar los amigos registrados: " . mysqli_error($connection);
    }

    mysqli_close($connection);
}

// Obtener la cantidad de amigos registrados
$cantidad_amigos = contarAmigosRegistrados();

function contarArbolesDisponibles() {
    $connection = getConnection();

    // Consulta para contar la cantidad de árboles disponibles
    $query = "SELECT COUNT(*) AS total_arboles FROM arboles_dispo WHERE estado = 1"; // 1 representa el estado "disponible"
    $result = mysqli_query($connection, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_arboles = $row['total_arboles'];
        return $total_arboles;
    } else {
        return "Error al contar los árboles disponibles: " . mysqli_error($connection);
    }

    mysqli_close($connection);
}


function contarArbolesVendidos() {
    $connection = getConnection();

    // Consulta para contar la cantidad de árboles vendidos
    $query = "SELECT COUNT(*) AS total_vendidos FROM compras";
    $result = mysqli_query($connection, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_vendidos = $row['total_vendidos'];
        return $total_vendidos;
    } else {
        return "Error al contar los árboles vendidos: " . mysqli_error($connection);
    }

    mysqli_close($connection);
}

function editarArbol2($id, $especieId, $nombreComercial, $nombreCientifico, $ubicacion, $precio, $estado, $tamano, $file) {
    $connection = getConnection();

    // Iniciar una transacción
    mysqli_begin_transaction($connection);

    try {
        // Actualizar la especie en la tabla especies (si es necesario)
        $queryEspecie = "UPDATE especies SET nombre_comercial = ?, nombre_cientifico = ? WHERE id = ?";
        $stmtEspecie = mysqli_prepare($connection, $queryEspecie);
        mysqli_stmt_bind_param($stmtEspecie, 'ssi', $nombreComercial, $nombreCientifico, $especieId);
        mysqli_stmt_execute($stmtEspecie);

        // Ahora actualizamos el árbol (incluyendo el campo `especie`)
        $queryArbol = "UPDATE arboles_dispo SET especie = ?, ubicacion = ?, precio = ?, estado = ?, tamano = ?, imagen = ? WHERE id = ?";
        $stmtArbol = mysqli_prepare($connection, $queryArbol);

        // Manejo de la imagen
        $imageName = '';
        if (!empty($file['name'])) {
            $imageName = uniqid() . '_' . basename($file['name']);
            $targetFilePath = '../arboles/' . $imageName;

            if (!move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                throw new Exception('Error al subir la imagen.');
            }
        } else {
            $currentQuery = "SELECT imagen FROM arboles_dispo WHERE id = ?";
            $currentStmt = mysqli_prepare($connection, $currentQuery);
            mysqli_stmt_bind_param($currentStmt, 'i', $id);
            mysqli_stmt_execute($currentStmt);
            $currentResult = mysqli_stmt_get_result($currentStmt);
            $currentRow = mysqli_fetch_assoc($currentResult);
            $imageName = $currentRow['imagen'];
            mysqli_stmt_close($currentStmt);
        }

        // Vincular parámetros a la consulta
        mysqli_stmt_bind_param($stmtArbol, 'isdssis', $especieId, $ubicacion, $precio, $estado, $tamano, $imageName, $id);
        $executeResult = mysqli_stmt_execute($stmtArbol);

        if ($executeResult) {
            // Confirmar la transacción
            mysqli_commit($connection);
            return ['success' => true, 'message' => 'Árbol actualizado exitosamente.'];
        } else {
            throw new Exception(mysqli_error($connection));
        }

    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        mysqli_rollback($connection);
        return ['success' => false, 'message' => $e->getMessage()];
    } finally {
        // Cerrar las conexiones preparadas
        if (isset($stmtEspecie)) {
            mysqli_stmt_close($stmtEspecie);
        }
        if (isset($stmtArbol)) {
            mysqli_stmt_close($stmtArbol);
        }
        mysqli_close($connection);
    }
}

?>

<?php
