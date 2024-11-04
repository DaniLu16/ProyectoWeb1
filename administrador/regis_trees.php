<?php 
require('../includes/header_admin.php'); 
include('../funciones.php');
?>

<body class="signup-background2">

<?php
// Opciones para las especies
$opcionesEspecie = obtenerOpcionesEspecies(); // Función que obtiene las especies de la tabla 'especies'

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
    $especieId = trim($_POST['especie']);
    $ubicacion = trim($_POST['ubicacion']);
    $precio = trim($_POST['precio']);
    $tamano = trim($_POST['tamano']);
    $imagen = $_FILES['imagen'];

    // Validar que los campos no estén vacíos
    if (empty($especieId) || empty($ubicacion) || empty($precio) || empty($tamano) || $imagen['error'] !== UPLOAD_ERR_OK) {
        $mensaje = "Por favor, complete todos los campos y suba una imagen válida.";
        $tipoMensaje = 'error';
    } else {
        // Llamar a la función de registro
        if (registrarArbol($especieId, $tamano, $ubicacion, $precio, $imagen)) {
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
            <label for="especie">Especie:</label>
            <select name="especie" id="especie" class="form-control" required>
                <option value="">Seleccione una especie</option>
                <?php foreach ($opcionesEspecie as $opcion): ?>
                    <option value="<?= htmlspecialchars($opcion['id']) ?>"><?= htmlspecialchars($opcion['nombre_comercial'] . " (" . $opcion['nombre_cientifico'] . ")") ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="ubicacion">Ubicación:</label>
            <input type="text" name="ubicacion" id="ubicacion" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" name="precio" id="precio" class="form-control" step="0.01" min="0" required>
        </div>
        
        <div class="form-group">
            <label for="tamano">Tamaño:</label>
            <input type="text" name="tamano" id="tamano" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen del Árbol:</label>
            <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" class="btn">Registrar Árbol</button>
    </form>
</div> <!-- Cierre del contenedor del formulario -->

</body>

