<body class="signup-background2">
<?php
include('../funciones.php');
require('includes/header_admin.php');
// Inicializar la variable de error
$error = '';

// Verificar si se ha enviado el formulario con el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombreComercial = $_POST['nombre_comercial'];
    $nombreCientifico = $_POST['nombre_cientifico'];
    $ubicacion = $_POST['ubicacion'];
    $precio = $_POST['precio'];
    $estado = $_POST['estado'];
    $file = $_FILES['imagen'];

    // Llamar a la función para editar el árbol
    $resultado = editarArbol($id, $nombreComercial, $nombreCientifico, $ubicacion, $precio, $estado, $file);

    if ($resultado['success']) {
        header("Location: adm_trees.php?msg=" . urlencode($resultado['message']));
        exit;
    } else {
        $error = "Error al actualizar el árbol: " . $resultado['message'];
    }
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del árbol desde la base de datos
    $connection = getConnection();
    if (!$connection) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    $query = "
        SELECT ad.*, e.nombre_comercial, e.nombre_cientifico
        FROM arboles_dispo AS ad
        JOIN especies AS e ON ad.especie = e.id
        WHERE ad.id = ?
    ";

    $stmt = mysqli_prepare($connection, $query);
    if (!$stmt) {
        die("Error al preparar la consulta: " . mysqli_error($connection));
    }

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
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="administrador/eddit_tree.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo isset($arbol['id']) ? htmlspecialchars($arbol['id']) : ''; ?>">

        <div class="form-group">
            <label for="nombre_comercial">Nombre Comercial:</label>
            <input type="text" name="nombre_comercial" value="<?php echo isset($arbol['nombre_comercial']) ? htmlspecialchars($arbol['nombre_comercial']) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="nombre_cientifico">Nombre Científico:</label>
            <input type="text" name="nombre_cientifico" value="<?php echo isset($arbol['nombre_cientifico']) ? htmlspecialchars($arbol['nombre_cientifico']) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="ubicacion">Ubicación:</label>
            <input type="text" name="ubicacion" value="<?php echo isset($arbol['ubicacion']) ? htmlspecialchars($arbol['ubicacion']) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" name="precio" step="0.01" value="<?php echo isset($arbol['precio']) ? htmlspecialchars($arbol['precio']) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="estado">Estado:</label>
            <select name="estado" required>
                <option value="1" <?php echo isset($arbol['estado']) && $arbol['estado'] == 1 ? 'selected' : ''; ?>>Disponible</option>
                <option value="0" <?php echo isset($arbol['estado']) && $arbol['estado'] == 0 ? 'selected' : ''; ?>>No Disponible</option>
            </select>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen:</label>
            <input type="file" name="imagen">
            <?php if (isset($arbol['imagen']) && !empty($arbol['imagen'])): ?>
                <p>Imagen actual: <img src="../arboles/<?php echo htmlspecialchars($arbol['imagen']); ?>" alt="Imagen del árbol" style="width: 100px; height: auto;"></p>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>
</body>