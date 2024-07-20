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
    "nick" => "",
    "nombre" => "",
    "contrasena" => "",
    "contrasena2" => "",
    "role" =>-1,
    "nif" => "",
    "direccion" => "",
    "poblacion" => "",
    "provincia" => "",
    "codigoPostal" => 0,
    "nacimiento" => "",
    "borrado" => 0,
    "foto" => ""
];
$errores = []; //comprobar si se ha dado a insertar

if ($_POST) {


    //NICK
    $nick = "";
    if (isset($_POST["nick"])) {
        $nick = trim($_POST["nick"]);

        if (strlen($nick) === 0) {
            $errores["nick"][] = "Cadena vacía";
        }

        if (!validaCadena($nick, 20, "")) {
            $errores["nick"][] = "Introduce un nick, de menos de 20 caracteres";
        }

        $select = "SELECT `nick` FROM `usuarios` WHERE nick= '$nick'";
        $buscarNick = $mysqli->query($select);

        if ($buscarNick->num_rows > 0) {
            $errores["nick"][] = "Ese nick ya está registrado";
        }
    }
    $datos["nick"] = $nick;


    //Nombre
    $nombre = "";
    if(isset($_POST["nombreCompleto"])){
        
        $nombre = trim($_POST["nombreCompleto"]);

        if($nombre === ""){
            $errores["nombre"][] = "Introduce un nombre";
        }

        if (!validaCadena($nombre, 20, "")){
            $errores["nombre"][] = "El nombre no puede tener más de 20 caracteres";
        }

    }
    $datos["nombre"] = $nombre;


    //NIF
    $nif = "";
    if (isset($_POST["nif"])) {
        $nif = trim($_POST["nif"]);

        if (strlen($nif) === 0) {
            $errores["nif"][] = "Cadena vacía";
        }

        if (mb_strlen($nif) !== 9) { 
            $errores["nif"][] = "Introduce un nif, valido";
        }
    }
    $datos["nif"] = $nif;




    //contraseña
    $contrasena = "";
    $repiteContrasena = "";
    if (isset($_POST["contrasena"])){
        $contrasena = trim($_POST["contrasena"]);
        
        if(isset($_POST["contrasena2"])){
            $repiteContrasena = trim($_POST["contrasena2"]);
        }


        //Se comprueba que la cadena no sea vacia
        if ($contrasena === ""){
            $errores["contraseña"][] = "Introduce una contraseña!";
        }

        //Se comprueba que tenga una longitud de hasta 32 caracteres
        if (!validaCadena($contrasena, 32, "")){
            $errores["contraseña"][] = "La longitud de la contraseña debe ser de hasta 32 caracteres!";
        }


        //Se comprueba que la contraseña tenga un número y una letra      
        if((!validaExpresion($contrasena, "/[a-zñA-ZÑ]{1,}/" ,""))  || (!validaExpresion($contrasena, "/[0-9]{1,}/" ,""))){
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


    //role
    $role = -1;
    if(isset($_POST["role"])){

        $role = intval($_POST["role"]);

        //guardamos roles
        $arrayRole = $listaACL -> dameRoles();


        //comprobamos el la posicion del array
        if(!validaRango($role, $arrayRole, 1)){
            $errores["role"][] = "No existe el role seleccionado";
        }
    }
    $datos["role"] = $role;
    



    //Direccion
    $direccion = "";
    if (isset($_POST["direccion"])) {
        $direccion = trim($_POST["direccion"]);

        if (strlen($direccion) === 0) {
            $errores["direccion"][] = "Cadena vacía";
        }

        if (!validaCadena($direccion, 20, "")) {
            $errores["direccion"][] = "Introduce un direccion, de menos de 20 caracteres";
        }
    }
    $datos["direccion"] = $direccion;


    //Poblacion
    $poblacion  = "";
    if (isset($_POST["poblacion"])) {
        $poblacion = trim($_POST["poblacion"]);

        if (strlen($poblacion) === 0) {
            $errores["poblacion"][] = "Cadena vacía";
        }

        if (!validaCadena($direccion, 20, "")) {
            $errores["poblacion"][] = "Introduce una poblacion, de menos de 20 caracteres";
        }
    }
    $datos["poblacion"] = $poblacion;



    //Provincia
    $provincia = "";
    if (isset($_POST["provincia"])) {
        $provincia = trim($_POST["provincia"]);

        if (strlen($provincia) === 0) {
            $errores["provincia"][] = "Cadena vacía";
        }

        if (!validaCadena($direccion, 20, "")) {
            $errores["provincia"][] = "Introduce una provincia, de menos de 20 caracteres";
        }
    }
    $datos["provincia"] = $provincia;



    //CODIGO POSTAL
    $codigoPostal  = 0;
    if (isset($_POST["codigoPostal"])) {
        $codigoPostal = intval($_POST["codigoPostal"]);

        if (!validaEntero($codigoPostal, 10000, 99999, $codigoPostal)) {
            $errores["codigoPostal"][] = "Introduce un código postal real";
        }
    }
    $datos["codigoPostal"] = $codigoPostal;


    //Nacimiento
    $nacimiento  = "";
    if (isset($_POST["nacimiento"])) {
        $nacimiento = trim($_POST["nacimiento"]);

        if (!validaFecha($nacimiento, "")) {
            $errores["nacimiento"][] = "Introduce una fecha correcta";
        }
    }
    $datos["nacimiento"] = $nacimiento;


    //Borrado
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


    //Foto
    $foto = "";
    if (isset($_FILES["foto"])) {

        $foto = $_FILES["foto"];


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
    }
    $datos["foto"] = $foto["name"];



    if (!$errores) //no hay errores hago la insercion
    {

        $hazInsert = false;

        //subimos foto
        if (isset($datos["foto"])) {

            //llamamos a la funcion subir foto
            if (!subeFoto($_FILES["foto"])) { //si hay error
                
            } else {
                $hazInsert = true;
            }
        }

        if (!$hazInsert) {
            paginaError("Error en el acceso de la base de datos");
            exit();
        } 
        
        else {
            //Si no hay errores,
            //se hace un insert en la base de datos prueba9
            //tabla usuarios

            //formateamos la fecha
            $fechaArray = explode("/", $datos["nacimiento"]);
            $datos["nacimiento"] = $fechaArray[2] . "-" . $fechaArray[1] . "-" . $fechaArray[0];

            //sentencia insert en la tabla USUARIOS
            $insertar = "INSERT INTO `usuarios` (`nick`, `nif`, `direccion`, `poblacion`, `provincia`, `cp`, `fecha_nacimiento`, `borrado`, `foto`)
            VALUES ('{$datos['nick']}','{$datos['nif']}','{$datos['direccion']}','{$datos['poblacion']}','{$datos['provincia']}','{$datos['codigoPostal']}','{$datos['nacimiento']}','{$datos['borrado']}','{$datos['foto']}')";


            $insertarBBDD = $mysqli->query($insertar);


            //Hacemos insert a la tabla de acl_usuarios
            $insertarACL = $listaACL->anadirUsuario($datos["nombre"],$datos["nick"],$datos["contrasena"], $datos["role"]);

            //Se comprueba que se añadio bien a la tabla usuarios
            // y a acl_usuarios
            if ($insertarBBDD && $insertarACL) {
                ?>
                    <script>alert("Usuario registrado!")</script>
                <?php
                    header("location: verUsuario.php?nick={$datos['nick']}");
            } else {
            ?>
                <script>
                    alert("Error al insertar usuario")
                </script>

            <?php
            }
        }
    }
}


//vista
inicioCabecera("Relacion 8 | Añadir usuario");
cabecera();
finCabecera();
inicioCuerpo("Formulario añadir usuario");
cuerpo($datos, $errores, $listaACL);
finCuerpo();

// **********************************************************
function cabecera()
{
}


function cuerpo(array $datos, array  $errores, ACLBD $listaACL)
{
    ?>
    <br>
    <br>
    <br>
<?php
    formulario($datos, $errores, $listaACL);
}
function formulario(array $datos, array $errores, ACLBD $listaACL)
{
?>

    <!--Formulario para añadir usuario -->
    <form action="" method="post" id="formularioAddUser" enctype="multipart/form-data">
        <h3 style="text-align: center;">Añadir usuario</h3>


        <!--Nick -->
        <label for="nick"><b>Nick</b></label>
        <input type="text" name="nick" value="<?php echo $datos["nick"]; ?>">
        <?php
        if (isset($errores["nick"])) {
            echo "<div class='error'>";
            foreach ($errores["nick"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>
        <br>

        <!--Nombre completo -->
        <label for="nombreCompleto"> <b>Nombre completo</b>  </label>
        <input type="text"  name="nombreCompleto" value="<?php echo $datos["nombre"]; ?>">
        <?php
        if (isset($errores["nombre"])) {
            echo "<div class='error'>";
            foreach ($errores["nombre"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>
        <br>

        <!--Contraseña -->
        <label for="contrasena">  <b>Contraseña</b>  </label>
        <input type="password"  name="contrasena" value="<?php echo $datos["contrasena"]; ?>">
        <?php
        if (isset($errores["contraseña"])) {
            echo "<div class='error'>";
            foreach ($errores["contraseña"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>
        <br>

        <!-- Repetir contraseña -->
        <label for="contrasena2">  <b>Repita contraseña</b>  </label>
        <input type="password"  name="contrasena2" value="<?php echo $datos["contrasena2"]; ?>">
        <br>


        <!--Elije role -->
        <label for="role"> <b>Elige un rol</b>  </label>
        <select name="role">
            <option value=-1>--Elige rol--</option>
            <?php
                //guardamos los roles y los iteramos para el select
                $arrayRole = $listaACL -> dameRoles();
                foreach ($arrayRole as $clave => $valor){
                        echo "<option  value=$clave";
                        if ($datos["role"] == $clave){
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
        <!--NIF -->
        <label for="nif"><b>NIF</b></label>
        <input type="text" name="nif" value="<?php echo $datos["nif"]; ?>">
        <?php
        if (isset($errores["nif"])) {
            echo "<div class='error'>";
            foreach ($errores["nif"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>
        <br>


        <!--Direccion -->
        <label for="direccion"><b>Direccion</b></label>
        <input type="text" name="direccion" value="<?php echo $datos["direccion"];  ?>">
        <?php
        if (isset($errores["direccion"])) {
            echo "<div class='error'>";
            foreach ($errores["direccion"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>
        <br>


        <!--Poblacion -->
        <label for="poblacion"><b>Poblacion</b></label>
        <input type="text" name="poblacion" value="<?php echo $datos["poblacion"]; ?>">
        <?php
        if (isset($errores["poblacion"])) {
            echo "<div class='error'>";
            foreach ($errores["poblacion"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>
        <br>

        <!--Provincia -->
        <label for="provincia"><b>Provincia</b></label>
        <input type="text" name="provincia" value="<?php echo $datos["provincia"]; ?>">
        <?php
        if (isset($errores["provincia"])) {
            echo "<div class='error'>";
            foreach ($errores["provincia"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>

        <br>

        <!--Código postal -->
        <label for="codigoPostal"><b>Código postal:</b> </label>
        <input type="text" name="codigoPostal" value=<?php echo $datos["codigoPostal"]; ?>>
        <?php
        if (isset($errores["codigoPostal"])) {
            echo "<div class='error'>";
            foreach ($errores["codigoPostal"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>

        <br>

        <!--Nacimiento -->
        <label for="nacimiento"><b>Fecha de nacimiento</b></label>
        <input type="text" name="nacimiento" value="<?php echo $datos["nacimiento"]; ?>" placeholder="dd/mm/yyyy">
        <?php
        if (isset($errores["nacimiento"])) {
            echo "<div class='error'>";
            foreach ($errores["nacimiento"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>

        <br>

        <!--Borrado -->
        <label for="borrado"><b>Borrado</b></label>
        <?php
        /*Comprobamos el radio pulsado */
        if ($datos["borrado"] === 1) {
        ?>
            <input type="radio" name="borrado" value=1 checked>Si
            <input type="radio" name="borrado" value=0>No
        <?php
        } else {
        ?>
            <input type="radio" name="borrado" value=1>Si
            <input type="radio" name="borrado" value=0 checked>No
        <?php
        }
        ?>
        <?php
        if (isset($errores["borrado"])) {
            echo "<div class='error'>";
            foreach ($errores["borrado"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>

        <br>

        <!--Foto -->
        <label for="foto"><b>Foto</b></label>
        <input type="hidden" name="MAX_FILE_SIZE" value=100000>
        <input type="file" name="foto" accept="image/*"> <!--Solo se permiten subida de archivos de tipo imagen -->
        <?php
        if (isset($errores["foto"])) {
            echo "<div class='error'>";
            foreach ($errores["foto"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>
        <br>
        <br>

        <input type="submit" name="crear" class="boton" value="Añadir usuario">
        <button class="boton"> <a href="index.php">Cancelar</a> </button>
    </form>




<?php
}
