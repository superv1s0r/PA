<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Productos</title>
</head>

<body>
    <h1>Inventario de Productos</h1>

    <?php
    $entrada = "CCIKE123@@MesaDeEscritorio@@1@@01/01/2025-
                CCIKE123@@MesaDeEscritorio@@3@@02/01/2025-
                CCIKE123@@MesaDeEscritorio@@3@@03/01/2025-
                AWERF545@@MesaDeOficina@@1@@01/01/2025-
                RETG@@SillaDeOficina@@87@@02/01/2025";

    $lineas = explode('-', $entrada);
    $productos = [];

    foreach ($lineas as $linea) {
        $linea = trim($linea); // Eliminar espacios en blanco al inicio y al final
        list($sku, $descripcion, $unidades, $fecha) = explode('@@', $linea);

        if (!isset($productos[$sku])) {
            $productos[$sku] = [
                'sku' => $sku,
                'descripcion' => $descripcion,
                'primera_fecha' => $fecha,
                'ultima_fecha' => $fecha,
                'unidades' => (int)$unidades
            ];
        } else {
            $productos[$sku]['ultima_fecha'] = $fecha;
            $productos[$sku]['unidades'] += $unidades;
        }
    }

    usort($productos, function ($a, $b) {
        return strtotime($b['ultima_fecha']) <=> strtotime($a['ultima_fecha']);
    });

    echo "<table border='1'>
        <tr>
            <th>SKU</th>
            <th>Descripción</th>
            <th>Primera Fecha de Recepción</th>
            <th>Última Fecha de Recepción</th>
            <th>Total de Unidades</th>
            <th>Días Transcurridos</th>
        </tr>";

    foreach ($productos as $sku => $producto) {
        $primera_fecha = date_create_from_format('d/m/Y', $producto['primera_fecha']);
        $ultima_fecha = date_create_from_format('d/m/Y', $producto['ultima_fecha']);
        $interval = $primera_fecha->diff($ultima_fecha);
        $dias_transcurridos = $interval->days;

        echo "<tr>
                    <td>{$producto['sku']}</td>
                    <td>{$producto['descripcion']}</td>
                    <td>{$primera_fecha->format('d/m/Y')}</td>
                    <td>{$ultima_fecha->format('d/m/Y')}</td>
                    <td>{$producto['unidades']}</td>
                    <td>$dias_transcurridos días</td>
                  </tr>";
    }

    echo "</table>";
    ?>
</body>

</html>
