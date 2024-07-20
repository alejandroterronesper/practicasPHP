<?php
define("RUTABASE", dirname(__FILE__));
//define("MODO_TRABAJO","produccion"); //en "produccion o en desarrollo
define("MODO_TRABAJO", "desarrollo"); //en "produccion o en desarrollo

if (MODO_TRABAJO == "produccion")
    error_reporting(0);
else
    error_reporting(E_ALL);

spl_autoload_register(function ($clase) {



    $ruta = RUTABASE . "/aplicacion/clases/"; //ruta de las clases
    $rutaScript = RUTABASE . "/scripts/clases/"; //ruta de las clases de scripts
    $fichero = $ruta . "$clase.php";
    $ficheroScript = $rutaScript . "$clase.php";

    if (file_exists($fichero)) {
        require_once($fichero);
    } else {

        if (file_exists($ficheroScript)) {
            require_once($ficheroScript);
        } else {

            throw new Exception("La clase $clase no se ha encontrado.");
        }
    }
});
    
include(RUTABASE . "/aplicacion/plantilla/plantilla.php");
include_once(RUTABASE . "/scripts/librerias/utilidades.php"); 
include_once(RUTABASE . "/scripts/librerias/validacion.php"); 
include_once(RUTABASE . "/scripts/librerias/peticiones.php");   


//Sesion
session_start(); 

//array global de errores para pasarlo como parametro a la funcion peticionesXML
$errores = [];
$parametros = [];

//Creamos arrays dentro de sesion
//para no perder los valores en el post
//en caso de que haya errores
if (!isset($_SESSION["datosArrays"])  ){
    $_SESSION["datosArrays"] = []; //donde guardamos arrays de municipios para no perderlos
}
if (!isset($_SESSION["datosXML"])){
    $_SESSION["datosXML"] = []; //guardamos los datos de la peticion
}

if (!isset($_SESSION["guardaMunicipio"])){
    $_SESSION["guardaMunicipio"] = []; //lo usaremos para consulta por datos ajax para poder guardar la option seleccionada del municipio
}



//variable global
$proxy = ""; //casa
//$proxy = "192.168.2.254:3128"; //clase
$ruta = "http://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/ConsultaProvincia"; 

$peticion = peticionesXML($ruta,$errores,$parametros,$proxy);

if ($peticion !== false) {
    $arbol = $peticion;

    $provincias; //Cargamos un array de provincias global para no tener que hacer petición en cada página
    foreach ($arbol->xpath("//provinciero/prov/np") as $provincia) {
        $provincias[] = "" . $provincia; //lo pasamos a cadena
    }
}


