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
    
include(RUTABASE . "/aplicacion/config/acceso_bd.php"); //acceso a la base de datos
include_once(RUTABASE . "/scripts/librerias/utilidades.php"); 
include_once(RUTABASE . "/scripts/librerias/validacion.php");  


//CONEXION BASE DE DATOS
mysqli_report(MYSQLI_REPORT_OFF);


//Establece conexion a la base de datos
$mysqli = @new mysqli($servidor, $usuario, $contrasenia, $bbdd);


//Compruebo si se ha establecido o no la conexión.
if ($mysqli->connect_errno) {
    header("Problemas internos");
    $resultado = ["datos" => "No se puede conectar a la base de datos",
            "correcto" => false];
    $res = json_encode($resultado, JSON_PRETTY_PRINT);
    echo $res;
    exit;
}


//establece la página de códigos del cliente
$mysqli->set_charset("utf8");


if ($mysqli->errno != 0) {
    header("Problemas internos");
    $resultado = [
        "datos" => "Problemas con la base de datos",
        "correcto" => false
    ];
    $res = json_encode($resultado, JSON_PRETTY_PRINT);
    echo $res;
    exit;

}
