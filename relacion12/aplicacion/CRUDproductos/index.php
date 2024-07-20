<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

$filas = [];
$parametros = "";

//---------------------------------------------//
//petición curl para obtener los productos
$res = getCURL($rutaProductos, "GET");

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
    paginaError($res["datos"]);
    exit;
}

$filas = $res["datos"];
//---------------------------------------------//



$_posPagina = [
    [
        "texto" => "inicio",
        "url" => "/"
    ],
    [
        "texto" => "CRUD productos",
        "url" => "/aplicacion/CRUDproductos/index.php"
    ]
];


inicioCabecera("Badulaque - Productos");
cabecera();
finCabecera();
inicioCuerpo("Badulaque", $_posPagina);
cuerpo($filas);
finCuerpo();

function cabecera()
{
}


function cuerpo(array $filas)
{
    ?>

        <form class="formulario">
            <fieldset>
                <legend><b>Opciones: </b></legend>
                <button class="boton">  <a href="anadirProducto.php">Añadir producto</a> </button>
                <button class="boton"> <a href="../../index.php">Volver atrás</a> </button>
            </fieldset>

        </form>

        
        <!--Tabla de productos -->
        <table class="tabla" style="text-align: center;">
            <caption><h3>Productos</h3></caption>
            <tr style="font-size: small;">
                <th>NOMBRE</th>
                <th>FABRICANTE</th>
                <th>CATEGORÍA</th>
                <th>FECHA DE ALTA</th>
                <th>UNIDADES</th>
                <th>PRECIO BASE</th>
                <th>IVA</th>
                <th>PRECIO IVA</th>
                <th>PRECIO VENTA</th>
                <th>FOTO</th>
                <th>BORRADO</th>
                <th>OPCIONES</th>
            </tr>
            <?php
                if(isset($filas)){
                    foreach($filas as $fila){
                        ?>
                            <tr>
                                <td> <?php echo $fila["nombre"].PHP_EOL;?></td>
                                <td> <?php echo $fila["fabricante"].PHP_EOL;?></td>
                                <td> <?php echo $fila["descripcion"].PHP_EOL;?></td>
                                <td> <?php echo $fila["fecha_alta"].PHP_EOL;?></td>
                                <td> <?php echo $fila["unidades"].PHP_EOL;?></td>
                                <td> <?php echo $fila["precio_base"].PHP_EOL;?></td>
                                <td> <?php echo $fila["iva"].PHP_EOL; ?></td>
                                <td> <?php echo $fila["precio_iva"].PHP_EOL;?></td>
                                <td> <?php echo $fila["precio_venta"].PHP_EOL;?></td>
                                <td>  <img  style="width: 90%;"  src=<?php echo $fila["foto"].PHP_EOL; ?>></td>
                                <td> 
                                    <?php 
                                        if (intval($fila["borrado"]) === 0){
                                                echo "No".PHP_EOL;
                                            }
                                            else{
                                                echo "Si".PHP_EOL;
                                            } 
                                    ?>
                                </td>
                                <td id="opciones2"> 
                                    <a href=<?php echo  "'verProducto.php?id=" . $fila['cod_producto'] . "'"; ?>> <img src="../../imagenes/iconos/ver.png" title="Ver producto"></a>
                        
                                    <a href=<?php echo  "'modificarProducto.php?id=" . $fila['cod_producto'] . "'"; ?>> <img src="../../imagenes/iconos/editar.png" title="Modificar producto"></a>
                                    
                                    <!--Borrar o recuperar producto -->
                                    <a href=<?php echo  "'borrarProducto.php?id=" . $fila['cod_producto'] . "'"; ?>> <img src="../../imagenes/iconos/eliminar.png" title="Borrar/Recuperar producto"></a>
       
                                </td>
                            </tr>
                        <?php
                    }
                }
            ?>
        </table>
        
    <?php
}
?>