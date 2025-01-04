<?php
include 'seguridad.php';
session_start();

$conn = Helper::getConn();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = "DELETE FROM usuario WHERE id = $id";
    $result = mysqli_query($conn, $query);

    $mensaje = $result ? "Paciente eliminado correctamente." : "Error al eliminar el paciente: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Paciente</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <section class="notification">
        <h2>Resultado de la operación</h2>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
        <a href="menu.php">Volver al menú</a>
    </section>
</body>
</html>
