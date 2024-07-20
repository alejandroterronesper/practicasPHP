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
include_once(RUTABASE."/scripts/librerias/utilidades.php"); //Ruta de la libreria de utilidades

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

//CONEXION BASE DE DATOS
mysqli_report(MYSQLI_REPORT_OFF);
// $servidor = "127.0.0.1";
// $usuario = "usu9";
// $contrasenia = "2daw";
// $bbdd = "practica9";

$arrayBBDD = [];
    if (leerDeFichero("acceso_bd.inc", $arrayBBDD)) {
        $servidor = $arrayBBDD[0];
        $usuario =  $arrayBBDD[1];
        $contrasenia = $arrayBBDD[2];
        $bbdd = $arrayBBDD[3];

        //Instancia ACLBD
        $listaACL = new ACLBD($servidor, $usuario, $contrasenia, $bbdd);



        $acceso = new Acceso();



        //Establece conexion a la base de datos
        $mysqli = @new mysqli($servidor, $usuario, $contrasenia, $bbdd);


        //Compruebo si se ha establecido o no la conexión.
        if ($mysqli->connect_errno) {
            paginaError("Problemas internos");
            exit;
        }


        //establece la página de códigos del cliente
        $mysqli->set_charset("utf8");


        if ($mysqli->errno != 0) {
            paginaError("Error en el acceso de la base de datos");
            exit;
        }
    }
    else{
        paginaError("Error en el acceso de la base de datos");
        exit;
    }


