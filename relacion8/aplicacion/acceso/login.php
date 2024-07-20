<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");


//CONTROLADOR
//inicializaciones
$datos = [
    "nick" => "",
    "contrasenha" => ""
];

$errores = []; //comprobar si se ha dado a insertar

if ($_POST) {

    //nick
    $nick = "";
    if (isset($_POST["nick"])){
        $nick = trim($_POST["nick"]);
        
        if ($nick == ""){
            $errores["nick"][] = "Error, se ha introducido un nick vacio";
        }


    }
    $datos["nick"] = $nick;

    //contraseña
    $contrasenha = "";
    if (isset($_POST["contrasenha"])){
        $contrasenha = trim($_POST["contrasenha"]);


        if ($contrasenha == ""){
            $errores["contrasenha"][] = "Error, se ha introducido una contraseña vacia";
        }

    }
    $datos["contrasenha"] = $contrasenha;

  
    
    if (!$errores) 
    {
        //Si no hay errores, en validacion de cadenas
        //valido nick y contraseñas con el objeto ACLArray

        
        //compruebo que existe el nick
        if($listaACL->existeUsuario($nick)){
            //si existe el nick
            //validamos contraseña

            

            if ($listaACL -> esValido($nick, $contrasenha)){
                //hemos validado contraseña
                //ahora iniciamos la sesion
                //para ello accedemos a las posiciones asociativas de SESSION

                //obtenemos codigo de usuairo
                //para acceder a su nombre
                //y los permisos
                $codUser = $listaACL -> getCodUsuario($nick);
                $nombreUser = $listaACL ->getNombre ($codUser); //nombre de usuario
                $arrayPermisos = $listaACL -> getPermisos($codUser); //lista de permisos
                
                if ($listaACL->getBorrado($codUser)){ //si da true es que esta borrado
                    $errores["nick"][] = "Error, usuario borrado";
                }
                else{ //si no esta borrado
                    if (isset($acceso)){
                        $acceso ->registrarUsuario($nick, $nombreUser , $arrayPermisos);
    
                        //si me registro, mando al usuario al index.php
    
                        //compreubo que el usuario no este borrado
                        if(isset( $_SESSION["redirigir"])){
                                header("location: ". $_SESSION['redirigir']);
                                exit();
                            } 
                    } 
                }

                             
            }
            else{ //si hay error en contraseña
                $errores["contrasenha"][] = "Error, contraseña incorrecta";
            }
        }
        else{ //si no existe el nick
            $errores["nick"][] = "Error, el nick introducido no existe";
        }
    }
}


//vista
inicioCabecera("Iniciar sesión");
cabecera();
finCabecera();
inicioCuerpo("Formulario de login");
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

    <h2>Formulario de login</h2>
    <!--Formulario login -->
    <form action="" method="post">

        <!--Nick -->
        <label for="nick"><b>Introduce tu nombre de usuario</b></label>
        <input type="text" name="nick" placeholder="nick" minlength="1" value=<?php echo $datos["nick"] ?>>
        <br>
        <?php
        if (isset($errores["nick"])) {
            echo "<div class='error'>";
            foreach ($errores["nick"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>
        <br>


        <!--Contraseña -->
        <label for="contrasenha"><b>Introduce tu contraseña</b></label>
        <input type="password" name="contrasenha" minlength="1" placeholder="contraseña" value=<?php echo $datos["contrasenha"] ?>>
        <br>
        <?php
        if (isset($errores["contrasenha"])) {
            echo "<div class='error'>";
            foreach ($errores["contrasenha"] as $error)
                echo "$error<br> " . PHP_EOL;
            echo "</div>";
        }
        ?>
        <br>


        <br>
        <!-- Boton para iniciar sesión -->
        <input type="submit" name="crear" class="boton" value="Acceder">
    </form>

<?php
}
