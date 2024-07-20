<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");
include_once(RUTABASE . "/scripts/librerias/validacion.php"); //Ruta de la libreria de funciones
include_once(RUTABASE . "/scripts/librerias/utilidades.php");

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
$datos = [
    "contrasena" => "",
    "contrasena2" => ""
];

//comprobar si se ha dado a insertar
$errores = [];

//bool que nos permite mostrar formulario modificar
$muestraFormulario = false;

//array donde guardamos consulta
$filas = [];

//Rellenamos los datos del formulario
if ($_GET) {

    $nick = "";
    if (isset($_GET["nick"])) {
        $nick = trim($_GET["nick"]);


        //Comprobamos que existe el usuario con el id
        $sentenciaUsuario = "SELECT * FROM `usuarios` WHERE `nick` = '$nick';";
        $consulta = $mysqli->query($sentenciaUsuario);

        if (!$consulta) {
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

            if (@!$filas[0]) { //nos sale un warning de undefined key, ponemos @ 
                $errores["nick"][] = "No existe el usuario " . $nick;
            } else {

                //Cargamos el array de datos
                foreach ($filas[0] as $clave => $valor) {
                    $datos[$clave] = $valor;
                }
            }
        }
    }
}


//En caso de que alguien acceda sin ponerle un nick
if($_SERVER["QUERY_STRING"] === "" || (!array_key_exists("nick" ,$_GET))){
    paginaError("Error en el acceso de la base de datos");
    exit();
}

//Despues de comprobar que existe el nick, sacamos el role de usuario
//Tenemos que sacar el rol del usuario a modificar

//sacamos el codigo del usuario
$codigoUsuario = $listaACL->getCodUsuario($nick);

$datos["role"] = $listaACL->getUsuarioRole($codigoUsuario);
$datos["nombreCompleto"] = $listaACL->getNombre($codigoUsuario);

