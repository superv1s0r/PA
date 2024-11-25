<?php
$host = 'localhost';
$dbname = 'EPD6';
$username = 'root';
$password = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_EMAIL); // Usamos SANITIZE_EMAIL para validar el formato de email
    $contrasenya = $_POST['contrasenya'];

    if (strpos($usuario, '@almacen.com') === false) {
        $error = "El email debe tener el dominio @almacen.com.";
    } else {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = :usuario");
            $stmt->bindParam(':usuario', $usuario);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($contrasenya, $usuario_db['password'])) {
                    session_start();
                    $_SESSION['usuario'] = $usuario;

                    if (isset($usuario_db['id_rol'])) {
                        $_SESSION['rol'] = $usuario_db['id_rol'];
                    } else {
                        echo "Error: id_rol no encontrado en la base de datos.";
                        exit;
                    }

                    $_SESSION['id_usuario'] = $usuario_db['id_usuario']; // Corrección del nombre

                    setcookie("ultimoUsuario", $usuario, time() + 24 * 60 * 60,"/"); // Cookie válida por 1 día
                    header("Location: index.php");
                    exit;
                } else {
                    header("Location: login.php?error=" . urlencode("Contraseña incorrecta."));
                    exit;
                }
            } else {
                header("Location: login.php?error=" . urlencode("El usuario no existe."));
                exit;
            }
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }
}
?>
