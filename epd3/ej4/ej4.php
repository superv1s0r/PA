<a href="ej4.html">Átras</a>
<br>
<?php
$almacen = [
    1 => [
        'a' => [
            ['SKU' => 'CCFWT-000500', 'Cantidad' => 7],
            ['SKU' => 'CCFWT-005000', 'Cantidad' => 5],
            ['SKU' => 'CCT-025000', 'Cantidad' => 8],
        ],
        'b' => [
            ['SKU' => 'COS-025000', 'Cantidad' => 5],
        ],
        'c' => [
            ['SKU' => 'COS-025000', 'Cantidad' => 9],
            ['SKU' => 'CCT-025000', 'Cantidad' => 1],
            ['SKU' => 'CCT-025000', 'Cantidad' => 8],
        ],
    ],
    2 => [
        'a' => [
            ['SKU' => 'CCT-025000', 'Cantidad' => 8],
            ['SKU' => 'CCT-025000', 'Cantidad' => 9],
        ],
    ]
];

$sku_recibido = $_GET['SKU'];
$sku_recibido = strtoupper($sku_recibido);

$matches = 0;

foreach ($almacen as $pasillo => $estantes) {
    foreach ($estantes as $estante => $productos) {
        $cantidad_aux = 0;
        foreach ($productos as $producto) {

            if ($producto['SKU'] == $sku_recibido) {
                $cantidad_aux += $producto['Cantidad'];
                $matches += 1;    

            }
        }
        if($cantidad_aux > 0)
            echo "Pasillo $pasillo - Estante $estante - $cantidad_aux unidades </br>";
        
    }
}
    if($matches == 0)
        echo "No se ha encontrado ningun articulo relacionado con " . $sku_recibido;
?>

