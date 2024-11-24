<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['hiddden_field']) ){
    session_start();
    setcookie('ultimoUsuario', '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    $_SESSION = array();
    session_destroy();
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
    <h2>Register</h2>
    <form action="./welcome.php" method="post">
        <input type="hidden" value='ok' name='hiddden_field'>
        <button type="submit">Register</button>
    </form>
</div>

</body>
</html>
