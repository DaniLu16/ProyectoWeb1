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
    // Limpiar los datos de entrada
    $nombreComercial = trim($_POST['nombre_comercial']);
    $nombreCientifico = trim($_POST['nombre_cientifico']);
    $imagen = $_FILES['imagen'];

    // Validar que los campos no estén vacíos
    if (empty($nombreComercial) || empty($nombreCientifico) || $imagen['error'] !== UPLOAD_ERR_OK) {
        $mensaje = "Por favor, complete todos los campos y suba una imagen válida.";
        $tipoMensaje = 'error';
    } else {
        // Llamar a la función de registro
        if (registrarArbol($nombreComercial, $nombreCientifico, $imagen)) {
            // Redirigir a la misma página con mensaje de éxito
            header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']) . "?msg=Árbol registrado con éxito");
            exit();
        } else {
            $mensaje = "Error al registrar el árbol. Por favor, inténtelo de nuevo.";
            $tipoMensaje = 'error';
        }
    }
}
?>

<div class="form-container">
    <h1 class="text-center">Registrar Árbol</h1>

    <!-- Mostrar mensaje si está presente -->
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?= $tipoMensaje === 'success' ? 'success' : 'danger' ?>" role="alert">
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

        <div class="form-group">
            <label for="imagen">Imagen del Árbol:</label>
            <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" class="btn">Registrar Árbol</button>
    </form>
</div> <!-- Cierre del contenedor del formulario -->
