<?php
session_start();

$conn = Helper::getConn();

if (!isset($_SESSION['valid']) || $_SESSION['valid'] !== true) {
    $_SESSION['error'] = "Por favor, inicia sesión para continuar.";
    Helper::redirect("Location: login.php");
    exit();
}

?>
