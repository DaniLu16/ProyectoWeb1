<?php  
include('../funciones.php');
include('../includes/header_admin.php');

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

<!-- Aplicamos la clase de fondo al cuerpo de la página -->
<body class="signup-background2">
    <div class="container mt-5">

        <!-- Mostrar mensaje si está presente en la URL -->
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

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
                    <th>Acciones</th>
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
                        <td style="white-space: nowrap;">
                            <a href="../administrador/eddit_user.php?id=<?php echo htmlspecialchars($usuario['id']); ?>" class="action-btn edit-btn">
                               Editar
                            </a>
                            <a href="/administrador/delete_user.php?id=<?php echo htmlspecialchars($usuario['id']); ?>" class="action-btn delete-btn">
                               Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

<?php 
mysqli_close($connection);
?>  
