<?php
// Verificar si se ha enviado el formulario de cierre de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerrarSesion'])) {
    // Destruir la sesión
    $_SESSION = array();
     
    session_unset();
    session_destroy();
    
    
    setcookie('PHPSESSID', '', time() - 3600, '/');
   // Redirigir al formulario de login
    header("Location: pe_login.php");
    exit;
}
?>
