<?php

/**
 * Función para realizar peticiones post
 * desde una API
 * nos llega una ruta, un array con los parametros
 * y un string que es el proxy esto puede ser nulo o una cadena
 *
 * @param String $url
 * @param Array $parametros
 * @param String|Null $proxy
 * @return SimpleXMLElement | False
 */
function peticionesXML(string $url, array &$errores ,array $parametros = [], string $proxy = "" ): false | SimpleXMLElement{

    $enlaceCurl=curl_init();

    if (!curl_setopt($enlaceCurl,CURLOPT_URL, $url)){
        return false;
    }
    
    curl_setopt($enlaceCurl, CURLOPT_POST, 1);


    if (count($parametros) !== 0){ //comprobamos si nos llegan parametros al array
        $cadena = "";

        foreach($parametros as $clave => $valor){
                $cadena .= "$clave=$valor&"; 
           }
        
        $cadena = mb_substr($cadena, 0, -1);

        curl_setopt($enlaceCurl,CURLOPT_POSTFIELDS,"$cadena");
    }

    curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
    curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER,1);


    //Comprobamos si llega proxy o no
    if ($proxy !== ""){ //si hay proxy se añade

        if (!curl_setopt($enlaceCurl, CURLOPT_PROXY, $proxy)){
            return false;
        }
    }

    //ejecuto la petición
    $xml = curl_exec($enlaceCurl);
    //cierro la sesión
    curl_close($enlaceCurl);

    
    $xml = str_replace('xmlns=', 'ns=', $xml);
    $arbol = new SimpleXMLElement($xml);


    if (count($arbol->xpath("//lerr/err/des")) !== 0){
        
        foreach ($arbol->xpath("//lerr/err/des") as $valor){
            $pasaACadena = "".$valor[0][0];
            $errores["peticion"][] = $pasaACadena;

        }
        return false;

    }
    else{

        return $arbol;
    }

}
?>