<?php
session_start();
require 'config.php'; // Clase Database
require 'utilidad.php'; // Clase Helper

// Redirigir si ya está autenticado
if (isset($_SESSION['usuario'])) {
    Helper::redirect('index.php');
    exit;
}

// Autocompletar usuario desde cookie si existe
if (isset($_COOKIE['ultimoUsuario'])) {
    $_SESSION['usuario'] = $_COOKIE['ultimoUsuario'];
    Helper::redirect('index.php');
    exit;
}

// Manejar el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = Helper::sanitizeInput(filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_EMAIL));
    $contrasenya = Helper::sanitizeInput($_POST['contrasenya']);

    // Validar que el email tenga el dominio correcto
    $emailDomainError = Helper::validateEmailDomain($usuario, '@almacen.com');
    if ($emailDomainError) {
        $error = $emailDomainError;
    } else {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = :usuario");
            $stmt->bindParam(':usuario', $usuario);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verificar la contraseña
                if (password_verify($contrasenya, $usuario_db['password'])) {
                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['rol'] = $usuario_db['id_rol'];
                    $_SESSION['id_usuario'] = $usuario_db['id_usuario'];

                    // Establecer una cookie para recordar al usuario
                    setcookie("ultimoUsuario", $usuario, time() + 86400, "/"); // 1 día

                    Helper::redirect('index.php');
                    exit;
                } else {
                    $error = "Contraseña incorrecta.";
                }
            } else {
                $error = "El usuario no existe.";
            }
        } catch (PDOException $e) {
            $error = "Error de conexión: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="./assets/signupStyles.css">
</head>
<body>
    <div class="container" style=" text-align: center;">
    <h2>Login</h2>
    <form action="login.php" method="post" style=" padding: 20px; display: flex; flex-direction: column; align-items: center;">
        <label for="usuario">Usuario</label>
        <input type="email" id="usuario" name="usuario" required>

        <label for="contrasenya">Contraseña</label>
        <input type="password" id="contrasenya" name="contrasenya" required>
        
        <button type="submit">Login</button>
    </form>
    <a href="./signup.php" style="text-decoration: none; color: blue; padding-top: 50px;">Registrate!</a>
    <?php
    // Mostrar mensajes de error si los hay
    if (isset($error)) {
        echo '<div class="message error">' . htmlspecialchars($error) . '</div>';
    }
    ?>
    </div>
</body>
</html>

