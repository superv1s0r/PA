<?php

require 'utilidad.php';
session_start();

if (!isset($_SESSION['valid']) || $_SESSION['valid'] !== true) {
    $_SESSION['error'] = "Por favor, inicia sesión para continuar.";
    Helper::redirect('login.php');
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $edad = filter_input(INPUT_POST, 'edad', FILTER_VALIDATE_INT);

    if ($nombre && $edad !== false) {
        $conn = Helper::getConn();

        $query = "INSERT INTO pacientes (nombre, edad) VALUES ($nombre, $edad)";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            Helper::redirect('login.php');
        } else {
            echo "Error al agregar el paciente: " . $conn->error;
        }

        $conn->close();
    } else {
        echo "Datos no válidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Añadir Paciente</title>
</head>

<body>
    <h1>Añadir Paciente</h1>
    <form action="anadirPaciente.php" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" required>

        <button type="submit">Guardar</button>
    </form>
</body>

</html>
