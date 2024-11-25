<?php
session_start();

require 'utilidad.php'; // Clase Helper
// Limpiar todas las variables de sesión
$_SESSION = [];

// Si la sesión tiene cookies asociadas, eliminar las cookies de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), // Nombre de la cookie de la sesión
        '', // Borramos el valor
        time() - 42000, // Establecemos el tiempo en el pasado
        $params["path"], // Usamos el mismo path que la cookie de sesión
        $params["domain"], // Usamos el mismo dominio
        $params["secure"], // Si la cookie es segura
        $params["httponly"] // Si la cookie es solo accesible por HTTP
    );
}

// Destruir la sesión
session_destroy();

// Verificar si existe la cookie "ultimoUsuario" y eliminarla
if (isset($_COOKIE['ultimoUsuario'])) {
    setcookie("ultimoUsuario", "", time() - 3600, "/"); // Establecemos la cookie con tiempo negativo para eliminarla
}

// Ahora redirigimos a login.php
Helper::redirect('login.php');
exit;
