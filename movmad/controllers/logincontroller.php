<?php
echo "Inicio controller"."<br>";

require_once("../db/db.php"); // Incluir el archivo db.php para obtener la conexión a la base de datos.
require_once("../models/login_model.php");

if(isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $cliente = validarUsuario($email, $password);

    if($cliente) {
        session_start();
        $_SESSION['cliente'] = $cliente;
        header('Location: ../views/movwelcome.php');
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
        header('Location: ../views/movlogin.php?error='.$error);
        exit();
    }
} else {
    header('Location: ../views/movlogin.php');
    exit();
}

echo "Fin controller"."<br>";
?>
