<?php
require('funciones.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $pais = $_POST['pais'];
    $password = $_POST['password'];

    // Llama a la función de registro
    registrarUsuario($nombre, $apellidos, $telefono, $email, $direccion, $pais, $password);

    // Redirigir a index.php después de un registro exitoso
    header("Location: index.php");
    exit(); // Asegúrate de salir después de la redirección
}
?>
