<?php
// Conexión a la base de datos
include 'funciones.php'; // Asegúrate de que funciones.php contiene la función getConnection y el correo del administrador

function verificarArbolesDesactualizados() {
    $connection = getConnection(); // Llama a la función para conectar a la base de datos
    $fechaLimite = date("Y-m-d H:i:s", strtotime("-1 month"));

    // Consulta para obtener arboles cuya última actualización fue hace más de 1 mes
    $query = "SELECT arbol_id FROM actualizaciones_arboles WHERE fecha_actualizacion < ?";
    $stmt = $connection->prepare($query); // Usa -> en lugar de >
    
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $connection->error);
    }

    $stmt->bind_param("s", $fechaLimite);
    $stmt->execute();
    $result = $stmt->get_result();

    $arbolesDesactualizados = [];
    while ($row = $result->fetch_assoc()) {
        $arbolesDesactualizados[] = $row['arbol_id'];
    }

    $stmt->close();
    $connection->close();

    // Enviar correo si hay árboles desactualizados
    if (count($arbolesDesactualizados) > 0) {
        enviarCorreoAdministrador($arbolesDesactualizados);
    }
}

function enviarCorreoAdministrador($arbolesDesactualizados) {
    $to = "erickaborge13@gmail.com"; // Cambia esto al correo del administrador
    $subject = "Notificación: Árboles desactualizados";
    $message = "Los siguientes árboles no han sido actualizados desde hace 1 mes:\n";
    foreach ($arbolesDesactualizados as $arbol_id) {
        $message .= "• Árbol ID: $arbol_id\n";
    }

    $headers = "From: no-reply@example.com"; // Configura un correo de origen válido
    mail($to, $subject, $message, $headers);
}

// Ejecuta la función de verificación
verificarArbolesDesactualizados();
?>
