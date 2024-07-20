<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");


//variables
$datos = [];
$parametros = "";
$errores = [];
//sacamos la categoria por petición GET

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


if ($_POST){

    if (isset($_POST["enviarBorrado"])){

        //borrado
        $borrado = "";
        if (isset($_POST["deleteProducto"])){
            $borrado = intval($_POST["deleteProducto"]);
        }
        $datos["borrado"] = $borrado;
        $parametros = "borrado=$borrado";
        $parametros .= "&cod_producto=$id";
    }


    //comprobamos que se pulsa el enviar y que parametros no esté vacio
    if (isset($_POST["enviarBorrado"]) && ($parametros !== "")){

        //petición DELETE
        $res = getCURL($rutaProductos, "DELETE", $parametros);

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
            //cogemos el id insertado
            $id = intval($res["datos"]);
            header("location:verProducto.php?id=$id");
            exit;
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
        "texto" => "Borrar/Recuperar producto",
        "url" => "/aplicacion/CRUDproductos/borrarProducto.php?id=$id"
    ]
];


inicioCabecera("Badulaque - Borrar/Recuperar producto");
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

    <!--Formulario borrar/recuperar -->
    <form action="" method="post" class="formulario">
        <fieldset>
            <legend><b>¿Deseas borrar/recuperar el producto?</b></legend>

            <?php
            if (intval($datos["borrado"]) === 0) {
            ?>
                <input type="radio" name="deleteProducto" value=0 checked> No
                <input type="radio" name="deleteProducto" value=1> Si

            <?php
            }
            if (intval($datos["borrado"]) === 1) {
            ?>
                <input type="radio" name="deleteProducto" value=0> No
                <input type="radio" name="deleteProducto" value=1 checked> Si

            <?php
            }
            ?>
            <br>
            <?php
            if (isset($errores["deleteProducto"])) {
                echo "<div class='error'>";
                foreach ($errores["deleteProducto"] as $error)
                    echo "$error<br> " . PHP_EOL;
                echo "</div>";
            }
            ?>
            <input type="submit" name="enviarBorrado" value="Aceptar" class="boton" style="margin-top: 2%;">
            <button class="boton"> <a href="index.php">Cancelar</a> </button>
        </fieldset>
    </form>

    <br>
    <br>

    <!--Datos del producto -->
    <form class="formulario">
        <fieldset>

            <legend><b>Datos producto</b></legend>


            <!--Nombre -->
            <label for="nombre"><b>Nombre: </b></label>
            <input type="text" name="nombre" readonly value="<?php echo $datos["nombre"]; ?>">

            <br>

            <!--Fabricante -->
            <label for="fabricante"><b>Fabricante: </b></label>
            <input type="text" name="fabricante" readonly value="<?php echo $datos["fabricante"]; ?>">
            <br>

            <!--Categoría -->
            <label for="categoria"><b>Categoría: </b></label>
            <input type="text" name="categoria" readonly value=<?php echo $datos["descripcion"]; ?>>
            <br>


            <!--Fecha de alta -->
            <label for="fechaAlta"><b>Fecha de alta: </b></label>
            <input type="text" name="fechaAlta" readonly value=<?php echo $datos["fecha_alta"]; ?>>
            <br>

            <!--Unidades -->
            <label for="unidades"><b>Nº de unidades: </b></label>
            <input type="text" name="unidades" readonly value=<?php echo $datos["unidades"]; ?>>
            <br>

            <!--Precio base -->
            <label for="precioBase"><b>Precio base: </b></label>
            <input type="text" name="precioBase" readonly value=<?php echo $datos["precio_base"] . " euros"; ?>>
            <br>

            <!--IVA -->
            <label for="IVA"><b>IVA: </b></label>
            <input type="text" name="IVA" readonly value=<?php echo $datos["iva"]; ?>>
            <br>


            <!--precio IVA -->
            <label for="precioIVA"><b>Precio IVA: </b></label>
            <input type="text" name="precioIVA" readonly value=<?php echo $datos["precio_iva"]; ?>>
            <br>

            <!--Precio venta -->
            <label for="precioVenta"><b>Precio venta: </b></label>
            <input type="text" name="precioVenta" readonly value=<?php echo $datos["precio_venta"]; ?>>
            <br>

            <!--Borrado -->
            <label for="borrado"><b>Borrado: </b></label>
            <input type="text" name="borrado" readonly value=<?php if (intval($datos["borrado"]) === 1) {
                                                                    echo "Sí";
                                                                }
                                                                if (intval($datos["borrado"]) === 0) {
                                                                    echo "No";
                                                                } ?>>
            <br>

            <!--Foto -->
            <label for="foto"><b>Foto: </b></label><br>
            <img src="<?php echo $datos['foto'] ?>" id="fotoVer">
        </fieldset>
    </form>

    <br>
    <button class="boton"> <a href="index.php">Volver atrás</a> </button>

<?php
}


?>