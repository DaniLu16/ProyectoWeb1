<?php
require('../includes/header_us.php'); 
include('../funciones.php');

// Opciones para los combos
$opcionesComercial = obtenerOpciones('nombre_comercial');
$opcionesCientifico = obtenerOpciones('nombre_cientifico');

// Lógica de registro cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreComercial = $_POST['nombre_comercial'];
    $nombreCientifico = $_POST['nombre_cientifico'];

    // Llamar a la función de registro
    if (registrarArbol($nombreComercial, $nombreCientifico)) {
        echo "<p>Árbol registrado con éxito.</p>";
    } else {
        echo "<p>Error al registrar el árbol. Por favor, inténtelo de nuevo.</p>";
    }
}
?>

<div class="form-wrapper"> <!-- Contenedor para el formulario -->
    <h1 class="form-title">Registrar Árbol</h1>
    <form action="registrar_arbol.php" method="POST">
        <div class="form-group">
            <label for="nombre_comercial">Nombre Comercial:</label>
            <select name="nombre_comercial" id="nombre_comercial" class="form-control" required>
                <option value="">Seleccione un nombre comercial</option>
                <?php foreach ($opcionesComercial as $opcion) : ?>
                    <option value="<?= htmlspecialchars($opcion) ?>"><?= htmlspecialchars($opcion) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="nombre_cientifico">Nombre Científico:</label>
            <select name="nombre_cientifico" id="nombre_cientifico" class="form-control" required>
                <option value="">Seleccione un nombre científico</option>
                <?php foreach ($opcionesCientifico as $opcion) : ?>
                    <option value="<?= htmlspecialchars($opcion) ?>"><?= htmlspecialchars($opcion) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn">Registrar Árbol</button>
    </form>
</div> <!-- Cierre del contenedor del formulario -->
