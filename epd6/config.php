<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "Usuario";

$conn = new mysqli($host, $username, $password, $dbname);

if (!$conn) {
    die("Conexion fallida". mysqli_connect_error());
}else{
    echo "Conectado con exito!!";
}
?>
