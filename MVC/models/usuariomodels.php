<?php

function verificarCredenciales($usuario, $password, $conn) {
    $sql = "SELECT * FROM customers WHERE CustomerNumber = :usuario AND ContactLastName = :password";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    return $stmt->rowCount() > 0;
}

?>
