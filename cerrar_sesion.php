<?php
session_start();
session_destroy();
header("Location: comlogincli.php");
exit;
?>
