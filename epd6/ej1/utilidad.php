<?php
//Clase para las funciones frecuentemente usadas
class Helper {
    public static function redirect($url) {
        header("Location: $url");
        exit();
    }

    public static function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public static function validateFields($fields) {
        $errors = [];
        foreach ($fields as $field => $value) {
            if (empty(trim($value))) {
                $errors[$field] = "El campo $field no puede estar vacío.";
            }
        }
        return $errors;
    }

    public static function formatErrors($errors) {
        $output = "<ul style='color: red;'>";
        foreach ($errors as $error) {
            $output .= "<li>$error</li>";
        }
        $output .= "</ul>";
        return $output;
    }
    public static function validateEmailDomain($email, $domain = '@almacen.com')
    {
        if (!str_ends_with($email, $domain)) {
            return "El email debe tener el dominio '$domain'.";
        }
        return null; // Retorna null si no hay errores
    }

}
