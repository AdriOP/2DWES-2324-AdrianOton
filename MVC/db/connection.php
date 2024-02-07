<?php
$dsn = "mysql:host=localhost;dbname=pedidos";
$username = "root";
$password = "rootroot";

try {
    $conn = new PDO($dsn, $username, $password);
    // Configurar el modo de error de PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
