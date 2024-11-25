<?php
session_start();
require 'config.php';
require 'utilidad.php';

if (!isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit;
}

$rol = $_SESSION['rol'];
$idUsuarioActual = $_SESSION['id_usuario'];
$accion = $_GET['accion'] ?? 'listar';
$idUsuario = $_GET['id'] ?? null;
$pdo = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($accion === 'crear') {
        $datosUsuario = [
            'nombre' => $_POST['nombre'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
            'id_rol' => $_POST['id_rol']
        ];
        $resultado = HELPER::crear($pdo, $rol, $datosUsuario);
        if ($resultado === true) {
            header("Location: usuarios.php");
            exit;
        } else {
            $error = $resultado;
        }
    } elseif ($accion === 'editar') {
        $nuevosDatos = [
            'nombre' => $_POST['nombre'],
            'email' => $_POST['email']
        ];
        $resultado = HELPER::editar($pdo, $rol, $idUsuarioActual, $idUsuario, $nuevosDatos);
        if ($resultado === true) {
            header("Location: usuarios.php");
            exit;
        } else {
            $error = $resultado;
        }
    }
} elseif ($accion === 'eliminar' && $idUsuario && $rol == 1) {
    HELPER::eliminar($pdo, $idUsuario);
    header("Location: usuarios.php");
    exit;
}

if ($accion === 'listar') {
    $usuarios = HELPER::listarUsuarios($pdo, $rol, $idUsuarioActual);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Gestión de Usuarios</h1>

<?php if ($accion === 'listar'): ?>
    <a href="usuarios.php?accion=listar" class="btn">Listar</a>
    <table border="1">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?php echo $usuario['id_usuario']; ?></td>
                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                <td>
                    <?php
                    switch ($usuario['id_rol']) {
                        case 1: echo "Administrador"; break;
                        case 2: echo "Administrativo"; break;
                        case 3: echo "Operario"; break;
                    }
                    ?>
                </td>
                <td>
                    <a href="usuarios.php?accion=editar&id=<?php echo $usuario['id_usuario']; ?>">Editar</a>
                    <?php if ($rol == 1): ?>
                        <a href="usuarios.php?accion=eliminar&id=<?php echo $usuario['id_usuario']; ?>" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif ($accion === 'crear'): ?>
    <h2>Crear Usuario</h2>
    <form method="POST" action="usuarios.php?accion=crear">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" required>

        <label for="id_rol">Rol:</label>
        <select name="id_rol" id="id_rol">
            <option value="3">Operario</option>
            <?php if ($rol == 1): ?>
                <option value="2">Administrativo</option>
                <option value="1">Administrador</option>
            <?php endif; ?>
        </select>

        <button type="submit">Crear Usuario</button>
    </form>
<?php elseif ($accion === 'editar'): ?>
    <h2>Editar Usuario</h2>
    <form method="POST" action="usuarios.php?accion=editar&id=<?php echo $idUsuario; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>

        <button type="submit">Guardar Cambios</button>
    </form>
<?php endif; ?>
</body>
</html>
