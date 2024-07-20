<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");
include_once(RUTABASE . "/scripts/librerias/validacion.php"); //Ruta de la libreria de funciones

$nickUserActual = $acceso->getNick();
$codUserActual = $listaACL->getCodUsuario($nickUserActual);
$borradoActual = $listaACL->getBorrado($codUserActual); //tiene que ser false, 0, para que podamos entrar


//Validamos permisos del usuario actual
if ($acceso->hayUsuario() === true && (!$borradoActual)) {
    //comprobamos si no tiene permiso 1 y 2 a true
    if (!($acceso->puedePermiso(2))) {
        paginaError("NO tienes permisos para acceder a la página ver usuario");
        exit();
    }
} //puedePermiso


else { //si el usuario no esta validado, registrado, lo llevamos a login

    //cuando vayamos a login y nos registremos
    //nos va a redirigir a la página donde estabamos antes, se la mandamos
    //desde la variable global session
    $redirigir = $_SERVER["SCRIPT_NAME"];
    $redirigir = explode("/", $redirigir);
    $redirigir = "../" . $redirigir[2] . "/" . $redirigir[3];
    $_SESSION["redirigir"] =  $redirigir;
    header("location:../acceso/login.php");
    exit();
}



//CONTROLADOR

//inicializaciones
$datos = [];

//comprobar si se ha dado a insertar
$errores = [];

//bool que nos permite mostrar resumen
$muestraResumen = false;

//array donde guardamos consulta
$filas = [];

if ($_GET) {

    $nick = "";
    if (isset($_GET["nick"])) {
        $nick = trim($_GET["nick"]);


        //Comprobamos que existe el usuario con el id
        $sentenciaUsuario = "SELECT * FROM `usuarios` WHERE `nick` = '$nick';";
        $consulta = $mysqli->query($sentenciaUsuario);

        if ((!$consulta) || (!isset($_SERVER["QUERY_STRING"]))) {
            paginaError("Error en el acceso de la base de datos");
            exit();
        } else {


            while ($fila = $consulta->fetch_assoc()) {
                $fecha = $fila["fecha_nacimiento"];
                $partes = [];
                if (preg_match("/([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2})/", $fecha, $partes)) {
                    $fecha = $partes[3] . "/" . $partes[2] . "/" . $partes[1];
                }

                $fila["fecha_nacimiento"] = $fecha;


                $filas[] = $fila;
            }

            if (@!isset($filas[0])) { //nos sale un warning de undefined key, ponemos @ 
                $errores["id"][] = "No existe el usuario   " . $nick;
            } else {
                //Mostramos resumen
                $muestraResumen = true;
            }
        }
    }
}

//En caso de que alguien acceda sin ponerle un nick
if ($_SERVER["QUERY_STRING"] === "" || (!array_key_exists("nick" ,$_GET))) {
    paginaError("Error en el acceso de la base de datos");
    exit();
}


//vista
inicioCabecera("Relacion 8 | Ver usuario");
cabecera();
finCabecera();
inicioCuerpo("Formulario ver usuario");
cuerpo($datos, $errores, $muestraResumen, $filas, $acceso, $listaACL);
finCuerpo();

// **********************************************************
function cabecera()
{
}


function cuerpo(array $datos, array  $errores, bool $muestraResumen, array $filas, Acceso $acceso, ACLBD $listaACL)
{


    //Si hay errores
    if (isset($errores["id"])) {
        echo "<div class='error'>";
        foreach ($errores["id"] as $error)
            echo "$error<br> " . PHP_EOL;
        echo "</div>";
    } else {
        $array = $filas[0];
?>
        <?php
        //tienes que tener permiso 3 para modificar y borrar
        if ($acceso->puedePermiso(3)) {
        ?>
            <button class="boton"> <?php echo "<a href='modificarUsuario.php?nick={$array['nick']}' >Modificar Usuario</a>" ?> </button>
            <button class="boton"> <?php echo "<a href='borrarUsuario.php?nick={$array['nick']}' >Borrar Usuario</a>" ?> </button>
        <?php
        }
        ?>



        <?php
        //Si no, mostramos datos en un formulario
        formularioVer($filas, $acceso, $listaACL);
        ?>
        <br>
        <button class="boton"> <a href="index.php">Cancelar </a> </button>

    <?php

    }
    ?>
    <br>
    <br>
    <br>
<?php


}



/**
 * Formulario que nos muestra
 * los datos de un usuario seleccionado
 * en la tabla index
 *
 * @param array $filas el array que contiene las filas
 * @return void no devuelve nada, muestra un formulario
 */
function formularioVer(array $filas, Acceso $acceso, ACLBD $listaACL)
{

?> <br><br>
    <form action="" method="post">
        <fieldset style="background-color: lightblue;">
            <legend style="background-color: white; border: black solid 1px;"><b>Datos usuario</b></legend>
            <?php
            foreach ($filas[0] as $clave => $valor) {

                if ($clave !== "cod_usuario") {
            ?>
                    <label> <?php echo "<b>$clave</b>"; ?> </label>
                    <?php
                    if ($clave === "nick") {

                        //Sacamos el nombre del usuario
                        //Obtenemos array de roles
                        $arrayRole = $listaACL->dameRoles();

                        //Obtenemos el codigo del usuario a partir del nick
                        $codigoUsuario = $listaACL->getCodUsuario($valor);


                        //A partir del codigo del usuario, obtenemos el codigo de su role
                        $codigoUsuarioRole = $listaACL->getUsuarioRole($codigoUsuario);

                        //Obtenemos el nombre del role a partir del usuario
                        $nombreRole = $arrayRole[intval($codigoUsuarioRole)];


                        //Sacamos nombre completo de usuario

                        //Primero obtenemos el codigo de usuario a partir del nick
                        $codigoUsuario = $listaACL->getCodUsuario($valor);

                        //Obtenemos el nombre a partir del codgio de usuario
                        $nombreUsuario = $listaACL->getNombre($codigoUsuario);


                    ?>
                        <input type="text" value=<?php echo "'$valor'"; ?> readonly>
                        <br>
                        <label for="nombre"><b>Nombre</b></label>
                        <input type="text" value="<?php echo  $nombreUsuario; ?>" readonly>
                        <br>
                        <label for="nombreRole"> <b>Role</b> </label>
                        <input type="text" name="nombreRole" value="<?php echo $nombreRole; ?>" readonly>
                        <br>
                        <?php
                    } else if ($clave === "borrado") {
                        if (intval($valor) === 0) {
                        ?>
                            <input type="radio" value="0" checked disabled>No
                            <input type="radio" value="1" disabled>Si
                            <br>
                        <?php
                        } else {
                        ?>
                            <input type="radio" value="0" disabled>No
                            <input type="radio" value="1" checked disabled>Si
                            <br>
                        <?php
                        }
                    } else if ($clave === "foto") {
                        echo "<img style='height: 100px; width: 100px' src ='../../imagenes/" . $valor . "' >";
                    } else {
                        ?>
                        <input type="text" value=<?php echo "'$valor'"; ?> readonly>
                        <br>
            <?php
                    }
                }
            }
            ?>
        </fieldset>
    </form>
<?php
}
