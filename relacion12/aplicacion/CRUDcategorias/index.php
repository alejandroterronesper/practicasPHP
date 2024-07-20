<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

$datos = [
    "id" => "",
    "descripcion" => ""
];

$arrayCategorias = [];
$errores = [];


$parametros = "";

//--------------------------------------------------------------------//

//filtrado
if ($_POST){

    //filtrado
    if (isset($_POST["filtrarCategoria"])){

        $id = "";
        if (isset($_POST["id"])){

            $id = intval($_POST["id"]);

            if ($id !== 0){ //No existe id = 0, tiene que ser a partir de 1
                $parametros  = "cod_categoria=$id";
            }
        }

        $datos["id"] = $id;
    }


    //añadir categoria
    if (isset($_POST["addCategoria"])){

        $descripcion = "";
        if (isset($_POST["descripcion"])){

            $descripcion = trim($_POST["descripcion"]);

            if ($descripcion === ""){
                $errores["descripcion"][] = "Debes introducir una descripción a la categoría";
            }

            if (!validaCadena($descripcion, 20, "")){
                $errores["descripcion"][] = "Debes introducir una cadena de una longitud menor de 20 caracteres";

            }
            $datos["descripcion"] = $descripcion;
            $parametros = "descripcion=$descripcion";
        }
    }

    //Si se pulsa añadir categoria pero no hay errores, 
    //hacemos petición POST (inserción)
    if ((isset($_POST["addCategoria"])) && (!$errores)){


        //peticion POST
        $res = getCURL($rutaCategoria, "POST", $parametros);

        if (!$res){

            paginaError("No se han podido obtener los datos");
            exit;

        }

        $res=json_decode($res,true);

        if (!isset($res["correcto"])){

            paginaError("La respuesta no cumple el formato");
            exit;

        }
        
        if (!$res["correcto"]){//comprobamos si hay errores

            $errores = $res["datos"];
            $datos["descripcion"] = "";

        }

        //Si se realiza la sentencia INSERT, avisamos al usuario
        if($res["correcto"] === true){
            ?>
                <script>
                    alert("Categoría añadida");
                </script>
            <?php
        }

    }


    //borrar filtrado
    if (isset($_POST["borrarFiltradoCategoria"])){
        $datos["id"] = "";
    }


    //borramos campo de añadir categoria
    if (isset($_POST["limpiaCampoCategoria"])){
        $datos["descripcion"] = "";
    }
}


//Petición GET para mostrar categorías
$res = getCURL($rutaCategoria, "GET", $parametros);


if (!$res){ //Se comprueba si hemos recibido datos
    paginaError("No se han podido obtener los datos");
    exit;
}
$res=json_decode($res,true);

if (!isset($res["correcto"])) { //comprobamos que haya llegado el parametro correcto
    paginaError("La respuesta no cumple el formato");
    exit;
}

if (!$res["correcto"]) { //comprobamos que correcto esté a true
    paginaError($res["datos"]);
    exit;
}


$arrayCategorias =$res["datos"]; //guardamos los datos de categorías


$_posPagina = [
    [
        "texto" => "inicio",
        "url" => "/"
    ],
    [
        "texto" => "CRUD categorias",
        "url" => "/aplicacion/CRUDcategorias/index.php"
    ]
];



inicioCabecera("Badulaque - Categorías");
cabecera();
finCabecera();
inicioCuerpo("Badulaque", $_posPagina);
cuerpo($arrayCategorias, $datos, $errores);
finCuerpo();

// **********************************************************
function cabecera()
{
}

function cuerpo(array $arrayCategorias, array $datos, array $errores)
{
?>
    <form action="" method="post" class="formulario">
        <fieldset>
            <legend>Filtrar categoría</legend>
            <label for="id"><b>ID:</b></label>
            <input type="text" name="id" value="<?php echo $datos["id"]?>">
            <br>
            <input type="submit" name="filtrarCategoria" value="Filtrar" class="boton">
            <input type="submit" name="borrarFiltradoCategoria" value="Borrar filtrado" class="boton">
        </fieldset>

    </form>

    
    <table class="tabla" style="width: fit-content; margin-left: 35%">
        <caption>
            <h3>Categorías</h3>
        </caption>
        <tr>
            <th>DESCRIPCIÓN</th>
            <th>OPCIONES</th>
        </tr>
        <?php
            if (isset($arrayCategorias)){
                foreach($arrayCategorias as $clave => $valor){
                    ?>
                        <tr>
                            <td> <?php echo $valor;?> </td>
                            <td>
                                <a href="<?php echo 'modificarCategoria.php?id='.$clave?>" > <img style="width: 20%;"  src="../../imagenes/iconos/editar.png" title="Modificar categoría"></a>
                                <a href="<?php  echo 'borrarCategoria.php?id='.$clave?>"> <img style="width: 20%;" src="../../imagenes/iconos/eliminar.png" title="Borrar categoría"></a>
                            </td>
                        </tr>
                    <?php
                }
            }
        ?>
    </table>

    <br>

    <form action="" method="post" class="formulario">
            <fieldset>
                <legend>Añade categoría</legend>
                <label for="descripcion"><b>Descripción: </b></label>
                <input type="text" name="descripcion"  value="<?php echo $datos["descripcion"]?>">
                <input type="submit" name="addCategoria" value="Añade categoría" class="boton">
                <input type="submit" name="limpiaCampoCategoria" value="Limpiar campo" class="boton">
                <?php
                    if (isset($errores["descripcion"])) {
                        echo "<div class='error'>";
                        foreach ($errores["descripcion"] as $error)
                            echo "$error<br> " . PHP_EOL;
                        echo "</div>";
                    }
                ?>
            </fieldset>
    </form>
    
    <br>
    <button class="boton"><a href="../../index.php">Volver atrás</a> </button>
<?php
}

?>