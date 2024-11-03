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

// Manejar la solicitud de eliminación
if (eliminarArbol($id)) {
    header("Location: adm_trees.php?msg=" . urlencode("Árbol eliminado exitosamente."));
    exit();
} else {
    echo "Error al eliminar el árbol.";
}
?>
