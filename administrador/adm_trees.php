<?php  
require('../includes/header_us.php'); 
include('../funciones.php');

// Obtener la conexión a la base de datos
$connection = getConnection();

// Verificar la conexión
if (!$connection) {
    die("Error de conexión: " . mysqli_connect_error());
}



// Cargar árboles desde la base de datos
$result = cargarArboles(); // Llamar a la función que carga los árboles

?>
<div class="container mt-5">
<div class="form-wrapper"> 

    <!-- Mostrar mensaje si está presente en la URL -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

    

        <!-- Mostrar la tabla de árboles -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre Comercial</th>
                    <th>Nombre Científico</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($arbol = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($arbol['id']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['nombre_comercial']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['nombre_cientifico']); ?></td>
                        <td><img src="../images/arboles/?php echo htmlspecialchars($arbol['imagen']); ?>" alt="Imagen del árbol" style="width: 100px; height: auto;">

                        </td>
                        <td style="white-space: nowrap;"> <!-- Evita que los botones se envuelvan en varias líneas -->
    <a href="#" class="action-btn edit-btn" 
       onclick="loadContent('administrador/eddit_tree.php?id=<?php echo htmlspecialchars($arbol['id']); ?>'); return false;">
       Editar
    </a>
    <a href="#" class="action-btn delete-btn" 
       onclick="loadContent('administrador/delete_tree.php?id=<?php echo htmlspecialchars($arbol['id']); ?>'); return false;">
       Eliminar
    </a>
</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<?php 
// Cerrar la conexión
mysqli_close($connection);


?>
 