<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");
include_once(RUTABASE . "/scripts/librerias/validacion.php"); //Ruta de la libreria de funciones

$nickUserActual = $acceso->getNick();
$codUserActual = $listaACL->getCodUsuario($nickUserActual);
$borradoActual = $listaACL->getBorrado($codUserActual); //tiene que ser false, 0, para que podamos entrar


//Validamos permisos del usuario actual
if ($acceso->hayUsuario() === true && (!$borradoActual)) {
    if (!($acceso->puedePermiso(1))){//comprobamos si no tiene permiso 1 a true
        paginaError("NO tienes permisos para acceder a la página personalizar");
        exit();
    }
}
else{ //si el usuario no esta validado, registrado, lo llevamos a login

    //cuando vayamos a login y nos registremos
    //nos va a redirigir a la página donde estabamos antes, se la mandamos
    //desde la variable global session
    $redirigir = $_SERVER["SCRIPT_NAME"];
    $redirigir = explode("/", $redirigir);
    $redirigir = "../". $redirigir[2]. "/" .$redirigir[3];
    $_SESSION["redirigir"] =  $redirigir;
    header("location:../acceso/login.php");
    exit();
}



//CONTROLADOR
//inicializaciones
$datos = [

];

//Si existe la sesión, 
//accedemos al array Textos guarda en la sesion
// y actualizamos el array
if (isset($_SESSION["arrayTextos"])) { //si existe la sesion 
    if (gettype($_SESSION["arrayTextos"]) === "array") {
        foreach ($_SESSION["arrayTextos"] as $key => $valor) {
            $textos[] = $valor;
        }
    }
}



$errores = []; //comprobar si se ha dado a insertar

if ($_POST) {

    $crearTexto = "";
    if (isset($_POST["crearTexto"])){
        $crearTexto = trim($_POST["crearTexto"]);

        if ($crearTexto == ""){
            $errores["crearTexto"][] = "Introduce una cadena";
        }
        else{
            $objTexto = new registroTexto($crearTexto);
            array_push($textos, $objTexto);

            //actualizamos sesion, vaciamos el array
            $_SESSION["arrayTextos"] = [];
    
            foreach($textos as $key => $valor){
                
                array_push($_SESSION["arrayTextos"], $valor);
            }
        }
    }

    if (isset($_POST["borrar"])) {

        if (count ($textos ) > 0) { //si hay objetos borramos
            $textos = [];
            $_SESSION["arrayTextos"] = [];
        }
        else{
            //si no hay textos, avisamos al usuario con un error
            $errores["borrar"][] = "No hay textos que borrar";
        }
    }



}





//vista
inicioCabecera("Formulario para crear textos");
cabecera();
finCabecera();
inicioCuerpo("Formulario textos");
cuerpo($datos, $errores, $textos);
finCuerpo();

// ********************************************************** //
function cabecera()
{
}


function cuerpo(array $datos, array  $errores, array $textos)
{
?>
    <br>
    <br>
    <br>
<?php
    formulario($datos, $errores, $textos);
}



function formulario(array $datos, array $errores, array $textos)
{


?>


    <form action="" method="post">
        <label for="crearTexto"><b>Crea texto</b></label>
        
        <input type="text" name="crearTexto" placeholder="introduce texto">
        <br>
        <?php
        if (isset($errores["crearTexto"])) {
            echo "<div class='error'>";
            foreach ($errores["crearTexto"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>
        <br>
        <br>
        <!-- Boton para crear texto -->
        <input type="submit" name="crear" class="boton" value="Crea texto">
    </form>

    <form action="" method="post">
        <br>
        <br>
        <h3>Textos registrados</h3>
        <textarea readonly rows="10" cols="60">
        <?php
        echo "\n";
        if (isset($textos)) {
            foreach ($textos as $clave => $valor) {
                echo "\t".$valor;
                echo "\n";
            }
        }
        ?>
        </textarea>

        <br>
        <br>
        <!--Boton para borrar todos los textos -->
        <input type="submit" name="borrar" class="boton" value="Borra todo los textos">
        <?php
        if (isset($errores["borrar"])) {
            echo "<div class='error'>";
            foreach ($errores["borrar"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>

    </form>



<?php
}
