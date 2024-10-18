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

foreach ($almacen as $pasillo => $estantes) {
    foreach ($estantes as $estante => $productos) {
        $cantidad_aux = 0;
        foreach ($productos as $producto) {

            if ($producto['SKU'] == $sku_recibido) {
                $cantidad_aux += $producto['Cantidad'];
            }
        }
        if($cantidad_aux > 0)
        echo "Pasillo $pasillo - Estante $estante - $cantidad_aux unidades </br>";


    }
}

?>

