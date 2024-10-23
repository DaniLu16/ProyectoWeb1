<?php 
include('/funciones.php');
require('/inc/header.php');

// Obtener la conexión a la base de datos
$connection = getConnection();

// Consulta SQL para obtener todos los usuarios
$sql = "SELECT * FROM usuario";
$result = mysqli_query($connection, $sql);

// Verificar si se obtuvieron resultados
if (!$result) {
    die("Error en la consulta: " . mysqli_error($connection));
}

// Mostrar la tabla de usuarios
if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Dirección</th>
                <th>País</th>
                <th>Rol ID</th>
                <th>Estado ID</th>
                <th>Último Inicio Sesión</th>
                <th>Acciones</th>
            </tr>";
    
    // Recorrer los resultados y mostrarlos en la tabla
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['apellidos']}</td>
                <td>{$row['telefono']}</td>
                <td>{$row['email']}</td>
                <td>{$row['direccion']}</td>
                <td>{$row['pais']}</td>
                <td>{$row['rol_id']}</td>
                <td>{$row['estado_id']}</td>
                <td>{$row['ultimo_inicio_sesion']}</td>
                <td>
                    <a href='editar.php?id={$row['id']}'>Editar</a> | 
                    <a href='borrar.php?id={$row['id']}'>Borrar</a>
                </td>
              </tr>";
    }
    
    echo "</table>";
} else {
    echo "No hay usuarios registrados.";
}

// Cerrar la conexión
mysqli_close($connection);
?>