//Cuando modificamos el formulario
if ($_POST) {

    //nombre completo
    $nombreCompleto = "";
    if (isset($_POST["nombreCompleto"])){
        $nombreCompleto = trim($_POST["nombreCompleto"]);

        if($nombreCompleto === ""){
            $errores["nombreCompleto"][] = "Introduce un nombre";
        }

        if (!validaCadena($nombreCompleto, 20, "")){
            $errores["nombreCompleto"][] = "El nombre no puede tener más de 20 caracteres";
        }
    }
    $datos["nombreCompleto"] = $nombreCompleto;


    //contraseña
    $contrasena = "";
    $repiteContrasena ="";
    if (isset($_POST["contrasena"])){

        $contrasena = trim ($_POST["contrasena"]); 

        if (isset($_POST["repiteContrasena"])){
            $repiteContrasena = trim($_POST["repiteContrasena"]);
        }


        //Comprobamos que no sea cadena vacia
        if ($contrasena  === ""){
            $errores["contraseña"][] = "Introduce una contraseña!";
        }



        //Se comprueba la longitud de la cadena
        if (!validaCadena($contrasena, 32, "")){
            $errores["contraseña"][] = "La longitud de la contraseña debe ser de hasta 32 caracteres!";
        }


        //Se comprueba que la contraseña tenga un nº y una letra
        if ((!validaExpresion($contrasena, "/[a-zñA-ZÑ]{1,}/" ,""))  || (!validaExpresion($contrasena, "/[0-9]{1,}/" ,""))){
            $errores["contraseña"][] = "La contraseña debe incluir al menos un número y una letra";
        }


        //Se comprueba que contraseña y repite contraseña son iguales
        if ($contrasena !== $repiteContrasena){
            $errores["contraseña"][] = "Las contraseñas deben coincidir!";

            //si no coincide contraseña, vacio repiteContraseña
            $repiteContrasena = null;
        }
    }
    $datos["contrasena"] = $contrasena;
    $datos["contrasena2"] = $repiteContrasena;


    //rol
    $role = -1;
    if(isset($_POST["rol"])){

        $role = intval($_POST["rol"]);

        //array roles
        $arrayRole = $listaACL -> dameRoles();


        //comprobamos rango
        if (!validaRango($role, $arrayRole, 1)){
            $errores["role"][] = "No existe el role seleccionado";
        }
    }
    $datos["role"] = $role;

    //nif
    $nif = "";
    if (isset($_POST["nif"])) {
        $nif = trim($_POST["nif"]);

        if (strlen($nif) === 0) {
            $errores["nif"][] = "DNI vacío";
        }

        if (mb_strlen($nif) !== 9) { 
            $errores["nif"][] = "Introduce un nif, valido";
        }
    }
    $datos["nif"] = $nif;


    //direccion
    $direccion = "";
    if (isset($_POST["direccion"])) {
        $direccion = trim($_POST["direccion"]);

        if (strlen($direccion) === 0) {
            $errores["direccion"][] = "Dirección vacía";
        }

        if (!validaCadena($direccion, 20, "")) {
            $errores["direccion"][] = "Introduce un direccion, de menos de 20 caracteres";
        }
    }
    $datos["direccion"] = $direccion;



    //poblacion
    $poblacion  = "";
    if (isset($_POST["poblacion"])) {
        $poblacion = trim($_POST["poblacion"]);

        if (strlen($poblacion) === 0) {
            $errores["poblacion"][] = "Población vacía";
        }

        if (!validaCadena($direccion, 20, "")) {
            $errores["poblacion"][] = "Introduce una poblacion, de menos de 20 caracteres";
        }
    }
    $datos["poblacion"] = $poblacion;



    //provincia
    $provincia = "";
    if (isset($_POST["provincia"])) {
        $provincia = trim($_POST["provincia"]);

        if (strlen($provincia) === 0) {
            $errores["provincia"][] = "Provincia vacía";
        }

        if (!validaCadena($direccion, 20, "")) {
            $errores["provincia"][] = "Introduce una provincia, de menos de 20 caracteres";
        }
    }
    $datos["provincia"] = $provincia;



    //codigo postal
    $cp = 0;
    if (isset($_POST["cp"])) {
        $cp  = intval($_POST["cp"]);

        if (!validaEntero($cp, 10000, 99999, 0)) {
            $errores["cp"][] = "Introduce un código postal real";
        }
    }
    $datos["cp"] = $cp;


    //nacimiento
    $nacimiento  = "";
    if (isset($_POST["fecha_nacimiento"])) {
        $nacimiento = trim($_POST["fecha_nacimiento"]);

        if (!validaFecha($nacimiento, "")) {
            $errores["fecha_nacimiento"][] = "Introduce una fecha correcta";
        }
    }
    $datos["fecha_nacimiento"] = $nacimiento;


    //borrado
    $borrado = 0;
    if (isset($_POST["borrado"])) {
        $borrado = intval($_POST["borrado"]);

        //Validamos entre 0 y 1
        //ya que son los valores posibles de los radios
        if (!validaEntero($borrado, 0, 1, 0)) {
            $errores["borrado"][] = "Introduce una opción correcta";
        }
    }
    $datos["borrado"] = $borrado;


    //foto
    $foto = "";
    if (isset($_FILES["foto"])) {


        $foto = $_FILES["foto"];

        if ($foto["name"] === "") {
            $foto["name"] = $datos["foto"];
        } else {

            //comprobamos que sean del tipo jpg o png
            if (($foto["type"] !== "image/jpeg") && ($foto["type"] !== "image/png")) {
                $errores["foto"][] = "Debe subir una foto en extensión jpeg o png";
            }

            if (!validaEntero($foto["size"], 1, 100000, 1)) {
                $error["foto"][] = "La foto debe tener un tamaño entre 1 y 100000 bytes";
            }

            if ($foto["error"]) {
                $errores["foto"][] = "La foto contiene el error nº " . $foto["error"];
            }


            //comprobamos que no hay errores en foto
            if (@!$error["foto"]) {
                //llamamos a la funcion subir foto
                if (!subeFoto($_FILES["foto"])) { //si hay error
                    paginaError("Error en la subida de foto");
                    exit();
                } else {
                    $hazUpdate = true;
                }
            }
        }
    }
    $datos["foto"] = $foto["name"];



    if (!$errores) //no hay errores hago la insercion
    {

        //Si no hay errores,
        //se hace un update en la base de datos prueba9
        //tabla usuarios

        //formateamos la fecha
        $fechaArray = explode("/", $datos["fecha_nacimiento"]);
        $datos["fecha_nacimiento"] = $fechaArray[2] . "-" . $fechaArray[1] . "-" . $fechaArray[0];

        //sentencia insert
        $update = "UPDATE `usuarios`  SET `nif`='{$datos['nif']}', `direccion`= '{$datos['direccion']}', `poblacion`= '{$datos['poblacion']}',
                    `provincia`=  '{$datos['provincia']}', `cp`= '{$datos['cp']}', `fecha_nacimiento`= '{$datos['fecha_nacimiento']}',
                    `borrado`='{$datos['borrado']}', `foto`=  '{$datos['foto']}'
                    WHERE `nick` = '{$datos['nick']}'";


        $updateBBDD = $mysqli->query($update);


        //Hacemos update en la tabla acl_usuarios

        $updateNombre = $listaACL -> setNombre($codigoUsuario, $datos["nombreCompleto"]);
        $updateContrasenia = $listaACL->setContrasenia($codigoUsuario, $datos["contrasena"]);
        $updateUserRole = $listaACL->setUsuarioRole($codigoUsuario, $datos["role"]);
        $updateUseBorrado = $listaACL->setBorrado($codigoUsuario,$datos['borrado'] );


        if ($updateBBDD  && $updateNombre && $updateContrasenia && $updateUserRole  &&  $updateUseBorrado) {
            //Cuando se introducen los datos correctamente, mandamos al usuario a verUsuario.php con el id del usuario modificado
            $muestraFormulario = true;
        } else {
?>
            <script>
                alert("Error al modificar usuario")
            </script>
        <?php
        }
    }
}












