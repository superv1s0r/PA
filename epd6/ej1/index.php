<?php
session_start();

if (!isset($_SESSION['usuario']) && !isset($_COOKIE['ultimoUsuario'])) {
    header('Location: login.php');
    exit;
}

$host = 'localhost';
$dbname = 'EPD6';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $usuario = $_SESSION['usuario'] ?? $_COOKIE['ultimoUsuario'];
    $stmt = $pdo->prepare("SELECT id_rol FROM usuario WHERE email = :usuario");
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();

    $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($usuario_db['id_rol'])) {
        $rol_id = $usuario_db['id_rol'];
    } else {
        echo "Error: El rol del usuario no está definido.";
        exit;
    }

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal</title>
    <link rel="stylesheet" href="./assets/indexStyles.css">
</head>
<body>
    <div class='container'>

    <h1 style="font-size: 3rem">Aplicacion de Almacen</h1>
<a href="index.php" class="inicio-btn">INICIO</a>
    <h1>Bienvenido <?php echo htmlspecialchars($_SESSION['usuario'] ?? $_COOKIE['ultimoUsuario']); ?></h1>

    <?php if ($rol_id == 1): ?>
        <div class="menu-options">
            <h2>Opciones Administrador</h2>
            <ul>
                <li><a href="./usuario.php">Gestionar Usuarios</a></li>
                <li><a href="./productos.php">Gestionar Productos</a></li>
            </ul>
        </div>
    <?php elseif ($rol_id == 2): ?>
        <div class="menu-options">
            <h2>Opciones Administrativas</h2>
            <ul>
                <li><a href="./productos.php">Gestionar Productos</a></li>
            </ul>
        </div>
    <?php elseif ($rol_id == 3): ?>
        <div class="menu-options">
            <h2>Opciones Operario</h2>
            <ul>
                <li><a href="./productos.php">Gestionar Productos</a></li>

            </ul>
        </div>
    <?php endif; ?>

    <a href="logout.php">Logout</a>
    </div>
<!-- Cookie de usuario -->
<script>
    // Si no hay cookie, la creamos al cargar la página
    if (!document.cookie.includes('ultimoUsuario')) {
        document.cookie = "ultimoUsuario=<?php echo htmlspecialchars($_SESSION['usuario'] ?? $_COOKIE['ultimoUsuario']); ?>; max-age=86400; path=/";
    }
</script>

</body>
</html>
