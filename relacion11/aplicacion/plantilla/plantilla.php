<?php

function paginaError($mensaje)
{
    header("HTTP/1.1 404 $mensaje");
    inicioCabecera("TIENDA");
    finCabecera();
    inicioCuerpo("ERROR");
    echo "<br />\n";
    echo $mensaje;
    echo "<br />\n";
    echo "<br />\n";
    echo "<br />\n";
    echo "<a href='/index.php'>Ir a la pagina principal</a>\n";
    finCuerpo();
}

function inicioCabecera($titulo)
{
?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="utf-8">
        <!-- Always force latest IE rendering engine (even in
intranet) & Chrome Frame
Remove this if you use the .htaccess -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $titulo ?></title>
        <meta name="description" content="">
        <meta name="author" content="Administrador">
        <meta name="viewport" content="width=device-width; initialscale=1.0">
        <!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="stylesheet" type="text/css" href="/estilos/base.css">
    <?php
}
function finCabecera()
{
    ?>

    </head>

<?php
}
function inicioCuerpo(string $cabecera, array $barraUbicacion=[])
{
    global $acceso;
?>

    <body>
        <div id="documento">
            <header>
               <a href="/" ><img src="\imagenes\logoTienda.jpg" id="logo"></a> <h1 id="titulo" style="display:inline;"><?php echo $cabecera; ?></h1>
            </header>
            <div id="barraLogin">
               
            
            </div>
            <div id="barraMenu">
  
            </div>
            <div id="barraUbicacion">
                <?php
                    if ($barraUbicacion)
                      {
                        foreach ($barraUbicacion as $ele)
                           {
                            if (isset($ele["texto"]))
                                {
                                    if (isset($ele["url"]))
                                        echo "<a href='{$ele["url"]}'>";

                                    echo " <span>".$ele["texto"]."</span>";

                                    if (isset($ele["url"]))
                                        echo "</a>";
                                    echo " &gt;&gt; ";
                                }
                           }
                      }
                ?>
            </div>
            <br>
            <div>
            <?php
        }

function finCuerpo()
{
    ?>
        <br />
        <br />
    </div>
    <footer>
        <hr width="80%" />
        <div>
            &copy; Copyright by Alejandro Terrones Pérez
        </div>
    </footer>
</div>
</body>

</html>
<?php
}
