<?php
    function generarTextoAleatorio($longitud) {
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

$numFiles = isset($_GET['num_files']) ? intval(value: $_GET['num_files']) : null;
$errors = [];
$fileLinks = [];

if(numFiles !== null){
    if($numFiles < 1 || $numFiles > 100){
        $errors[] = "El número de archivos debe ser entre 1 y 100";
    }else{
        $directory = 'generated';
        if(!is_dir($directory)){
            mkdir($directory, 0777, true);
        }else{
            array_map('unlink',glob("$directory/*"));
        }

        $today = date('d/m/Y');
        for($i= 1; $i <= $numFiles; $i++){
            $fileName = "$directory/file_$i.txt";
            $fileContent = "";
        }

        for($j=0; $j < 100; $j++){
            $line = $today;
            $numFields = random_int(2,10);
            for($k=0; $k < $numFields; $k++){
                $line .= "#" . generarTextoAleatorio(random_int(5,20));
            }
            $line .= "\n";
            if(strlen($line) > 500){
                $line = substr($line, 0, 500) . "\n";
            }
            $fileContent .= $line;
        }

        file_put_contents($fileName, $fileContent);
        $fileLinks[] = "<a href='$fileName' target= '_blank'>Archivo $i</a>";
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
    <?php ?>
</body>
</html>
