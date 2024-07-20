<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");
$errores = [];
$provincia = "";
$datos = [];
$proxy = "";

//Vamos a recibir por ajax la provincia seleccionada
//se hara una petición
//y se cargaran los datos de municipios de esa provincia en concreto
//lo devolvemos por JSON
if (isset($_POST["provincia"])){
    $provincia =  trim($_POST["provincia"]);
 

    $datos = [
        "Provincia" => $provincia,
        "Municipio" => ""
    ];
    $ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/ConsultaMunicipio";
    $arbol = peticionesXML($ruta, $errores, $datos, $proxy);
    
    if ($arbol !== false) {

        foreach ($arbol->xpath("//municipiero/muni/nm") as $valor) {
            $arrayMunicipios[] = "" . $valor; //lo pasamos a cadena
        }


        $_SESSION["guardaMunicipio"] = $arrayMunicipios; //guardamos los municipios para guardar el option seleccionado
    }

    echo json_encode($arrayMunicipios);

}





















?>