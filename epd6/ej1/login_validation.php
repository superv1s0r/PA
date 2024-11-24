<?php
$host = 'localhost';         
$dbname = 'mysql'; 
$username = 'root';          
$password = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasenya = $_POST['contrasenya'];

    $hashed_password = password_hash($contrasenya, PASSWORD_BCRYPT);

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT * FROM  Usuario WHERE usuario = :usuario, contrasenya = :contrasenya");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':contrasenya', $hashed_password);

        $stmt->execute();
        
        if($stmt->rowCount() == 1){
            session_start();
            $_SESSION['usuario'] = $usuario;
            setcookie("ultimoUsuario", $usuario, time() + 24 * 60 * 60);
            header("Location: welcome.php ");
        }else{
            header("Location: login.php");
        }
    } catch (PDOException $e) {
    
        if ($e->getCode() == 23000) { 
            if (strpos($e->getMessage(), 'usuario') !== false) {
                header('Location: index.php?error=' . urlencode("Error: El nombre de usuario ya está registrado."));
            }
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}

?>


<script>
    setTimeout(function() {
        window.location.href = "./index.php";
    }, 1000); 
</script>

