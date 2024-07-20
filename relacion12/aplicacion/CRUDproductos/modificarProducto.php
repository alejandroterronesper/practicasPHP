<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

//variables
$datos = [];
$parametros = "";
$errores = [];


//sacamos la categoria por petición GET
//-----------------------------------------------------------//

//PETICION GET PARA OBTENER EL COMBO DE CATEGORIAS

$arrayCategorias = [];
$parametros = "";
$res = getCURL($rutaCategoria, "GET");

if (!$res) {
    paginaError("No se han podido obtener los datos");
    exit;
}
$res = json_decode($res, true);


if (!isset($res["correcto"])) {
    paginaError("La respuesta no cumple el formato");
    exit;
}

if (!$res["correcto"]) {
    paginaError($res["datos"]);
    exit;
}


$arrayCategorias = $res["datos"];

//-----------------------------------------------------------//


//Primero validamos que id nos llega por GET
if (($_SERVER["QUERY_STRING"] === "") || (!array_key_exists("id", $_GET))) {
    paginaError("Error, no se ha accedido con el parámetro id");
    exit();
}

//validamos el get
if ($_GET) {

    $id = "";
    if (isset($_GET["id"])) { //id del producto

        $id = intval($_GET["id"]);


        $parametros = "cod_producto=$id";


        $res = getCURL($rutaProductos, "GET", $parametros);


        //comprobamos posibles errores
        if (!$res) {
            paginaError("No se han podido obtener los datos");
            exit;
        }

        $res = json_decode($res, true);
        if (!isset($res["correcto"])) {
            paginaError("La respuesta no cumple el formato");
            exit;
        }

        if (!$res["correcto"]) {
            paginaError($res["datos"]);
            exit;
        }


        if (count($res["datos"]) === 0) { //se comprueba si han llegado valores a los datos, en caso de realizar una petición, que no devuelva nada
            paginaError("No se han podido obtener datos de la petición realizada");
            exit;
        } else {
            $datos = $res["datos"][0];
        }
    }
}


//validamos formulario
if ($_POST) {


    if (isset($_POST["modProducto"])) { //las validaciones se hacen en la API de productos


        //nombre
        $nombre = "";
        if (isset($_POST["nombre"])) {
            $nombre = trim($_POST["nombre"]);
        }
        $datos["nombre"] = $nombre;
        $parametros = "nombre=$nombre";


        //fabricante
        $fabricante = "";
        if (isset($_POST["fabricante"])) {
            $fabricante = trim($_POST["fabricante"]);
        }
        $datos["fabricante"] = $fabricante;
        $parametros .= "&fabricante=$fabricante";

        //cod categoria
        $codCategoria = "";
        if (isset($_POST["cod_categoria"])) {
            $codCategoria = intval($_POST["cod_categoria"]);
        }
        $datos["cod_categoria"] = $codCategoria;
        $parametros .= "&cod_categoria=$codCategoria";


        //unidades
        $unidades = "";
        if (isset($_POST["unidades"])) {
            $unidades = intval($_POST["unidades"]);
        }
        $datos["unidades"] = $unidades;
        $parametros .= "&unidades=$unidades";

        //precio base
        $precioBase = "";
        if (isset($_POST["precio_base"])) {
            $precioBase = floatval($_POST["precio_base"]);
        }
        $datos["precio_base"] = $precioBase;
        $parametros .= "&precio_base=$precioBase";

        //añadimos resto de parámetros
        $parametros .= "&cod_producto={$datos['cod_producto']}";
        //$parametros .= "&oper=1"; //si hacemos la peticion por POST

    }


    //Se comprueba que se haya pulsado 
    //el boton modificar y la cadena no esté vacia
    if (isset($_POST["modProducto"]) && ($parametros !== "")){


        //hacemos petición PUT
        // $res = getCURL($rutaProductos, "POST", $parametros); //en caso de quere hacer peticion por post con parametro oper = 1
        $res = getCURL($rutaProductos, "PUT", $parametros);

        if (!$res) {

            paginaError("No se han podido obtener los datos");
            exit;
        }

        $res = json_decode($res, true);

        if (!isset($res["correcto"])) {

            paginaError("La respuesta no cumple el formato");
            exit;
        }

        if (!$res["correcto"]) { //comprobamos si hay errores

            $errores = $res["datos"];

            //recorremos el array de errores
            //si las claves coinciden con las del array datos
            //mandamos campo en blanco
            foreach ($errores as $clave => $valor) { //limpiamos los campos erróneos del formulario
                $datos[$clave] = "";
            }
        }

        //Si se realiza la sentencia UPDATE,
        //enviamos al usuario a ver producto
        if ($res["correcto"] === true) {
            //cogemos el id insertado
            $id = intval($res["datos"]);
            header("location:verProducto.php?id=$id");
        }
    }

}


$_posPagina = [
    [
        "texto" => "inicio",
        "url" => "/"
    ],
    [
        "texto" => "CRUD productos",
        "url" => "/aplicacion/CRUDproductos/index.php"
    ],
    [
        "texto" => "Modificar producto",
        "url" => "/aplicacion/CRUDproductos/modificarProducto.php?id=$id"
    ]
];

