<?php
session_start();
require_once 'config.php';
require_once 'utilidad.php';

// Si el usuario ya está logueado, redirigirlo a la página principal
if (!isset($_SESSION['usuario']) || !isset($_COOKIE['ultimoUsuario'])) {
    Helper::redirect('login.php'); 
}
if($_SESSION['rol'] === 1 || $_SESSION['rol'] === 3){
    Helper::redirect('index.php');
}
$mensaje = '';
$error = '';

try {
    $conexion = Database::getConnection(); // Obtener la conexión PDO

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
                $stmt->execute([$descripcion, $num_pasillo, $num_estanteria, $cantidad]);
                $mensaje = "Producto creado exitosamente.";
            } elseif ($accion === 'modificar') {
                $query = "UPDATE producto SET descripcion=?, num_pasillo=?, num_estanteria=?, cantidad=? WHERE sku=?";
                $stmt = $conexion->prepare($query);
                $stmt->execute([$descripcion, $num_pasillo, $num_estanteria, $cantidad, $sku]);
                $mensaje = "Producto modificado exitosamente.";
            } elseif ($accion === 'eliminar' && $_SESSION['rol'] === 'administrador') {
                $query = "DELETE FROM producto WHERE sku=?";
                $stmt = $conexion->prepare($query);
                $stmt->execute([$sku]);
                $mensaje = "Producto eliminado exitosamente.";
            }
        }
    }
} catch (PDOException $e) {
    $error = "Error de conexión o consulta: " . $e->getMessage();
}

$mensaje = $_GET['mensaje'] ?? $mensaje;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="assets/productCarStyles.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Productos</h1>
        <nav>
            <a href="index.php" style="text-decoration: none; color: blue;" >Inicio</a>
            <a href="logout.php" style="text-decoration: none; color: blue;">Cerrar Sesión</a>
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
            
            <button type="submit" name="accion" value="crear" style="margin: 10px;">Crear</button>
            <button type="submit" name="accion" value="modificar" style="margin: 10px;">Modificar</button>
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
        $stmt->execute([$like_busqueda, $registros_por_pagina, $offset]);
        $resultado = $stmt->fetchAll();

        echo "<table><tr><th>SKU</th><th>Descripción</th><th>Pasillo</th><th>Estantería</th><th>Cantidad</th><th>Acciones</th></tr>";
        foreach ($resultado as $row) {
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
        $stmt_total->execute([$like_busqueda]);
        $resultado_total = $stmt_total->fetch();
        $total_registros = $resultado_total['total'];
        $total_paginas = ceil($total_registros / $registros_por_pagina);

        echo "<p>Página $pagina de $total_paginas</p>";
        echo "<div class='pagination'>";
        if ($pagina > 1) {
            echo "<a href='productos.php?pagina=" . ($pagina - 1) . "&busqueda=$busqueda'>Página Anterior</a>";
        }
        if ($pagina < $total_paginas) {
            echo "<a href='productos.php?pagina=" . ($pagina + 1) . "&busqueda=$busqueda'>Página Siguiente</a>";
        }
        echo "</div>";

        ?>
    </div>
</body>
</html>
