<?php

/**
 * Función que convierte una fecha
 * en formato dd/mm/yyyy en formato
 * 
 * aaaa-mm-dd
 *
 * @param String $fecha fecha en formato dd/mm/aaaa
 * @return String si se convierte en formato false si no se convierte
 */
function fechaAMYSQL (string $fecha):string | false{
    
    $partes=[];
    if (!preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{1,4})/", $fecha, $partes)) 
        {
            return false;
        }
    else
    {
        return $fecha = $partes[3] . "-" . $partes[2] . "-" . $partes[1];
    }

}


/**
 * funcion que convierte una fecha de formato aaaa- mm - dd
 *  dd/mm/aaaa 
 * 
 * @param string $fecha fecha en formato aaaa-mm-dd 
 * @return String devuelve fecha en dd/mm/aaaa o false si no se convierte
 */
function MYSQLaFecha(string $fecha):string | false{

    $partes=[];
    if (!preg_match("/([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2})/", $fecha, $partes)) 
        {
            return false;
        }
    else
    {
        return $fecha = $partes[3] . "/" . $partes[2] . "/" . $partes[1];
    }
}


/**
 * Funcion que nos permite subir una foto
 *
 * @param array $arrayFoto array de la variable global $_FILES
 * @return boolean true si se pasa la foto false si no
 */
function subeFoto (array $arrayFoto): bool{


    $rutaImagenes = RUTABASE. "\\imagenes\\" . basename($arrayFoto["name"]);

    if (move_uploaded_file($arrayFoto["tmp_name"], $rutaImagenes)){
        return true;
    }
    else{
        return false;
    }

}


/**
* funcion para leer de un fichero
* @param string $nombre nombre del fichero
* @param mixed $datos valores
* @return bool devuelve si ejecuta correctamente o hay algún error
*/
function leerDeFichero(string $nombre, array &$datos): bool
{
    //ruta en la que se guardará el fichero
    $ruta = RUTABASE . "\\aplicacion\\config\\";


    //si no existe la ruta se crea
    if (!file_exists($ruta)){
        mkdir($ruta);
    }

    $ruta .= $nombre;


    //se abre el fichero para lectura
    //debe existir
    $fic = fopen($ruta, "r");
    if (!$fic){
        return false;
    }
    
    //borro el contenido del array
    foreach ($datos as $pos => $valor) {
        unset($datos[$pos]);
    }


    
    //leo el fichero linea a linea
    while ($linea = fgets($fic)) {


        $linea = str_replace("\n", "", $linea);

        if ($linea != "") {
            $datos[] = trim($linea);
        }
    }


    //se cierra el fichero
    fclose($fic);
    return true;
}