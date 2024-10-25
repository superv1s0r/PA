<!DOCTYPE html>
<html>
<body>
<?php
function tratar_Error(&$errores)
{
    if (isset($_POST['envio'])) {

        if (!isset($_POST['name']) || empty($_POST['name']))
            $errores[] = 'Indique su nombre';
        if (!isset($_POST['fecha_llamada']) || empty($_POST['fecha_llamada']))
            $errores[] = 'Indique la fecha de llamada';
        if (!isset($_POST['hora_llamada']) || empty($_POST['hora_llamada']))
            $errores[] = 'Indique la hora de la llamada';
        if (!isset($_POST['n_dias']) || empty($_POST['n_dias']))
            $errores[] = 'Indique un turno';
        if (!isset($_POST['peticion']) || empty($_POST['peticion']))
            $errores[] = 'No dejes en blanco la petición';

        if(strlen($_POST['name'])> 200) {

            $errores[] = 'El nombre es mayor que 200 caracteres';
        }
        if(!empty($_POST['fecha_llamada']) && (date('Y-m-d') > $_POST['fecha_llamada'])) {

            $errores[] = 'La fecha llamada es mayor que la fecha actual';

        }
        if(!empty($_POST['hora_llamada'])&& strtotime($_POST['hora_llamada']) < strtotime('09:00') || strtotime($_POST['hora_llamada']) > strtotime('18:00')) {

            $errores[] = 'La fecha llamada está fuera del rango';

        }
        if(!empty($_POST['n_dias']) &&  !preg_match('/^\d+(\.\d{1,2})?$/', $_POST['n_dias'])) {

            $errores[] = 'EL numero de dia debe de ser decimal y como maximo 2 decimales';

        }
        if(strlen($_POST['peticion']) > 500) {
            $errores[] = 'El contenido de la gestion es mayor que 500 caracteres';
        }


        if (empty($errores)) {
            echo '<h1> Recibidos </h1>';
            echo 'Cliente: ' . ($_POST['name']) . '<br />';
            $newTimestamp = strtotime('+'. round($_POST['n_dias']) . 'days', strtotime($_POST['fecha_llamada']));

            echo  'Fecha máxima para dar una respuesta: ' . date('Y/m/d',$newTimestamp) . ' - ' .($_POST['hora_llamada']) . ' <br />';
            echo 'Estado: ' . ($_POST['peticion']) . ' <br />';
        }
    }
}

$errores = [];
if (isset($_POST['envio'])) {
    tratar_Error($errores);
}

if (!isset($_POST['envio']) || !empty($errores)) {
    echo '<h1> Formulario de petici&oacute;n </h1>';
    if (!empty($errores)) {
        echo '<p style="color:red">Errores cometidos:</p>';
        echo '<ul style="color:red">';
        foreach ($errores as $e)
            echo "<li>$e</li>";
        echo '</ul>';
    }


    ?>


    <form method="post">
        <label for="name">Nombre del Cliente:</label>
        <input type="text" name="name" id="name" value="<?php echo isset($_POST['name']) ? trim($_POST['name']) : ''; ?>">
        <br><br>
        <label for="fecha_llamada">Fecha de recepción de la llamada:</label>
        <input type="date" name="fecha_llamada" id="fecha_llamada" value="<?php echo isset($_POST['fecha_llamada']) ? trim($_POST['fecha_llamada']) : ''; ?>">
        <br><br>
        <label for="hora_llamada">Hora de recepción de la llamada:</label>
        <input type="time" name="hora_llamada" id="hora_llamada" value="<?php echo isset($_POST['hora_llamada']) ? trim($_POST['hora_llamada']) : ''; ?>">
        <br><br>
        <label for="n_dias">Número de días para dar una respuesta:</label>
        <input type="text" name="n_dias" id="n_dias" value="<?php echo isset($_POST['n_dias']) ? trim($_POST['n_dias']) : ''; ?>">
        <br><br>
        <label for="peticion">Petición:</label>
        <textarea name="peticion" id="peticion" rows="3" cols="35"><?php echo isset($_POST['peticion']) ? trim($_POST['peticion']) : ''; ?></textarea>
        <br><br>
        <button type="submit" name="envio" id="envio">Enviar</button>
    </form>

    <?php
}
?>
</body>
</html>
