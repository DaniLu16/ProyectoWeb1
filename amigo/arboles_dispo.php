<body class="signup-background2">
<?php  

require('../includes/header_amigo.php'); 
include('../funciones.php');

// Cargar árboles desde la base de datos
$result = cargarArbolesDisponibles(); // Llamar a la función que carga los árboles

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
                    <th>Ubicación</th>
                    <th>Estado</th>
                    <th>Precio</th>
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
                        <td><?php echo htmlspecialchars($arbol['ubicacion']); ?></td>
                        <td><?php echo htmlspecialchars($arbol['estado'] == 1 ? 'Disponible' : 'No Disponible'); ?></td>
                        <td><?php echo htmlspecialchars($arbol['precio']); ?></td>
                        <td>
                            <img src="../arboles/<?php echo htmlspecialchars($arbol['imagen']); ?>" alt="Imagen de <?php echo htmlspecialchars($arbol['nombre_comercial']); ?>" style="width: 100px; height: auto;">
                        </td>
                        <td style="white-space: nowrap;">
                            <a href="comprar.php?id=<?php echo $arbol['id']; ?>" class="btn btn-success">Comprar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
?>
</body>
