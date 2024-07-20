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


$datos = [
    "id" => intval(array_keys($arrayCategorias)[0]),
    "descripcion" => array_values($arrayCategorias)[0]
];
$parametros = "";
$errores = [];
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
        "texto" => "Modificar categoría",
        "url" => "/aplicacion/CRUDcategorias/modificarCategoria.php?id=$id"
    ]
];

if($_POST){


    if (isset($_POST["modificarCategoria"])){

        //tenemos que comprobar que tengamos el id 
        //gaurdado el en el hidden 
        //para poder realizar la petición
        if (isset($_POST["idCategoria"])){

            $id = intval($_POST["idCategoria"]);

            $descripcion = "";
            if (isset($_POST["descripcion"])){
                $descripcion = trim($_POST["descripcion"]);
    
                if ($descripcion === ""){
                    $errores["descripcion"][] = "Debes introducir una descripción";
                }
    
                if (!validaCadena($descripcion, 20, "")){
                    $errores["descripcion"][] = "La cadena debe tener como máximo 20 caracteres";
                }
                $datos["descripcion"] = $descripcion;
                $parametros = "cod_categoria=$id&descripcion=$descripcion";
                //$parametros .= "&oper=1"; //si hacemos la peticion por POST
            }
        }
        else{
            $errores["idCategoria"][]  ="Para realizar la petición, debes tener el id de la categoría";
        }    
    

    }


    //si no hay errores, se hace petición CURL PUT
    if (isset($_POST["modificarCategoria"]) && (!$errores)){

        //peticion put
        // $res = getCURL($rutaCategoria, "POST", $parametros); //en caso de quere hacer peticion por post con parametro oper = 1
        $res = getCURL($rutaCategoria, "PUT", $parametros); 
        
        if (!$res){
            paginaError("No se han podido obtener los datos");
            exit;
        }
        
        $res=json_decode($res,true);
        if (!isset($res["correcto"])){
            paginaError("La respuesta no cumple el formato");
            exit;
        }
        
        if (!$res["correcto"]){
            $errores = $res["datos"];
            //borramos el valor del campo, porque hay errores
            $datos["descripcion"] = "";
        }


        if ($res["correcto"] === true){
            ?>
                <script>
                    alert("Descripción de categoría cambiada")
                </script>
            <?php
        }


    }
}


inicioCabecera("Badulaque - Modificar categoría");
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
        <form method="post" action="" class="formulario">
            <fieldset>
                <legend><b>Modificar categoría: </b></legend>
                <label for="descripcion"><b>Descripción: </b></label>
                <input type="text" name="descripcion" value="<?php echo $datos["descripcion"]; ?>">
                <?php
                    if (isset($errores["descripcion"])) {
                        echo "<div class='error'>";
                        foreach ($errores["descripcion"] as $error)
                            echo "$error<br> " . PHP_EOL;
                        echo "</div>";
                    }
                ?>
                <input type="submit" name="modificarCategoria" value="Cambiar descripción" class="boton">
                <input type="hidden"  name="idCategoria" value="<?php echo $datos["id"]; ?>"> <!-- guardo el cod de categoria -->
                <?php
                    if (isset($errores["idCategoria"])) { //en caso de realizar petición si id
                        echo "<div class='error'>";
                        foreach ($errores["idCategoria"] as $error)
                            echo "$error<br> " . PHP_EOL;
                        echo "</div>";
                    }
                ?>
            </fieldset>
        </form>



        <br>
        <button class="boton"> <a href="index.php"> Volver atrás </a></button>
    <?php
}



?>