<?php
define("RUTABASE", dirname(__FILE__)); //metodos
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
//include(RUTABASE . "/aplicacion/config/acceso_bd.php");



//Creamos las constantes COLORESTEXTO y COLORESFONDO

const COLORESTEXTO = [
    "black" => "negro",
    "blue" => "azul",
    "white" => "blanco",
    "red" => "rojo",
    "green" => "verde",
];


const COLORESFONDO = [
    "white" => "blanco",
    "red" => "rojo",
    "green" => "verde",
    "blue" => "azul",
    "cyan" => "cian"
];

/**
 * Array donde guardamos los textos
 */
$textos = [];

//Sesion para array Textos y login
session_start();

//si no existe, la creamos
if (!isset($_SESSION["arrayTextos"])){
    $_SESSION["arrayTextos"] = "";
}


//Instancia ACLArray
$listaACL = new ACLArray();

if (!isset($_SESSION["acceso"])){ 
    //creo la instancia acceso
    $miAcceso = new Acceso ();
    $_SESSION["objetoAcceso"] = new Acceso ();
    $_SESSION["acceso"]["validado"] = "";
    $_SESSION["acceso"]["nick"] = "";
    $_SESSION["acceso"]["nombre"] = "";
    $_SESSION["acceso"]["permisos"] = false;
}




