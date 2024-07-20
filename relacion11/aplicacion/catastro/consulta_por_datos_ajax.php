<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

//lo usamos para cargar el combo de tipo de vias de consulta_por_datos_ajax.php, 
//vamos a sacar las de Málaga por defecto
$arrayVias = []; 

$rutaVias = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/ConsultaVia";
$datosVias = [
    "Provincia" => "Málaga",
    "Municipio" => "Málaga",
    "TipoVia" => "",
    "NombreVia" => ""
];
$datosCompletos = [];
$muestraTodo = false;
$peticionVias = peticionesXML($rutaVias,$errores,$datosVias,$proxy);


if ($peticionVias !== false) {
    $arbol = $peticionVias;

    foreach ($arbol->xpath("//callejero/calle/dir/tv") as $via) {
        if (!validaRango($via, $arrayVias)) { //metemos las tipos vias sin repetir
            $arrayVias[] = "" . $via; //lo pasamos a cadena
        }
    }
}



//arrays de datos y errores
$datos = [
    "Provincia" => "",
    "Municipio" => "",
    "Sigla" => "",
    "Calle" => "",
    "Numero" => "",
    "Bloque" => "",
    "Escalera" => "",
    "Planta" => "",
    "Puerta" => ""
];

$errores = [];


//formulario
if ($_POST) {


    if (isset($_POST["validaDatos"])){


        $provincia = "";
        if (isset($_POST["Provincia"])){

            $provincia = trim($_POST["Provincia"]);


            if (!validaRango($provincia, $provincias)){
                $errores["Provincia"][] = "Debe elegir entre una de las provincias disponibles";
            }
            $datos["Provincia"] = $provincia;
        }



        $municipio = "";
        if (isset($_POST["Municipio"])){

            $municipio = trim($_POST["Municipio"]);

            if ($municipio === "defecto"){
                $errores["Municipio"][] = "Debe elegir entre uno de los municipios disponibles";
            }
        }
        $datos["Municipio"] = $municipio;


        $tipoVia = "";
        if (isset($_POST["tipoVia"])){
            $tipoVia = trim($_POST["tipoVia"]);

            if (!validaRango($tipoVia, $arrayVias)){
                $errores["tipoVia"][] = "Debe elegir entre uno de los tipos de vias disponibles";
            }
        }
        $datos["Sigla"] = $tipoVia;


        $calle = "";
        if (isset($_POST["Calle"])){

            $calle = trim($_POST["Calle"]);

            if ($calle === ""){
                $errores["Calle"][] = "Debe introducir una calle";
            }
        }
        $datos["Calle"] = $calle;


        $numero = "";
        if (isset($_POST["Numero"])){
            $numero = intval($_POST["Numero"]);

            if (!validaEntero($numero, 1,1000, 0)){
                $errores["Numero"][] = "Tienes que introducir un número positivo del 1 al 1000";
            }
        }
        $datos["Numero"] = $numero;

    }


    if (!$errores){
        $ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/Consulta_DNPLOC";
        $arbol = peticionesXML($ruta, $errores,  $datos, $proxy);


        if ($arbol !== false) {
            //Iteramos el XML
            if (count($arbol->xpath("//bico")) !== 0) { //casa
                
                foreach ($arbol->xpath("//bico/bi/debi") as $clave => $valor) {
                    foreach ($valor as $pos => $dato) {
                        $datosCompletos["" . $pos] = "" . $dato;
                    }
                }

                foreach ($arbol->xpath("//ldt") as $data) {
                    $datosCompletos["direccion"] = "" . $data;
                }


                foreach ($arbol->xpath("//bico/lcons") as $data) {
                    foreach ($data as $clave => $valor) {
                        foreach ($valor[0]->xpath("//lcd") as $clave => $valor) {
                            $datosCompletos["tipo"] = "" . $valor;
                        }
                    }
                }

            }



            if (count(($arbol->xpath("//lrcdnp"))) !== 0){ //piso


                $valor = $arbol->xpath("//lrcdnp/rcdnp[1]");
                $xmlSimple = $valor[0];
                $catastro = $xmlSimple->xpath("//rc[1]");
                $catastro = $catastro[0];

                foreach($catastro as $clave => $valor){
                    $datosCompletos[$clave] = "".$valor;
                }
            }
           
            $muestraTodo = true;
        }
        else{
            
            $muestraTodo = true;
            $datos["Calle"] = "";
            $datos["Numero"] = "";
        }
    }
}




$_posPagina = [
    [
        "texto" => "catastro",
        "url" => "/aplicacion/catastro/index.php"
    ],
    [
        "texto" => "Consultar por datos AJAX",
        "url" => "/aplicacion/catastro/consulta_por_datos_ajax.php"
    ],
];

//se guardara el array con los municipios 
//cada vez que se cambie el combo,
//para así poder guardar el option de municipio
//que se ha pulsado
$arrayDeMunicipios = $_SESSION["guardaMunicipio"];


