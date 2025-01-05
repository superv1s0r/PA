<?php
require_once '../crud/crudPacientes.php';
require_once 'seguridad.php';
require_once 'utilidad.php';

$conn = Helper::getConn();
if (!Security::isLogged()) {
    Helper::redirect('login.php');
}

try {
    $pacienteCrud = new PacienteCrud($conn);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Obtener los datos del formulario
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $edad = filter_input(INPUT_POST, 'edad', FILTER_VALIDATE_INT);
        $genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_STRING);
        $fecha_registro = filter_input(INPUT_POST, 'fecha_registro', FILTER_SANITIZE_STRING);
        $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);

        // Validación de campos
        if ($nombre && $edad && $genero && $fecha_registro && $telefono && $email && $direccion) {
            // Crear los datos del paciente
            $pacienteData = [
                'nombre' => $nombre,
                'edad' => $edad,
                'genero' => $genero,
                'fecha_registro' => $fecha_registro,
                'telefono' => $telefono,
                'email' => $email,
                'direccion' => $direccion
            ];

            // Crear paciente en la base de datos
            $pacienteCrud->create($pacienteData);
            echo "Paciente añadido con éxito.";
        } else {
            throw new Exception("Por favor, completa todos los campos correctamente.");
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
    <title>Añadir Paciente</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- Asegúrate de tener el archivo CSS adecuado -->
</head>

<body>
    <header>
        <nav>
            <a href="pacientes.php">Volver a Pacientes</a>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </header>

    <section class="gestion-container">
        <h2>Añadir Paciente</h2>
        <br>
        <form action="añadirPaciente.php" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            <br><br>

            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad" required>
            <br><br>

            <label for="genero">Género:</label>
            <select id="genero" name="genero" required>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
            </select>
            <br><br>

            <label for="fecha_registro">Fecha de Registro:</label>
            <input type="text" id="fecha_registro" name="fecha_registro" placeholder="YYYY-MM-DD" required>
            <br><br>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" required>
            <br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br><br>

            <label for="direccion">Dirección:</label>
            <textarea id="direccion" name="direccion" required></textarea>
            <br><br>

            <button style="display: block; margin: 0 auto;" type="submit">Guardar</button>
        </form>
    </section>
</body>

</html>
