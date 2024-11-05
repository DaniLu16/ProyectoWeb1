<?php
require('../includes/header_admin.php'); 
include('../funciones.php');

// Cargar árboles disponibles
$result = cargarArbolesDisponibles();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Árboles Disponibles</title>
</head>
<body class="signup-background2">
<div class="container mt-5">
    

        <!-- Mostrar mensaje si está presente en la URL -->
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar la tabla de árboles disponibles -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>ID Especie</th> 
                    <th>Nombre Comercial</th>
                    <th>Nombre Científico</th>
                    <th>Ubicación</th>
                    <th>Estado</th>
                    <th>Precio</th>
                    <th>Tamaño</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($arbol = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($arbol['id']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['especie']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['nombre_comercial']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['nombre_cientifico']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['ubicacion']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['estado'] == 1 ? 'Disponible' : 'No Disponible'); ?></td>
                        <td><?php echo htmlspecialchars($arbol['precio']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['tamano']); ?></td>
                        <td>
                            <img src="../arboles/<?php echo htmlspecialchars($arbol['imagen']); ?>" alt="Imagen del árbol" style="width: 100px; height: auto;">
                        </td>
                        <td style="white-space: nowrap;">
                            <a href="../administrador/eddit_tree.php?id=<?php echo htmlspecialchars($arbol['id']); ?>" class="action-btn edit-btn">
                               Editar
                            </a>
                            <a href="../administrador/delete_tree.php?id=<?php echo htmlspecialchars($arbol['id']); ?>" class="action-btn delete-btn">
                               Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
  
</div>
</body>
</html>
