<?php
// Conexión a la base de datos
include 'funciones.php'; // Asegúrate de que funciones.php contiene la función getConnection y el correo del administrador


// Incluir PHPMailer
require '../ProyectoWeb1/PHPMailer-master/src/Exception.php';
require '../ProyectoWeb1/PHPMailer-master/src/PHPMailer.php';
require '../ProyectoWeb1/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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
    $correoAdministrador = "danielapereiraomier@gmail.com"; // Cambia esto al correo del administrador
    $subject = "Notificación: Árboles desactualizados";
    $message = "Los siguientes árboles no han sido actualizados desde hace 1 mes:\n";
    foreach ($arbolesDesactualizados as $arbol_id) {
        $message .= "• Árbol ID: $arbol_id\n";
    }

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'danielapereiraomier@gmail.com'; // Tu correo de Gmail
        $mail->Password = 'nojt jqvx paxa kdoz'; // Tu contraseña de Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remitente y destinatario
        $mail->setFrom('no-reply@example.com', 'Sistema de Notificaciones');
        $mail->addAddress($correoAdministrador);

        // Contenido del correo
        $mail->isHTML(false); // Configura el correo como texto sin formato
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Enviar el correo
        $mail->send();
        echo 'Correo enviado exitosamente.';
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }
}

// Ejecuta la función de verificación
verificarArbolesDesactualizados();
?>
