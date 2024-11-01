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

    // Llamar a la función para editar la especie
    $resultado = editarEspecie($id, $nombreComercial, $nombreCientifico);

    if ($resultado['success']) {
        header("Location: adm_especie.php?msg=" . urlencode($resultado['message']));
        exit;
    } else {
        $error = $resultado['message'];
    }
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos de la especie desde la base de datos
    $connection = getConnection();
    $query = "SELECT * FROM especies WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Verificar si se encontró la especie
    if ($result && mysqli_num_rows($result) > 0) {
        $arbol = mysqli_fetch_assoc($result);
    } else {
        echo "Especie no encontrada.";
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
    <h1 class="text-center">Editar Especie</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="administrador/eddit_especie.php" method="POST" enctype="multipart/form-data">
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

        <button type="submit" class="btn btn-primary">Guardar Especie</button>
    </form>
</div>   

