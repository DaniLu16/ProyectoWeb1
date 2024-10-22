<?php
session_start();
include('funciones.php');
require('includes/header.php');
$error_msg = isset($_GET['error']) ? $_GET['error'] : '';
?>


<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar los datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Llamar a la función para validar el login
    $rol_id = validarLogin($email, $password);

    if ($rol_id !== false) {
        // Redirigir al usuario según su rol
        if ($rol_id == 1) {
            header("Location: admin.php"); // Dashboard del admin
        } else {
            header("Location: amigo.php"); // Dashboard de amigos
        }
        exit;
    } else {
        // Redirigir con mensaje de error
        header("Location: index.php?error=Credenciales incorrectas");
        exit;
    }
} else {
    // Si no se accede mediante POST, redirige a la página principal
    header("Location: index.php");
    exit;
}

