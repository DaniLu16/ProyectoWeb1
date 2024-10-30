<?php
include('../funciones.php');
require('../includes/header_us.php');

// Inicializar la variable de error
$error = '';

// Verificar si se ha enviado el formulario con el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombreComercial = $_POST['nombre_comercial'];
    $nombreCientifico = $_POST['nombre_cientifico'];
    $file = $_FILES['imagen'];

    // Llamar a la función para editar el árbol
    $resultado = editarArbol($id, $nombreComercial, $nombreCientifico, $file);

    if ($resultado['success']) {
        header("Location: adm_trees.php?msg=" . urlencode($resultado['message']));
        exit;
    } else {
        $error = $resultado['message'];
    }
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del árbol desde la base de datos
    $connection = getConnection();
    $query = "SELECT * FROM arboles WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Verificar si se encontró el árbol
    if ($result && mysqli_num_rows($result) > 0) {
        $arbol = mysqli_fetch_assoc($result);
    } else {
        echo "Árbol no encontrado.";
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
} else {
    echo "ID no especificado.";
    exit;
}
?>

<div class="form-container">
    <h1 class="text-center">Editar Árbol</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="administrador/eddit_tree.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?php echo htmlspecialchars($arbol['id']); ?>">

        <div class="form-row">
            <div class="form-group">
                <label for="nombre_comercial">Nombre Comercial:</label>
                <input type="text" name="nombre_comercial" value="<?php echo htmlspecialchars($arbol['nombre_comercial']); ?>" required>
            </div>
            <div class="form-group">
                <label for="nombre_cientifico">Nombre Científico:</label>
                <input type="text" name="nombre_cientifico" value="<?php echo htmlspecialchars($arbol['nombre_cientifico']); ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="imagen">Imagen:</label>
                <input type="file" name="imagen">
                <?php if (!empty($arbol['imagen'])): ?>
                    <p>Imagen actual: <img src="../arboles/<?php echo htmlspecialchars($arbol['imagen']); ?>" alt="Imagen del árbol" style="width: 100px; height: auto;"></p>
                <?php endif; ?>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>
