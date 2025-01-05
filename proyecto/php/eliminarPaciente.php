<?php
require_once '../crud/crudPacientes.php';
require_once 'seguridad.php';
require_once 'utilidad.php';

// Verificar si el usuario ha iniciado sesión
if (!Security::isLogged()) {
    Helper::redirect('login.php');
}

// Conexión a la base de datos
$conn = Helper::getConn();
$pacienteCrud = new PacienteCrud($conn);

// Inicializar mensaje y error
$error = "";
$mensaje = "";

// Verificar si se proporcionó un ID de paciente
if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        try {
            // Intentar eliminar al paciente
            $affectedRows = $pacienteCrud->delete(['id' => $id]);

            if ($affectedRows > 0) {
                $mensaje = "Paciente eliminado con éxito.";
            } else {
                $error = "No se encontró el paciente con el ID proporcionado.";
            }
        } catch (Exception $e) {
            $error = "Error al eliminar al paciente: " . $e->getMessage();
        }
    } else {
        $error = "ID de paciente no válido.";
    }
} else {
    $error = "No se proporcionó el ID de paciente.";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Paciente</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            <a href="pacientes.php">Volver a Pacientes</a>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </header>
    <section class="gestion-container">
        <h2>Eliminar Paciente</h2>

        <!-- Mostrar mensajes -->
        <?php if (!empty($mensaje)) { ?>
            <p class="success"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php } ?>
        <?php if (!empty($error)) { ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>
    </section>
</body>

</html>
