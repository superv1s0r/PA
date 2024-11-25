<?php
class Helper
{
    public static function redirect($url)
    {
        header("Location: $url");
        exit();
    }

    public static function sanitizeInput($input)
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public static function validateFields($fields)
    {
        $errors = [];
        foreach ($fields as $field => $value) {
            if (empty(trim($value))) {
                $errors[$field] = "El campo $field no puede estar vacío.";
            }
        }
        return $errors;
    }

    public static function formatErrors($errors)
    {
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


   public static function listarUsuarios($pdo, $rol, $idUsuarioActual)
   {
        $rol = Helper::sanitizeInput($rol);
        $idUsuarioActual = Helper::sanitizeInput($idUsuarioActual);


        if ($rol == 1) {
            $stmt = $pdo->prepare("SELECT * FROM usuario");
        } elseif ($rol == 2) {
            $stmt = $pdo->prepare("SELECT * FROM usuario WHERE id_rol = 3 OR id_usuario = :idUsuarioActual");
            $stmt->bindParam(':idUsuarioActual', $idUsuarioActual);
        } elseif ($rol == 3) {
            $stmt = $pdo->prepare("SELECT * FROM usuario WHERE id_usuario = :idUsuarioActual");
            $stmt->bindParam(':idUsuarioActual', $idUsuarioActual);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function crear($pdo, $rol, $datosUsuario)
    {

        if ($rol == 1 || ($rol == 2 && $datosUsuario['id_rol'] == 3)) {
            $stmt = $pdo->prepare("INSERT INTO usuario (nombre, email, password, id_rol) VALUES (:nombre, :email, :password, :id_rol)");
            $stmt->bindParam(':nombre', $datosUsuario['nombre']);
            $stmt->bindParam(':email', $datosUsuario['email']);
            $stmt->bindParam(':password', $datosUsuario['password']);
            $stmt->bindParam(':id_rol', $datosUsuario['id_rol']);
            return $stmt->execute();
        }
        return "No tienes permisos para crear este tipo de usuario.";
    }


    public static function editar($pdo, $rol, $idUsuarioActual, $idUsuario, $nuevosDatos)
    {
        if ($rol == 3 && $idUsuario != $idUsuarioActual) {
            return "No tienes permiso para editar información de otros usuarios.";
        }

        if ($rol == 2 && $idUsuario != $idUsuarioActual && !verificarRol($pdo, $idUsuario, 3)) {
            return "No tienes permiso para editar usuarios que no sean operarios.";
        }

        $stmt = $pdo->prepare("UPDATE usuario SET nombre = :nombre, email = :email WHERE id_usuario = :idUsuario");
        $stmt->bindParam(':nombre', $nuevosDatos['nombre']);
        $stmt->bindParam(':email', $nuevosDatos['email']);
        $stmt->bindParam(':idUsuario', $idUsuario);
        return $stmt->execute();
    }


    public static function eliminar($pdo, $idUsuario)
    {

        $stmt = $pdo->prepare("DELETE FROM usuario WHERE id_usuario = :idUsuario");
        $stmt->bindParam(':idUsuario', $idUsuario);
        return $stmt->execute();
    }

}



