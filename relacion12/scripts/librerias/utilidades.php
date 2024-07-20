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


    $rutaImagenes = RUTABASE. "\\imagenes\\productos\\" . basename($arrayFoto["name"]);

    if (move_uploaded_file($arrayFoto["tmp_name"], $rutaImagenes)){
        return true;
    }
    else{
        return false;
    }

}



/**
 * Función que se va a utilizar para leer un fichero,
 * el fichero contiene líneas con los datos del producto que vamos a subir
 * cada linea tendra el siguiente formato campo: valor, cada línea
 * contiene los valores de los campos de cada producto
 * Si hay problemas durante la lectura, no se sube el producto
 *
 * @param String $ficheroRuta --> ruta de donde leemos fichero
 * @param Array $datos --> array que guarda los valores de campos de cada obj
 * @param Array $arrayNombres --> lo usamos para validar que el nombre sea único
 * @param Array $arrayCategorias --> comprobamos que la categoría este en el array
 * @return Boolean true si hay valores en array datos, false si no se ha podido actualizar
 */
function leerDeFichero(string $ficheroRuta, array &$datos, array $arrayNombres, array $arrayCategorias): bool
{
    //ruta en la que se guardará el fichero
    $lineasProductos = file($ficheroRuta); //cada línea se guarda en un array

    $obj = [
        "nombre" => "",
        "fabricante" => "",
        "categoria" => 0,
        "fecha" => "",
        "unidades" => 0,
        "precio" => 0,
        "borrado" => 0,
        "iva" => 21,
        "foto" => "foto.png"

    ];

    //itero el array
    for ($cont = 0; $cont <= count($lineasProductos) -1; $cont++){
        
        $lineasProductos[$cont] = trim($lineasProductos[$cont]);


        if ($lineasProductos[$cont] !== "") {
            $campos = explode(",", $lineasProductos[$cont]);  //campos guardados en posiciones del array

            $nombre = trim(explode("nombre:", $campos[0])[1]);
            $fabricante = trim(explode("fabricante:", $campos[1])[1]);
            $categoria = trim(explode("categoria:", $campos[2])[1]);
            $fecha = trim(explode("fecha:", $campos[3])[1]);
            $unidades = intval(explode("unidades:", $campos[4])[1]);
            $precio = floatval(explode("precio:", $campos[5])[1]);
            $borrado = trim(explode("borrado:", $campos[6])[1]);

            if (count($campos) === 8) { //tiene campo iva al final
                $iva = floatval(explode("iva:", $campos[7])[1]);
            } 
            else {
                $iva = 21; //si no, se coge por defecto
            }

            //Validamos los campos
            //Si hay un campo que no se valida correctamente, no seguimos validando el resto
            if ($nombre !== "") { //NOMBRE

                if (validaCadena($nombre, 20, "")) {
                    $unico = true;

                    //comprobamos que el nombre del producto sea único
                    for ($cont2 = 0; $cont2 <= count($arrayNombres) - 1; $cont2++) {
                        foreach ($arrayNombres[$cont2] as $clave => $valor) {
                            if ($valor === $nombre) {
                                $unico = false;
                            }
                        }
                    }
                    if ($unico === true) { //producto con nombre único
                        $obj["nombre"] = $nombre;
                        //FABRICANTE
                        if ($fabricante !== "") {
                            if (validaCadena($fabricante, 20, "")) {
                                $obj["fabricante"] = $fabricante;
                                //CATEGORIA
                                if (validaRango($categoria, $arrayCategorias, 2)) {

                                    //Sacamos el valor numérico del array
                                    $categoria = array_search($categoria, $arrayCategorias);
                                    $obj["categoria"] = $categoria;

                                    //FECHA ALTA
                                    if ($fecha !== "") {

                                        if (validaFecha($fecha, "")) {

                                            $fechaDate = DateTime::createFromFormat("d/m/Y", $fecha);

                                            //Validamos que la fecha no sea menor de 28/2/2010 
                                            $fechaMenor = DateTime::createFromFormat("d/m/Y", "28/02/2010");
                                            if ($fechaDate > $fechaMenor) { //Se debe cumplir que nuestra fecha sea mayor

                                                $fechaActual = new DateTime();

                                                if ($fechaDate < $fechaActual) { //Fecha nuestra debe ser menor que la actual
                                                    $fecha = fechaAMYSQL($fecha); //guardamos fecha en formato mysql
                                                    $obj["fecha"] = $fecha;


                                                    //Unidades
                                                    if ($unidades >= 0) {
                                                        $obj["unidades"] = $unidades;

                                                        //precio
                                                        if ($precio >= 0) {
                                                            $obj["precio"] = $precio;


                                                            //Iva
                                                            if ($iva >= 0) {
                                                                $obj["iva"] = $iva;

                                                                //Borrado
                                                                if ($borrado === "si") {
                                                                    $borrado = 1;
                                                                }

                                                                if ($borrado === "no") {
                                                                    $borrado = 0;
                                                                }


                                                                if ($borrado === 0 || $borrado === 1) {

                                                                    $obj["borrado"] = $borrado;
                                                                    //guardamos en array
                                                                    $datos[] = $obj;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    //Si el array tiene datos devuelve true, si no da false
    if (count($datos) > 0){
        return true;
    }
    else
    {
        return false;
    }
    
}