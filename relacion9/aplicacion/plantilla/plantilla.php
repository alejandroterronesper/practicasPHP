<?php
function paginaError($mensaje)
{
    header("HTTP/1.0 404 $mensaje");
    inicioCabecera("PRACTICA");
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
        <!-- Replace favicon.ico & apple-touch-icon.png in the root
of your domain and delete these references -->
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
function inicioCuerpo($cabecera)
{
    global $acceso;


    //declaramos la cookie que va a inicializar los valores por defecto
    if (!isset($_COOKIE["colorFondo"]) && !isset($_COOKIE["colorTexto"])) {

        //ponemos la ruta / para que este disponible en todo el dominio
        setcookie("colorFondo", "white", time() + (86400 * 30), '/');
        setcookie("colorLetra", "black", time() + (86400 * 30), '/');
    }


    //contador de visitas
    if (isset($_COOKIE["numero"])) { //si existe la cookie numero
        setcookie("numero", $_COOKIE["numero"] + 1, time() + 60 * 60);
    } else { //si no existe la cookie numero
        setcookie("numero", 1, time() + 60 * 60);
    }



?>
    <!--Establecemos el color fondo y la letra accediendo desde las cookies -->

    <body style="background-color: <?php if (isset($_COOKIE["colorFondo"])) {
                                        echo $_COOKIE["colorFondo"];
                                    } ?>;
                                                           color: <?php if (isset($_COOKIE["colorLetra"])) {
                                                                        echo $_COOKIE["colorLetra"];
                                                                    } ?> ;">
        <div id="documento">

            <header>
                <h1 id="titulo"><?php echo $cabecera; ?></h1>
            </header>

            <div id="barraLogin">
            <?php
                if ($_SESSION["objetoAcceso"]->hayUsuario() === true) {
                    //se comprueba si estamos validados para mostrar nombre de user y el boton de cerrar sesion
                    if ($_POST){

                        //comprobamos si se pulsa el boton
                        if (isset($_POST["cerrar"])){
                            //Si pulsamos cerrar, nos deslogueamos del usuario actual
                                
                            $_SESSION["objetoAcceso"]->quitarRegistroUsuario();
                            header("location:../../index.php");
                            exit();
                        }
                    }


                ?>
                    <!--Si esta validado, se muestra el nombre del usuario y un boton para cerrar la sesion -->
                    <form action="" method="post">
                        <?php
                            if ($_SESSION["objetoAcceso"]->hayUsuario() === true){
                                ?>
                                <!--Usuario actual -->
                                <p> <b>Bienvenido:</b> <i>  <?php echo $_SESSION["objetoAcceso"]->getNick() ?> </i><br></p>
                                <!--Cerrar sesion -->
                                <input type="submit" name="cerrar" class="boton" value="Cerrar sesion" >
                                <?php
                            }
                        ?>

                    </form>

                <?php
                }
                else{
                    if ($_POST){
                        if (isset($_POST["iniciar"])){

                            header("location:aplicacion/acceso/login.php");
                            exit();
                        }
                    }
                    //quitamos el boton de inicicio de sesion cuando estamos en login
                    //solo se muestra cuando no este en login
                    if ($_SERVER["PHP_SELF"] !== "/aplicacion/acceso/login.php"){
                        ?>
                        <form action="" method="post">
                            <input type="submit" name="iniciar" class="boton" value="Iniciar sesion" >
                        </form>
                        <?php
                    }
                }
                ?>
            </div>
            <div id="barraMenu">
                <ul>
                    <li><a href="/index.php">Inicio</a></li>
                </ul>
                <hr width="80%" />
            </div>
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
