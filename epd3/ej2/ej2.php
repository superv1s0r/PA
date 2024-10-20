<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Ej2 - Generar SKU</title>
</head>
<body>
    <h1>Generar SKU</h1>
    
    <form method="POST">
        <label for="numChar">Introduce el número de carácteres del SKU:</label>
        <input type="number" id="numChar" name="numChar" min="1" required>
        <button type="submit">Enviar</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        function crearSKU($numChar) {
            $skuid = uniqid();
            $squidMayus = strtoupper($skuid);
            $sku = substr($squidMayus, 0, $numChar);

            return $sku;
        }

        $numChar = $_POST['numChar'];
        $skuFinal = crearSKU($numChar);
        echo "<p>SKU de $numChar carácteres: <strong>$skuFinal</strong></p>";
    }
    ?>
</body>
</html>
