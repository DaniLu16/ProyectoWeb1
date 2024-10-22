

<?php
session_start();
require('funciones.php');


// Captura del mensaje de error (si existe)
$error_msg = isset($_GET['error']) ? $_GET['error'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Llama a la función de inicio de sesión
    loginUsuario($email, $password);
}
?>