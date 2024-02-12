<?php
echo "Inicio modelo"."<br>";

function validarUsuario($email, $password) {
    global $conexion;

    try {
        $query = $conexion->prepare("SELECT idcliente, nombre, apellido, email FROM rclientes WHERE email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        $cliente = $query->fetch(PDO::FETCH_ASSOC);

        if ($cliente && $password == $cliente['idcliente']) {
            return $cliente;
        } else {
            return false;
        }
    } catch (PDOException $ex) {
        echo "Error al validar usuario: " . $ex->getMessage();
        return false;
    }
}

echo "Fin modelo"."<br>";
?>
