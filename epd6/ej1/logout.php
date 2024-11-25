<?php
session_start();

$_SESSION = array();

if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}
if (isset($_COOKIE['ultimoUsuario'])) {

    setcookie("ultimoUsuario", "", time() - 24*3600, "/path", "localhost"); // Si la cookie está configurada en una ruta específica
}

header('Location: login.php');
exit;
