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
$articuloSeleccionado = $cantidadSeleccionada = $numeroPago = $mensajeError = $mensajeExito = "";

// Función para obtener el MSRP de un producto
function obtenerMSRP($codigoProducto) {
    global $conn;
    $sql = "SELECT MSRP FROM products WHERE productCode = '$codigoProducto'";
    $result = $conn->query($sql);
    return ($result->num_rows > 0) ? $result->fetch_assoc()['MSRP'] : 0;
}

// Función para añadir un producto al carrito
function agregarAlCarrito($articulo, $cantidad) {
    global $nombreUsuario, $conn;
    $precioUnitario = obtenerMSRP($articulo);
    $carrito = isset($_COOKIE['carrito_' . $nombreUsuario]) ? json_decode($_COOKIE['carrito_' . $nombreUsuario], true) : array();

    $carrito[] = array(
        'producto' => $articulo,
        'cantidad' => $cantidad,
        'precioUnitario' => $precioUnitario
    );

    setcookie('carrito_' . $nombreUsuario, json_encode($carrito), time() + (7 * 24 * 60 * 60), '/');
}

// Procesar el formulario cuando se envíe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si los índices están definidos en $_POST antes de acceder a ellos
    $articuloSeleccionado = $_POST['articulo'] ?? '';
    $cantidadSeleccionada = intval($_POST['cantidad'] ?? 0);
    $numeroPago = $_POST['numero_pago'] ?? '';

    // Verificar si se ha seleccionado un artículo antes de realizar la consulta
    if (!empty($articuloSeleccionado)) {
        // Obtener información del artículo seleccionado
        $sqlArticulo = "SELECT * FROM products WHERE productCode = '$articuloSeleccionado'";
        $resultArticulo = $conn->query($sqlArticulo);

        if ($resultArticulo->num_rows > 0) {
            // Añadir el producto al carrito
            agregarAlCarrito($articuloSeleccionado, $cantidadSeleccionada);

            // Recargar la página después de agregar al carrito
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            $mensajeError = "El artículo seleccionado no existe.";
        }
    } elseif (!empty($numeroPago)) {
        // Procesar el pago de todos los productos seleccionados en el carrito
        $carrito = isset($_COOKIE['carrito_' . $nombreUsuario]) ? json_decode($_COOKIE['carrito_' . $nombreUsuario], true) : array();
        if (!empty($carrito)) {
            try {
                // Obtener el último número de orden
                $stmtUltimaOrden = $conn->query("SELECT * FROM orders ORDER BY orderNumber DESC LIMIT 1");
                $ultimaOrden = $stmtUltimaOrden->fetch_assoc();

                // Obtener el siguiente número de orden
                $siguienteNumero = $ultimaOrden['orderNumber'] + 1;
                $estadoDefecto = 'Enviado';

                // Insertar en la tabla 'orders'
                $stmtInsertOrder = $conn->prepare("INSERT INTO orders (orderNumber, orderDate, requiredDate, shippedDate, CustomerNumber, status) VALUES (?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL, ?, ?)");
                $stmtInsertOrder->bind_param('iss', $siguienteNumero, $nombreUsuario, $estadoDefecto);
                $stmtInsertOrder->execute();

                // Iterar sobre los elementos del carrito
                foreach ($carrito as $item) {
                    $articuloSeleccionado = $item['producto'];
                    $cantidadSeleccionada = $item['cantidad'];

                    // Actualizar el stock del artículo
                    $sqlActualizarStock = "UPDATE products SET quantityInStock = quantityInStock - '$cantidadSeleccionada' WHERE productCode = '$articuloSeleccionado'";
                    $conn->query($sqlActualizarStock);
                }

                // Verificar si el número de pago ya está en uso
                $stmtChequeExistente = $conn->prepare("SELECT 1 FROM payments WHERE checkNumber = ?");
                $stmtChequeExistente->bind_param('s', $numeroPago);
                $stmtChequeExistente->execute();
                $chequeExistente = $stmtChequeExistente->fetch();
                $stmtChequeExistente->close();

                if ($chequeExistente) {
                    $mensajeError = "El número de pago ya está en uso. Por favor, seleccione otro.";
                } else {
                    // Insertar en la tabla 'payments'
                    $totalCarrito = array_sum(array_column($carrito, 'cantidad', 'precioUnitario'));
                    $stmtInsertPago = $conn->prepare("INSERT INTO payments (customerNumber, checkNumber, paymentDate, amount) VALUES (?, ?, CURRENT_TIMESTAMP, ?)");
                    $stmtInsertPago->bind_param('ssd', $nombreUsuario, $numeroPago, $totalCarrito);
                    $stmtInsertPago->execute();

                    // Limpiar el carrito después de realizar el pago
                    setcookie('carrito_' . $nombreUsuario, '', time() - 3600, '/');
                    $mensajeExito = "Pedido realizado con éxito. Número de Pedido: $siguienteNumero";
                    header("Location: pe_altaped.php");
                    exit;
                }
            } catch (Exception $e) {
                $mensajeError = "Error al procesar el pedido. Por favor, inténtelo de nuevo más tarde. Detalles del error: " . $e->getMessage();
            }
        } else {
            $mensajeError = "El carrito está vacío. Agregue productos antes de realizar el pedido.";
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
    // Mostrar mensaje de error o éxito
    if (!empty($mensajeError)) {
        echo '<p style="color: red;">' . $mensajeError . '</p>';
    } elseif (!empty($mensajeExito)) {
        echo '<p style="color: green;">' . $mensajeExito . '</p>';
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

        <input type="submit" value="Agregar al Carrito">
    </form>

    <br>

    <!-- Mostrar el carrito del usuario en una tabla -->
    <?php
        $carrito = isset($_COOKIE['carrito_' . $nombreUsuario]) ? json_decode($_COOKIE['carrito_' . $nombreUsuario], true) : array();
        if (!empty($carrito)) {
            echo '<h3>Carrito Actual</h3>';
            echo '<table border="1">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Producto</th>';
            echo '<th>Cantidad</th>';
            echo '<th>Precio Unitario</th>';
            echo '<th>Subtotal</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody id="tablaCarrito">';
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
            echo '<tr>';
            echo '<td colspan="3" align="right"><strong>Total:</strong></td>';
            echo '<td>' . $totalCarrito . '</td>';
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
        }
    ?>

    <br>

    <!-- Añadir la casilla para el número de pago y el botón de realizar pedido -->
    <form method="post" action="pe_altaped.php">
        <label for="numero_pago">Número de Pago (Formato AA99999):</label>
        <input type="text" id="numero_pago" name="numero_pago" pattern="[A-Z]{2}[0-9]{5}" required>

        <br>

        <input type="submit" value="Realizar Pedido">
    </form>

    <br>
    <form method="post" action="cerrarsesion.php">
        <input type="submit" name="cerrarSesion" value="Cerrar Sesión">
    </form>

</body>
</html>

