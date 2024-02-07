<?php

require_once '../models/UsuarioModel.php';

function mostrarFormulario() {
    include '../views/login_view.php';
}

function procesarInicioSesion($conn) {
    session_start();

    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $valido = verificarCredenciales($usuario, $password, $conn);

    if ($valido) {
        $_SESSION['usuario'] = $usuario;
        header("Location: pe_inicio.php");
        exit;
    } else {
        $mensajeError = "Usuario o contraseña incorrectos.";
        include '../views/login_view.php';
    }
}

?>
