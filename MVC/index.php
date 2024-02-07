<?php
require_once 'db/connection.php';
require_once 'controllers/LoginController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    procesarInicioSesion($conn);
} else {
    mostrarFormulario();
}

// Cerrar la conexión PDO
$conn = null;
?>
