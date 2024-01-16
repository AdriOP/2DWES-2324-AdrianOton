<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Clientes</title>
</head>
<body>
    <h2>Registro de Clientes</h2>
    <form action="procesar_comregcli.php" method="post">
        <label for="nif">NIF del Cliente:</label>
        <input type="text" name="nif" required>
        <br>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>
        <br>

        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" required>
        <br>

        <label for="cp">Código Postal:</label>
        <input type="text" name="cp" required>
        <br>

        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" required>
        <br>

        <label for="ciudad">Ciudad:</label>
        <input type="text" name="ciudad" required>
        <br>

        <button type="submit">Registrar Cliente</button>
        <a href="comlogincli.php"><button type="button">Ya tengo una cuenta, Iniciar Sesión</button></a>
    </form>
</body>
</html>
