<?php
session_start();
require_once 'config.php';
require_once 'utilidad.php';

if (!isset($_SESSION['usuario']) || ($_SESSION['rol'] !== 'operario' && $_SESSION['rol'] !== 'administrador')) {
    Helper::redirect('login.php?error=acceso_denegado');
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
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
        if ($accion === 'agregar') {
            $_SESSION['carrito'][] = [
                'sku' => $sku,
                'descripcion' => $descripcion,
                'num_pasillo' => $num_pasillo,
                'num_estanteria' => $num_estanteria,
                'cantidad' => $cantidad,
                'operacion' => 'crear'
            ];
            $mensaje = "Producto agregado al carrito.";
        } elseif ($accion === 'modificar') {
            $encontrado = false;
            foreach ($_SESSION['carrito'] as &$producto) {
                if ($producto['sku'] === $sku) {
                    $producto['descripcion'] = $descripcion;
                    $producto['num_pasillo'] = $num_pasillo;
                    $producto['num_estanteria'] = $num_estanteria;
                    $producto['cantidad'] = $cantidad;
                    $producto['operacion'] = 'modificar';
                    $encontrado = true;
                    $mensaje = "Producto modificado en el carrito.";
                    break;
                }
            }
            if (!$encontrado) {
                $error = "Producto con SKU $sku no encontrado en el carrito.";
            }
        } elseif ($accion === 'eliminar') {
            $_SESSION['carrito'] = array_filter($_SESSION['carrito'], fn($producto) => $producto['sku'] !== $sku);
            $mensaje = "Producto eliminado del carrito.";
        } elseif ($accion === 'confirmar') {
            foreach ($_SESSION['carrito'] as $producto) {
                if ($producto['operacion'] === 'crear') {
                    $query = "INSERT INTO producto (descripcion, num_pasillo, num_estanteria, cantidad) VALUES (?, ?, ?, ?)";
                    $stmt = $conexion->prepare($query);
                    $stmt->bind_param('siii', $producto['descripcion'], $producto['num_pasillo'], $producto['num_estanteria'], $producto['cantidad']);
                    $stmt->execute();
                } elseif ($producto['operacion'] === 'modificar') {
                    $query = "UPDATE producto SET descripcion=?, num_pasillo=?, num_estanteria=?, cantidad=? WHERE sku=?";
                    $stmt = $conexion->prepare($query);
                    $stmt->bind_param('siiii', $producto['descripcion'], $producto['num_pasillo'], $producto['num_estanteria'], $producto['cantidad'], $producto['sku']);
                    $stmt->execute();
                }
            }
            $_SESSION['carrito'] = [];
            Helper::redirect('carrito.php?mensaje=Operaciones confirmadas exitosamente.');
        }
    }
}

$mensaje = $_GET['mensaje'] ?? $mensaje;
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Operaciones</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Carrito de Operaciones</h1>
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

    <form action="carrito.php" method="POST">
        <h2>Agregar/Modificar Producto</h2>
        <label for="sku">SKU (para modificar):</label>
        <input type="number" name="sku" id="sku" placeholder="SKU del producto">
        
        <label for="descripcion">Descripción:</label>
        <input type="text" name="descripcion" id="descripcion" placeholder="Descripción del producto" required>
        
        <label for="num_pasillo">Número de Pasillo:</label>
        <input type="number" name="num_pasillo" id="num_pasillo" required>
        
        <label for="num_estanteria">Número de Estantería:</label>
        <input type="number" name="num_estanteria" id="num_estanteria" required>
        
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" required>
        
        <button type="submit" name="accion" value="agregar">Agregar</button>
        <button type="submit" name="accion" value="modificar">Modificar</button>
    </form>



    <h2>Productos en el Carrito</h2>
    <?php if (empty($_SESSION['carrito'])): ?>
        <p>No hay productos en el carrito.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>SKU</th>
                <th>Descripción</th>
                <th>Pasillo</th>
                <th>Estantería</th>
                <th>Cantidad</th>
                <th>Operación</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($_SESSION['carrito'] as $producto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($producto['sku']); ?></td>
                    <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($producto['num_pasillo']); ?></td>
                    <td><?php echo htmlspecialchars($producto['num_estanteria']); ?></td>
                    <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                    <td><?php echo htmlspecialchars($producto['operacion']); ?></td>
                    <td>
                        <form action="carrito.php" method="POST" style="display:inline;">
                            <input type="hidden" name="sku" value="<?php echo htmlspecialchars($producto['sku']); ?>">
                            <button type="submit" name="accion" value="eliminar" onclick="return confirm('¿Está seguro de eliminar este producto del carrito?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <form action="carrito.php" method="POST">
            <button type="submit" name="accion" value="confirmar">Confirmar Operaciones</button>
        </form>
    <?php endif; ?>
</body>
</html>
