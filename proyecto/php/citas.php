<?php

include 'seguridad.php';
include 'utilidad.php';

// Verificar si el usuario ha iniciado sesión
if (!Security::isLogged()) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$conn = Helper::getConn();
$query = "SELECT c.id, c.fecha_cita, c.motivo, c.estado, p.nombre AS paciente_nombre 
          FROM Citas c
          INNER JOIN Pacientes p ON c.id_paciente = p.id";
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
    <title>Gestión de Citas</title>
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
        <h2>Gestión de Citas</h2>
        <button onclick="window.location.href='añadirCita.php'">Programar Cita</button>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Fecha de la Cita</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['paciente_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_cita']); ?></td>
                        <td><?php echo htmlspecialchars($row['motivo']); ?></td>
                        <td><?php echo htmlspecialchars($row['estado']); ?></td>
                        <td>
                            <a href="editarCita.php?id=<?php echo urlencode($row['id']); ?>">Editar</a>
                            <a href="eliminarCita.php?id=<?php echo urlencode($row['id']); ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
</body>

</html>
