<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 3 - Creador de Formularios</title>
    <style>
        body {
            font-family: Arial;
        }
        
        .invalido {
            border: 1px solid red;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .exito {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Generador de Formularios</h1>

<?php

if (isset($_POST['cargar_csv']) && isset($_FILES['archivo_csv'])) {
    $archivo = $_FILES['archivo_csv'];

     // Comprobar que se sube un CSV
    if ($archivo['type'] !== 'text/csv' && pathinfo($archivo['name'], PATHINFO_EXTENSION) !== 'csv') {
        echo "<p class='error'>Error: No se ha subido un CSV.</p>";
        mostrarFormulario();
        
    } else {
        // Mover archivo a disco
        $nombreArchivo = 'data/' . time() . '.csv';
        if (!is_dir('data')) mkdir('data');
        move_uploaded_file($archivo['tmp_name'], $nombreArchivo);

        // Generar el formulario
        if (($handle = fopen($nombreArchivo, "r")) !== FALSE) {
            echo "<form action='' method='POST'>";
            echo "<input type='hidden' name='archivo_id' value='{$nombreArchivo}'>";
            
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $label = htmlspecialchars($data[0]);
                $type = htmlspecialchars($data[1]);
                $id = htmlspecialchars($data[2]);

                echo "<label for='{$id}'>{$label}:</label> ";
                echo "<input type='{$type}' name='{$id}' id='{$id}' required>";
                echo "<br>";
            }
            fclose($handle);

            echo "<button type='submit' name='enviar_formulario'>Enviar</button>";
            echo "</form>";
            
        } else {
            echo "<p class='error'>Error al abrir el archivo.</p>";
        }
    }
} elseif (isset($_POST['enviar_formulario'])) {
    $archivo_id = $_POST['archivo_id'];
    $errors = [];

    // Lectura del csv
    if (($handle = fopen($archivo_id, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            $label = htmlspecialchars($data[0]);
            $type = htmlspecialchars($data[1]);
            $id = htmlspecialchars($data[2]);

            // Validar todos los campos
            if (empty($_POST[$id])) {
                $errors[$id] = "El campo '{$label}' es obligatorio.";
            } else {
                $valor = $_POST[$id];
                switch ($type) {
                    case 'email':
                        if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                            $errors[$id] = "El campo '{$label}' no contiene un email válido.";
                        }
                        break;
                    case 'date':
                        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
                            $errors[$id] = "El campo '{$label}' no contiene una fecha válida.";
                        }
                        break;
                    case 'text':
                    default:
                        break;
                }
            }
        }
        fclose($handle);
    }

    if (empty($errors)) {
        echo "<p class='exito'>Formulario completado con éxito.</p>";
    } else {
        echo "<p class='error'>Se han encontrado uno o varios errores:</p><ul>";
        foreach ($errors as $error) {
            echo "<li>{$error}</li>";
        }
        echo "</ul>";

        
        if (($handle = fopen($archivo_id, "r")) !== FALSE) {
            echo "<form action='' method='POST'>";
            echo "<input type='hidden' name='archivo_id' value='{$archivo_id}'>";

            
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $label = htmlspecialchars($data[0]);
                $type = htmlspecialchars($data[1]);
                $id = htmlspecialchars($data[2]);
                $value = isset($_POST[$id]) ? htmlspecialchars($_POST[$id]) : '';

                
                echo "<label for='{$id}'>{$label}</label>";
                $class = isset($errors[$id]) ? '.invalido' : '';
                echo "<input type='{$type}' name='{$id}' id='{$id}' value='{$value}' class='{$class}' required>";
                echo "<br>";
            }
            
            fclose($handle);

            echo "<button type='submit' name='enviar_formulario'>Enviar</button>";
            echo "</form>";
        }
    }
} else {
    mostrarFormulario();
}

function mostrarFormulario() {
    echo '<form action="" method="POST" enctype="multipart/form-data">';
    echo '<label for="archivo_csv">Sube tu archivo CSV: </label>';
    echo '<input type="file" name="archivo_csv" id="archivo_csv" accept=".csv" required>';
    echo '<br/><br/>';
    echo '<button type="submit" name="cargar_csv">Cargar Formulario</button>';
    echo '</form>';
}

?>

</body>
</html>
