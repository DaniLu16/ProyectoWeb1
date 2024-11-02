<?php
require('../funciones.php');
session_start();

// Verificar si el usuario está autenticado y es un amigo (rol_id = 2)
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 2) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $arbol_id = intval($_GET['id']); // Sanitizar el ID
    $user_id = $_SESSION['user_id']; // ID del usuario logueado

    $connection = getConnection();

    // Actualizar el estado del árbol en la base de datos
    $query = "UPDATE arboles_dispo SET estado = 0 WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $arbol_id);
        mysqli_stmt_execute($stmt);

        // Registrar la compra en la tabla `compras`
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $insertCompra = "INSERT INTO compras (user_id, arbol_id) VALUES (?, ?)";
            $stmtCompra = mysqli_prepare($connection, $insertCompra);
            mysqli_stmt_bind_param($stmtCompra, 'ii', $user_id, $arbol_id);
            mysqli_stmt_execute($stmtCompra);
            mysqli_stmt_close($stmtCompra);

            $msg = "Árbol comprado con éxito.";
        } else {
            $msg = "Error al comprar el árbol. Puede que ya haya sido comprado.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $msg = "Error al preparar la consulta.";
    }

    mysqli_close($connection);

    // Redirigir de vuelta a la página de árboles disponibles con un mensaje
    header("Location: arboles_dispo.php?msg=" . urlencode($msg));
    exit();
} else {
    // Si no se proporciona un ID válido, redirigir con un mensaje de error
    header("Location: arboles_dispo.php?msg=" . urlencode("ID de árbol inválido."));
    exit();
}
