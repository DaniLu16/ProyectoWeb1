<?php 
include('../funciones.php');
require('../includes/header_us.php');

// Obtener la conexión a la base de datos
$connection = getConnection();

// Consulta SQL para obtener todos los usuarios
$sql = "SELECT * FROM amigos";
$result = mysqli_query($connection, $sql);

// Verificar si se obtuvieron resultados
if (!$result) {
    die("Error en la consulta: " . mysqli_error($connection));
}
?>

<div class="container mt-5">
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
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
                <th>Último Inicio de Sesión</th>
                <th>Acciones</th> <!-- Nueva columna para acciones -->
            </tr>
        </thead>
        <tbody>
            <?php while ($usuario = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['direccion']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['pais']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['rol_id']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['estado_id']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['ultimo_inicio_sesion']); ?></td>
                    <td style="white-space: nowrap;"> <!-- Evita que los botones se envuelvan en varias líneas -->
    <!-- Botón de editar -->
    <a href="update.php?id=<?php echo htmlspecialchars($usuario['id']); ?>" class="action-btn edit-btn">Editar</a>
    
    <!-- Botón de eliminar -->
    <a href="delete.php?id=<?php echo htmlspecialchars($usuario['id']); ?>" class="action-btn delete-btn" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</a>
</td>

                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php 
mysqli_close($connection);

?>