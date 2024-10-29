<?php
function generarTextoAleatorio($longitud)
{
    $caracteres = 'abcdefghijklmnopqrstuvwxyz';
    $caracteres .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $caracteres .= '0123456789';
    $textoAleatorio = '';
    $maxIndice = strlen($caracteres) - 1;
    for ($i = 0; $i < $longitud; $i++) {
        $textoAleatorio .= $caracteres[random_int(0, $maxIndice)];
    }
    return $textoAleatorio;
}

$numFiles = isset($_GET['num_files']) ? intval($_GET['num_files']) : null;
$errors = [];
$fileLinks = [];

if ($numFiles !== null) {
    if ($numFiles < 1 || $numFiles > 100) {
        $errors[] = "El número de archivos debe ser entre 1 y 100";
    } else {
        $directory = 'generated';
        //Si por cualquier cosa da fallo de que no es escrbible, lo que hay que hacer es acceder desde 
        //el terminal al directorio de XAMPP y en htdocs dentro de la carpeta del ejercicio comprobar: 
        //1- si hay carpeta generated
        //2- si tiene permisos de escritura
        // si no hay pues en el terminal se los das
        if (!is_dir($directory)) {
            if (mkdir($directory, 0777, true)) {
                echo "Directorio '$directory' creado con éxito.<br>";
                chmod($directory, 0777);
                echo "Permisos de escritura establecidos en '$directory'.<br>";
            } else {
                echo "Error: no se pudo crear el directorio '$directory'. Verifica los permisos.<br>";
            }
        } else {
            array_map('unlink', glob("$directory/*"));
            echo "El directorio '$directory' ya existe.<br>";
        }
        
        if (is_writable($directory)) {
            echo "El directorio '$directory' tiene permisos de escritura.<br>";
        } else {
            echo "Error: el directorio '$directory' no tiene permisos de escritura.<br>";
        }

        $today = date('d/m/Y');
        for ($i = 1; $i <= $numFiles; $i++) {
            $fileName = "$directory/file_$i.txt";
            $fileContent = "";

            for ($j = 0; $j < 100; $j++) {
            $line = $today;
            $numFields = random_int(2, 10);
            for ($k = 0; $k < $numFields; $k++) {
                $line .= "#" . generarTextoAleatorio(random_int(5, 20));
            }
            $line .= "\n";
            if (strlen($line) > 500) {
                $line = substr($line, 0, 500) . "\n";
            }
            $fileContent .= $line;
            }

            $status = file_put_contents($fileName, $fileContent);
            if ($status === false) {
            $errors[] = "Error al escribir en el archivo $fileName.";
            } else {
            $fileLinks[] = "<a href='$fileName' target='_blank'>Archivo $i</a>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Archivos</title>
</head>

<body>

    <h2>Generador de Archivos Dinamicos</h2>
    <form action="index.php" method="GET">
        <label for="num_files">Numero de archivos a crear: </label>
        <input type="number" id="num_files" name="num_files" min="1" max="100" required>
        <button type="submit">Generar Archivos</button>
    </form>
    <br>

    <?php if ($errors): ?>
        <div style="color: red;">
            <ul><?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif ($fileLinks): ?>
        <h2>Archivos Generados</h2>
        <ul><?php foreach ($fileLinks as $link): ?>
                <li><?php echo $link; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif;
    ?>

</body>

</html>
