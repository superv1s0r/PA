<?php
include 'utilidad.php';
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

    $query = "SELECT * FROM perfil WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario_db = $result->fetch_assoc();

        if (password_verify($contrasenya, $usuario_db['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $usuario;
            $_SESSION['perfil'] = $usuario_db['rol'];

            Helper::redirect('menu.php');
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
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/show-password.js"></script>
    <header>
        <h1>Gestión de salud</h1>
    </header>

    <section class="login-container">

        <p><b>Inicio de sesión</b></p>
        <br>
        <form action="login.php" method="post">
            <input type="email" id="email" name="email" placeholder="Email">
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Contraseña">
                <i id="toggle-password" class="fa fa-eye"></i>
            </div>

            <input type="submit" value="Acceder">
            <br>
            <a href="signup.php" style="text-decoration: underline;">Registrar una cuenta</a>
            <br>
        </form>
    </section>
</body>

</html>
