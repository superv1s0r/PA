<?php

require_once '../crud/crudCitas.php';
require_once 'seguridad.php';
require_once 'utilidad.php';

$conn = Helper::getConn();
if (!Security::isLogged()) {
    Helper::redirect('login.php');
}

try {
    $pacienteCrud = new PacienteCrud($conn);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $edad = filter_input(INPUT_POST, 'edad', FILTER_VALIDATE_INT);
    $genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_STRING);
    $fecha_registro = filter_input(INPUT_POST, 'fecha_registro', FILTER_SANITIZE_STRING);
    $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);

    if ($nombre && $edad && $genero && $fecha_registro && $telefono && $email && $direccion) {
            $nuevaCita = $pacienteCrud->create([
                'nombre' => $nombre, 
                    'edad' => $edad, 
                    'genero' => $genero, 
                    'fecha_registro' => $fecha_registro, 
                    'telefono' => $telefono, 
                    'email' => $email, 
                    'direccion' => $direccion 
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paciente</title>
</head>

<body>
    <h1>Editar Paciente</h1>

    <?php if (isset($paciente)): ?>
        <form action="editarPaciente.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($paciente['id']); ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" 
                value="<?php echo htmlspecialchars($paciente['nombre']); ?>" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" 
                value="<?php echo htmlspecialchars($paciente['password']); ?>" required>

            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad" 
                value="<?php echo htmlspecialchars($paciente['edad']); ?>" required>

            <label for="genero">Género:</label>
            <select id="genero" name="genero" required>
                <option value="Masculino" <?php echo ($paciente['genero'] === 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                <option value="Femenino" <?php echo ($paciente['genero'] === 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
            </select>

            <label for="fecha_registro">Fecha de Registro:</label>
            <input type="date" id="fecha_registro" name="fecha_registro" 
                value="<?php echo htmlspecialchars($paciente['fecha_registro']); ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" 
                value="<?php echo htmlspecialchars($paciente['telefono']); ?>" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" 
                value="<?php echo htmlspecialchars($paciente['email']); ?>" required>

            <label for="direccion">Dirección:</label>
            <textarea id="direccion" name="direccion" required><?php echo htmlspecialchars($paciente['direccion']); ?></textarea>

            <button type="submit">Actualizar</button>
        </form>
    <?php else: ?>
        <p>No hay paciente para editar.</p>
    <?php endif; ?>

</body>

</html>
