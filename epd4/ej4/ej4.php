<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 4</title>
    <style>
        .campo-error {
        border: 2px solid red;
        }
        
        .mensaje-error {
        color: red;
        }
    </style>
</head>
<body>
    <?php

    $importe = isset($_GET['importe']) ? $_GET['importe'] : '';
    $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : '';
    $descuento = 0;
    $importeFinal = '';
    $errorImporte = '';
    $errorCodigo = '';

    function comprobarDescuento($codigo) {
        return preg_match('/^(?=.*\d)[A-Z0-9]{1,9}$/', $codigo);
    }

    function calcularDescuento($codigo) {
        preg_match_all('/\d/', $codigo, $numeros);
        $suma = array_sum($numeros[0]);

        return ($suma % 2 === 0) ? 10 : 5;
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['submit'])) {
        if (!preg_match('/^\d+(\.\d{2})?$/', $importe)) {
            $errorImporte = "El importe solo admite un máximo de 2 decimales";
        }


        if (!comprobarDescuento($codigo)) {
            $errorCodigo = "Error:  El código debe tener hasta 9 carácteres, con letras mayúsculas y al menos un número.";
        }


        if (empty($errorImporte) && empty($errorCodigo)) {
            $descuento = calcularDescuento($codigo);
            $importeFinal = number_format($importe * (1 - $descuento / 100), 2);
        }
    }

    ?>

    <form method="GET" action="">
        <label for="importe">Importe de la compra:</label><br>

        <input type="text" id="importe" name="importe" value="<?php echo htmlspecialchars($importe); ?>" 
               class="<?php echo !empty($errorImporte) ? 'campo-error' : ''; ?>">
        <span class="mensaje-error"><?php echo $errorImporte; ?></span>
        <br><br>

        <label for="codigo">Código promocional:</label><br>
        <input type="text" id="codigo" name="codigo" value="<?php echo htmlspecialchars($codigo); ?>"
               class="<?php echo !empty($errorCodigo) ? 'campo-error' : ''; ?>">
        <span class="mensaje-error"><?php echo $errorCodigo; ?></span>
        <br><br>

        <button type="submit" name="submit">Aplicar</button>
    </form>

    <?php if (!empty($importe) && !empty($codigo) && empty($errorImporte) && empty($errorCodigo)): ?>
        <h2>Resumen de Compra</h3>
        <p>Precio inicial: €<?php echo number_format($importe, 2); ?></p>
        <p>Descuento: <?php echo $descuento; ?>%</p>
        <p>Importe final: €<?php echo $importeFinal; ?></p>
    <?php endif; ?>

</body>
</html>
