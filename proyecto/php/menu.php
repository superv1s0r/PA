<?php
session_start();
include 'seguridad.php';

$conn = Helper::getConn();
$query = "SELECT * FROM citas WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION['username']);
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
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </header>
    <section class="gestion-container">
        <h2>Gestión de Pacientes</h2>
        <button onclick="window.location.href='crearCita.php'">Crear Cita</button>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Paciente</th>
                    <th>Fecha Cita</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['id_paciente']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_cita']); ?></td>
                        <td><?php echo htmlspecialchars($row['motivo']); ?></td>
                        <td><?php echo htmlspecialchars($row['estado']); ?></td>
                        <td>
                            <a href="añadirCita.php?id=<?php echo urlencode($row['id']); ?>">Añadir</a>
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
