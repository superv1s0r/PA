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
$error = "";
$mensaje = "";

if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        try {
            // Eliminar la cita utilizando tu método delete con condiciones dinámicas
            $affectedRows = $citaCrud->delete(['id' => $id]);

            if ($affectedRows > 0) {
                $mensaje = "Cita eliminada con éxito.";
            } else {
                $error = "No se encontró la cita con el ID proporcionado.";
            }
        } catch (Exception $e) {
            $error = "Error al eliminar la cita: " . $e->getMessage();
        }
    } else {
        $error = "ID de cita no válido.";
    }
} else {
    $error = "No se proporcionó el ID de la cita.";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Cita</title>
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
        <h2>Eliminar Cita</h2>

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
