<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");




//arrays de datos y errores
$datos = [
    "Provincia" => "",
    "Municipio" => "",
    "Poligono" => "",
    "Parcela" => ""
];

$errores = [];
$arrayMunicipios = [];
$datosFinales = [];
$muestraCampos = false; //bool que usamos para validar parametros y si se validan correctamente,
                        //muestro el resto de campos
$muestraInformacion = false; //bool para mostrar los datos de la petición

//formulario
if ($_POST) {

    //consulta
    if (isset($_POST["validaProvincia"])) {


        //provincias
        $provincia = "";
        if (isset($_POST["provincias"])) {
            $provincia = trim($_POST["provincias"]);

            if (!validaRango($provincia, $provincias)) {
                $errores["Provincias"][] = "Elija una provincia correcta";
            }
        }
        $datos["Provincia"] = $provincia;
        $_SESSION["rusticaXML"]["Provincia"] = $datos["Provincia"];
    }


    if (!$errores && (isset($_POST["validaProvincia"]))) {
        $ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/ConsultaMunicipio";
        $arbol = peticionesXML($ruta, $errores, $datos, $proxy);

        if ($arbol !== false) {

            foreach ($arbol->xpath("//municipiero/muni/nm") as $valor) {
                $arrayMunicipios[] = "" . $valor; //lo pasamos a cadena
            }

            $_SESSION["arrays"]["arrayMunicipios"] = $arrayMunicipios;
            $muestraCampos = true;
        }
    }




    if (isset($_POST["consulta"])){

        $municipio = "";
        if (isset($_POST["municipio"])){
            $municipio = trim($_POST["municipio"]);


            if (!validaRango($municipio,  $_SESSION["arrays"]["arrayMunicipios"])){
                $errores["Municipio"][] = "Elija un municipio correcta";
            }
        }
        $datos["Municipio"] = $municipio;



        //poligono
        $poligono = "";
        if (isset($_POST["poligono"])){

            $poligono = intval($_POST["poligono"]);


            if (!validaEntero($poligono, 1, 999, 1)){
                $errores["Poligono"][] = "Introduce un número de tres dígitos";
            }

        }
        $datos["Poligono"] = $poligono;


        //municipio
        $parcela = "";
        if (isset($_POST["parcela"])){

            $parcela = intval($_POST["parcela"]);

            
            if (!validaEntero($parcela, 1, 999, 1)){
                $errores["Parcela"][] = "Introduce un número de tres dígitos";
            }
            
        }
        $datos["Parcela"] = $parcela;

        $datos["Provincia"] =  $_SESSION["rusticaXML"]["Provincia"];
        $arrayMunicipios =  $_SESSION["arrays"]["arrayMunicipios"];
        $_SESSION["rusticaXML"]["Provincia"] =  $datos["Provincia"];
        $_SESSION["rusticaXML"]["Municipio"] =  $datos["Municipio"];
        $_SESSION["rusticaXML"]["Poligono"] =  $datos["Poligono"];
        $_SESSION["rusticaXML"]["Parcela"] =  $datos["Parcela"];
        $muestraCampos = true;       
    }


    if (!$errores && isset($_POST["consulta"])){

        $ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/Consulta_DNPPP";
        $arbol = peticionesXML($ruta, $errores, $_SESSION["rusticaXML"], $proxy);

        if ($arbol !== false){


            if (count($arbol->xpath("//bico/bi/ldt")) > 0){
                    foreach($arbol->xpath("//bico/bi/ldt")  as $direccion){
                        $datosFinales["sitio"] = "".$direccion;
                }

                foreach($arbol->xpath("//bico/lspr/spr/dspr")  as $valores){
                    foreach($valores as $key => $value){
                        $datosFinales["".$key] = "".$value;
                    }
                }
            }
            else{
                $arbol = $arbol->xpath("//lrcdnp/rcdnp");
                $arbol = $arbol[0];

                foreach ($arbol->xpath("//rc") as $pos => $val){
                    foreach($val as $key => $value){
                        $datosFinales[$key] = "".$value;
                    }
                   
                }


                foreach ($arbol->xpath("//dt/locs/lors/lourb/dir") as $clave => $valor){
                    foreach($valor as $miClave => $miValor){
                        $datosFinales[$miClave] = "".$miValor;

                    }
                }


                //sacamos paisaje
                $paisaje = $arbol->xpath("//dt/locs/lors/lorus/npa");
                $paisaje = $paisaje[0];
                $datosFinales["NombreDelParaje"] = "".$paisaje;

            }

            $muestraInformacion = true;
        }
        else{
            $datos["Poligono"] = "";
            $datos["Parcela"] = "";
        }
        
    }
}




$_posPagina = [
    [
        "texto" => "catastro",
        "url" => "/aplicacion/catastro/index.php"
    ],
    [
        "texto" => "Consulta rustica",
        "url" => "/aplicacion/catastro/consulta_rustica.php"
    ],
];


inicioCabecera("Consulta rústica");
cabecera();
finCabecera();
inicioCuerpo("Catastro", $_posPagina);
cuerpo($provincias, $errores, $datos, $muestraCampos, 
$arrayMunicipios, $muestraInformacion, $datosFinales);
finCuerpo();


