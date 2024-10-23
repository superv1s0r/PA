<?php

function datos(&$errores, &$data, &$index){
    $data['fecha'] = isset($_POST["fecha_$index"]) ? trim($_POST["fecha_$index"]) : "";
    $data['almacen'] = isset($_POST["almacen_$index"]) ? trim($_POST["almacen_$index"]) : "";
    $data['precio'] = isset($_POST["precio_$index"]) ? trim($_POST["precio_$index"]) : "";
    $data['descripcion'] = isset($_POST["descripcion_$index"]) ? strtoupper(trim($_POST["descripcion_$index"])) : "";

    if(strtotime($data['fecha']) < strtotime('2024-01-01')){
        $errores["fecha_$index"] = "La fecha no puede ser anterior al año 2024";
    }

    if(empty($data['almacen'])){
        $errores["almacen_$index"] = "Debe seleccionar un tipo de almacen";
    }

    if(!is_numeric($data['precio']) || $data['precio'] < 1.00 || $data['precio'] > 999.99){
        $errores["precio_$index"] = "El precio tiene que estar entre 1.00 y 999.99";
    }

    if(strlen($data['descripcion']) > 500){
        $errores["descripcion_$index"] = "La descripcion tiene que tener menos de 500 palabras";
    }
}

if(isset($_GET['num_encargos'])){
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Encargos</title>
    <link rel="stylesheet" href="path/to/your/css/file.css"> <!-- Asegúrate de que la ruta sea correcta -->
</head>
<body>
    <form method="GET" action="">
        <label for="num_encargos">Numero de encargos: </label>
        <input type="number" id="num_encargos" name="num_encargos" min=1 required>
        <button type="submit"> Generar Formulario</button>
    </form>
</body>
</html>

<?php
}else{
    $num_encargos = intval($_GET['num_encargos']);
    $errores = [];
    $data = [];

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        for($i = 0; $i <= $num_encargos; $i++){
            $datos_encargo = [];
            datos($errores, $datos_encargo, $i);
            $data[$i] = $datos_encargo;
        }
    }

    if(!empty($errores)){
        foreach($errores as $campo => $mensaje){
            echo "<p class = 'error-mensaje'>$mensaje</p>";
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Encargos</title>
    <link rel="stylesheet" href="path/to/your/css/file.css"> <!-- Asegúrate de que la ruta sea correcta -->
</head>
<body>
    <form method="POST" action="">
        
        <?php
        for($i = 0; $i <= $num_encargos; $i++){
            if (isset($data[$i])) {
            $encargo = $data[$i];
            } else {
            $encargo = ['fecha'=>'', 'almacen'=>'', 'precio'=>'', 'descripcion'=>''];
            }
        ?>
        
        <fieldset>
            <legend>Encargo <?php echo $i+1; ?></legend>
            <label for="fecha_<?php echo $i; ?>">Fecha de la incidencia</label>
            <input type="date" id="fecha_<?php echo $i; ?>" name="fecha_<?php echo $i; ?>" value="<?php echo $encargo['fecha'] ?>" 
            class="<?php echo isset($errores["fecha_$i"]) ? 'campo-invalido' : '';?>" required><br><br>
            
            <label for="almacen_<?php echo $i; ?>">Almacen</label>
            <select name="almacen_<?php echo $i; ?>" id="almacen_<?php echo $i; ?>"
                    class="<?php echo isset($errores["almacen_$i"]) ? 'campo-invalido' : ''; ?>" required>
                    <option value="">Seleccionar</option>
                    <option value="Aire libre" <?php echo($encargo['almacen'] === 'Aire libre') ? 'selected' : ''; ?>>Aire libre</option>
                    <option value="Interior" <?php echo($encargo['almacen'] === 'Interior') ? 'selected' : ''; ?>>Interior</option>
                    <option value="En frio" <?php echo($encargo['almacen'] === 'En frio') ? 'selected' : ''; ?>>En frio</option>
                </select><br><br>

                <label for="precio_<?php echo $i; ?>">Precio estimado (€):</label>
                <input type="number" id="precio_<?php echo $i; ?>" name="precio_<?php echo $i; ?>" step="0.01" min="1.00" max="999.99"
                    value="<?php echo $encargo['precio']; ?>"
                    class="<?php echo isset($errores["precio_$i"]) ? 'campo-invalido' : ''; ?>" required><br><br>

                <label for="descripcion_<?php echo $i; ?>">Descripción:</label><br>
                <textarea id="descripcion_<?php echo $i; ?>" name="descripcion_<?php echo $i; ?>"
                    class="<?php echo isset($errores["descripcion_$i"]) ? 'campo-invalido' : ''; ?>"
                    maxlength="500" required><?php echo $encargo['descripcion']; ?>
                </textarea><br><br>
        </fieldset>
        <?php } ?>
        <button type="submit">Generar Informe</button>
    </form>

    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errores)) {

            usort($data, function ($a, $b) {
                return $b['precio'] <=> $a['precio'];
            });

            echo "<h2>Informe generado</h2>";
            echo "<p>Fecha del informe: " . date('d-m-Y') . "</p>";
            echo "<table border='1'>
                    <tr>
                        <th>Tipo de almacén</th>
                        <th>Precio total (€)</th>
                        <th>Precio estimado (€)</th>
                        <th>Fecha de la incidencia</th>
                        <th>Descripción</th>
                    </tr>";

            $precios_totales = [];
            foreach ($data as $encargo) {
                if (!isset($precios_totales[$encargo['almacen']])) {
                    $precios_totales[$encargo['almacen']] = 0;
                }
                $precios_totales[$encargo['almacen']] += $encargo['precio'];
            }
            foreach ($data as $encargo) {
                echo "<tr>
                        <td>{$encargo['almacen']}</td>
                        <td>{$precios_totales[$encargo['almacen']]} €</td>
                        <td>{$encargo['precio']} €</td>
                        <td>{$encargo['fecha']}</td>
                        <td>{$encargo['descripcion']}</td>
                      </tr>";
            }
            echo "</table>";
        }
    ?>
</body>
</html>

<?php
}
?>
