<?php
// config.php

$host = 'localhost';
$dbname = 'epd06';
$username = 'root';
$password = '';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

echo "Connected successfully";
?>