//vista
inicioCabecera("Badulaque - Modificar producto");
cabecera();
finCabecera();
inicioCuerpo("Badulaque", $_posPagina);
cuerpo($datos, $arrayCategorias, $errores);
finCuerpo();

// **********************************************************
function cabecera()
{
}


function cuerpo(array $datos, array $arrayCategorias, array $errores)
{

?>
    <form action="" method="post" class="formulario">
        <fieldset>

            <legend><b>Modificar producto</b></legend>


            <!--Nombre-->
            <label for="nombre"> <b>Nombre:</b> </label>
            <input type="text" name="nombre" value="<?php echo $datos["nombre"]; ?>">
            <?php
            if (isset($errores["nombre"])) {
                echo "<div class='error'>";
                foreach ($errores["nombre"] as $error)
                    echo "$error<br> " . PHP_EOL;
                echo "</div>";
            }
            ?>
            <br>

            <!--Fabricante-->
            <label for="fabricante"> <b>Fabricante:</b> </label>
            <input type="text" name="fabricante" value="<?php echo $datos["fabricante"]; ?>">
            <?php
            if (isset($errores["fabricante"])) {
                echo "<div class='error'>";
                foreach ($errores["fabricante"] as $error)
                    echo "$error<br> " . PHP_EOL;
                echo "</div>";
            }
            ?>
            <br>


            <!--Categoría -->
            <label for="cod_categoria"> <b>Categoría</b> </label>
            <select name="cod_categoria">
                <option value=-1>--Elige una categoría--</option>
                <?php
                //Conforme iteremos el array de las categorias
                //en el value del option guardamos el código
                //Y en la etiqueta ponemos el nombre de la categoría
                foreach ($arrayCategorias as $clave => $valor) {
                    echo "<option  value=$clave";
                    if ($datos["cod_categoria"] == $clave) {
                        echo " selected='selected'";
                    }
                    echo ">$valor</option>" . PHP_EOL;
                }
                ?>
            </select>
            <?php
            if (isset($errores["cod_categoria"])) {
                echo "<div class='error'>";
                foreach ($errores["cod_categoria"] as $error)
                    echo "$error<br> " . PHP_EOL;
                echo "</div>";
            }
            ?>
            <br>

            <!--Unidades -->
            <label for="unidades" title="0 o mayor que este"> <b>Unidades</b> </label>
            <input type="number" name="unidades" value=<?php echo $datos["unidades"]; ?>>
            <?php
            if (isset($errores["unidades"])) {
                echo "<div class='error'>";
                foreach ($errores["unidades"] as $error)
                    echo "$error<br> " . PHP_EOL;
                echo "</div>";
            }
            ?>
            <br>

            <!--Precio base -->
            <label for="precio_base" title="No puede ser negativo"> <b>Precio base</b> </label>
            <input type="text" name="precio_base" value=<?php echo $datos["precio_base"]; ?>>
            <?php
            if (isset($errores["precio_base"])) {
                echo "<div class='error'>";
                foreach ($errores["precio_base"] as $error)
                    echo "$error<br> " . PHP_EOL;
                echo "</div>";
            }
            ?>
            <br>


            <!--Fecha alta -->
            <label for="fecha_alta"><b>Fecha de alta (no se puede modificar):</b></label>
            <input type="text" name="fecha_alta" value=<?php echo $datos["fecha_alta"]; ?> readonly>
            <br>

            <!--Iva -->
            <label for="iva"> <b>Iva (No se puede modificar)</b> </label>
            <input type="text" name="iva" value=<?php echo $datos["iva"]; ?> readonly>
            <br>

            <!--precio IVA -->
            <label for="precio_iva"><b>Precio IVA (No se puede modificar): </b></label>
            <input type="text" name="precio_iva" readonly value=<?php echo $datos["precio_iva"]; ?>>
            <br>

            <!--Precio venta -->
            <label for="precio_venta"><b>Precio venta (No se puede modificar): </b></label>
            <input type="text" name="precio_venta" readonly value=<?php echo $datos["precio_venta"]; ?>>
            <br>

            <!--Borrado -->
            <label for="borrado"><b>Borrado (No se puede modificar):  </b></label>
            <input type="text" name="borrado" readonly value=<?php if (intval($datos["borrado"]) === 1) {
                                                                    echo "Sí";
                                                                }
                                                                if (intval($datos["borrado"]) === 0) {
                                                                    echo "No";
                                                                } ?>>
            <br>

            <!--Foto -->
            <label for="foto"><b>Foto (No se puede modificar): </b></label><br>
            <img src="<?php echo $datos['foto'] ?>" id="fotoVer">


            <br>
            <input type="submit" class="boton" value="Modificar producto" name="modProducto" style="margin-top: 2%;">
            <button class="boton"> <a href="index.php">Cancelar</a> </button>
        </fieldset>
    </form>



<?php


}


?>