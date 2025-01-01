<?php

require 'utilidad.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errores = [];
    $usuario = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $contrasenya = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $usuario)) {
        $errores[] = "El campo de email no cumple el requisito.";
    }

    $fields = ['email', 'password'];
    $errores = array_merge($errores, Helper::isEmpty($fields));

    $conn = Helper::getConn();

    if (!$conn) {
        die("Fallo al conectar a MySQL: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM usuario WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario_db = $result->fetch_assoc();

        if (password_verify($contrasenya, $usuario_db['password'])) {
            $_SESSION['valid'] = true;
            $_SESSION['username'] = $usuario;
            Helper::dirigir('menu.php');
        } else {
            $errores[] = "Contraseña incorrecta.";
        }
    } else {
        $errores[] = "No se encuentra el usuario.";
    }

    if (!empty($errores)) {
        echo '<p style="color:red">Errores cometidos:</p>';
        echo '<ul style="color:red">';
        foreach ($errores as $e) {
            echo "<li>$e</li>";
        }
        echo '</ul>';
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Sistema de notas</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <h1>Iniciar sesión</h1>
    </header>
    <section class="login-container">
        <article class="icons-account">
            <a href="#" class="icons"><i class="fa-brands fa-google-plus-g"></i></a>
            <a href="#" class="icons"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" class="icons"><i class="fa-brands fa-github"></i></a>
            <a href="#" class="icons"><i class="fa-brands fa-apple"></i></a>
        </article>

        <p> O usa una cuenta ya existente</p>
        <form action="login.php" method="post">
            <input type="email" id="email" name="email" placeholder="Email">
            <input type="password" id="password" name="password" placeholder="Contraseña">
            <input type="submit" value="Acceder">
            <a href="signup.php">Registrar una cuenta</a>
        </form>
    </section>
</body>

</html>
