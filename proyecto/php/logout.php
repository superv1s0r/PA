<?php
session_start();

session_unset();
session_destroy();

echo 'Sesion Cerrada';
header('Refresh: 2; URL = login.php');
?>
