<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");


//variables
$datos = [
    "Provincia" => "",
    "Municipio" => "",
];
$errores = [];
$arrayResultado = []; //array donde guardamos los resultados de la petición


//formulario
if ($_POST){

    //Comprobamos que se pulse el botón consulta
    if (isset($_POST["consulta"])){ 

        //provincia
        $provincia  ="";
        if (isset($_POST["provincias"])){
            $provincia = trim($_POST["provincias"]);

            //validamos en el array de provincias
            if (!validaRango($provincia, $provincias)){
                $errores["provincias"][] = "Elige una opción correcta";
            }
        }
        $datos["Provincia"] = $provincia;


        //municipio
        $municipio = "";// nos puede llegar vacio
        if (isset($_POST["municipio"])){
            $municipio = trim ($_POST["municipio"]);
        }
        $datos["Municipio"] = $municipio;
    }


    if (!$errores){ //Si no hay errores, hacemos la petición
        $ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/ConsultaMunicipio";
        $arbol = peticionesXML($ruta, $errores, $datos,$proxy);
        if ($arbol !== false){ //Si la petición es distinto de falso, formateamos resultado

            foreach ($arbol->xpath("//municipiero/muni/nm") as $valor){ //acedemos a la ruta
                $arrayResultado[] = "".$valor; //lo pasamos a cadena
            }
        }
        
        if ($errores){ //Se comprueban si hay errores de la peticion
            $datos["Municipio"] = ""; //dejamos campo municipio en blanco, porque hay error de petición
        }
    }
}


$_posPagina = [
    [
        "texto" => "catastro",
        "url" => "/aplicacion/catastro/index.php"
    ],
    [
        "texto" => "Consultar municipios",
        "url" => "/aplicacion/catastro/consulta_municipios.php"
    ],
];


inicioCabecera("Consultar municipios");
cabecera();
finCabecera();
inicioCuerpo("Catastro", $_posPagina);
cuerpo($provincias, $errores, $datos, $arrayResultado);
finCuerpo();


// **********************************************************
function cabecera()
{
}
function cuerpo(array $provincias, array $errores, array $datos, array $arrayResultado)
{
?>
    <!--Formulario de provincias -->
    <form class="formulario" method="post">
        <fieldset>
            <legend><b>Consultar municipios de una provincia</b></legend>
            <label for="provincias"><b>Seleccione provincia</b></label>
            <select name="provincias">
                <option value="defecto">--Seleccione provinica--</option>
                <?php
                foreach ($provincias as $clave => $provincia) { //guardamos la opción del select, cuando se haga post
                    if ($datos["Provincia"] === $provincia){
                        ?>
                        <option value="<?php echo $provincia?>" selected><?php echo $provincia?></option>
                        <?php
                    }
                    else{
                        ?>
                        <option value="<?php echo $provincia?>"><?php echo $provincia?></option>
                        <?php
                    }

                }
                ?>
            </select>
                <?php
                    if (isset($errores["provincias"])) {
                        echo "<div class='error'>";
                        foreach ($errores["provincias"] as $error)
                            echo "$error<br> " . PHP_EOL;
                            echo "</div>";
                        }
                ?>
            <br>
            <label for="municipio"><b>Municipio</b></label>
            <input type="text" name="municipio" value="<?php echo $datos["Municipio"] ?>"><br><br>
            <input type="submit" name="consulta" value="Realizar consulta" class="boton">
        </fieldset>
    </form>

    <?php //comprobamos que el array de consulta tenga valores
        if (count($arrayResultado) > 0){
            ?>
                <form class="formulario">
                    <fieldset>
                        <legend><b>Resultado de consulta: </b></legend>
                        <ul>
                            <?php
                            foreach($arrayResultado as $valores){ //iteramos los diferentes resultados
                                ?><li><?php echo $valores;?></li><?php
                            }
                            ?>
                        </ul>
                    </fieldset>
                </form>
            <?php
        }


        if (isset($errores["peticion"])) { //se muestran los posibles errores de la petición
            echo "<div class='error'>";
            foreach ($errores["peticion"] as $error)
                echo "$error<br> " . PHP_EOL;
                echo "</div>";
            }
    ?>
    

    <button class="boton"> <a href="../catastro/index.php">Volver atrás</a> </button> <!-- Botón para volver al index -->
    
    <?php
}
