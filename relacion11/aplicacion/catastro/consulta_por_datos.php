<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");




//arrays de datos y errores
$datos = [
    "Provincia" => "",
    "Municipio" => "",
    "tipoVia" => "",
    "Calle" => "",
    "numCalle" => ""
];

$errores = [];
$muestraMunicipio = false; //para mostrar combo municipios
$muestraTipoVia = false; //para mostrar combo tipos vias
$muestraTodo = false; //para mostrar el resto de campos, cuando se validen los anteriores
$arrayMunicipios = []; //array donde guardaremos los municipios tras la petición
$arrayTipoVias = []; //array de tipo de vias
$datosCompletos = []; //array que mostraremos cuando hagamos la petición
$muestraDatos = false; //para mostrar el array de datosCompletos

//formulario
if ($_POST) {


    //validamos provincia
    if (isset($_POST["validaProvincia"])) {
        //provincias
        $provincia = "";
        if (isset($_POST["Provincia"])) {
            $provincia = trim($_POST["Provincia"]);

            if (!validaRango($provincia, $provincias)) {
                $errores["Provincia"][] = "Elija una provincia correcta";
            }
        }
        $datos["Provincia"] = $provincia;
        $_SESSION["datosXML"]["Provincia"] = $datos["Provincia"]; //guardamos la provincia, para la consulta
    }


    if (!$errores && (isset($_POST["validaProvincia"]))) { //si no hay errores y se ha pulsado boton valida provincia
                                                            //sacamos la peticion con sus municipios

        $ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/ConsultaMunicipio";
        $arbol = peticionesXML($ruta, $errores, $datos, $proxy);

        if ($arbol !== false) {

            foreach ($arbol->xpath("//municipiero/muni/nm") as $valor) {
                $arrayMunicipios[] = "" . $valor; //lo pasamos a cadena
            }

            $_SESSION["datosArrays"]["arrayMunicipios"] = $arrayMunicipios; //lo guardamos en sesion para no perderlo en post
            $muestraMunicipio = true; //mostramos combo de municipios
        }
    }

    if (isset($_POST["validaMunicipio"])) { //cuando se pulsa el boton de municipio


        $municipio = "";
        if (isset($_POST["Municipio"])) {
            $municipio = trim($_POST["Municipio"]);

            if (!validaRango($municipio, $_SESSION["datosArrays"]["arrayMunicipios"])) {
                $errores["Municipio"][] = "Elija un municipio correcta";
            }
        }

        $arrayMunicipios =  $_SESSION["datosArrays"]["arrayMunicipios"]; //se vuelve a actualizar el array de municipios, porque se pierde con post
        $datos["Provincia"] = $_SESSION["datosXML"]["Provincia"];
        $datos["Municipio"] = $municipio;
        $_SESSION["datosXML"]["Municipio"] = $datos["Municipio"];
        $_SESSION["datosXML"]["TipoVia"] = '';
        $_SESSION["datosXML"]["NombreVia"] = '';
        $muestraMunicipio = true; //mostramos municipio
    }

    if (!$errores && (isset($_POST["validaMunicipio"]))) { 

        //al comprobar los municipios
        //obtenemos los tipos de vias
        //de la petición del catastro

        $ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/ConsultaVia";
        $arbol = peticionesXML($ruta, $errores,  $_SESSION["datosXML"], $proxy);

        if ($arbol !== false) {

            foreach ($arbol->xpath("//callejero/calle/dir/tv") as $valor) {

                if (!validaRango($valor, $arrayTipoVias)) { //metemos las tipos vias sin repetir
                    $arrayTipoVias[] = "" . $valor; //lo pasamos a cadena
                }
            }
            $datos["Provincia"] = $_SESSION["datosXML"]["Provincia"];
            $datos["Municipio"] = $_SESSION["datosXML"]["Municipio"];
            $arrayMunicipios =  $_SESSION["datosArrays"]["arrayMunicipios"];
            $_SESSION["datosArrays"]["arrayTipoVias"] = $arrayTipoVias;
            $muestraTipoVia = true; //mostramos el combo de vias
        }
    }



    //Validamos los datos restantes
    if (isset($_POST["validaDatos"])){

        $sigla = "";
        if (isset($_POST["tipoVia"])){
            $sigla = trim($_POST["tipoVia"]);

            if (!validaRango($sigla,  $_SESSION["datosArrays"]["arrayTipoVias"])){
                $errores["tipoVia"][] = "Elija un tipo de vía correcta";

            }
        }
        $datos["tipoVia"] =  $sigla;


        $calle= "";
        if (isset($_POST["Calle"])){
            $calle = trim ($_POST["Calle"]);


            if ($calle === ""){
                $errores["Calle"][] = "Introduce una calle";
            }
        }

        //guardamos los datos para mostrarlos en el formulario
        $datos["Provincia"] = $_SESSION["datosXML"]["Provincia"];
        $datos["Municipio"] = $_SESSION["datosXML"]["Municipio"];
        $arrayMunicipios =  $_SESSION["datosArrays"]["arrayMunicipios"];
        $arrayTipoVias =  $_SESSION["datosArrays"]["arrayTipoVias"];
        $datos["Calle"] = $calle;


        $numero = 0;
        if (isset($_POST["Numero"])){
            $numero = intval($_POST["Numero"]);

            if (!validaEntero($numero, 1, 10000, 0)){
                $errores["Numero"][] = "El número debe ser mayor de 0 ";

            }
        }
        $datos["numCalle"] = $numero;
        $muestraTipoVia = true; //lo ponemos a true, porque al pulsar el post se reinicia a false
    }


    //Validacion final
    if (!$errores && isset($_POST["validaDatos"])){


        $ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/Consulta_DNPLOC";
        $_SESSION["datosFinal"] = [ //sesion con todos los datos que vamos enviar
            "Provincia" => $_SESSION["datosXML"]["Provincia"],
            "Municipio" => $_SESSION["datosXML"]["Municipio"],
            "Sigla" =>$datos["tipoVia"] ,
            "Calle" => $datos["Calle"],
            "Numero" => $datos["numCalle"],
            "Bloque" => "",
            "Escalera" => "",
            "Planta" => "",
            "Puerta" =>  ""
         ];


        $arbol = peticionesXML($ruta, $errores,  $_SESSION["datosFinal"], $proxy);


        if ($arbol !== false){ //cpmprobamos que la petición sea distinto de false


            //volvemos a guardar datos para el formulario
            $datos["Provincia"] = $_SESSION["datosXML"]["Provincia"];
            $datos["Municipio"] = $_SESSION["datosXML"]["Municipio"];
            $arrayMunicipios =  $_SESSION["datosArrays"]["arrayMunicipios"];
            $arrayTipoVias =  $_SESSION["datosArrays"]["arrayTipoVias"];
            $datos["tipoVia"] = $_SESSION["datosFinal"]["Sigla"];
            $datos["Calle"] = $_SESSION["datosFinal"]["Calle"];
            $datos["numCalle"] = $_SESSION["datosFinal"]["Numero"];
            $muestraTodo = true;


            //Iteramos el XML
            if (count($arbol->xpath("//bico")) !== 0){ //comprobamos si es una casa
                foreach($arbol->xpath("//bico/bi/debi") as $clave => $valor){
                    foreach ($valor as $pos => $dato){
                        $datosCompletos["".$pos] = "".$dato;
                    }
                }

                foreach($arbol->xpath("//ldt") as $data){
                    $datosCompletos["direccion"]= "".$data;
                }

                
                foreach($arbol->xpath("//bico/lcons") as $data){
                    foreach($data as $clave => $valor){
                        foreach($valor[0]->xpath("//lcd") as $clave => $valor){
                            $datosCompletos["tipo"] = "".$valor;
                        }
                    }
                }


            }
            
            

            if (count (($arbol->xpath("//lrcdnp"))) !== 0){ //comprobamos si es un piso
            
                //en caso de ser un piso
                //contiene numerosas viviendas
                //por lo que sacaremos la referencia
                //catastral de la primera vivienda

                $valor = $arbol->xpath("//lrcdnp/rcdnp[1]");
                $xmlSimple = $valor[0];
                $catastro = $xmlSimple->xpath("//rc[1]");
                $catastro = $catastro[0];

                foreach($catastro as $clave => $valor){
                    $datosCompletos[$clave] = "".$valor;
                }
            }

            $muestraDatos = true;
            
        }
        else{ //en caso de haber errores, se limpian los campos
            //para otra consulta
            $datos["tipoVia"] =  "";
            $datos["Calle"] = "";
            $datos["numCalle"] = "";
        }

    }
}




