<?php
function obtenerDatosCliente() {
    global $conexion;

    try {
        $query = $conexion->prepare("SELECT nombre, apellido, idcliente FROM rclientes WHERE idcliente = :idcliente");
        $query->bindParam(':idcliente', $_SESSION['cliente']['idcliente']);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $ex) {
        echo "Error al obtener datos del cliente: " . $ex->getMessage();
        return null;
    }
}
?>
