<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$database = "EPD6";

try {

    $dsn = "mysql:host=$host;dbname=$database";
    $conn = new PDO($dsn, $user, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_SESSION['usuario_id'])) {
        header('Location: login.php');
        exit;
    }
    $query = "SELECT u.id_usuario, u.nombre, r.nombre_rol FROM usuario u
              JOIN rol r ON u.id_rol = r.id_rol WHERE u.id_usuario = :id_usuario";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_usuario', $_SESSION['usuario_id']);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "Usuario no encontrado.";
        exit;
    }

    echo "Bienvenido, " . $usuario['nombre'] . " (" . $usuario['nombre_rol'] . ")<br><br>";

    echo '<a href="">Crear</a><br>';
    echo '<a href="">Modificar</a><br>';
    echo '<a href="">Listar</a><br>';
    echo '<a href="">Borrar</a><br>';

    if ($usuario['nombre_rol'] == 'Administrador') {
        echo '<a href="">Administrar</a><br>';
    } elseif ($usuario['nombre_rol'] == 'Operario') {
        echo '<a href="">Productos</a><br>';
    } elseif ($usuario['nombre_rol'] == 'Administrativo') {
        echo '<a href="">Ventas</a><br>';
    }

    echo '<br><a href="index.php">INICIO</a>';

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