// **********************************************************
function cabecera()
{
}
function cuerpo(array $provincias, array $errores, array $datos, 
bool $muestraCampos, array $arrayMunicipios, 
bool $muestraInformacion, 
array $datosFinales)
{
?>


    <form class="formulario" method="post">
        <fieldset>
            <legend><b>Consulta rústica</b></legend>
            <label for="provincias"><b>Seleccione provincia</b></label>
            <select name="provincias">
                <option value="defecto">--Seleccione provinica--</option>
                <?php
                foreach ($provincias as $clave => $provincia) {
                    if ($datos["Provincia"] === $provincia) {
                ?>
                        <option value="<?php echo $provincia ?>" selected><?php echo $provincia ?></option>
                    <?php
                    } else {
                    ?>
                        <option value="<?php echo $provincia ?>"><?php echo $provincia ?></option>
                <?php
                    }
                }
                ?>
            </select>
            <?php
            if (isset($errores["Provincias"])) {
                echo "<div class='error'>";
                foreach ($errores["Provincias"] as $error)
                    echo "$error<br> " . PHP_EOL;
                echo "</div>";
            }
            ?>
            <input type="submit" name="validaProvincia" value="Validar provincia" class="boton"><br>
            <?php
            if ($muestraCampos === true) {
            ?>
                <label for="municipio"><b>Municipio</b></label>
                <select name="municipio">
                    <option value="defecto">--Seleccione municipio--</option>
                    <?php
                        foreach ($arrayMunicipios as $clave => $municipio) {
                            if ($datos["Municipio"] === $municipio) {
                    ?>
                        <option value="<?php echo $municipio ?>" selected><?php echo $municipio ?></option>
                        <?php
                            } else {
                        ?>
                            <option value="<?php echo $municipio ?>"><?php echo $municipio ?></option>
                    <?php
                                    }
                            }
                    ?>
                </select>
                <?php
                if (isset($errores["Municipio"])) {
                    echo "<div class='error'>";
                    foreach ($errores["Municipio"] as $error)
                        echo "$error<br> " . PHP_EOL;
                    echo "</div>";
                }
                ?>
                </select>
                <br>
                <label for="poligono"><b>Polígono</b></label>
                <input type="text" name="poligono" value="<?php echo $datos["Poligono"];?>">
                <?php
                if (isset($errores["Poligono"])) {
                    echo "<div class='error'>";
                    foreach ($errores["Poligono"] as $error)
                        echo "$error<br> " . PHP_EOL;
                    echo "</div>";
                }
                ?>
                <br>
                <label for="parcela"><b>Parcela</b></label>
                <input type="text" name="parcela" value="<?php echo $datos["Parcela"];?>">
                <?php
                if (isset($errores["Parcela"])) {
                    echo "<div class='error'>";
                    foreach ($errores["Parcela"] as $error)
                        echo "$error<br> " . PHP_EOL;
                    echo "</div>";
                }
                ?>
                <br>
                <input type="submit" name="consulta" value="Realizar consulta" class="boton">
            <?php
            }
            ?>

        </fieldset>
    </form>

    <?php
        if ($muestraInformacion  === true){

            ?>
                <label><b>Información de la parcela</b></label>
                <ul>
                    <?php
                        if (isset($datosFinales["sitio"])){
                            ?>
                                <li><b>Localización: <?php echo $datosFinales["sitio"];?> </b></li>
                                <li><b>Calificación catastral: <?php echo $datosFinales["ccc"];?> </b></li>
                                <li><b>Denominación de la clase de cultivo: <?php echo $datosFinales["dcc"];?> </b></li>
                                <li><b>Intensidad productiva: <?php echo $datosFinales["ip"];?> </b></li>
                                <li><b>Superficie de la subparcela: <?php echo $datosFinales["ssp"];?> m<sup>2</sup> </b></li>
                            <?php
                        }
                        else{
                            ?>
                            <li><b>Nombre de la vía: <?php echo $datosFinales["nv"];?> </b></li>
                            <li><b>Dirección no estructurada: <?php echo $datosFinales["td"];?> </b></li>
                            <li><b>Nombre del paraje: <?php echo $datosFinales["NombreDelParaje"];?> </b></li>
                            <li><b>Tipo de vía: <?php echo $datosFinales["tv"];?> </b></li>
                            <li><b>Ref catastral: <?php echo $datosFinales["pc1"]. "". $datosFinales["pc2"].
                                     "". $datosFinales["car"]."". $datosFinales["cc1"]."".
                            $datosFinales["cc2"];  ?> </b></li>
                            

                        <?php
                        }


                    ?>


                </ul>
            <?php
        }
        else{
            if (isset($errores["peticion"])) {
                echo "<div class='error'>";
                foreach ($errores["peticion"] as $error)
                    echo "$error<br> " . PHP_EOL;
                echo "</div>";
            }
        }

    ?>


    <button class="boton"> <a href="../catastro/index.php">Volver atrás</a> </button>

<?php
}
