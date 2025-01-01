<?php

require 'utilidad.php';
session_start();

if (!isset($_SESSION['valid']) || $_SESSION['valid'] !== true) {
    $_SESSION['error'] = "Por favor, inicia sesión para continuar.";
    Helper::dirigir('login.php');
}

$conn = Helper::getConn();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $edad = filter_input(INPUT_POST, 'edad', FILTER_VALIDATE_INT);

    if ($id && $nombre && $edad !== false) {
        $query = "UPDATE pacientes SET nombre = $nombre, edad = $edad WHERE id = $id";
        $result = mysqli_query($conn, $query);
    } else {
        echo "Datos no válidos.";
    }
} elseif (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    $query = "SELECT * FROM pacientes WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $paciente = $result->fetch_assoc();

    if (!$paciente) {
        echo "Paciente no encontrado.";
        exit();
    }

    $stmt->close();
} else {
    Helper::dirigir('menu.php');
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Paciente</title>
</head>

<body>
    <h1>Editar Paciente</h1>
    <form action="editarPaciente.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($paciente['id']); ?>">

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($paciente['nombre']); ?>"
            required>

        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" value="<?php echo htmlspecialchars($paciente['edad']); ?>" required>

        <button type="submit">Actualizar</button>
    </form>
</body>

</html>
