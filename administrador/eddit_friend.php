<?php
require('../includes/header_admin.php');
include('../funciones.php');
session_start();

// Verifica si el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: login.php");
    exit();
}

// Obtener el ID del árbol
if (!isset($_GET['id'])) {
    header("Location: adm_trees.php?msg=" . urlencode("ID de árbol no proporcionado."));
    exit();
}

$id = $_GET['id'];
$arbol = obtenerDatosArbol($id); // Función para obtener los datos del árbol

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos enviados desde el formulario
    $tamano = $_POST['tamano'];
    $especie = $_POST['especie'];
    $ubicacion = $_POST['ubicacion'];
    $precio = $_POST['precio'];
    $estado = $_POST['estado'];
    $file = $_FILES['imagen'] ?? null; // Imagen opcional

    // Actualizar el árbol y registrar la actualización
    $resultado = editarArbol($id, $tamano, $especie, $ubicacion, $precio, $estado, $file);
    if ($resultado['success']) {
        registrarActualizacion($id, $tamano, $estado); // Registrar la actualización
        header("Location: adm_friends.php?msg=" . urlencode("Árbol actualizado exitosamente."));
        exit();
    } else {
        echo "Error al actualizar el árbol: " . htmlspecialchars($resultado['message']);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Árbol</title>
</head>
<body class="signup-background2">
<div class="container mt-5">
    <div class="form-wrapper">
    <h2>Editar Árbol</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="tamano">Tamaño:</label>
            <input type="text" name="tamano" class="form-control" value="<?php echo htmlspecialchars($arbol['tamano']); ?>" required>
        </div>
        <div class="form-group">
            <label for="especie">Especie:</label>
            <input type="text" name="especie" class="form-control" value="<?php echo htmlspecialchars($arbol['especie']); ?>" required>
        </div>
        <div class="form-group">
            <label for="ubicacion">Ubicación:</label>
            <input type="text" name="ubicacion" class="form-control" value="<?php echo htmlspecialchars($arbol['ubicacion']); ?>" required>
        </div>
        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" name="precio" step="0.01" value="<?php echo isset($arbol['precio']) ? htmlspecialchars($arbol['precio']) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="estado">Estado:</label>
            <select name="estado" class="form-control" required>
                <option value="Disponible" <?php if ($arbol['estado'] == 'Disponible') echo 'selected'; ?>>Disponible</option>
                <option value="Vendido" <?php if ($arbol['estado'] == 'Vendido') echo 'selected'; ?>>Vendido</option>
            </select>
        </div>
        <div class="form-group">
            <label for="imagen">Imagen (opcional):</label>
            <input type="file" name="imagen" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
    </div>
</div>
</body>
</html>
