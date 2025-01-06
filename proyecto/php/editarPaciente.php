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

// Inicializar variables para errores y mensajes
$error = "";
$mensaje = "";

// Verificar si se ha proporcionado un ID de paciente en la URL
if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        // Obtener el paciente desde la base de datos
        $paciente = $pacienteCrud->getById($id);

        // Verificar si el paciente existe
        if (!$paciente) {
            $error = "No se encontró el paciente con el ID proporcionado.";
        }
    } else {
        $error = "ID de paciente no válido.";
    }
} else {
    $error = "No se proporcionó el ID de paciente.";
}

// Procesar el formulario de edición
if ($_SERVER["REQUEST_METHOD"] === "POST" && !$error) {
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $edad = filter_input(INPUT_POST, 'edad', FILTER_VALIDATE_INT);
    $genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_STRING);
    $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);

    // Validar los campos
    if ($nombre && $edad && $genero && $telefono && $email && $direccion) {
        try {
            // Actualizar el paciente
            $pacienteCrud->update([
                'id' => $id,
                'nombre' => $nombre,
                'edad' => $edad,
                'genero' => $genero,
                'telefono' => $telefono,
                'email' => $email,
                'direccion' => $direccion
            ], ['id' => $id]);

            $mensaje = "Paciente actualizado con éxito.";


        } catch (Exception $e) {
            $error = "Error al actualizar el paciente: " . $e->getMessage();
        }
    } else {
        $error = "Por favor, complete todos los campos.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paciente</title>
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
        <h2>Editar Paciente</h2>

        <!-- Mostrar mensajes -->
        <?php if (!empty($mensaje)) { ?>
            <p class="success"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php } ?>
        <?php if (!empty($error)) { ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>

        <!-- Formulario de edición -->
        <?php if ($paciente) { ?>
            <form action="editarPaciente.php?id=<?php echo urlencode($id); ?>" method="post">
                <br>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($paciente['nombre']); ?>" required>
                <br><br>

                <label for="edad">Edad:</label>
                <input type="number" id="edad" name="edad" value="<?php echo htmlspecialchars($paciente['edad']); ?>" required>
                <br><br>

                <label for="genero">Género:</label>
                <select id="genero" name="genero" required>
                    <option value="Masculino" <?php echo $paciente['genero'] === 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                    <option value="Femenino" <?php echo $paciente['genero'] === 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                </select>
                <br><br>

                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($paciente['telefono']); ?>" required>
                <br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($paciente['email']); ?>" required>
                <br><br>

                <label for="direccion">Dirección:</label>
                <textarea id="direccion" name="direccion" required><?php echo htmlspecialchars($paciente['direccion']); ?></textarea>
                <br><br>
                
                <button style="display: block; margin: 0 auto;" type="submit">Actualizar</button>
            </form>
        <?php } ?>
    </section>
</body>

</html>
