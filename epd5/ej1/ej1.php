<!DOCTYPE html>
<html>
<head>
    <title>Manejo archivo</title>
</head>
<body>
<?php
if (isset($_POST['envio'])) {

    $linea = 0;
    $lineasVacias = 0;
    $errores = [];

    if (!filter_has_var(INPUT_POST, 'email')) {

        $errores[] = 'Input type does not exist';

    } else {

        if (empty(trim($_POST['email']))) {

            $errores[] = 'Email está vacío';

        } else {

            $emailSaneado = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

            if (!filter_var($emailSaneado, FILTER_VALIDATE_EMAIL)) {

                $errores[] = 'E-Mail no es válido';
            }
        }
    }

    if ($_FILES['archivo']['type'] === 'text/plain') {

        $fileExtension = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);

        if (strtolower($fileExtension) === 'txt') {

            $fichero = fopen($_FILES['archivo']['tmp_name'], 'r');

            while (($lineaLeido = fgets($fichero)) !== false) {
                $linea++;

                if ($linea > 100) {

                    $errores[] = "El archivo tiene más de 100 líneas.";
                    break;
                }
                $lineLeido = trim($lineaLeido);

                if ($lineLeido === '') {

                    $lineasVacias++;
                    $errores[] = "Línea $linea está vacía.";

                } else if (strlen(htmlspecialchars(strip_tags($lineaLeido) > 500))) {

                    $errores[] = "Línea $linea supera los 500 caracteres.";
                }
            }
            fclose($fichero);
        } else {
            $errores[] = "El archivo no tiene la extensión .txt";
        }
    } else {
        $errores[] = "El archivo no tiene el formato MIME text/plain";
    }

    if ($_FILES['archivo']['error'] > 0) {

        echo '<p style="color:red">Errores encontrados: </p>';
        foreach ($errores as $error) {
            echo "<p style='color:red'> $error</p>";
        }
    } else {

        $DirectorioOK = __DIR__ . '/ok';
        $uniqueId = uniqid();
        $NombreOriginal = pathinfo($_FILES['archivo']['name'], PATHINFO_FILENAME);
        $NombreArchivoID = $NombreOriginal . '_' . $uniqueId . '.' . $fileExtension;
        $DirectorioTXT = $DirectorioOK . '/' . $NombreArchivoID;

        if (!file_exists($DirectorioOK)) {

            if (!mkdir($DirectorioOK, 0777)) {
                $errores[] = "Error al crear el directorio 'ok'.";
            }
        }
        while (file_exists($DirectorioTXT)) {
            $uniqueId = uniqid();
            $NombreArchivoID = $NombreOriginal . '_' . $uniqueId . '.' . $fileExtension;
            $DirectorioTXT = $DirectorioOK . '/' . $NombreArchivoID;
        }

        if (empty($errores)) {
            $DorectorioLog = __DIR__ . '/log.txt';
            $log = fopen($DorectorioLog, 'a+');

            if ($log && flock($log, LOCK_EX)) {

                if (move_uploaded_file($_FILES['archivo']['tmp_name'], $DirectorioTXT)) {

                    echo 'Archivo movido al directorio "ok" con éxito.<br>';
                    fwrite($log, "Archivo movido al directorio 'ok': {$NombreArchivoID} - Fecha: " . date('Y-m-d H:i:s') . "\r\n");
                    echo '<p style="color:green">Proceso completado correctamente. Archivo considerado apto.</p>';

                } else {

                    $errores[] = "Error al mover el archivo al directorio 'ok'.";

                }
                flock($log, LOCK_UN);

            } else {
                $errores[] = "Error: No se pudo bloquear el archivo de log o abrirlo.";
            }
            fclose($log);
        }

        if (!empty($errores)) {

            echo '<p style="color:red">Errores encontrados:</p>';
            foreach ($errores as $error) {
                echo "<p style='color:red'>$error</p>";

            }
        }
    }
}
if (!isset($_POST['envio'])) {
    ?>
    <h1> Envía un archivo </h1>
    <form method='post' enctype='multipart/form-data'>
        <input type='text' name='email'/> <br/>
        <input type='file' name='archivo'/> <br/>
        <input type='submit' name='envio' value='Enviar'/>
    </form>
    <?php
}
?>
</body>
</html>
