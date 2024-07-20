<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");


//-----------------------------------------------------------//

//PETICION GET PARA OBTENER EL COMBO DE CATEGORIAS

$arrayCategorias = [];
$parametros = "";
$res = getCURL($rutaCategoria, "GET");

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


$arrayCategorias =$res["datos"];

//-----------------------------------------------------------//



//CONTROLADOR
//inicializaciones
$datos = [
    "nombre" => "",
    "fabricante" => "",
    "categoria" => 0,
    "fecha_alta" => "",
    "unidades" => 0,
    "precio_base" => 0,
    "iva" => 21, //por defecto
    "borrado" => 0, //por defecto
    "foto" => "foto.png", //por defecto
];
$errores = [];


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
        "texto" => "Nuevo producto",
        "url" => "/aplicacion/CRUDproductos/anadirProducto.php"
    ]
];

//formulario para recoger datos
if ($_POST){

    if (isset($_POST["newProduct"])){ //nuevo producto

        //nombre
        $nombre  ="";
        if (isset($_POST["nombre"])){ //las validaciones SE HARÁN DESDE LA API
                            
            $nombre = trim($_POST["nombre"]);

        }
        $datos["nombre"] = $nombre;
        $parametros = "nombre={$datos['nombre']}"; 
      


        //fabricante
        $fabricante = "";
        if (isset($_POST["fabricante"])) {
            $fabricante = trim($_POST["fabricante"]);
        }
        $datos["fabricante"] = $fabricante;
        $parametros .= "&fabricante={$datos['fabricante']}"; 


        //cod categoria
        $categoria = -1;
        if (isset($_POST["categoria"])) {
            $categoria = intval($_POST["categoria"]);
        }
        $datos["categoria"] = $categoria;
        $parametros .= "&categoria={$datos['categoria']}"; 


        //fecha alta
        $fechaAlta = "";
        if (isset($_POST["fecha_alta"])) {
            $fechaAlta = trim(($_POST["fecha_alta"]));
        }
        $datos["fecha_alta"] = $fechaAlta;
        $parametros .= "&fecha_alta={$datos['fecha_alta']}"; 


        //unidades
        $unidades = 0;
        if (isset($_POST["unidades"])) {
            $unidades = intval($_POST["unidades"]);
        }
        $datos["unidades"] = $unidades;
        $parametros .= "&unidades={$datos['unidades']}"; 

        //precio base
        $precioBase = 0;
        if (isset($_POST["precio_base"])) {
            $precioBase = floatval($_POST["precio_base"]);
        }
        $datos["precio_base"] = $precioBase;
        $parametros .= "&precio_base={$datos['precio_base']}"; 

        //iva
        $iva = 21;
        if (isset($_POST["iva"])) {
            $iva = floatval($_POST["iva"]);

        }
        $datos["iva"] = $iva;
        $parametros .= "&iva={$datos['iva']}"; 
        $parametros.= "&borrado={$datos['borrado']}";
        $parametros.= "&foto={$datos['foto']}";

    }



    //Si se ha pulsado crear producto
    // y hay parámetros hacemos la consulta CURL por POST
    if (isset($_POST["newProduct"]) && ($parametros !== "")){ 



        //petición POST
        $res = getCURL($rutaProductos, "POST", $parametros);


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

            //recorremos el array de errores
            //si las claves coinciden con las del array datos
            //mandamos campo en blanco
            foreach($errores as $clave => $valor){//limpiamos los campos erróneos del formulario
                    $datos[$clave] = "";
            }
        
        }

        //Si se realiza la sentencia INSERT,
        //enviamos al usuario a ver producto
        if($res["correcto"] === true){
           //cogemos el id insertado
           $id = intval($res["datos"]);
           header("location:verProducto.php?id=$id");
        }
    }

}




inicioCabecera("Badulaque - Añadir producto");
cabecera();
finCabecera();
inicioCuerpo("Badulaque", $_posPagina);
cuerpo($datos, $arrayCategorias, $errores);
finCuerpo();

// **********************************************************
function cabecera()
{
}


function cuerpo(array $datos, array $arrayCategorias, array $errores) {
?>

    <form action="" method="post" class="formulario">
        <fieldset>
            <legend><b>Crear producto</b></legend>


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
            <label for="categoria"> <b>Categoría</b> </label>
            <select name="categoria">
                <option value=-1>--Elige una categoría--</option>
                <?php
                //Conforme iteremos el array de las categorias
                //en el value del option guardamos el código
                //Y en la etiqueta ponemos el nombre de la categoría
                foreach ($arrayCategorias as $clave => $valor) {
                    echo "<option  value=$clave";
                    if ($datos["categoria"] == $clave) {
                        echo " selected='selected'";
                    }
                    echo ">$valor</option>" . PHP_EOL;
                }
                ?>
            </select>
            <?php
            if (isset($errores["categoria"])) {
                echo "<div class='error'>";
                foreach ($errores["categoria"] as $error)
                    echo "$error<br> " . PHP_EOL;
                echo "</div>";
            }
            ?>
            <br>

            <!--Fecha alta -->
            <label for="fecha_alta" title="La fecha debe ser mayor de 28/2/2010 y no superior a la fecha actual"><b>Fecha de alta:</b></label>
            <input type="text" name="fecha_alta" placeholder="dd/mm/aaaa" value=<?php echo $datos["fecha_alta"]; ?>>
            <?php
            if (isset($errores["fecha_alta"])) {
                echo "<div class='error'>";
                foreach ($errores["fecha_alta"] as $error)
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


            <!--Iva -->
            <label for="iva" title="Por defecto 21, no puede ser negativo"> <b>Iva</b> </label>
            <input type="text" name="iva" value=<?php echo $datos["iva"]; ?>>
            <?php
            if (isset($errores["iva"])) {
                echo "<div class='error'>";
                foreach ($errores["iva"] as $error)
                    echo "$error<br> " . PHP_EOL;
                echo "</div>";
            }
            ?>
            <br>

            <input type="submit" class="boton" value="Añadir producto" name="newProduct">
            <button class="boton"> <a href="index.php">Cancelar</a> </button>
        </fieldset>
    </form>

<?php
}

?>