<?php

class Helper
{
    public static function getConn(): mysqli | bool{
        $servername = "localhost";
        $username = "root";
        $pass = "1748";
        $dbname = "salud";

        return mysqli_connect($servername, $username, $pass, $dbname);

    }
    public static function redirect(string $url): void {
        header("Location: $url");
        exit();
    }


    /**
     * Verifica si los campos están vacíos y devuelve un array con errores.
     *
     * @param array<string, string> $fields Array de campos donde las claves son los nombres de los campos y los valores son sus contenidos.
     * @return array<string, string> Array con mensajes de error para los campos vacíos.
     */
    public static function isEmpty(array $fields): array {
        $errors = [];
        foreach ($fields as $field => $value) {
            if (empty(trim($value))) {
                $errors[$field] = "El campo $field no puede estar vacío.";
            }
        }
        return $errors;
    }

}



