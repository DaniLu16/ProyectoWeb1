<?php
include('../funciones.php');

// Obtener la conexión a la base de datos
$connection = getConnection();

// Verificar si se proporcionó un ID válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de usuario no proporcionado.");
}

$id = intval($_GET['id']); // Sanitizar el ID

// Eliminar usuario de la base de datos
$sql = "DELETE FROM amigos WHERE id = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: lista_usuarios.php?msg=Usuario eliminado correctamente');
    exit;
} else {
    echo "Error al eliminar: " . mysqli_error($connection);
}

mysqli_close($connection);
?>
