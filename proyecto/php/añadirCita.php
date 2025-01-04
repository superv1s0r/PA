<?php

require_once '../crud/crudCitas.php';
require_once 'seguridad.php';
require_once 'utilidad.php';

$conn = Helper::getConn();
if (!Security::isLogged()) {
    Helper::redirect('login.php');
}

try {
    $citaCrud = new CitaCrud($conn);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id_paciente = filter_input(INPUT_POST, 'id_paciente', FILTER_VALIDATE_INT);
        $fecha_cita = filter_input(INPUT_POST, 'fecha_cita', FILTER_SANITIZE_STRING);
        $motivo = filter_input(INPUT_POST, 'motivo', FILTER_SANITIZE_STRING);
        $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);

        if ($id_paciente && $fecha_cita && $motivo && $estado) {
            $nuevaCita = $citaCrud->create([
                'id_paciente' => $id_paciente,
                'fecha_cita' => $fecha_cita,
                'motivo' => $motivo,
                'estado' => $estado,
            ]);
            echo "Cita añadida con éxito.";
        } else {
            throw new Exception("Datos no válidos. Por favor, verifica los campos.");
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Añadir Cita</title>
</head>

<body>
    <h1>Añadir Cita</h1>
    <form action="anadirCita.php" method="post">
        <label for="id_paciente">ID Paciente:</label>
        <input type="number" id="id_paciente" name="id_paciente" required>

        <label for="fecha_cita">Fecha Cita:</label>
        <input type="text" id="fecha_cita" name="fecha_cita" placeholder="YYYY-MM-DD" required>

        <label for="motivo">Motivo:</label>
        <input type="text" id="motivo" name="motivo" required>

        <label for="estado">Estado:</label>
        <select id="estado" name="estado" required>
            <option value="Pendiente">Pendiente</option>
            <option value="Completada">Completada</option>
            <option value="Cancelada">Cancelada</option>
        </select>

        <button type="submit">Guardar</button>
    </form>
</body>

</html>
