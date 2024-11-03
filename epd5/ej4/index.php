<?php
// Ruta al archivo CSV
$archivo_csv = 'inventario.csv';
$error = "";

// Función para leer el inventario
function leerInventario($archivo_csv) {
    $inventario = [];
    if (($archivo = fopen($archivo_csv, 'r')) !== false) {
        while (($datos = fgetcsv($archivo, 1000, ',')) !== false) {
            $inventario[] = $datos;
        }
        fclose($archivo);
    }
    return $inventario;
}

// Función para escribir en el inventario después de eliminar/actualizar
function escribirInventario($archivo_csv, $inventario) {
    if (($archivo = fopen($archivo_csv, 'w')) !== false) {
        foreach ($inventario as $producto) {
            fputcsv($archivo, $producto);
        }
        fclose($archivo);
    }
}

// Procesar eliminación si se recibe un ID en la URL
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $inventario = leerInventario($archivo_csv);

    // Filtrar el inventario para eliminar el producto con el ID indicado
    $inventario = array_filter($inventario, function($producto) use ($id) {
        return $producto[0] !== $id;
    });

    // Guardar el inventario actualizado
    escribirInventario($archivo_csv, $inventario);

    // Redirigir a la página para evitar reenvío de formularios
    header("Location: index.php");
    exit;
}

// Procesar actualización si se recibe una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $nombre = filter_var(trim($_POST['nombre']), FILTER_SANITIZE_STRING);
    $descripcion = filter_var(trim($_POST['descripcion']), FILTER_SANITIZE_STRING);
    $cantidad = filter_var($_POST['cantidad'], FILTER_VALIDATE_INT);
    $precio = filter_var($_POST['precio'], FILTER_VALIDATE_FLOAT);

     

    // Validar datos (similar al agregar)
    if (empty($nombre) || empty($descripcion) || $cantidad < 0 || !is_numeric($precio) || $precio < 0) {
        $error = "ERRORES: " ;
       $errores = [];

        if (empty($nombre)) {
            $errores[] = "El campo 'Nombre' está vacío o no es válido.";
        }
        
        if (empty($descripcion)) {
            $errores[] = "El campo 'Descripción' está vacío o no es válido.";
        }

        if (!ctype_digit($cantidad)) {
            $errores[] = "El campo 'Cantidad' debe ser un número entero positivo";
        }

        if (!is_numeric($precio) || $precio < 0) {
            $errores[] = "El campo 'Precio' debe ser un número decimal positivo.";
        }

        if (!empty($errores)) {
            $error .= implode(" ", $errores);         
        }
    } else {
        $inventario = leerInventario($archivo_csv);

        foreach ($inventario as &$producto) {
            if ($producto[0] === $id) {
                $producto = [$id, $nombre, $descripcion, $cantidad, number_format((float)$precio, 2, '.', '')];
                break;
            }
        }
        escribirInventario($archivo_csv, $inventario);

        header("Location: index.php");
        exit;
    }
}

// Leer el inventario actualizado
$inventario = leerInventario($archivo_csv);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario del Almacén</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h1>Inventario del Almacén</h1>
    <a href="agregar.php">Agregar Nuevo Artículo</a>
    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($inventario)): ?>
                <?php foreach ($inventario as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto[0]); ?></td>
                        <td><?php echo htmlspecialchars($producto[1]); ?></td>
                        <td><?php echo htmlspecialchars($producto[2]); ?></td>
                        <td><?php echo htmlspecialchars($producto[3]); ?></td>
                        <td><?php echo htmlspecialchars(number_format((float)$producto[4], 2)); ?></td>
                        <td>
                            <a href="index.php?accion=eliminar&id=<?php echo urlencode($producto[0]); ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este artículo?');">Eliminar</a> |
                            <a href="index.php?accion=editar&id=<?php echo urlencode($producto[0]); ?>">Actualizar</a>
                        </td>
                    </tr>
                    <?php if (isset($_GET['accion']) && $_GET['accion'] === 'editar' && $_GET['id'] === $producto[0]): ?>
                        <tr>
                            <td colspan="6" class="update_form">
                                <form action="index.php" method="post" class="inside_form">

                                    <input type="hidden" name="accion" value="actualizar">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($producto[0]); ?>">
                                    <label>Nombre:</label>
                                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto[1]); ?>" required>
                                    <label>Descripción:</label>
                                    <input type="text" name="descripcion" value="<?php echo htmlspecialchars($producto[2]); ?>" required>
                                    <label>Cantidad:</label>
                                    <input type="number" name="cantidad" value="<?php echo htmlspecialchars($producto[3]); ?>" required min="0">
                                    <label>Precio:</label>
                                    <input type="number" name="precio" value="<?php echo htmlspecialchars($producto[4]); ?>" required step="0.01" min="0">
                                    <input type="submit" value="Guardar">
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No hay productos en el inventario.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>



