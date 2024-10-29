<?php  
require('../includes/header_us.php'); 
include('../funciones.php');

// Obtener la conexión a la base de datos
$connection = getConnection();


  // Consulta para obtener todos los árboles, incluyendo la imagen
  $query = "SELECT id, nombre_comercial, nombre_cientifico, imagen FROM arboles";
  $result = mysqli_query($connection, $query);
// Verificar si se obtuvieron resultados
if (!$result) {
    die("Error en la consulta: " . mysqli_error($connection));
}
?>

<div class="container mt-5">

    <!-- Mostrar mensaje si está presente en la URL -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

    <div class="form-wrapper">

        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre Comercial</th>
                    <th>Nombre Científico</th>
                    <th>Imagen</th>
                    <th>Acciones</th> <!-- Nueva columna para acciones -->
                </tr>
            </thead>
            <tbody>
                <?php while ($arbol = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($arbol['id']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['nombre_comercial']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['nombre_cientifico']); ?></td>
                        <td>
                        <img src="../arboles/<?php echo htmlspecialchars($arbol['imagen']); ?>" alt="Imagen del árbol" style="width: 100px; height: auto;">
                        
                        </td>
                        <td style="white-space: nowrap;"> <!-- Evita que los botones se envuelvan en varias líneas -->
                            <a href="#" class="action-btn edit-btn" 
                               onclick="loadContent('editar_arbol.php?id=<?php echo urlencode($arbol['id']); ?>'); return false;">
                               Editar
                            </a>
                            <a href="#" class="action-btn delete-btn" 
                               onclick="loadContent('eliminar_arbol.php?id=<?php echo urlencode($arbol['id']); ?>'); return false;">
                               Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
// Cerrar la conexión
mysqli_close($connection);
?>
