<?php
require_once '../crud/crudPacientes.php';
require_once 'seguridad.php';
require_once 'utilidad.php';

$conn = Helper::getConn();

$nombre = $edad = $genero = $telefono = $email = $direccion = "";

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $edad = filter_input(INPUT_POST, 'edad', FILTER_VALIDATE_INT);
        $genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_STRING);
        $fecha_registro = date("Y-m-d");
        $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if ($nombre && $edad && $genero && $telefono && $email && $direccion && $password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $pacienteCrud = new PacienteCrud($conn);
            $pacienteData = [
                'nombre' => $nombre,
                'edad' => $edad,
                'genero' => $genero,
                'fecha_registro' => $fecha_registro,
                'telefono' => $telefono,
                'email' => $email,
                'direccion' => $direccion,
            ];

            $pacienteCrud->create($pacienteData);

            $rol = 'paciente';
            $registrar = Helper::signUp($conn, $rol, $email, $hashedPassword);

            Helper::redirect('login.php');
        } else {
            throw new Exception("Por favor, complete todos los campos correctamente.");
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
    <title>Registro de usuario</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/show-password.js"></script>
    <header>
        <h1>Gestión de salud</h1>
    </header>

    <section class="login-container">

        <p><b>Registro de usuario</b></p>
        <br>
        <form action="signup.php" method="post">
            <input type="text" id="nombre" name="nombre" placeholder="Nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>

            <input type="number" id="edad" name="edad" placeholder="Edad" value="<?php echo htmlspecialchars($edad); ?>" required>

            <select id="genero" name="genero" required>
                <option disabled <?php echo empty($genero) ? 'selected' : ''; ?>>Género</option>
                <option value="Masculino" <?php echo $genero === "Masculino" ? 'selected' : ''; ?>>Masculino</option>
                <option value="Femenino" <?php echo $genero === "Femenino" ? 'selected' : ''; ?>>Femenino</option>
            </select>

            <input type="text" id="telefono" name="telefono" placeholder="Teléfono" value="<?php echo htmlspecialchars($telefono); ?>" required>

            <input type="email" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>

            <textarea id="direccion" name="direccion" placeholder="Dirección" required><?php echo htmlspecialchars($direccion); ?></textarea>

            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Contraseña">
                <i id="toggle-password" class="fa fa-eye"></i>
            </div>

            <button type="submit">REGISTRAR</button>

            <br>
            <a href="login.php" style="text-decoration: underline;">Iniciar sesión</a>
            <br>
        </form>
    </section>
</body>

</html>
