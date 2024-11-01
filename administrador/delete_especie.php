<?php
include('../funciones.php');



$response = ["success" => false, "message" => ""];

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Llamar a la función para eliminar el árbol
    if (eliminarEspecie($id)) {
        $response["success"] = true;
        $response["message"] = "Especie eliminado correctamente.";
    } else {
        $response["message"] = "Error al eliminar el especie.";
    }
} else {
    $response["message"] = "ID no especificado.";
}

// Cerrar la conexión si está abierta
if (isset($connection) && $connection) {
    mysqli_close($connection);
}

// Enviar respuesta JSON
echo json_encode($response);
?>
