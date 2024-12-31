<?php

class Helper
{
    public static function getConn() {
        $servername = "localhost";
        $username = "root";
        $pass = "1748";
        $dbname = "salud";

        return mysqli_connect($servername, $username, $pass, $dbname);

    }
    public static function dirigir($url)
    {
        header("Location: $url");
        exit();
    }


    public static function isEmpty($fields)
    {
        $errors = [];
        foreach ($fields as $field => $value) {
            if (empty(trim($value))) {
                $errors[$field] = "El campo $field no puede estar vacío.";
            }
        }
        return $errors;
    }



}