//vista
inicioCabecera("Relacion 8 | Modificar Usuario");
cabecera();
finCabecera();
inicioCuerpo("Formulario modificar usuario");
cuerpo($datos, $errores, $muestraFormulario, $listaACL);
finCuerpo();

// **********************************************************
function cabecera()
{
}


function cuerpo(array $datos, array  $errores, bool $muestraFormulario, ACLBD $listaACL)
{

    if (isset($errores["nick"])) {
        echo "<div class='error'>";
        foreach ($errores["nick"] as $error)
            echo "$error<br> " . PHP_EOL;
        echo "</div>";
    } else {


        if ($muestraFormulario) {
        ?>
            <script>alert("Usuario Actualizado!");</script>
        <?php
            header("location:verUsuario.php?nick=" . $datos['nick']);
        } else {
        ?>

            <button class="boton"> <a href="verUsuario.php?nick=<?php echo $datos['nick']; ?>"> Ver usuario</a> </button>
            <br>
            <br>
    <?php
            formularioModificar($datos, $errores, $listaACL);
        }
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
function formularioModificar(array $datos, array $errores, ACLBD $listaACL)
{

?>
    <form action="" method="post" enctype="multipart/form-data"> <!--Para subir foto -->
        <fieldset style="background-color: lightblue;">
            <legend style="background-color: white; border: black solid 1px;"><b>Datos usuario</b></legend>
            <?php
            foreach ($datos as $clave => $valor) {
                if ($clave !== "role"  && $clave !== "cod_usuario"
                    && $clave !== "contrasena" && $clave !== "contrasena2"
                    && $clave !== "nombreCompleto") {
            ?>
                    <label> <?php echo "<b>$clave</b>"; ?> </label>
                    <?php
                    if ($clave === "borrado") {
                        if (intval($datos["borrado"]) === 0) {
                    ?>
                            <input type="radio" value="0" name=<?php echo "'$clave'"; ?> checked>No
                            <input type="radio" value="1" name=<?php echo "'$clave'"; ?>>Si
                            <br>
                            <?php
                            if (isset($errores["borrado"])) {
                                echo "<div class='error'>";
                                foreach ($errores["borrado"] as $error)
                                    echo "$error<br> " . PHP_EOL;
                                echo "</div>";
                            }
                            ?>
                        <?php
                        } else {
                        ?>
                            <input type="radio" value="0" name=<?php echo "'$clave'"; ?>>No
                            <input type="radio" value="1" name=<?php echo "'$clave'"; ?> checked>Si
                            <br>
                            <?php
                            if (isset($errores["borrado"])) {
                                echo "<div class='error'>";
                                foreach ($errores["borrado"] as $error)
                                    echo "$error<br> " . PHP_EOL;
                                echo "</div>";
                            }
                            ?>
                        <?php
                        }
                    } else {

                        if ($clave === "nick") { //el nick no se puede modificar
                        ?>
                            <input type="text" value=<?php echo "'$valor'"; ?> readonly>
                            <br>


                            <!--Nombre completo -->
                            <label for="nombreCompleto"> <b>Nombre completo </b> </label>
                            <input type="text" name="nombreCompleto" value="<?php echo $datos["nombreCompleto"]; ?>">
                            <?php
                            if (isset($errores["nombreCompleto"])) {
                                echo "<div class='error'>";
                                foreach ($errores["nombreCompleto"] as $error)
                                    echo "$error<br> " . PHP_EOL;
                                echo "</div>";
                            }
                            ?>
                            <br>

                            <!--Nueva contraseña -->
                            <label for="contrasena"> <b>Nueva contraseña <b> </label>
                            <input type="password" name="contrasena" value="<?php echo $datos['contrasena'];?>">
                            <?php
                            if (isset($errores["contraseña"])) {
                                echo "<div class='error'>";
                                foreach ($errores["contraseña"] as $error)
                                    echo "$error<br> " . PHP_EOL;
                                echo "</div>";
                            }
                            ?>
                            <br>


                            <!--Repite contraseña -->
                            <label for="repiteContrasena"> <b>Repite contraseña <b> </label>
                            <input type="password" name="repiteContrasena" value="<?php echo $datos['contrasena2'];?>">
                            <br>

                            <!--Elije un rol -->
                            <label for="rol"> <b>Elije un rol </b> </label>
                            <select name="rol">
                                <option value=-1>--Elije un rol--</option>
                                <?php
                                //guardamos los roles y los iteramos para el select
                                $arrayRole = $listaACL->dameRoles();
                                foreach ($arrayRole as $clave => $valor) {
                                    echo "<option  value=$clave";
                                    if ($datos["role"] == $clave) {
                                        echo " selected='selected'";
                                    }
                                    echo ">$valor</option>" . PHP_EOL;
                                }
                                ?>
                            </select>
                            <?php
                            if (isset($errores["role"])) {
                                echo "<div class='error'>";
                                foreach ($errores["role"] as $error)
                                    echo "$error<br> " . PHP_EOL;
                                echo "</div>";
                            }
                            ?>
                            <br>
                        <?php

                        }
                        
                        else if ($clave === "foto") {
                        ?>
                            <input type="hidden" name="MAX_FILE_SIZE" value=100000>
                            <input type="file" name=<?php echo "'$clave'"; ?> accept="image/*"> <!--Solo se permiten subida de archivos de tipo imagen -->
                            <?php
                            if (isset($errores["$clave"])) {
                                echo "<div class='error'>";
                                foreach ($errores["$clave"] as $error)
                                    echo "$error<br> " . PHP_EOL;
                                echo "</div>";
                            }
                            ?>
                            <br>
                        <?php
                        } else {
                        ?>
                            <input type="text" name=<?php echo "'$clave'"; ?> value='<?php echo $datos["$clave"]; ?>'>
                            <?php
                            if (isset($errores["$clave"])) {
                                echo "<div class='error'>";
                                foreach ($errores["$clave"] as $error)
                                    echo "$error<br> " . PHP_EOL;
                                echo "</div>";
                            }
                            ?>
                            <br>
                    <?php
                        }
                    }
                    ?>
            <?php
                }
            }
            ?>
            <!--Modificar usuario-->
            <br>
            <input type="submit" name="modificar" class="boton" value="Modificar usuario">
            <button class="boton"> <a href="index.php"> Cancelar </a> </button>
        </fieldset>
    </form>
<?php
}
