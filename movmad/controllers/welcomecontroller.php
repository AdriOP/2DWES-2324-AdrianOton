<?php
// Llamada al modelo
require_once("../models/welcome_model.php");

// Obtener datos del cliente
$cliente = obtenerDatosCliente();

// Llamada a la vista
require_once("../views/movwelcome.php");
?>
