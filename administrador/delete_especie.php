<?php
include('../funciones.php');



$response = ["success" => false, "message" => ""];

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Llamar a la función para eliminar la especie
    $response = eliminarEspecie($id);
} else {
    $response["message"] = "ID no especificado.";
}

// Enviar respuesta JSON
echo $response;

?>