$_posPagina = [
    [
        "texto" => "catastro",
        "url" => "/aplicacion/catastro/index.php"
    ],
    [
        "texto" => "Consultar por datos",
        "url" => "/aplicacion/catastro/consulta_por_datos.php"
    ],
];


inicioCabecera("Consultar por datos");
cabecera();
finCabecera();
inicioCuerpo("Catastro", $_posPagina);
cuerpo($provincias, $arrayMunicipios, $errores, $datos,
         $muestraMunicipio, $muestraTipoVia, $arrayTipoVias,
          $muestraTodo, $datosCompletos, $muestraDatos );
finCuerpo();


// **********************************************************
function cabecera()
{
}
function cuerpo(array $provincias, array $arrayMunicipios, 
                array $errores, array $datos,
                bool $muestraMunicipio, bool $muestraTipoVia,
                array $arrayTipoVias,bool $muestraTodo, 
                array $datosCompletos,bool $muestraDatos )
{
?>
    <form class="formulario" method="post">
        <fieldset>
            <legend><b>Consultar por datos</b></legend>
            <label for="Provincia"><b>Seleccione provincia</b></label>
            <select name="Provincia" >
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
            if (isset($errores["Provincia"])) {
                echo "<div class='error'>";
                foreach ($errores["Provincia"] as $error)
                    echo "$error<br> " . PHP_EOL;
                echo "</div>";
            }
            ?>
            <input type="submit" name="validaProvincia" value="Valida provincia" class="boton">
            <br>
            <?php
            //se comprueba que haya sido validado el campo anterior
            if ($muestraMunicipio === true || $muestraTipoVia === true || $muestraTodo === true) { 
            ?>
                <label for="Municipio"><b>Municipio</b></label>
                <select name="Municipio">
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
                <input type="submit" name="validaMunicipio" value="Valida municipio" class="boton">
                <br>
            <?php
            }
            ?>

            <?php
            //si están validados los campos anteriores, mostramos los campos siguientes
            if ($muestraTipoVia === true || $muestraTodo === true) {
            ?>
                <label for="tipoVia"><b>Tipo de via</b></label>
                <select name="tipoVia">
                    <option value="defecto">--Seleccione un tipo de via--</option>
                    <?php 
                        foreach($arrayTipoVias as $clave => $tipoVia){
                            if ($datos["tipoVia"] === $tipoVia){
                                ?>
                                <option value="<?php echo $tipoVia ?>" selected><?php echo $tipoVia ?> </option>
                                <?php
                            }
                            else{
                                ?>
                                <option value="<?php echo $tipoVia ?>"><?php echo $tipoVia ?></option>
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
                ?><br>
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
                <input type="text" name="Numero" value="<?php echo $datos["numCalle"]?>"><br>
                <?php
                    if (isset($errores["Numero"])) {
                        echo "<div class='error'>";
                        foreach ($errores["Numero"] as $error)
                            echo "$error<br> " . PHP_EOL;
                        echo "</div>";
                    }
                ?>
                <input type="submit" name="validaDatos" value="Validar Datos" class="boton">
                <br>
                <br>
            <?php
            }

            if ($muestraDatos === true){ //se verifica que haya habido petición
                
                if (isset($datosCompletos["direccion"])){ //comprobamos si es casa
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
                else{ //piso
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
            else{ //si ha habido errores de petición, los sacamos
                if (isset($errores["peticion"])) {
                    echo "<div class='error'>";
                    foreach ($errores["peticion"] as $error)
                        echo "$error<br> " . PHP_EOL;
                    echo "</div>";
                }
            }


            ?>
            <br>
        </fieldset>
    </form>



    <button class="boton"> <a href="../catastro/index.php">Volver atrás</a> </button>

<?php
}
