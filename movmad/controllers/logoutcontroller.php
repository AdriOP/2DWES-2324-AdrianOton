<?php
session_start();
session_destroy();
header('Location: ../views/movlogin.php');
exit();
?>
