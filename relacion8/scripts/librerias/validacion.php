<?php

/**
 * Libreria de funciones varias para validar diferentes valores.
 * Se usará en formularios y clases
 */
 
/**
 * Funcion que comprueba que $var contiene 
 * un entero que está entre $min y $max.
 * 
 * Deuvelve true si está entre el rango indicado
 * Devuelve false si está feura del rango indicado, 
 * en este caso $var toma el valor $defecto
 *
 * @param Integer $var valor que comprobamos que está en los rangos
 * @param Integer $min valor mínimo en el que debe estar $var
 * @param Integer $max valor máximo en el que debe estar $var
 * @param Integer $defecto valor por defecto que mandamos
 * @return Bool devuelve true o false, si es false $var = $defecto
 */
function validaEntero(int &$var, int $min, int $max, int $defecto): bool{


    if ($var >= $min && $var <= $max){
        return true;
    }
    else{
        $var = $defecto;
        return false;
    }

    
}


/**
 * Función que comprueba que $var de tipo real esta entre $min y $max
 * 
 * devuelve true si se cumple la condicion
 * devuelve false si no se cumple, y $var toma el valor $defecto
 *
 * @param Float $var valor float que comprobamos que está entre $min y $max
 * @param Float $min $min valor mínimo en el que debe estar $var
 * @param Float $max $max valor máximo en el que debe estar $var
 * @param Float $defecto $defecto valor por defecto que mandamos
 * @return Bool devuelve true o false, si es false $var = $defecto
 */
function validaReal(float &$var, float $min, float $max, float $defecto): bool{

    if ($var >= $min && $var <= $max){
        return true;
    }
    else{
        $var = $defecto;
        return false;
    }

}


/**
 * Función que me permite validar la fecha
 * que le paso como parámetro en $var, para que sea
 * tipo d/m/yyy o dd/mm/yyyy, en caso de cumplir dicha condicion
 * 
 * devuelve true
 * 
 * en caso contrario, $var toma el valor $defecto y 
 * la función devuelve false
 *
 * @param String $var cadena que comprobamos que sea fecha correcta
 * @param String $defecto fecha cadena que mandamos por defecto
 * @return Bool true -> sanea la fecha, false -> $var=$defecto
 */
function validaFecha(string &$var, string $defecto): bool{
    

    //creo la regEx para comprobar el formato de fecha d/m/yyyy o dd/mm/yyyy
    $exReg = "/\d{1,2}\/\d{1,2}\/\d{4}/";
    $arrayFecha = explode("/", $var);

    if (preg_match_all($exReg, $var) && checkdate($arrayFecha[1], $arrayFecha[0], $arrayFecha[2])){
        //guardamos fecha saneada en $var
        $var = mb_substr("00". $arrayFecha[0], -2)."/".
               mb_substr("00". $arrayFecha[1], -2). "/". 
               $arrayFecha[2];

        return true;      

    }
    else{ // en caso contrario, var toma valor por defecto
        $var = $defecto; 
        return false; //se devuelve false
    }

}


/**
 * Función que nos valida que la cadena $var
 * contiene el formato de fecha correcta h:m:s o  hh:mm:ss, 
 * en tal caso devuelve true
 * 
 * si no se cumple la condicion, 
 * asignara $var el valor $defecto y devolvera false
 *
 * @param String $var cadena hora que comprobamos que cumple el formato
 * @param String $defecto cadena hora que coge por defecto en caso de no validarse
 * @return Bool true -> $var hora saneada , false -> $var=$defecto
 */
function validaHora(string &$var, string $defecto): bool{

    //expresion regular para h:m:s o hh:mm:ss
    $exRegHora = "/[0-2]?[0-9]:[0-5]?[0-9]:[0-5]?[0-9]/";

    if (preg_match_all($exRegHora, $var)){

        //guardamos horas, minutos y segundos en array
        $arrayHora = mb_split(":", $var); 

        //devolvemos hora saneada
        $var = mb_substr("00". $arrayHora[0], -2). ":". 
               mb_substr("00", $arrayHora[1], -2). ":". 
               mb_substr("00". $arrayHora[2], -2);

        return true;
    }
    else{
        $var = $defecto;
        return false;
    }
}


/**
 * Función que nos comprueba que $var contiene un formato 
 * de email 
 * aaaaa@bbbb.ccc, en tal caso devuelve true
 * 
 * Si no se cumple la condición, devuelve false
 * y var toma el valor de $defecto 
 * 
 * @param String $var cadena con correo que se comprueba
 * @param String $defecto cadena correo que se cogería por defecto si no se valida
 * @return Bool true -> $var es correcto , false -> $var=$defecto
 */
function validaEmail(string &$var, string $defecto): bool{

    //Expresion regular que nos permite validar cadenas del tipo correo:  aaaaa@bbbb.ccc
    $exRegCorreo = "/^[a-zA-Z0-9-_\.]+@{1}[a-z]+\.[a-z]{2,3}$/";


    if (preg_match_all($exRegCorreo, $var)){
        return true;
    }
    else{
        $var = $defecto;
        return false;
    }
}

/**
 * Función que permite validar la longitud máxima 
 * de $var en función de una pasado por parámetro
 * 
 * en caso de cumplirse devuelve true,
 * sino var toma el valor de $defecto
 *
 * @param String $var cadena string que pasamos para comprobar su longitud
 * @param Integer $longitud longitud que debe tener la cadena como máximo
 * @param String $defecto valor que toma $var si se pasa de longitud
 * @return Boolean true -> $var es correcto | false -> $var=$defecto
 */
function validaCadena(string &$var, int $longitud, string $defecto): bool{

    if (mb_strlen($var) <= $longitud){ //metodo para sacar longitud de una cadena
        return true;
    }
    else{
        $var = $defecto;
        return false;
    }
}

/**
 * Función que comprueba que la cadena $var
 * cumple con la expresión regular $expresion, 
 * en caso de ser así devuelve true, 
 * 
 * sino deuvelve false
 * y $var toma el valor de $defecto
 *
 * @param String $var cadena que vamos a validar con expresion
 * @param String $expresion expresion regular que se usa para validar
 * @param String $defecto cadena que se coge por defecto si $var no se valida
 * @return Boolean true -> $var es validada | false -> $var=$defecto
 */
function validaExpresion(string &$var, string $expresion, string $defecto):bool{
    
    if (preg_match($expresion, $var)){
        return true;
    }
    else{
        $var  = $defecto;
        return false;
    }
}

/**
 * Función que comprueba que 
 * var es una de las keys del array, cuando tipo sea 1 
 * o si var es uno de los posibles valores del array, cuando tipo sea 2
 * 
 * en caso de ser cierto devuelve true,
 * si no encuentra ocurrencias devuelve false
 *
 * @param Mixed $var puede ser una clave o un valor, depende del $tipo
 * @param Array $posibles array en el que comprobamos que se encuentra $var
 * @param Integer $tipo puede ser 1, busca en las claves o 2 que busca en los valores
 * @return Boolean true si lo encuentra, false si no se encuentra
 */
function validaRango(mixed $var, array $posibles, int $tipo=2): bool{

    switch($tipo){//en función del valor 1,2 comprobamos si es key o value
        case 1: //claves
            return array_key_exists($var, $posibles); //comprobamos que una clave esta en un key del array
            break;
        case 2: //valores
             return in_array($var,  $posibles, true); //con esta funcion comprobamos si un valor esta en un array
            break;
        default://en caso de no introducir 1 o 2, devolvemos false porque no podemos operar
            return false;
    }
}



