<?php
session_start();

// sesion iniciada o no
if (!isset($_SESSION['usuario'])) {
    header("Location: comlogincli.php");
   
    session_abort();
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra de Productos</title>
</head>
<body>
    <h2>Compra de Productos</h2>
    
    
    <form action="cerrar_sesion.php" method="post">
        <input type="submit" value="Cerrar Sesión">
    </form>
    

    <form method="post" action="procesar_comprocli.php">
        <label for="producto">Seleccionar Producto:</label>
        <select id="producto" name="producto" required>
            <?php
            // products bdds
            try {
                $conn = new PDO("mysql:host=localhost;dbname=COMPRASWEB", "root", "rootroot");
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("SELECT ID_PRODUCTO, NOMBRE FROM PRODUCTO");
                $stmt->execute();
                $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($productos as $producto) {
                    echo '<option value="' . $producto['ID_PRODUCTO'] . '">' . $producto['NOMBRE'] . '</option>';
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            } finally {
                $conn = null;
            }
            ?>
        </select>

        <br>

        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" min="1" required>

        <br>

        <input type="submit" value="Agregar al Carrito">
    </form>
</body>
</html>
