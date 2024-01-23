<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: pe_login.php");
    exit;
}

// Obtener el nombre de usuario
$nombreUsuario = $_SESSION['usuario'];

// Conectar a la base de datos
$conn = new mysqli("localhost", "root", "rootroot", "pedidos");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar variables
$articuloSeleccionado = $cantidadSeleccionada = $numeroPago = $mensajeError = "";

// Función para obtener el MSRP de un producto
function obtenerMSRP($codigoProducto) {
    global $conn;
    $sql = "SELECT MSRP FROM products WHERE productCode = '$codigoProducto'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['MSRP'];
    }
    return 0;
}

// Procesar el formulario cuando se envíe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si los índices están definidos en $_POST antes de acceder a ellos
    $articuloSeleccionado = isset($_POST['articulo']) ? $_POST['articulo'] : '';
    $cantidadSeleccionada = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;
    $numeroPago = isset($_POST['numero_pago']) ? $_POST['numero_pago'] : '';

    // Verificar si se ha seleccionado un artículo antes de realizar la consulta
    if (!empty($articuloSeleccionado)) {
        // Obtener información del artículo seleccionado
        $sqlArticulo = "SELECT * FROM products WHERE productCode = '$articuloSeleccionado'";
        $resultArticulo = $conn->query($sqlArticulo);

        if ($resultArticulo->num_rows > 0) {
            $rowArticulo = $resultArticulo->fetch_assoc();
            $precioUnitario = obtenerMSRP($articuloSeleccionado);
            $totalPedido = $precioUnitario * $cantidadSeleccionada;

            // Realizar la inserción del pedido en la base de datos y actualizar el stock
            $fechaPedido = date("Y-m-d");
            $sqlInsertPedido = "INSERT INTO orders (orderDate, requiredDate, customerNumber) VALUES ('$fechaPedido', '$fechaPedido', '$nombreUsuario')";
            $conn->query($sqlInsertPedido);
            $pedidoID = $conn->insert_id;

            $sqlInsertDetallePedido = "INSERT INTO orderdetails (orderNumber, productCode, quantityOrdered, priceEach, orderLineNumber) 
                                      VALUES ('$pedidoID', '$articuloSeleccionado', '$cantidadSeleccionada', '$precioUnitario', 1)";
            $conn->query($sqlInsertDetallePedido);

            // Actualizar el stock del artículo
            $nuevoStock = $rowArticulo['quantityInStock'] - $cantidadSeleccionada;
            $sqlActualizarStock = "UPDATE products SET quantityInStock = '$nuevoStock' WHERE productCode = '$articuloSeleccionado'";
            $conn->query($sqlActualizarStock);

            // Añadir el producto al carrito
            $carrito = isset($_COOKIE['carrito_' . $nombreUsuario]) ? json_decode($_COOKIE['carrito_' . $nombreUsuario], true) : array();
            $carrito[] = array(
                'producto' => $articuloSeleccionado,
                'cantidad' => $cantidadSeleccionada,
                'precioUnitario' => $precioUnitario
            );
            setcookie('carrito_' . $nombreUsuario, json_encode($carrito), time() + (7 * 24 * 60 * 60), '/');
        } else {
            // Mostrar mensaje de error si no se encuentra el artículo
            $mensajeError = "El artículo seleccionado no existe.";
        }
    }
}

// Obtener los artículos disponibles con stock
$sqlArticulosDisponibles = "SELECT * FROM products WHERE quantityInStock > 0";
$resultArticulosDisponibles = $conn->query($sqlArticulosDisponibles);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Pedido</title>
</head>
<body>
    <h2>Realizar Pedido</h2>

    <?php
    // Mostrar mensaje de error solo después de enviar el formulario sin seleccionar un artículo
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($articuloSeleccionado)) {
        echo '<p style="color: red;">Por favor, selecciona un artículo.</p>';
    }
    ?>

    <form method="post" action="pe_altaped.php">
        <label for="articulo">Seleccionar Artículo:</label>
        <select id="articulo" name="articulo" required>
            <?php
            while ($row = $resultArticulosDisponibles->fetch_assoc()) {
                echo '<option value="' . $row['productCode'] . '">' . $row['productName'] . '</option>';
            }
            ?>
        </select>

        <br>

        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" min="1" required>

        <br>

        <label for="numero_pago">Número de Pago (Formato AA99999):</label>
        <input type="text" id="numero_pago" name="numero_pago" pattern="[A-Z]{2}[0-9]{5}" required>

        <br>

        <input type="submit" value="Realizar Pedido">

        <!-- Botón para agregar al carrito -->
        <input type="button" value="Agregar al Carrito" onclick="agregarAlCarrito()">
    </form>

    <br>

    <!-- Botón para cerrar sesión -->
    <form action="pe_cerrar_sesion.php" method="post">
        <input type="submit" value="Cerrar Sesión">
    </form>

    <br>

    <!-- Mostrar el carrito actual del usuario en una tabla -->
    <h3>Carrito Actual</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody id="tablaCarrito">
            <?php
            // Obtener el carrito actual del usuario desde la cookie (si existe)
            $carrito = isset($_COOKIE['carrito_' . $nombreUsuario]) ? json_decode($_COOKIE['carrito_' . $nombreUsuario], true) : array();

            $totalCarrito = 0;

            foreach ($carrito as $item) {
                $subtotal = $item['cantidad'] * $item['precioUnitario'];
                $totalCarrito += $subtotal;

                echo '<tr>';
                echo '<td>' . htmlspecialchars($item['producto']) . '</td>';
                echo '<td>' . htmlspecialchars($item['cantidad']) . '</td>';
                echo '<td>' . htmlspecialchars($item['precioUnitario']) . '</td>';
                echo '<td>' . $subtotal . '</td>';
                echo '</tr>';
            }
            ?>
            <tr>
                <td colspan="3" align="right"><strong>Total:</strong></td>
                <td><?php echo $totalCarrito; ?></td>
            </tr>
        </tbody>
    </table>

    <script>
        // Función para agregar al carrito sin enviar el formulario
        function agregarAlCarrito() {
            document.forms[0].submit();
        }
    </script>

</body>
</html>
