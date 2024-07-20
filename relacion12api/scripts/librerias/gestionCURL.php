<?php


/**
 * Realiza una petición CURL a un servidor, servicio web, etc
 *
 * @param string $link  Direccion a la que realizar la petición
 * @param string $metodo Metodo de la petición: GET, POST, PUT, DELETE
 * @param string $parametros Parametros a incluir en la petición
 * @param boolean $proxy Usa proxy para salir a internet
 * @param string $dirproxy  Dirección del proxy
 * @return string|false  Falso en caso de error, la cadena que devuelve la
 *                      peticion en caso de exito
 */
function getCURL(string $link, string $metodo="POST", string $parametros = "", 
            bool $proxy = false, string $dirproxy = "" ) : string|false
{
    //metodos posibles 
    $metodos=["POST","GET","DELETE", "PUT"];
    $metodo=mb_strtoupper($metodo);
    if (!in_array($metodo,$metodos))
         return false;

    //creo una sesión CUrl
    $enlaceCurl = curl_init();

    //se indican las opciones para una petición HTTP Post
    
    //método de la petición
    switch ($metodo)
    {
        case 'GET': curl_setopt($enlaceCurl, CURLOPT_HTTPGET, 1);
                    if ($parametros!="")
                        $link.="?$parametros";
                    break;
        case 'POST':  curl_setopt($enlaceCurl, CURLOPT_POST, 1);
                    break;           
        case 'PUT': curl_setopt($enlaceCurl, CURLOPT_CUSTOMREQUEST, "PUT");
                    break;
        case 'DELETE': curl_setopt($enlaceCurl, CURLOPT_CUSTOMREQUEST, "DELETE");
                        break;
    }
    curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
    curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER, 1);
    
    //direccion url de la petición
    curl_setopt(
        $enlaceCurl,
        CURLOPT_URL,
        $link
    );

    //parametros si el método es distinto de GET
    if(!empty($parametros) && in_array($metodo,["POST","PUT","DELETE"])){
        curl_setopt($enlaceCurl, CURLOPT_POSTFIELDS, $parametros);
    }

    // PROXY
    if ($proxy) {
        curl_setopt($enlaceCurl, CURLOPT_PROXY, $dirproxy);
    }
    //ejecuto la petición
    $res = curl_exec($enlaceCurl);
    //cierro la sesión
    curl_close($enlaceCurl);

    return $res;
}