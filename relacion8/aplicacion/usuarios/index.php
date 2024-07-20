<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");
include_once(RUTABASE . "/scripts/librerias/validacion.php"); //Ruta de la libreria de funciones


$nickUserActual = $acceso->getNick();
$codUserActual = $listaACL->getCodUsuario($nickUserActual);
$borradoActual = $listaACL->getBorrado($codUserActual); //tiene que ser false, 0, para que podamos entrar


//Validamos permisos del usuario actual
if ($acceso->hayUsuario() === true && (!$borradoActual)) {
    //comprobamos si no tiene permiso 1 y 2 a true
    if (!($acceso->puedePermiso(1))  || !($acceso->puedePermiso(2))) {
        paginaError("NO tienes permisos para acceder a la página");
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




//Hacemos la sentencia para mostrar als tablas
$sentencia = "SELECT * FROM usuarios";





$selectWhere = "";
if ($_POST) {


    //  Nick
    $nick = "";
    if (isset($_POST["nick"])) {

        $nick = trim($_POST["nick"]);


        //Escapamos la cadena
        $nick = $mysqli->escape_string($nick);

        if ($nick !== "") {
            $selectWhere .= " WHERE nick LIKE  '%$nick%'";
        }
    }



    // Provincia 
    $provincia = "";
    if (isset($_POST["provincia"])) {
        $provincia = trim($_POST["provincia"]);

        //Escapamos cadena
        $provincia = $mysqli->escape_string(($provincia));

        //Si no se filtra con nick
        if ($selectWhere !== "") { //si ya hay consulta nick
            if ($provincia !== "") {
                $selectWhere .= " AND provincia LIKE  '%$provincia%'";
            }
        } else {
            if ($provincia !== "") { //si no hay consulta
                $selectWhere .= " WHERE provincia LIKE  '%$provincia%'";
            }
        }
    }


    //Borrado
    $borrado = 0;
    if (isset($_POST["borrado"])) {
        $borrado = intval($_POST["borrado"]);

        //Comprobamos si se ha elejido true o false
        if ($borrado === 0) { //false

            //Si no se filtra con nick o con provincia
            if ($selectWhere !== "") {
                $selectWhere .= " AND borrado = 0"; //borrado = false
            } else {
                $selectWhere .= " WHERE borrado = 0"; //borrado = true
            }
        }


        if ($borrado === 1) { //true

            //Si no se filtra con nick o con provincia
            if ($selectWhere !== "") {
                $selectWhere .= " AND borrado = 1"; //borrado = false
            } else {
                $selectWhere .= " WHERE borrado = 1"; //borrado = true
            }
        }
    }


    $sentencia .= $selectWhere;
}


$filas = [];
$consulta = $mysqli->query($sentencia);




//Vamos iterando filas
while ($fila = $consulta->fetch_assoc()) {


    $filas[] = $fila;
}








inicioCabecera("Relacion 8 - CRUD"); /* ESTA EN PLANTILLA */
cabecera();
finCabecera(); /* ESTA EN PLANTILLA */

inicioCuerpo("CRUD"); /* ESTA EN PLANTILLA */
cuerpo($filas, $acceso);
finCuerpo(); /* ESTA EN PLANTILLA */



// ************FUNCIONES***************

function cabecera()
{
    echo "<script>
     </script>";
}


function cuerpo(array $filas, Acceso $acceso)
{

?>


    <!--Formulario de filtrado -->
    <form action="" method="post" id="formulario" style="margin-top: 5%;">
        <fieldset style="background-color: lightblue;">
            <legend style="background-color: white; border: black solid 1px;"><b>Criterios de filtrado</b></legend>
            <label for="nick"><b>Nick</b></label>
            <input type="text" name="nick" size=20>
            <br>
            <label for="provincia"><b>Provincia</b></label>
            <input type="text" name="provincia">
            <br>
            <label for="borrado"><b>Borrado</b></label>
            <input type="radio" name="borrado" value=1>Si
            <input type="radio" name="borrado" value=0>No
            <br>
            <input type="submit" name="filtrar" value="Filtrar">
        </fieldset>
    </form>

    <br>


    <!--Tabla de usuarios -->
    <table class="tabla">
        <caption>
            <h3>Tabla usuarios</h3>
        </caption>
        <tr>
            <th>NICK</th>
            <th>NIF</th>
            <th>DIRECCION</th>
            <th>POBLACION</th>
            <th>PROVINCIA</th>
            <th>CP</th>
            <th>FECHA DE NACIMIENTO</th>
            <th>BORRADO</th>
            <th>FOTO</th>
            <?php
            if ($acceso->puedePermiso(2)) {
            ?>
                <th>OPCIONES</th>
            <?php
            }
            ?>

        </tr>
        <?php
        //Comprobamos que hay consultas
        if ($filas) {
            foreach ($filas as $fila) {
        ?>
                <tr> <?php
                        foreach ($fila as $clave => $valor) {
                            if($clave !== "cod_usuario"){
                                if ($clave === "borrado") {
                                    $boolInt = intval($valor);
    
                                    if ($boolInt === 0) {
                                        $valor = "false";
                                    } else {
                                        $valor = "true";
                                    }
                                }

                                if ($clave === "foto") {
                                    $valor = "<img style='height: 30px; width: 30px' src ='../../imagenes/" . $valor . "' >";
                                }
                            ?> 
                                <?php
                                    if($clave === "fecha_nacimiento"){
                                        $valor = MYSQLaFecha($valor);    
                                    }
                                ?>
                                <td> <?php echo $valor . PHP_EOL; ?> </td><img>
                        <?php
                            }
                            }
                            
                    ?>
                    <td id="opciones">

                        <?php
                        if ($acceso->puedePermiso(2)) { //si tengo permiso 1 puedo ver la opcion Ver
                        ?>
                            <!--"verUsuario.php?id=" -->
                            <a href=<?php echo  "'verUsuario.php?nick=" . $fila['nick'] . "'"; ?>> <img src="../../imagenes/iconos/icon_ver.png" title="Ver usuario"></a>
                        <?php
                        }

                        if ($acceso->puedePermiso(3)) {
                        ?>
                            <!--Permiso 3: Modificar -->
                            <a href=<?php echo  "'modificarUsuario.php?nick=" . $fila['nick'] . "'"; ?>> <img src="../../imagenes/iconos/icon_modificar.png" title="Modificar usuario"></a>
                        <?php
                        }

                        if (intval($fila["borrado"]) === 0 && $acceso->puedePermiso(3)) {
                        ?>
                            <!--Permiso 3, puede Borrar -->
                            <a href=<?php echo  "'borrarUsuario.php?nick=" . $fila['nick'] . "'"; ?>> <img src="../../imagenes/iconos/icon_borrar.png" title="Borrar usuario"></a>
                        <?php
                        }
                        ?>
                    </td>
                </tr>
        <?php
            }
        }
        ?>
    </table>

    <br>

    <?php
    //Con permiso 3 se pueden añadir usuarios
    if ($acceso->puedePermiso(3)) {
    ?>
        <!-- Con permiso 3 se pueden añadir usuarios -->
        <button>
            <a href="nuevoUsuario.php"><img src="../../imagenes/iconos/icon_crear.png" title="Añadir usuario">Añadir usuario</a>
        </button>
    <?php
    }
    ?>


<?php
}
