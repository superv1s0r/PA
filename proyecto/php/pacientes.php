<?php
session_start();
include 'seguridad.php';
include 'utilidad.php';

$conn = Helper::getConn();
$query = "SELECT * FROM pacientes";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Error en la consulta SQL: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pacientes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
<header>
    <nav>
        <a href="menu.php">Volver al Menú</a>
        <a href="logout.php">Cerrar sesión</a>
    </nav>
</header>
<section class="gestion-container">
    <h2>Gestión de Pacientes</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Edad</th>
            <th>Género</th>
            <th>Fecha Registro</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Dirección</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($_SESSION['perfil'] === 'admin'): ?>
            <button onclick="window.location.href='añadirPaciente.php'">Crear Paciente</button>

            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['edad']); ?></td>
                    <td><?php echo htmlspecialchars($row['genero']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_registro']); ?></td>
                    <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                    <td>
                        <a href="editarPaciente.php?id=<?php echo urlencode($row['id']); ?>">Editar</a>
                        <a class="delete-link" href="eliminarPaciente.php?id=<?php echo urlencode($row['id']); ?>">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        <?php else: ?>
            <?php
            $query = "SELECT * FROM pacientes WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_SESSION['username']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $usuario_db = $result->fetch_assoc();
            } else {
                $usuario_db = null; // Asegúrate de inicializar la variable si no hay resultados
            }
            ?>

            <?php if ($usuario_db): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario_db['id']); ?></td>
                    <td><?php echo htmlspecialchars($usuario_db['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($usuario_db['edad']); ?></td>
                    <td><?php echo htmlspecialchars($usuario_db['genero']); ?></td>
                    <td><?php echo htmlspecialchars($usuario_db['fecha_registro']); ?></td>
                    <td><?php echo htmlspecialchars($usuario_db['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($usuario_db['email']); ?></td>
                    <td><?php echo htmlspecialchars($usuario_db['direccion']); ?></td>
                    <td>
                        <a href="editarPaciente.php?id=<?php echo urlencode($usuario_db['id']); ?>">Editar</a>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="9">No se encontraron registros para este usuario.</td>
                </tr>
            <?php endif; ?>
        <?php endif; ?>
        </tbody>
    </table>
</section>

<script>
    document.querySelectorAll('.delete-link').forEach(link => {
        link.addEventListener('click', function (event) {
            const confirmation = confirm("¿Estás seguro de que deseas eliminar este paciente?");
            if (!confirmation) {
                event.preventDefault();  // Previene la acción del enlace si el usuario cancela
            }
        });
    });
</script>
</body>

</html>
