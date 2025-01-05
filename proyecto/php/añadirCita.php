<?php
require_once '../crud/crudCitas.php';
require_once 'seguridad.php';
require_once 'utilidad.php';

// Verificar si el usuario ha iniciado sesión
if (!Security::isLogged()) {
    Helper::redirect('login.php');
}

// Conexión a la base de datos
$conn = Helper::getConn();
$citaCrud = new CitaCrud($conn);

// Inicializar variables para mensajes
$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_paciente = filter_input(INPUT_POST, 'id_paciente', FILTER_VALIDATE_INT);
    $fecha_cita = filter_input(INPUT_POST, 'fecha_cita', FILTER_SANITIZE_STRING);
    $motivo = filter_input(INPUT_POST, 'motivo', FILTER_SANITIZE_STRING);
    $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);

    if ($id_paciente && $fecha_cita && $motivo && $estado) {
        try {
            $citaCrud->create([
                'id_paciente' => $id_paciente,
                'fecha_cita' => $fecha_cita,
                'motivo' => $motivo,
                'estado' => $estado,
            ]);
            $mensaje = "Cita añadida con éxito.";
        } catch (Exception $e) {
            $error = "Error al añadir la cita: " . $e->getMessage();
        }
    } else {
        $error = "Por favor, rellena todos los campos correctamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Cita</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            <a href="citas.php">Volver a Citas</a>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </header>
    <section class="gestion-container">
        <h2>Añadir Nueva Cita</h2>

        <!-- Mostrar mensajes -->
        <?php if (!empty($mensaje)) { ?>
            <p class="success"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php } ?>
        <?php if (!empty($error)) { ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>

        <form action="añadirCita.php" method="post" class="form-container">
            <label for="id_paciente">ID Paciente:</label>
            <input type="number" id="id_paciente" name="id_paciente" placeholder="Ejemplo: 1" required>

            <label for="fecha_cita">Fecha Cita:</label>
            <input type="datetime-local" id="fecha_cita" name="fecha_cita" required>

            <label for="motivo">Motivo:</label>
            <input type="text" id="motivo" name="motivo" placeholder="Motivo de la cita" required>

            <label for="estado">Estado:</label>
            <select id="estado" name="estado" required>
                <option value="Programada">Programada</option>
                <option value="Completada">Completada</option>
                <option value="Cancelada">Cancelada</option>
            </select>

            <button type="submit">Guardar</button>
        </form>
    </section>
</body>

</html>
