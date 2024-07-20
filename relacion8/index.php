<?php
include_once(dirname(__FILE__) . "/cabecera.php");
include_once(RUTABASE . "/scripts/librerias/validacion.php"); //Ruta de la libreria de funciones

//cogemos la ruta de index, en caso de iniciar sesion
//para que cuando nos registremos nos redirija aqui
$redirigir = $_SERVER["SCRIPT_NAME"];
$redirigir = "../.." . $redirigir;
$_SESSION["redirigir"] =  $redirigir;



inicioCabecera("Relacion 8"); /* ESTA EN PLANTILLA */
cabecera();
finCabecera(); /* ESTA EN PLANTILLA */

inicioCuerpo("Relacion 8"); /* ESTA EN PLANTILLA */
cuerpo($acceso, $listaACL);
finCuerpo(); /* ESTA EN PLANTILLA */



// ************FUNCIONES***************

function cabecera()
{
    echo "<script>
     </script>";
}


function cuerpo(Acceso $acceso, ACLBD $listaACL)
{

    echo "<br><br>" . PHP_EOL;
    //Llamamos a la var global COOKIE
    if (isset($_COOKIE["numero"])) { //Comprobamos que existe
        echo "<b>Nº de veces que se ha accedido al sitio</b> " . $_COOKIE["numero"] . "<br>" . PHP_EOL;
    } else {
        echo "<b>Nº de veces que se ha accedido al sitio</b> 0<br>" . PHP_EOL;
    }



?>  
    <ul>
        <?php
    $nickUserActual = $acceso->getNick();
    $codUserActual = $listaACL->getCodUsuario($nickUserActual);
    $borradoActual = $listaACL->getBorrado($codUserActual); //tiene que ser false, 0, para que podamos entrar
    
    
    //Validamos permisos del usuario actual
    if ($acceso->hayUsuario() === true && (!$borradoActual)) 
            if (($acceso->puedePermiso(1))) {
        ?>
                <li>
                    <a href="aplicacion/texto/verTextos.php"> Ver textos</a>
                </li>
        <?php
                if (($acceso->puedePermiso(2)  && (!$borradoActual))){
                    ?>
                        <li>
                            <a href="aplicacion/personalizar/personalizar.php"> Formulario personalizar</a>
                        </li>
                    <?php
                }
            }
        }
        ?>
    </ul>
    



<?php