inicioCabecera("Consultar por datos AJAX");
cabecera();
finCabecera();
inicioCuerpo("Catastro", $_posPagina);
cuerpo($provincias,$errores, $datos, $arrayVias, $muestraTodo, $datosCompletos,$arrayDeMunicipios  );
finCuerpo();


// **********************************************************
function cabecera()
{
    ?>
    <script src="../../javascript/main.js" defer></script> <!-- Fichero js -->
    <?php
}
function cuerpo(array $provincias,array $errores, array $datos, array $arrayVias
, bool $muestraTodo, array $datosCompletos, array $arrayDeMunicipios )
{
?>
    <form class="formulario" method="post">
        <fieldset>
            <legend><b>Consultar por datos</b></legend>
            <label for="Provincia"><b>Seleccione provincia</b></label>
            <select name="Provincia" id="provincias">
                <option value="defecto">--Seleccione provincia--</option>
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
            if (isset($errores["Provincia"])) {
                echo "<div class='error'>";
                foreach ($errores["Provincia"] as $error)
                    echo "$error<br> " . PHP_EOL;
                echo "</div>";
            }
            ?>

            <br>
            <label for="Municipio"> <b>Seleccione municipio</b></label>
            <select name="Municipio" id="municipios">
                <option value="defecto">--Elige un municipio--</option>
                <?php
                //Se comprueba que se haya pulsado el boton de validar y haya
                //valores en el arrayMunicipios, solo iteraremos en ese caso
                //para asi poder mostrar la opción seleccionada
                if (isset($_POST["Municipio"]) && isset($arrayDeMunicipios)){
                    
                    foreach ($arrayDeMunicipios as $clave => $muni){
                        if ($muni === $datos["Municipio"]){
                            ?> 
                                <option value="<?php echo $muni;?>" selected><?php echo $muni;?></option>
                            <?php
                        }
                        else{
                            ?> 
                            <option value="<?php echo $muni;?>"><?php echo $muni;?></option>
                        <?php
                        }
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
            <br>

            <label for="tipoVia"><b>Seleccione tipo de vía</b></label>
            <select name="tipoVia">
                <option value="defecto">--Elige tipo de vía--</option>
                <?php
                    foreach ($arrayVias as $clave => $via){
                        if ($via === $datos["Sigla"]){
                            ?>
                                <option value="<?php echo $via ?>" selected><?php echo $via ?></option>
                            <?php
                        }
                        else{
                            ?>
                                <option value="<?php echo $via ?>"><?php echo $via ?></option>
                            <?php
                        }
                    }
                ?>
            </select>
            <?php
                    if (isset($errores["tipoVia"])) {
                        echo "<div class='error'>";
                        foreach ($errores["tipoVia"] as $error)
                            echo "$error<br> " . PHP_EOL;
                        echo "</div>";
                    }
            ?>
            <br>
            <label for="Calle"><b>Nombre de la vía: </b></label>
                <input type="text" name="Calle" value="<?php echo $datos["Calle"]?>"><br>
                <?php
                    if (isset($errores["Calle"])) {
                        echo "<div class='error'>";
                        foreach ($errores["Calle"] as $error)
                            echo "$error<br> " . PHP_EOL;
                        echo "</div>";
                    }
                ?>
                <label for="Numero"><b>Número:</b> </label>
                <input type="text" name="Numero" value="<?php echo $datos["Numero"]?>"><br>
                <?php
                    if (isset($errores["Numero"])) {
                        echo "<div class='error'>";
                        foreach ($errores["Numero"] as $error)
                            echo "$error<br> " . PHP_EOL;
                        echo "</div>";
                    }
                ?>
                <br>
                <input type="submit" name="validaDatos" value="Validar datos" class="boton">




                <?php
                    if ($muestraTodo === true && (!$errores)){

                        if (isset($datosCompletos["direccion"])){
                            ?>  
                                <br>
                                <br>
                                <label>Casa</label>
                                <ul>
                                    <li><?php echo "Direccion: " .$datosCompletos['direccion'];?> </li>
                                    <li><?php echo "Tipo: " .$datosCompletos['tipo'];?> </li>
                                    <li><?php echo "Antigüedad: " .$datosCompletos['ant'];?> </li>
                                    <li><?php echo "Uso: " .$datosCompletos['luso'];?> </li>
                                    <li><?php echo "Superficie: " .$datosCompletos['sfc'] ."cm3";?> </li>
                                    <li><?php echo "Coeficiente de participación: " .$datosCompletos['cpt'];?> </li>
                                </ul>
                            <?php
                        }
                        else{
                            ?>
                                <br>
                                <br>
                                <label>Referencia catastral del piso: </label>  
                                <label><b>  
                                <?php
                                    echo "{$datosCompletos['pc1']}
                                    {$datosCompletos['pc2']}
                                    {$datosCompletos['car']}
                                    {$datosCompletos['cc1']}
                                    {$datosCompletos['cc2']}";
                                ?>
                                </b></label>
                            <?php

                        }
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
        </fieldset>
    </form>

    <button class="boton"> <a href="../catastro/index.php">Volver atrás</a> </button>

<?php
}
