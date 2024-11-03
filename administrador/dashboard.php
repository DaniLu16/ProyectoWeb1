<?php
session_start();
require('../includes/header_admin.php');
include('../funciones.php');

// Obtener la cantidad de amigos registrados usando la función contarAmigosRegistrados
$cantidad_amigos = contarAmigosRegistrados();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Amigos Registrados</title>
</head>
<body class="signup-background2">

    <h2>Estadísticas</h2>
    
    <!-- Mostrar la cantidad de amigos registrados -->
    <p>Cantidad de amigos registrados: <?php echo is_numeric($cantidad_amigos) ? $cantidad_amigos : $cantidad_amigos; ?></p>

</body>
</html>
