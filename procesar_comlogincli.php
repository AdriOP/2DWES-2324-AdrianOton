<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuarioCliente = $_POST["usuario"];
    $claveCliente = $_POST["clave"];

    try {
        $conn = new PDO("mysql:host=localhost;dbname=COMPRASWEB", "root", "rootroot");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM CLIENTE WHERE usuario = :usuario AND contrasena = :contrasena");
        $stmt->bindParam(':usuario', $usuarioCliente);
        $stmt->bindParam(':contrasena', $claveCliente);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            //contraeña correcta
            $_SESSION['usuario'] = $usuarioCliente;
            header("Location: comprocli.php");
            exit;
        } else {
            // contraseña incorrecta
            $_SESSION['error_message'] = "Usuario o contraseña incorrectos";
            header("Location: comlogincli.php");
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conn = null;
    }
} else {
    
    header("Location: comlogincli.php");
    exit;
}
?>

