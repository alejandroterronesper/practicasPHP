<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");
include_once(RUTABASE . "/scripts/librerias/validacion.php"); //Ruta de la libreria de funciones

//Validamos permisos del usuario actual
if ($_SESSION["objetoAcceso"]->hayUsuario() === true){ 
    //comprobamos si no tiene permiso 1 y 2 a true
    if (!($_SESSION["objetoAcceso"]->puedePermiso(1))  || !($_SESSION["objetoAcceso"]->puedePermiso(2))){
        paginaError("NO tienes permisos para acceder a la página personalizar");
        exit();
    }
}//puedePermiso
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
    "colorFondo" => "white",
    "colorLetra" => "black"
];


$errores = []; //comprobar si se ha dado a insertar

if ($_POST) {

    $colorFondo ="";
    if (isset($_POST["colorFondo"])){
        $colorFondo = $_POST["colorFondo"];

        if (!validaRango($colorFondo, COLORESFONDO, 1)){
            $errores["colorFondo"][] = "Elije un color de fondo";
        }


    }
    $datos["colorFondo"] = $colorFondo;


    $colorLetra ="";
    if (isset($_POST["colorLetra"])){
        $colorLetra = $_POST["colorLetra"];

        if (!validaRango($colorLetra, COLORESTEXTO, 1)){
            $errores["colorLetra"][] = "Elije un color para las letras";
        }

    }
    $datos["colorLetra"] = $colorLetra;

    //cuando pulsamos el boton de cerrar sesion
    //va a entrar en POST
    //por lo que para cambiar los valores del color fondo y texto
    //nos aseguramos que solo ocurra cuando no haya errores
    //y el boton cerrar sesion no esta pulsado, es decir, este a null
    if (!$errores && !(isset($_POST["cerrar"]))) //no hay errores hago la insercion
    {

        //comprobamos que existen las cookies, luego asigno valores otra vez
        if (isset ($_COOKIE["colorFondo"]) && isset ($_COOKIE["colorLetra"])){
            
            //ponemos la ruta / para que este disponible en todo el dominio
            setcookie("colorFondo", $_COOKIE["colorFondo"] = $colorFondo,time() + 60 * 60, '/');
            setcookie("colorLetra", $_COOKIE["colorLetra"] = $colorLetra,time() + 60 * 60, '/');
        }
    }
}





//vista
inicioCabecera("Formulario para texto y fondo");
cabecera();
finCabecera();
inicioCuerpo("Formulario para modificar color de fondo y color de texto");
cuerpo($datos, $errores);
finCuerpo();

// ********************************************************** //
function cabecera()
{
}


function cuerpo(array $datos, array  $errores)
{
?>
    <br>
    <br>
    <br>
<?php
    formulario($datos, $errores);
}



function formulario(array $datos, array $errores)
{

?>

    <!-- Formulario para personalizar los colores de la aplicacion --> 
    <form action="" method="post">

        <!--color fondo -->
        <label for="colorFondo">Elige un color para el fondo </label>
        <select name="colorFondo">
            <option value= defecto >Seleccione un color</option>
            <?php
            foreach (COLORESFONDO as $clave => $valor) {
                echo "<option value=$clave";
                if ($datos["colorFondo"] == $clave)
                    echo " selected='selected'";
                echo ">$valor</option>" . PHP_EOL;
            }
            ?>
        </select>
        <br>

        <!--Errores de color fondo -->
        <?php
        if (isset($errores["colorFondo"])) {
            echo "<div class='error'>";
            foreach ($errores["colorFondo"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>

        
        <br>

        <!--color letras -->
        <label for="colorLetra">Elige un color para la letra </label>
        <select name="colorLetra">
            <option value= defecto >Seleccione un color</option>
            <?php
            foreach (COLORESTEXTO as $clave => $valor) {
                echo "<option value=$clave";
                if ($datos["colorLetra"] == $clave)
                    echo " selected='selected'";
                echo ">$valor</option>" . PHP_EOL;
            }
            ?>
        </select>
        <br>

        <!--Errores de letras -->
        <?php
        if (isset($errores["colorLetra"])) {
            echo "<div class='error'>";
            foreach ($errores["colorLetra"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>
        <br>

        <!-- Boton para modificar -->
        <input type="submit" name="modificar" class="boton" value="Modificar">
    </form>
<?php
}
