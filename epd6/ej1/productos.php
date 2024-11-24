<?php
session_start();
require_once 'config.php';
require_once 'utilidad.php';

if (!isset($_SESSION['usuario']) || ($_SESSION['rol'] !== 'operario' && $_SESSION['rol'] !== 'administrador')) {
    Helper::redirect('login.php?error=acceso_denegado');
}

$mensaje = '';
$error = '';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];
    $sku = intval($_POST['sku'] ?? 0);
    $descripcion = Helper::sanitizeInput($_POST['descripcion'] ?? '');
    $num_pasillo = intval($_POST['num_pasillo'] ?? 0);
    $num_estanteria = intval($_POST['num_estanteria'] ?? 0);
    $cantidad = intval($_POST['cantidad'] ?? 0);

    $errors = Helper::validateFields([
        'Descripción' => $descripcion,
        'Número de Pasillo' => $num_pasillo,
        'Número de Estantería' => $num_estanteria,
        'Cantidad' => $cantidad
    ]);

    if (!empty($errors)) {
        $error = Helper::formatErrors($errors);
    } else {
        if ($accion === 'crear') {
            $query = "INSERT INTO producto (descripcion, num_pasillo, num_estanteria, cantidad) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param('siii', $descripcion, $num_pasillo, $num_estanteria, $cantidad);
            $stmt->execute();
            $mensaje = "Producto creado exitosamente.";
        } elseif ($accion === 'modificar') {
            $query = "UPDATE producto SET descripcion=?, num_pasillo=?, num_estanteria=?, cantidad=? WHERE sku=?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param('siiii', $descripcion, $num_pasillo, $num_estanteria, $cantidad, $sku);
            $stmt->execute();
            $mensaje = "Producto modificado exitosamente.";
        } elseif ($accion === 'eliminar' && $_SESSION['rol'] === 'administrador') {
            $query = "DELETE FROM producto WHERE sku=?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param('i', $sku);
            $stmt->execute();
            $mensaje = "Producto eliminado exitosamente.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Gestión de Productos</h1>
    <nav>
        <a href="index.php">Inicio</a>
        <a href="logout.php">Cerrar Sesión</a>
    </nav>

    <?php if ($mensaje): ?>
        <div class="mensaje"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="productos.php" method="POST">
        <h2>Crear/Modificar Producto</h2>
        <label for="sku">SKU (solo para modificar):</label>
        <input type="number" name="sku" id="sku" placeholder="ID del producto (solo modificar)">
        
        <label for="descripcion">Descripción:</label>
        <input type="text" name="descripcion" id="descripcion" placeholder="Descripción del producto" required>
        
        <label for="num_pasillo">Número de Pasillo:</label>
        <input type="number" name="num_pasillo" id="num_pasillo" required>
        
        <label for="num_estanteria">Número de Estantería:</label>
        <input type="number" name="num_estanteria" id="num_estanteria" required>
        
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" required>
        
        <button type="submit" name="accion" value="crear">Crear</button>
        <button type="submit" name="accion" value="modificar">Modificar</button>
    </form>

    <form action="productos.php" method="GET">
        <h2>Buscar Productos</h2>
        <input type="text" name="busqueda" placeholder="Buscar descripción">
        <button type="submit">Buscar</button>
    </form>

    <h2>Listado de Productos</h2>
    <?php
    $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
    $registros_por_pagina = 5;
    $offset = ($pagina - 1) * $registros_por_pagina;

    $busqueda = Helper::sanitizeInput($_GET['busqueda'] ?? '');
    $query = "SELECT * FROM producto WHERE descripcion LIKE ? LIMIT ? OFFSET ?";
    $stmt = $conexion->prepare($query);
    $like_busqueda = '%' . $busqueda . '%';
    $stmt->bind_param('sii', $like_busqueda, $registros_por_pagina, $offset);
    $stmt->execute();
    $resultado = $stmt->get_result();

    echo "<table><tr><th>SKU</th><th>Descripción</th><th>Pasillo</th><th>Estantería</th><th>Cantidad</th><th>Acciones</th></tr>";
    while ($row = $resultado->fetch_assoc()) {
        echo "<tr>
                <td>{$row['sku']}</td>
                <td>{$row['descripcion']}</td>
                <td>{$row['num_pasillo']}</td>
                <td>{$row['num_estanteria']}</td>
                <td>{$row['cantidad']}</td>
                <td>
                    <form action='productos.php' method='POST' style='display:inline;'>
                        <input type='hidden' name='sku' value='{$row['sku']}'>
                        <button type='submit' name='accion' value='eliminar' onclick='return confirm(\"¿Está seguro?\")'>Eliminar</button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";

    $query_total = "SELECT COUNT(*) AS total FROM producto WHERE descripcion LIKE ?";
    $stmt_total = $conexion->prepare($query_total);
    $stmt_total->bind_param('s', $like_busqueda);
    $stmt_total->execute();
    $resultado_total = $stmt_total->get_result()->fetch_assoc();
    $total_registros = $resultado_total['total'];
    $total_paginas = ceil($total_registros / $registros_por_pagina);

    echo "<p>Página $pagina de $total_paginas</p>";
    if ($pagina > 1) {
        echo "<a href='productos.php?pagina=" . ($pagina - 1) . "&busqueda=$busqueda'>Página Anterior</a> ";
    }
    if ($pagina < $total_paginas) {
        echo "<a href='productos.php?pagina=" . ($pagina + 1) . "&busqueda=$busqueda'>Página Siguiente</a>";
    }
    ?>
</body>
</html>
