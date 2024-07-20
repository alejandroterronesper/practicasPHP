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
include_once(RUTABASE . "/scripts/librerias/gestionCURL.php");  
//En las sesiones vamos a guardar el enlace anterior en caso de login para redirigir
session_start(); 

//si no existe, la creamos
if (!isset($_SESSION["arrayFiltrado"])){
    $_SESSION["arrayFiltrado"] = [
        "nombre" => "",
        "categoria" => -1,
        "sentencia" => "",
        "productos" => []
    ];
}


$rutaProductos = "www.relacion12api.es/aplicacion/API/productos.php";
$rutaCategoria = "www.relacion12api.es/aplicacion/API/categorias.php";
