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
    $errores = Helper::isEmpty($fields);

    $conn = Helper::getConn();

    if (!$conn) {
        echo "Fallo al conectar a MySQL: " . mysqli_connect_error();
        exit();
    }

    $query = "SELECT * FROM usuario WHERE email = '$usuario'";
    $check_result = mysqli_query($conn, $query);

    if (mysqli_num_rows($check_result) > 0) {

        $usuario_db = mysqli_fetch_assoc($check_result);

        if (password_verify($contrasenya, $usuario_db['password'])) {
            echo "Logeado correctamente";

            $_SESSION['valid'] = true;
            $_SESSION['username'] = $usuario;

            Helper::dirigir('menu.php');
            exit();
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
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Sistema de notas</title>
    <link rel="stylesheet" href="../css/login.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>
<body>
<h1>Iniciar sesi&oacute;n</h1>
<div class="space1"></div>

<div class="login-container">
    <div class="icons-account">
        <a href="#" class="icons"><i class="fa-brands fa-google-plus-g"></i></a>
        <a href="#" class="icons"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#" class="icons"><i class="fa-brands fa-github"></i></a>
        <a href="#" class="icons"><i class="fa-brands fa-apple"></i></a>

    </div>

    <span>O usa una cuenta ya existente</span>
    <form action="login.php" method="post">
        <input type="email" id="email" name="email" placeholder="email" >
        <input type="password" id="password" name="password" placeholder="password" >
        <input type="submit" value="Aceeder">
        <a href="signup.php"> Registrar una cuenta</a>
    </form>
</div>

<div class="space2"></div>

</body>
</html>
