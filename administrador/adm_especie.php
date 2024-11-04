<?php  
require('../includes/header_admin.php'); 
include('../funciones.php');

// Agregar la clase al body después de cargar el header
echo '<script>document.body.className += " signup-background2";</script>';

// Obtener la conexión a la base de datos
$connection = getConnection();

// Verificar la conexión
if (!$connection) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Cargar especies desde la base de datos
$result = cargarEspecie(); // Llamar a la función que carga las especies

?>
<div class="container mt-5">
    <div class="form-wrapper">
   

        <!-- Mostrar mensaje si está presente en la URL -->
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar la tabla de especies -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre Comercial</th>
                    <th>Nombre Científico</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($especie = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($especie['id']); ?></td>
                        <td><?php echo htmlspecialchars($especie['nombre_comercial']); ?></td>
                        <td><?php echo htmlspecialchars($especie['nombre_cientifico']); ?></td>
                        <td style="white-space: nowrap;"> <!-- Evita que los botones se envuelvan en varias líneas -->
                            <a href="../administrador/eddit_especie.php?id=<?php echo urlencode($especie['id']); ?>" 
                            class="action-btn edit-btn">
                            Editar
                            </a>
                            <a href="../administrador/delete_especie.php?id=<?php echo urlencode($especie['id']); ?>" 
                            class="action-btn delete-btn">
                            Eliminar
                            </a>
                        </td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<?php 
// Cerrar la conexión
mysqli_close($connection);
?>
<?