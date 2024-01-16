<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
</head>
<body>
    <h2>Inicio de Sesión</h2>

    <?php
 
    if (isset($_SESSION['error_message'])) {
        echo '<p style="color: red;">' . $_SESSION['error_message'] . '</p>';
        unset($_SESSION['error_message']);  
    }
    ?>

    <form method="post" action="procesar_comlogincli.php">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>

        <br>

        <label for="clave">Contraseña:</label>
        <input type="password" id="clave" name="clave" required>

        <br>

        <input type="submit" value="Iniciar Sesión">
        <a href="comregcli.php"><button type="button">No tengo cuenta, REGISTRARSE</button></a>
    </form>
</body>
</html>
