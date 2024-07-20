<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");


$parametros = "";
$errores = [];

//sacamos la categoria por petición GET

//Primero validamos que id nos llega por GET
if (($_SERVER["QUERY_STRING"] === "" ) || (!array_key_exists("id", $_GET))){
    paginaError("Error, no se ha accedido con el parámetro id");
    exit();
}

//validamos el get
if ($_GET){

    $id = "";
    if(isset($_GET["id"])){
        $id = intval($_GET["id"]);

        //hacemos la petición curl GET
        $parametros = "cod_categoria=$id";

        $res = getCURL($rutaCategoria, "GET", $parametros);

        //comprobamos posibles errores
        if (!$res)
        {
            paginaError("No se han podido obtener los datos");
            exit;
        }
        $res=json_decode($res,true);
        if (!isset($res["correcto"]))
        {
            paginaError("La respuesta no cumple el formato");
            exit;
        }

        if (!$res["correcto"])
        {
            paginaError($res["datos"]);
            exit;
        }

        if (count($res["datos"]) === 0){ //se comprueba si han llegado valores a los datos, en caso de realizar una petición, que no devuelva nada
            paginaError("No se han podido obtener datos de la petición realizada");
            exit;
        }
        else{
            $arrayCategorias =$res["datos"];
        }
    }
}


//Verificamos post de formulario
if($_POST){

    //se comprueba que se ha pulsado boton aceptar
    if (isset($_POST["botonBorrar"])){

        //se comprueba que el radio seleccionado
        //sea value = 1, que es cuando se quiere borrar
        $borrado = "";
        if(isset($_POST["deleteCategoria"])){

            $borrado = intval($_POST["deleteCategoria"]);

            if ($borrado === 1){ //Si el borrado es 1, es que se borra categoría
               
                //cogemos el id de la categoria
                $id = "";
                if (isset($_POST["idCategoria"])){

                    $id = intval($_POST["idCategoria"]);
                    $parametros = "cod_categoria=$id";
                    //$parametros .= "&oper=2"; //en caso de querer realizar peticion por POST con oper = 2
                }
                else{
                    $errores["idCategoria"][]  ="Para realizar la petición, debes tener el id de la categoría";
                }
            }
        }

        //Si se pulsa el boton borrar categoría y no hay errores
        //se hace la petición DELETE
        if (isset($_POST["deleteCategoria"]) && (!$errores)){

            //peticion DELETE borrado físico a la tabla de categorías
            $res = getCURL($rutaCategoria, "DELETE", $parametros);
            //$res = getCURL($rutaCategoria, "POST", $parametros); //si queremos hacerlo con POST el delete mandamos oper  = 2


            if(!$res){
                paginaError("No se han podido obtener los datos");
                exit; 
            }

            $res=json_decode($res,true);
            if (!isset($res["correcto"])){
                paginaError("La respuesta no cumple el formato");
                exit;
            }

            if (!$res["correcto"]){ //devolvemos los posibles errores
                $errores = $res["datos"];
            }

            if($res["correcto"] === true){
                //Si se ha realizado la petición, true
                //enviamos al usuario, al crud de categorias
                header("location:index.php");
                exit;
            }
            
        }
    }
}


$datos = [
    "id" => intval(array_keys($arrayCategorias)[0]),
    "descripcion" => array_values($arrayCategorias)[0]
];


$_posPagina = [
    [
        "texto" => "inicio",
        "url" => "/"
    ],
    [
        "texto" => "CRUD categorias",
        "url" => "/aplicacion/CRUDcategorias/index.php"
    ],
    [
        "texto" => "Borrar categoría",
        "url" => "/aplicacion/CRUDcategorias/borrarCategoria.php?id=$id"
    ]
];



inicioCabecera("Badulaque - Eliminar categoría");
cabecera();
finCabecera();
inicioCuerpo("Badulaque", $_posPagina);
cuerpo($datos, $errores);
finCuerpo();


// **********************************************************
function cabecera()
{
}


function cuerpo(array $datos, array $errores)
{
    ?>
        <form  method="post"  accept="" class="formulario">
            <fieldset>
                <legend><b>Borrar categoría</b></legend>

                <label for="descripcion"><b>Descripción:</b></label>
                <input type="text" name="descripcion" value="<?php echo $datos['descripcion'] ?>" readonly>
                <input type="hidden" value="<?php echo $datos["id"]; ?>" name="idCategoria">

                <p><b>¿Borrar categoría?</b></p>
                <input type="radio" name="deleteCategoria" value=0 checked> No
                <input type="radio" name="deleteCategoria" value=1> Sí
                <input type="submit" name="botonBorrar" value="Aceptar" class="boton" style="margin-left: 2%;">

                <?php //posibles errores
                    if (isset($errores["idCategoria"])) {
                        echo "<div class='error'>";
                        foreach ($errores["idCategoria"] as $error)
                            echo "$error<br> " . PHP_EOL;
                        echo "</div>";
                    }
                ?>
            </fieldset>
        </form>

        <button class="boton" style="margin-top: 2%; margin-left: 2%"> <a href="index.php" >Volver atrás</a> </button>
    <?php
}


?>