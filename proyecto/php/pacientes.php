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
        <button onclick="window.location.href='añadirPaciente.php'">Crear Paciente</button>
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
            </tbody>
        </table>
    </section>

    <script>
    document.querySelectorAll('.delete-link').forEach(link => {
        link.addEventListener('click', function(event) {
            const confirmation = confirm("¿Estás seguro de que deseas eliminar este paciente?");
            if (!confirmation) {
                event.preventDefault();  // Previene la acción del enlace si el usuario cancela
            }
        });
    });
</script>
</body>

</html>
