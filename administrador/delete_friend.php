<?php
require('../includes/header_admin.php');
include('../funciones.php');
session_start();

// Verifica si el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: login.php");
    exit();
}

// Obtener el ID del árbol a eliminar
if (!isset($_GET['id'])) {
    header("Location: adm_friends.php?msg=" . urlencode("ID de árbol no proporcionado."));
    exit();
}

$id = $_GET['id'];

// Eliminar el árbol
$resultado = eliminarArbol($id);

if ($resultado) {
    // Redirigir a la página de amigos después de eliminar
    header("Location: adm_friends.php?msg=" . urlencode("Árbol eliminado exitosamente."));
} else {
    // Manejo de error en caso de fallo en la eliminación
    header("Location: adm_friends.php?msg=" . urlencode("Error al eliminar el árbol."));
}
exit();
?>
