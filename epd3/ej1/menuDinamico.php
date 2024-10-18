<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Menu dinamico</title>
</head>
<body>
    <p>Menu generado dinamicamente</p>
    <ul>
        <?php
            $secciones = [
                "Empresa" => "empresa.html",
                "Productos" => "productos.html",
                "Consultoria" => "consultoria.html",
                "Clientes" => "clientes.html",
                "Proyectos" => "proyectos.html",
                "Blog" => "blog.html",
                "Noticias" => "noticias.html",
                "Contacto" => "contacto.html"];
            
            $nRand = rand(2, count($secciones));
            
            function mostrarMenuAleatorio($secciones, $nRand) {
                $secRandom = array_rand($secciones, $nRand);
            
                foreach ($secRandom as $seccion) {
                    echo "<li><a href='{$secciones[$seccion]}'>{$seccion}</a></li>";
                }
            }

            // Llamada a la función para mostrar el menú
            mostrarMenuAleatorio($secciones, $nRand);
        ?>
    </ul>
</body>
</html>
