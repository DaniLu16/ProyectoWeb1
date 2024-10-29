<?php
require('../includes/header_us.php'); 
include('../funciones.php');

// Opciones para los combos
$opcionesComercial = obtenerOpciones('nombre_comercial');
$opcionesCientifico = obtenerOpciones('nombre_cientifico');

// Inicializar variables para el mensaje
$mensaje = '';

// Lógica de registro cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreComercial = $_POST['nombre_comercial'];
    $nombreCientifico = $_POST['nombre_cientifico'];
    $imagen = $_FILES['imagen'];

    // Llamar a la función de registro
    if (registrarArbol($nombreComercial, $nombreCientifico, $imagen)) {
        $mensaje = "Árbol registrado con éxito.";
        
        // Redireccionar a la misma página para evitar el reenvío del formulario
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $mensaje = "Error al registrar el árbol. Por favor, inténtelo de nuevo.";
    }
}
?>

<div class="form-wrapper"> <!-- Contenedor para el formulario -->
 
    <h1 class="text-center">Registrar Árbol</h1>

    <!-- Mostrar mensaje si está presente -->
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombre_comercial">Nombre Comercial:</label>
            <select name="nombre_comercial" id="nombre_comercial" class="form-control" required>
                <option value="">Seleccione un nombre comercial</option>
                <?php foreach ($opcionesComercial as $opcion): ?>
                    <option value="<?= htmlspecialchars($opcion) ?>"><?= htmlspecialchars($opcion) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="nombre_cientifico">Nombre Científico:</label>
            <select name="nombre_cientifico" id="nombre_cientifico" class="form-control" required>
                <option value="">Seleccione un nombre científico</option>
                <?php foreach ($opcionesCientifico as $opcion): ?>
                    <option value="<?= htmlspecialchars($opcion) ?>"><?= htmlspecialchars($opcion) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen del Árbol:</label>
            <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" class="btn">Registrar Árbol</button>
    </form>
</div> <!-- Cierre del contenedor del formulario -->
