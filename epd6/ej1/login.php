
<?php
if(isset($_COOKIE['ultimoUsuario'])){
    session_start();
    $_SESSION['usuario'] = $_COOKIE['ultimoUsuario'];
    header('Location: welcome.php');
}
session_start();
if(isset($_SESSION['usuario'])){
    header('Location: welcome.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
     <link rel="stylesheet" href="./main.css"> 
</head>
<body>




<div class="registration-form">
    <h2>Login</h2>
    <form action="./login_validation.php" method="post">

        <label for="usuario">Usuario</label>
        <input type="text" id="password" name="usuario" required>
        
        <label for="contrasenya">Password</label>
        <input type="password" id="password" name="contrasenya" required>

        <button type="submit">Login</button>
    </form>
 <?php
    if (isset($_GET['error'])) {
        echo '<div class="message error">' . htmlspecialchars($_GET['error']) . '</div>';
    } elseif (isset($_GET['success'])) {
        echo '<div class="message success">' . htmlspecialchars($_GET['success']) . '</div>';
    }
?>
</div>

</body>
</html>
