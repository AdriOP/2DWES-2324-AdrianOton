<?php
session_start();

// nombre pa la cookie
$cookieNombre = 'carrito_' . $_SESSION['usuario'];

// carrito cookie usuario
$carrito = json_decode($_COOKIE[$cookieNombre], true) ?? array();


//Formulario
$productoSeleccionado = $_POST['producto'];
$cantidadSeleccionada = $_POST['cantidad'];



// Obtener el carrito de la sesión del usuario
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

$_SESSION['carrito'][] = array(
    'id_producto' => $productoSeleccionado,
    'cantidad' => $cantidadSeleccionada
);

// nombre cookie + usuario
$cookieNombre = 'carrito_' . $_SESSION['usuario'];

// guardar el carrito en la cookie 
setcookie($cookieNombre, json_encode($_SESSION['carrito']), time() + (7 * 24 * 60 * 60), '/');

// productos añadidos
header("Location: comprocli.php?mensaje=Producto añadido al carrito");
exit;
?>
