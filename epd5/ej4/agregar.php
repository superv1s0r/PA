<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ruta al archivo CSV
    $archivo_csv = 'inventario.csv';

    // Recibir y sanitizar datos del formulario
    $nombre = filter_var(trim($_POST['nombre']), FILTER_SANITIZE_STRING);
    $descripcion = filter_var(trim($_POST['descripcion']), FILTER_SANITIZE_STRING);
    $cantidad = filter_var($_POST['cantidad'], FILTER_VALIDATE_INT);
    $precio = filter_var($_POST['precio'], FILTER_VALIDATE_FLOAT);

    // Validación de los datos recibidos
    if (!$nombre || !$descripcion || $cantidad === false || $precio === false || $precio < 0) {
        $error = "Datos inválidos. Verifique que todos los campos estén correctamente llenos y en el formato adecuado.";
    } else {
        // Asignar ID automáticamente basado en el último ID en el archivo CSV
        $id = 1;
        if (($archivo = fopen($archivo_csv, 'r')) !== false) {
            while (($producto = fgetcsv($archivo, 1000, ',')) !== false) {
                $id = max($id, $producto[0] + 1); // Incrementar el ID
            }
            fclose($archivo);
        }

        // Abrir el archivo en modo de escritura al final del archivo
        if (($archivo = fopen($archivo_csv, 'a')) !== false) {
            // Escribir una nueva línea con los datos del producto
            fputcsv($archivo, [$id, $nombre, $descripcion, $cantidad, number_format((float)$precio, 2, '.', '')]);
            fclose($archivo);
        }

        // Redirigir a index.php
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Nuevo Artículo</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h1>Agregar Nuevo Artículo</h1>

    <?php if (isset($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <td colspan=6 class="update_form">
    <form action="agregar.php" method="post" class="inside_form">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        <br><br>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required></textarea>
        <br><br>

        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" required min="0">
        <br><br>

        <label for="precio">Precio:</label>
        <input type="number" name="precio" id="precio" required step="0.01" min="0">
        <br><br>

        <input type="submit" value="Agregar Artículo">
    </form>
    </td>
    <br>
    <a href="index.php">Volver al Inventario</a>
</body>
</html>

