<?php
include_once(dirname(__FILE__) . "/cabecera.php");

$datos = [
    "nombre" => "",
    "categoria" => -1,
    "borrado" => -1
];
$filas = [];
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

//------------------------------------------------------------//
$parametros = "";

//filtrado
if ($_POST) {

    if (isset($_POST["buscar"])){

        //nombre
        $nombre  ="";
        if (isset($_POST["nombre"])){

            $nombre = trim($_POST["nombre"]);
            

            if ($nombre !== ""){
                //parametros
                $parametros = "nombre=$nombre";
            }
        }
        $datos["nombre"]  = $nombre;




        //categoria
        $categoria = -1;
        if (isset($_POST["categoria"])){

            $categoria = intval($_POST["categoria"]);


            if ($parametros !== ""){
                if (validaRango($categoria, $arrayCategorias, 1)){
                    $parametros .= "&cod_categoria=$categoria";
                }
            }
            else{
                if (validaRango($categoria, $arrayCategorias, 1)){
                    $parametros = "cod_categoria=$categoria";
                }
            }
        }
        $datos["categoria"] = $categoria;



        //borrar
        $borrado = -1;
        if (isset($_POST["borrado"])){
            $borrado = intval($_POST["borrado"]);

            if ($borrado !== -1){

                if ($borrado === 0) {
                    $borrado = 0;
                }

                if ($borrado === 1) {
                    $borrado = 1;
                }


                if ($parametros !== "") {
                    $parametros .= "&borrado=$borrado";
                } else {
                    $parametros = "borrado=$borrado";
                }
            }

        }
        $datos["borrado"] = $borrado;
    }


    //borrado de filtrado
    if (isset($_POST["borrar"])){
        $datos["nombre"] = "";
        $datos["categoria"] = -1;
        $datos["borrado"] = -1;
    }
}



$res = getCURL($rutaProductos, "GET", $parametros);

if (!$res)
{
    paginaError("No se han podido obtener los datos");
    exit;
}


$res=json_decode($res,true); //datos de consulta devueltos en  array en json
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
$filas =$res["datos"];



$_posPagina = [
    [
        "texto" => "inicio",
        "url" => "/"
    ],
];

inicioCabecera("Badulaque - Inicio");
cabecera();
finCabecera();
inicioCuerpo("Badulaque", $_posPagina);
cuerpo($datos, $filas, $arrayCategorias);
finCuerpo();


// **********************************************************
function cabecera()
{
}
function cuerpo(array $datos, array $filas, array $arrayCategorias)
{
?>

    <!--Formulario de filtrado -->
    <form action="" method="post" class="formulario" style="margin-top: 5%;">
        <fieldset>
            <legend><b>Buscar producto</b></legend>
            <label for="nombre"> <b>Nombre: </b></label>
            <input name="nombre" type="text" value="<?php echo $datos["nombre"] ?>">
            <br>
            <label for="categoria"> <b>Categoría:</b> </label>
            <select name="categoria">
                <option value=-1>--Elige categoria--</option>
                <?php
                        foreach ($arrayCategorias as $clave => $valor){
                                echo "<option  value=$clave";
                                if ($datos["categoria"] == $clave) {
                                    echo " selected='selected'";
                                }
                                echo ">$valor</option>" . PHP_EOL;
                        }
                    ?>
            </select>
            <br>
            <label for="borrado"><b>Borrado: </b></label>
            <?php
                if ($datos["borrado"] === 0){
                    ?>
                        <input type="radio" name="borrado" value=1> Sí
                        <input type="radio" name="borrado" value=0 checked> No
                        <input type="radio" name="borrado" value=-1 > Todos
                    <?php
                }
                else if ($datos["borrado"] === 1){
                    ?>
                        <input type="radio" name="borrado" value=1 checked> Sí
                        <input type="radio" name="borrado" value=0> No
                        <input type="radio" name="borrado" value=-1 > Todos

                    <?php
                }
                else{
                    ?>
                        <input type="radio" name="borrado" value=1> Sí
                        <input type="radio" name="borrado" value=0> No
                        <input type="radio" name="borrado" value=-1 checked> Todos

                <?php
                }


            ?>


            <br>
            <br>
            <input type="submit" name="buscar" class="boton" value="Filtrar datos">
            <input type="submit" name="borrar" class="boton" value="Borrar búsqueda">
        </fieldset>
    </form>

    
    <?php
            foreach ($filas as $fila){
                ?>
                    <div class="productos">
                        <label> <?php echo $fila["nombre"] ?> </label>
                        <br>
                        <img src="<?php echo $fila["foto"]?>"><br>
                        <label>Precio: <?php echo $fila["precio_venta"] . "€" ?></label><br>
                        <label>Unidades: <?php echo $fila["unidades"]  ?> </label><br>
                    </div>
                <?php
            }
        ?>




<?php
}



