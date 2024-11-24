<?php
require_once 'config.php'; // Conexión a la base de datos
require_once 'utilidad.php'; // Funciones de utilidad

// Inicializar variables para el formulario
$errors = [];
$name = '';
$surname = '';
$email = '';
$password = '';
$role = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y sanitizar los datos enviados por el formulario
    $name = Helper::sanitizeInput($_POST['name']);
    $surname = Helper::sanitizeInput($_POST['surname']);
    $email = Helper::sanitizeInput($_POST['email']);
    $password = Helper::sanitizeInput($_POST['password']);
    $role = Helper::sanitizeInput($_POST['role']);

    // Validar los campos
    $errors = Helper::validateFields([
        'Nombre' => $name,
        'Apellidos' => $surname,
        'Email' => $email,
        'Contraseña' => $password,
        'Rol' => $role
    ]);

    // Validar el dominio del email
    $emailDomainError = Helper::validateEmailDomain($email);
    if ($emailDomainError) {
        $errors['Email'] = $emailDomainError;
    }

    // Si no hay errores, insertar en la base de datos
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $db = Database::getConnection();

            // Verificar si el rol existe en la tabla `rol`
            $stmt = $db->prepare("SELECT id_rol FROM rol WHERE nombre_rol = :role");
            $stmt->execute([':role' => $role]);
            $roleRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$roleRow) {
                $errors['Rol'] = "El rol especificado no existe.";
            } else {
                $roleId = $roleRow['id_rol'];

                // Insertar el usuario
                $stmt = $db->prepare(
                    "INSERT INTO usuario (email, password, nombre, apellidos, id_rol)
                    VALUES (:email, :password, :name, :surname, :roleId)"
                );

                $stmt->execute([
                    ':email' => $email,
                    ':password' => $hashedPassword,
                    ':name' => $name,
                    ':surname' => $surname,
                    ':roleId' => $roleId
                ]);

                echo "<p style='color: green;'>Usuario registrado con éxito.</p>";
            }
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Error al registrar usuario: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="./assets/signupStyles.css">
</head>
<body>
   
    <div class="container">
     <h1>Registro de Usuario</h1>

    <form method="POST" action="">
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required><br><br>

        <label for="surname">Apellidos:</label>
        <input type="text" id="surname" name="surname" value="<?= htmlspecialchars($surname) ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="role">Rol:</label>
        <select id="role" name="role" required>
            <option value="">Seleccione un rol</option>
            <option value="administrador" <?= $role === 'administrador' ? 'selected' : '' ?>>Administrador</option>
            <option value="administrativo" <?= $role === 'administrativo' ? 'selected' : '' ?>>Administrativo</option>
            <option value="operario" <?= $role === 'operario' ? 'selected' : '' ?>>Operario</option>
        </select><br><br>

        <button type="submit">Registrar</button>
    </form>
        <?php 
        // Mostrar errores si los hay
        if (!empty($errors)) {
            echo Helper::formatErrors($errors);
        }
?>
    </div>
</body>
</html>
