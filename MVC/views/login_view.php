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
    if (isset($mensajeError)) {
        echo '<p style="color: red;">' . $mensajeError . '</p>';
    }
    ?>

    <form method="post" action="index.php">
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
