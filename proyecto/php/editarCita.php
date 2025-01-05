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

// Inicializar variables
$error = "";
$mensaje = "";
$cita = null;

// Verificar si se proporcionó un ID de cita
if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        try {
            // Obtener los datos de la cita
            $query = "SELECT * FROM Citas WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $cita = $result->fetch_assoc();
            } else {
                $error = "No se encontró la cita con el ID proporcionado.";
            }
        } catch (Exception $e) {
            $error = "Error al obtener la cita: " . $e->getMessage();
        }
    } else {
        $error = "ID de cita no válido.";
    }
}

// Procesar el formulario de edición
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $id_paciente = filter_input(INPUT_POST, 'id_paciente', FILTER_VALIDATE_INT);
    $fecha_cita = filter_input(INPUT_POST, 'fecha_cita', FILTER_SANITIZE_STRING);
    $motivo = filter_input(INPUT_POST, 'motivo', FILTER_SANITIZE_STRING);
    $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);

    if ($id && $id_paciente && $fecha_cita && $motivo && $estado) {
        try {
            // Actualizar la cita
            $citaCrud->update([
                'id_paciente' => $id_paciente,
                'fecha_cita' => $fecha_cita,
                'motivo' => $motivo,
                'estado' => $estado
            ], ['id' => $id]);

            $mensaje = "Cita actualizada con éxito.";
        } catch (Exception $e) {
            $error = "Error al actualizar la cita: " . $e->getMessage();
        }
    } else {
        $error = "Por favor, completa todos los campos correctamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita</title>
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
        <h2>Editar Cita</h2>

        <!-- Mostrar mensajes -->
        <?php if (!empty($mensaje)) { ?>
            <p class="success"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php } ?>
        <?php if (!empty($error)) { ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>

        <?php if ($cita) { ?>
            <form action="editarCita.php" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($cita['id']); ?>">

                <label for="id_paciente">ID Paciente:</label>
                <input type="number" id="id_paciente" name="id_paciente" value="<?php echo htmlspecialchars($cita['id_paciente']); ?>" required>

                <label for="fecha_cita">Fecha Cita:</label>
                <input type="text" id="fecha_cita" name="fecha_cita" value="<?php echo htmlspecialchars($cita['fecha_cita']); ?>" placeholder="YYYY-MM-DD" required>

                <label for="motivo">Motivo:</label>
                <input type="text" id="motivo" name="motivo" value="<?php echo htmlspecialchars($cita['motivo']); ?>" required>

                <label for="estado">Estado:</label>
                <select id="estado" name="estado" required>
                    <option value="Programada" <?php echo $cita['estado'] === 'Programada' ? 'selected' : ''; ?>>Programada</option>
                    <option value="Cancelada" <?php echo $cita['estado'] === 'Cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                    <option value="Completada" <?php echo $cita['estado'] === 'Completada' ? 'selected' : ''; ?>>Completada</option>
                </select>

                <button type="submit">Guardar Cambios</button>
            </form>
        <?php } ?>
    </section>
</body>

</html>
