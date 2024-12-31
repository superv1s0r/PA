<?php
session_start();
if (!isset($_SESSION['valid']) || $_SESSION['valid'] !== true) {
    $_SESSION['error'] = "Por favor, inicia sesión para continuar.";
    header("Location: login.php");
    exit();
}

$conn = Helper::getConn();
$query = "SELECT * FROM `Pacientes`";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error en la consulta SQL: " . mysqli_error($conn));
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
    <div class="nav">
        <a href="logout.php">Cerrar sesión</a>
    </div>

    <div class="gestion-container">
        <h2>Gestión de Pacientes</h2>
        <button onclick="window.location.href='crear_paciente.php'">Crear Paciente</button>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['edad']; ?></td>
                        <td>
                            <a href="editarPaciente.php?id=<?php echo $row['id']; ?>">Editar</a>
                            <a href="eliminarPaciente.php?id=<?php echo $row['id']; ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>
