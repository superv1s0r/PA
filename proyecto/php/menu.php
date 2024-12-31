<?php
session_start();


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal</title>
</head>
<body>

<div class='container'>

    <h1 style="font-size: 3rem">Aplicacion de Salud</h1>
    <h2>Bienvenido <?php echo htmlspecialchars($_SESSION['username']); ?></h2>

        <div class="menu-options">
            <h3>Opciones</h3>
            <ul>
                <li><a href="#">Gestionar Pacientes</a></li>
            </ul>
        </div>
        <div class="menu-options">


    <a href="logout.php">Logout</a>
</div>

</body>
</html>