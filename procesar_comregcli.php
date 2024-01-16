<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
   

    //formulario
    $nifCliente = strtoupper($_POST["nif"]); 
    $nombreCliente = $_POST["nombre"];
    $apellidoCliente = $_POST["apellido"];
    $cpCliente = $_POST["cp"];
    $direccionCliente = $_POST["direccion"];
    $ciudadCliente = $_POST["ciudad"];

    try {
        //  bbd
        $conn = new PDO("mysql:host=localhost;dbname=COMPRASWEB", "root", "rootroot");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Nifclientes no se repitan
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM CLIENTE WHERE NIF = :nif");
        $stmt->bindParam(':nif', $nifCliente);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] > 0) {
            echo "Error: Ya existe un cliente con el mismo NIF.";
            exit;
        }

        //DatosLogin
        $usuarioCliente = strtolower(str_replace(' ', '', $nombreCliente));
        $claveCliente = strrev($apellidoCliente);

        // Guardar datos en la sesión
        $_SESSION['usuario'] = $usuarioCliente;
        $_SESSION['clave'] = $claveCliente;

        //nuevocliente
        $stmtInsertar = $conn->prepare("INSERT INTO CLIENTE (NIF, NOMBRE, APELLIDO, CP, DIRECCION, CIUDAD, usuario, contrasena) VALUES (:nif, :nombre, :apellido, :cp, :direccion, :ciudad, :usuario, :contrasena)");
        $stmtInsertar->bindParam(':nif', $nifCliente);
        $stmtInsertar->bindParam(':nombre', $nombreCliente);
        $stmtInsertar->bindParam(':apellido', $apellidoCliente);
        $stmtInsertar->bindParam(':cp', $cpCliente);
        $stmtInsertar->bindParam(':direccion', $direccionCliente);
        $stmtInsertar->bindParam(':ciudad', $ciudadCliente);
        $stmtInsertar->bindParam(':usuario', $usuarioCliente);
        $stmtInsertar->bindParam(':contrasena', $claveCliente);
        $stmtInsertar->execute();

    
        header("Location: comlogincli.php");
       
       
        exit;


    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conn = null;
    }
}
?>
