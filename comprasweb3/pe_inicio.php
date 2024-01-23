<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: pe_login.php");
    exit;
}



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio</title>
</head>
<body>
    <h2>Bienvenido a la Página de Inicio</h2>

    <!-- Botón para cerrar sesión -->
    <form method="post" action="cerrarsesion.php">
        <input type="submit" name="cerrarSesion" value="Cerrar Sesión">
    </form>
    
    <!-- Botón para realizar pedidos -->
    <form action="pe_altaped.php" method="post">
        <input type="submit" value="Hacer Pedidos">
    </form>

    <!-- Aquí puedes agregar más contenido o enlaces a otras opciones -->
</body>
</html>
