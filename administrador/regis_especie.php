<?php 
require('../includes/header_us.php'); 
include('../funciones.php');

// Opciones para los combos
$opcionesComercial = obtenerOpciones('nombre_comercial');
$opcionesCientifico = obtenerOpciones('nombre_cientifico');

// Inicializar variables para el mensaje
$mensaje = '';
$tipoMensaje = ''; // Variable para determinar si es éxito o error

// Mostrar el mensaje de éxito después de la redirección
if (isset($_GET['msg'])) {
    $mensaje = htmlspecialchars($_GET['msg']);
    $tipoMensaje = 'success';
}

// Lógica de registro cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Limpiar los datos de entradasssss
    $nombreComercial = trim($_POST['nombre_comercial']);
    $nombreCientifico = trim($_POST['nombre_cientifico']);

    // Llamar a la función de registro
    if (registrarEspecie($nombreComercial, $nombreCientifico)) {
        // Redirigir con un mensaje de éxito
        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=Especie registrada exitosamente!");
        exit();
    } else {
        $mensaje = "Error al registrar la especie.";
        $tipoMensaje = 'danger';
    }
}
?>

<div class="form-container">
    <h1 class="text-center">Registrar Especie</h1>

    <!-- Mostrar mensaje si está presente -->
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?= $tipoMensaje ?>" role="alert">
            <?php echo $mensaje; ?>
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

        <button type="submit" class="btn">Registrar Especie</button>
    </form>
</div> <!-- Cierre del contenedor del formulario -->