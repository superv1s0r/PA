<?php
require_once 'seguridad.php';
require_once 'utilidad.php';

session_start();

session_unset();
session_destroy();


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cerrar sesión</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <header>
        <h1>Gestión de salud</h1>
    </header>

    <section class="logout-container">
        <br>
        <p>Has cerrado sesión correctamente. Serás redirigido a la página de login en unos segundos...</p>
    </section>
    <script>
        setTimeout(function() {
            window.location.href = 'login.php';
        }, 3000);
    </script>
</body>

</html>
