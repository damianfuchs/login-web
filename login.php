<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_valido = 'fcytuader';
    $contrasena_valida = 'programacionavanzada';

    $usuario_ingresado = $_POST['usuario'];
    $contrasena_ingresada = $_POST['contraseña'];

    if ($usuario_ingresado === $usuario_valido && $contrasena_ingresada === $contrasena_valida) {
        $_SESSION['mensaje_exito'] = 'Inicio de sesión exitoso.';
    } else {
        $_SESSION['mensaje_error'] = 'Credenciales incorrectas. Inténtalo de nuevo.';
    }
}

header('Location: inicio.php');
?>