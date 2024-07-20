<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");
include_once(RUTABASE."/scripts/librerias/validacion.php"); //Ruta de la libreria de funciones

$nickUserActual = $acceso->getNick();
$codUserActual = $listaACL->getCodUsuario($nickUserActual);
$borradoActual = $listaACL->getBorrado($codUserActual); //tiene que ser false, 0, para que podamos entrar


//Validamos permisos del usuario actual
if ($acceso->hayUsuario() === true && (!$borradoActual)) {
    //comprobamos si no tiene permiso 1 y 2 a true
    if (!($acceso->puedePermiso(2))  || !($acceso->puedePermiso(3))) {
        paginaError("NO tienes permisos para acceder a la página añadir usuario");
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
$errores = []; //comprobar si se ha dado a insertar
$muestraFormulario = false;


if ($_GET) {

    $nick = "";
    if (isset($_GET["nick"])){
        $nick = trim($_GET["nick"]);


        //Comprobamos que existe el usuario con el id y tiene borrado a false
        $sentenciaUsuario = "SELECT * FROM `usuarios` WHERE `nick` = '$nick' AND `borrado` = 0;";
        $consulta = $mysqli->query($sentenciaUsuario);

        if ($consulta->num_rows === 0){
            paginaError("Error en el acceso de la base de datos");
            exit();
        }
        else{
            while ($fila = $consulta->fetch_assoc()) {
                $fecha = $fila["fecha_nacimiento"];
                $partes = [];
                if (preg_match("/([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2})/", $fecha, $partes)) {
                    $fecha = $partes[3] . "/" . $partes[2] . "/" . $partes[1];
                }

                $fila["fecha_nacimiento"] = $fecha;


                $filas[] = $fila;
            }

            if (@!$filas[0]){ //nos sale un warning de undefined key, ponemos @ 
                $errores["id"][] = "El usuario seleccionado no tiene la propiedad de borrado ";
            }

            else{
                $datos["nick"] = $filas[0]["nick"]; 
            }
        }
    }
}

//En caso de que alguien acceda sin ponerle un nick
if($_SERVER["QUERY_STRING"] === "" || (!array_key_exists("nick" ,$_GET))){
    paginaError("Error en el acceso de la base de datos");
    exit();
}




if ($_POST) {

    $borrado = 0;
    $borrar = false;
    if (isset($_POST["borraUsuario"])){
        $borrado = intval($_POST["borraUsuario"]);

        if ($borrado === 1){
            $borrar = true; //si se selecciona si, lo borramos
        }

    }

    //Si no hay errores y se ha seleccionado Si, borramo fisico del usuario
    if (!$errores && ($borrar === true)) 
    {
        //Borrado del usuario
        $borrado = "UPDATE `usuarios`  SET `borrado`='{$borrado}' WHERE `nick` = '{$_GET['nick']}'";

        $borraBBD = $mysqli->query($borrado);


        //borrado de usuario en la ACL
        
        $codUsuario = $listaACL->getCodUsuario($nick);
        $booleano = false; //lo pongo a false por defecto

        if($borrado === 1){$booleano = true;} //si se borra, se pone a true

        $borradoACL = $listaACL->setBorrado($codUsuario, $borrado);

        if ($borraBBD) {
            //Cuando se introducen los datos correctamente, mandamos al usuario a verUsuario.php con el id del usuario modificado
            $muestraFormulario = true;
        } else {
            ?>
            <script>
                alert("Error al borrar usuario")
            </script>
            <?php
        }

    }
}


//vista
inicioCabecera("Relacion 8 | Borrar usuario");
cabecera();
finCabecera();
inicioCuerpo("Formulario borrar usuario");
cuerpo($datos, $errores, $muestraFormulario,$filas);
finCuerpo();

// **********************************************************
function cabecera()
{
}


function cuerpo(array $datos, array  $errores, bool $muestraFormulario, array $filas)
{
?>
    <br>
    <br>
    <br>
<?php

    //Si hay errores
    if (isset($errores["id"])) {
        echo "<div class='error'>";
        foreach ($errores["id"] as $error)
            echo "$error<br> " . PHP_EOL;
        echo "</div>";
    }
    else{
        if ($muestraFormulario === true){
            ?>
            <script>alert("Usuario Actualizado!")</script>
            <?php
            header("location:verUsuario.php?nick=" . $_GET['nick']);
        }
        else{
                $array = $filas[0];
            ?>
                <button class="boton"> <a href="verUsuario.php?nick=<?php echo $array["nick"];?>"> Ver usuario</a> </button>
            <?php
            formularioVer($filas);
            formulario($datos, $errores);
        }
        
    }
    
}
function formulario(array $datos, array $errores)
{
?>

    <!--Formulario para añadir usuario -->
    <form action="" method="post"  id="deleteUser">
        <fieldset style="background-color: lightblue;">
            <legend style="background-color: white; border: black solid 1px;"><b>Borrar usuario</b></legend>
            <h3>¿Quiéres borrar el usuario <?php echo $datos['nick'];?>?</h3>
            <input type="radio" name="borraUsuario" value=0 checked> No
            <input type="radio" name="borraUsuario" value=1> Si
            <br>
            <br>
            <input type="submit" name="borrar" class="boton" value="Borrar usuario">
            <button class="boton"> <a href="index.php"> Cancelar </a> </button>
        </fieldset>
    </form>

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
function formularioVer(array $filas)
{

?>  <br><br>
    <form action="" method="post">
        <fieldset style="background-color: lightblue;">
            <legend style="background-color: white; border: black solid 1px;"><b>Datos usuario</b></legend>
            <?php
                foreach($filas[0] as $clave => $valor){
                    ?>
                    <label> <?php echo "<b>$clave</b>"; ?> </label>
                    <?php
                        if ($clave === "borrado"){
                                if(intval($valor) === 0){
                                    ?>
                                    <input type="radio" value="0" checked disabled>No
                                    <input type="radio" value="1" disabled>Si
                                    <br>
                                    <?php
                                }
                                else{
                                    ?>
                                    <input type="radio" value="0" disabled>No
                                    <input type="radio" value="1" checked disabled>Si
                                    <br>
                                    <?php
                                }
                        }

                        else if ($clave === "foto"){
                            echo "<img style='height: 100px; width: 100px' src ='../../imagenes/".$valor ."' >";
                        }

                        else{
                            ?>
                            <input type="text" value=<?php echo "'$valor'";?>  readonly>
                            <br>
                            <?php
                        }
                }
            ?>
        </fieldset>
    </form>
<?php
}
