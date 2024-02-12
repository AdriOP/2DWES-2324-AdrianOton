<html>
<head>
    <title>Bienvenido a MovilMAD</title>
</head>
<body>
    <h1>Bienvenido a MovilMAD</h1>
    
    <?php
    // Mostrar los datos del cliente
    echo "<p>Nombre: " . $cliente['nombre'] . "</p>";
    echo "<p>Apellidos: " . $cliente['apellido'] . "</p>";
    echo "<p>ID Cliente: " . $cliente['idcliente'] . "</p>";
    ?>
    
    <ul>
        <li><a href="alquilar.php">Alquilar Vehículos</a></li>
        <li><a href="consultar.php">Consultar Alquileres</a></li>
        <li><a href="devolver.php">Devolver Vehículos</a></li>
    </ul>
    
    <a href="../controllers/logoutcontroller.php">Cerrar Sesión</a>
</body>
</html>
