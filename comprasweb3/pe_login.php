<?php

// Verificamos si el usuario ya está logueado, en ese caso redirigimos a la página de inicio
if (isset($_SESSION['usuario'])) {
    header("Location: pe_inicio.php");
    exit;
}

// Verificamos si se ha enviado el formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conectamos a la base de datos
    $conn = new mysqli("localhost", "root", "rootroot", "pedidos");

    // Verificamos la conexión a la base de datos
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtenemos los datos del formulario
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Consulta SQL para verificar el usuario y contraseña
    $sql = "SELECT * FROM customers WHERE CustomerNumber = '$usuario' AND ContactLastName = '$password'";
    $result = $conn->query($sql);

    // Verificamos si se encontró un usuario con esos datos
    if ($result->num_rows > 0) {
        session_start();
        // Usuario y contraseña son válidos, iniciamos sesión
        $_SESSION['usuario'] = $usuario;
        header("Location: pe_inicio.php");
        exit;
    } else {
        // Usuario o contraseña incorrectos, mostramos un mensaje de error
        $mensajeError = "Usuario o contraseña incorrectos.";
    }

    // Cerramos la conexión a la base de datos
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>

    <?php
    // Mostramos el mensaje de error si existe
    if (isset($mensajeError)) {
        echo '<p style="color: red;">' . $mensajeError . '</p>';
    }
    ?>

    <!-- Formulario de login -->
    <form method="post" action="pe_login.php">
        <label for="usuario">Usuario (CustomerNumber):</label>
        <input type="text" id="usuario" name="usuario" required>

        <br>

        <label for="password">Contraseña (ContactLastName):</label>
        <input type="password" id="password" name="password" required>

        <br>

        <input type="submit" value="Iniciar Sesión">
    </form>
</body>
</html>

