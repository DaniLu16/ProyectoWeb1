<body class="signup-background2"> 
<?php
session_start();
require('../includes/header_admin.php');
include('../funciones.php');


// Obtener la cantidad de amigos registrados usando la función contarAmigosRegistrados
$cantidad_amigos = contarAmigosRegistrados();

// Obtener la cantidad de árboles disponibles usando la función contarArbolesDisponibles
$cantidad_arboles_disponibles = contarArbolesDisponibles();

$cantidad_arboles_vendidos = contarArbolesVendidos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Amigos Registrados</title>
    <link rel="stylesheet" href="../includes/style.css">
</head>

<body class="signup-background2">
    <div class="dashboard-main-container">
        <h2 class="dashboard-title">Estadísticas del Dashboard</h2>

        <div class="dashboard-container">
            <!-- Mostrar la cantidad de amigos registrados -->
            <div class="stat-card">
                <p class="stat-title">Amigos Registrados</p>
                <p class="stat-value"><?php echo is_numeric($cantidad_amigos) ? $cantidad_amigos : $cantidad_amigos; ?></p>
            </div>
            
            <!-- Mostrar la cantidad de árboles disponibles -->
            <div class="stat-card">
                <p class="stat-title">Árboles Disponibles</p>
                <p class="stat-value"><?php echo is_numeric($cantidad_arboles_disponibles) ? $cantidad_arboles_disponibles : $cantidad_arboles_disponibles; ?></p>
            </div>

            <!-- Mostrar la cantidad de árboles vendidos -->
            <div class="stat-card">
                <p class="stat-title">Árboles Vendidos</p>
                <p class="stat-value"><?php echo is_numeric($cantidad_arboles_vendidos) ? $cantidad_arboles_vendidos : $cantidad_arboles_vendidos; ?></p>
            </div>
        </div>
    </div>
</body>
</html>

