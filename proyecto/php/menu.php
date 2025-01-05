<?php

include 'seguridad.php';
include 'utilidad.php';

// Verificar si el usuario ha iniciado sesión
if (!Security::isLogged()) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </header>
    <section class="menu-container">
        <h2>Menú Principal</h2>
        <div class="menu-buttons">
            <button onclick="window.location.href='pacientes.php'">Gestión de Pacientes</button>
            <button onclick="window.location.href='citas.php'">Gestión de Citas</button>
        </div>
    </section>
</body>

</html>
