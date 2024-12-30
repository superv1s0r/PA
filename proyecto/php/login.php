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

        if (password_verify($contrasenya, $usuario_db['contrasenia_hash'])) {
            echo "Logeado correctamente";

            $_SESSION['valid'] = true;
            $_SESSION['username'] = $usuario;

            Helper::dirigir('index');
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
    <link rel="stylesheet" href="plantilla.css">
</head>
<body>
<h1>Iniciar sesi&oacute;n</h1>

<div class="contenedor">
    <form action="login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email">
        <br>
        <br>
        <label for="password">Contrase&ntilde;a:</label>
        <input type="password" id="password" name="password">
        <br>
        <br>
        <input type="submit" value="Acceder">
        <br>
    </form>
</div>

</body>
</html>
