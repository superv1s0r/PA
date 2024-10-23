<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Inventario</title>
    <link href="./main.css" rel="stylesheet"/>
</head>
<body>
    <h2>Introduzca los datos del inventario</h2>
    <form action="" method="POST">
        <textarea name="inventario" rows="10" cols="50" placeholder="Producto#Num_pasillo#Num_estanteria#Cantidad" required></textarea>
        <br>
        <input type="submit" value="Procesar Inventario">
    </form>
</body>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inventario = trim($_POST['inventario']);

    if(empty($inventario)){
        echo "<p>¡El campo que ha introducido está vacio!</p>";
        return; 
    }

    // Dividimos el inventario en líneas
    $lineas = explode(PHP_EOL, $inventario);
    
    $productos = [];
    $errores = [];
    
    $regex = "/^[^#]+#\d+#\d+#\d+$/";
    
    foreach ($lineas as $numero => $linea) {
        $linea = trim($linea);
        if (strlen($linea) > 150) {
            $errores[] = "Línea " . ($numero + 1) . ": supera los 150 caracteres";
            continue;
        }

        // Comprobar formato
        if ( !preg_match($regex, $linea) ) {
            $errores[] = "Línea " . ($numero + 1) . ": " . $linea;
            continue;
        }

        // Dividir la línea en componentes
        list($producto, $pasillo, $estanteria, $cantidad) = explode("#", $linea);

        // Guardar la información agrupada por producto
        if (!isset($productos[$producto])) {
            $productos[$producto] = [];
        }

        // Guardar la información por pasillo y estantería
        $productos[$producto][] = [
            "pasillo" => $pasillo,
            "estanteria" => $estanteria,
            "cantidad" => $cantidad
        ];
    }
    
    if (!empty($errores)) {
        echo "<h3>La información propuesta no está bien formateada:</h3>";
        echo "<ul>";
        foreach ($errores as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    } else {
        // Generar resumen del inventario
        echo "<h3>Resumen del inventario</h3>";
        $contador_productos = 1;
        foreach ($productos as $producto => $detalles) {
            $total = 0;
            $detalle_ubicaciones = [];
            foreach ($detalles as $detalle) {
                $total += $detalle['cantidad'];
                $ubicacion = " {$detalle['cantidad']} unidades en el pasillo {$detalle['pasillo']}, estantería {$detalle['estanteria']}.";
                $detalle_ubicaciones[] = $ubicacion;
            }

            echo "<p>Producto $contador_productos: $producto</p>";
            echo "<p>Total: $total unidades</p>";
            echo "<ul>";
            foreach ($detalle_ubicaciones as $ubicacion) {
                echo "<li>$ubicacion</li>";
            }
            echo "</ul>";
            $contador_productos++;
        }
    }
}


?>
