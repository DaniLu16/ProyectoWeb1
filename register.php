<?php
session_start(); // Iniciar la sesión
require('funciones.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $pais = $_POST['pais'];
    $password = $_POST['password'];

    // Llamar a la función para registrar el usuario
    registrarUsuario($nombre, $apellidos, $telefono, $email, $direccion, $pais, $password);

    // Establecer un mensaje de registro exitoso en la sesión
    $_SESSION['registro_exitoso'] = "¡Registro exitoso! Ahora puedes iniciar sesión.";

    // Redirigir a la página de inicio
    header("Location: index.php");
    exit();
}
?>
