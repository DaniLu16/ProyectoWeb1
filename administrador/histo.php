<?php
require('../includes/header_admin.php');
include('../funciones.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$historial = obtenerHistorialArbol($id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Actualizaciones del Árbol</title>
</head>
<body class="signup-background2">
<div class="container mt-5">
    <h2>Historial de Actualizaciones</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Fecha de Actualización</th>
                <th>Tamaño</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($actualizacion = mysqli_fetch_assoc($historial)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($actualizacion['fecha_actualizacion']); ?></td>
                    <td><?php echo htmlspecialchars($actualizacion['tamano']); ?></td>
                    <td><?php echo htmlspecialchars($actualizacion['estado']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
