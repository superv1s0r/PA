<?php
if(isset($_COOKIE['ultimoUsuario'])){
    session_start();
    $_SESSION['usuario'] = $_COOKIE['ultimoUsuario'];
    header('Location: index.php');
}
session_start();
if(isset($_SESSION['usuario'])){
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="./main.css">
</head>
<body>
    <h2>Login</h2>
    <form action="./login_validation.php" method="post">
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="contrasenya">Password</label>
        <input type="password" id="contrasenya" name="contrasenya" required>
        <button type="submit">Login</button>
    </form>
    <?php
    if (isset($_GET['error'])) {
        echo '<div class="message error">' . htmlspecialchars($_GET['error']) . '</div>';
    } elseif (isset($_GET['success'])) {
        echo '<div class="message success">' . htmlspecialchars($_GET['success']) . '</div>';
    }
    ?>
</body>
</html>