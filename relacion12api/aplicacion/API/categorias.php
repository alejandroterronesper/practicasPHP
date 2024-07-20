<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");



$sentenciaCategorias = "SELECT * FROM categorias";
$selectWhere = ""; //en caso de filtrar por cod_categoria
$errores = []; //array de los posibles errores que enviamos al cliente


//PETICION GET
if ($_SERVER["REQUEST_METHOD"] === "GET"){


    if (isset($_GET["cod_categoria"])){

        //se indica el elemento de la consulta
        $id = intval($_GET["cod_categoria"]);

        $selectWhere = " WHERE cod_categoria = '$id'";
    }


    //concatenamos consulta con WHERE
    $sentenciaCategorias.=$selectWhere;

    $arrayCategorias = [];
    $consulta = $mysqli->query($sentenciaCategorias);

    
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


    //Se comprueban los errores
    if (!$consulta) {
        header("Error en el acceso de la base de datos");
    
        $resultado = [
            "datos" => "No se puede conectar a la base de datos",
            "correcto" => false
        ];
    
        $res = json_encode($resultado, JSON_PRETTY_PRINT);
        echo $res;
        exit;
    }

    while ($fila = $consulta->fetch_assoc()){
        $arrayCategorias[(int)$fila["cod_categoria"]] = $fila["descripcion"];
    }


    $resultado = [
        "datos" => $arrayCategorias,
        "correcto" => true
    ];

    $res = json_encode($resultado, JSON_PRETTY_PRINT);
    echo $res;
    exit;

}




//PETICION POST (INSERTAR)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["oper"])){

        $oper = "";

        $oper = intval( ($_POST["oper"]));


        if ($oper === 1){
            peticionPUT($_POST, $errores, $mysqli);
        }

        if ($oper === 2){
            peticionDELETE($_POST, $errores, $mysqli);
        }


    }
    else{
        
    $descripcion = "";
    if (isset($_POST["descripcion"])){

        $descripcion = trim(($_POST["descripcion"]));
        $descripcion = $mysqli->escape_string($descripcion); //evitamos inyección SQL


        //se comprueba si la cadena es vacia
        if ($descripcion === ""){
            $errores["descripcion"][] = "Debes introducir una descripción a la categoría";
        }


        if (!validaCadena($descripcion, 20, "")){
            $errores["descripcion"][] = "La descripción debe tener como máximo 20 caracteres";
        }
        


        //Se comprueba si la cadena está repetida
        $select = "SELECT `descripcion` FROM `categorias`
                 WHERE descripcion= '$descripcion'";
        $buscarCategoria = $mysqli->query($select);

        if ($mysqli->errno != 0) { //se comprueba petición
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error en la base de datos",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
    
        }

        //Se comprueba si la categoría existe
        if ($buscarCategoria->num_rows > 0){
            $errores["descripcion"][] = "Error, ya existe una categoría con esta descripción";
        }



        //Comprobamos si no hay errores en el array
        if (!$errores) {
            //Hacemos insercción

            //insert en categorias de la bbdd tienda
            $sentenciaInsert = "INSERT INTO `categorias` (`descripcion`)
                                VALUES ('$descripcion')";

            $insertarBBDD = $mysqli->query($sentenciaInsert);


            if ($mysqli->errno != 0) { //comprobamos si hubo errores durante la consulta
                header("HTTP/1.0 404 Problemas internos.");
                $resultado = [
                    "datos" => "Error al procesar la petición",
                    "correcto" => false
                ];
                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;
            }


            if ($insertarBBDD === false) { //comprobamos si la consulta devuelve false
                header("HTTP/1.0 404 Problemas internos.");
                $resultado = [
                    "datos" => "Error añadir categoría",
                    "correcto" => false
                ];
                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;
            }

            //Se devuelve como dato el id, de categoría insertada
            //y correcto a true
            $id = intval($mysqli->insert_id); //id insertado
            $resultado = [
                "datos" => $id,
                "correcto" => true
            ];

            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }
        else{ //si hay errores, devolvemos el array de errores, y correcto a false
            $resultado = [
                "datos" => $errores,
                "correcto" => false
            ];

            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }
    }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "PUT") {

    //petición por PUT-> es una modificación
    //recojo los parámetros
    $parametros = recogerParametros();
    peticionPUT($parametros, $errores, $mysqli);
    
}


if ($_SERVER["REQUEST_METHOD"] == "DELETE"){
    //petición DELETE es borrado

    //En este caso hacemos un borrado físico
    //a la tabla de categorías

    //recojo los parámetros
    $parametros = recogerParametros();
    peticionDELETE($parametros, $errores, $mysqli);
    
}


echo "";
exit;
/********************************************************************/

/**
 * Función que recoge parámetros
 * de un .php se usa para las peticiones PUT y 
 * DELETE
 *
 * @return Array $par -> array de clave valor 
 *      nombre del parámetro : valor parámetro
 */
function recogerParametros()
{
    //recojo los parámetros
    $ficEntrada = fopen("php://input", "r");
    $datos = "";
    while ($leido = fread($ficEntrada, 1024)) {
        $datos .= $leido;
    }
    fclose($ficEntrada);
    //convierto los datos en variables
    $par = [];
    $partes = explode("&", $datos);
    foreach ($partes as $parte) {
        $p = explode("=", $parte);
        if (count($p) == 2)
            $par[$p[0]] = $p[1];
    }
    return $par;
}


/**
 * Funcion para realizar peticion PUT
 * ya sea por POST con oper = 1 o PUT
 * 
 * enviamos un array con parametros
 * ,array de posibles errores
 * y objeto de acceso a la BBDD
 * 
 * se devuelve un echo del array codificado en JSON de los resultados
 *
 * @param array $parametros parametros para hacer la peticion
 * @param array $errores array de posibles errores
 * @param mysqli $mysqli objeto de acceso a la BBDD
 * @return void echo del array de resultados en codificado en JSON
 */
function peticionPUT (array $parametros, array $errores, mysqli $mysqli): void{
    //se inserta un elemento
    if (isset($parametros["cod_categoria"])) {
        $id = intval($parametros["cod_categoria"]);


        //ahora tengo que hacer consult aselect
        //comprobar que existe la categoria
        $selectCategorias = "SELECT * 
                            FROM categorias
                             WHERE cod_categoria = '$id'";
        $consultaSelect = $mysqli->query($selectCategorias);

        if ($mysqli->errno != 0){ //se comprueba posible error al hacer consulta
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error en la base de datos",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }


        //Se comprueba si la consutla devuelve false
        if ($consultaSelect === false){
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error: no se ha devuelto consulta",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }

        //Se comprueba que nos haya devuelto una fila
        if ($consultaSelect->num_rows === 0){
            $errores["descripcion"][] = "Error la categoría a modificar no existe";
        }

        //Ahora comprobamos que la descripción, 
        // no se repita
        $descripcion = trim($parametros["descripcion"]);
        $descripcion = $mysqli->escape_string($descripcion); //evitamos inyección SQL

        if ($descripcion === ""){
            $errores["descripcion"][] = "Error debe introducir una descripción para la categoría";
        }

        if(!validaCadena($descripcion, 20, "")){
            $errores["descripcion"][] = "La descripción no debe tener más de 20 caracteres";
        }

        if (count($errores) === 0) { //Si no hay errores continuanos

            $selectDescripcion = "SELECT descripcion 
                                    FROM categorias
                                        WHERE descripcion = '$descripcion'";
            $consultaDescripcion = $mysqli->query($selectDescripcion);

            if ($mysqli->errno != 0) { //se comprueba posible error al hacer consulta
                header("HTTP/1.0 404 Problemas internos.");
                $resultado = [
                    "datos" => "Error en la base de datos",
                    "correcto" => false
                ];
                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;
            }


            //se comprueba si la consutla es falsa
            if ($consultaDescripcion === false) {
                header("HTTP/1.0 404 Problemas internos.");
                $resultado = [
                    "datos" => "Error: no se ha devuelto consulta",
                    "correcto" => false
                ];
                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;
            }

            //Si devuelve mas de una 1 fila entonces,
            //esta poniendo una descripcion existente
            //lanzamos error
            if ($consultaDescripcion->num_rows >= 1) {
                $errores["descripcion"][] = "La descripción ya existe para otra categoría";
            }

            if (count($errores) === 0) { //comprobamos que no haya errores

                //Si llegamos aquí, se puede hacer la sentencia UPDATE
                $update = "UPDATE `categorias` SET  `descripcion` = '$descripcion'
                            WHERE `cod_categoria` = '$id'";
                $updateCategoria = $mysqli->query($update);


                if ($mysqli->errno != 0) { //Se comprueba que puede realizar el UPDATE
                    header("HTTP/1.0 404 Problemas internos.");
                    $resultado = [
                        "datos" => "Error en la base de datos",
                        "correcto" => false
                    ];
                    $res = json_encode($resultado, JSON_PRETTY_PRINT);
                    echo $res;
                    exit;
                }


                //Se comprueba que la consulta no sea falsa
                if ($updateCategoria === false) {
                    header("HTTP/1.0 404 Problemas internos.");
                    $resultado = [
                        "datos" => "Error: no se ha podido realizar la sentencia",
                        "correcto" => false
                    ];
                    $res = json_encode($resultado, JSON_PRETTY_PRINT);
                    echo $res;
                    exit;
                }


                //Al llegar aquí, la sentencia se ha realizado
                //resultado devuelve id y true
                $resultado = [
                    "datos" => $id,
                    "correcto" => true
                ];

                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;
            }
            else{//devolvemos array con errores

                $resultado = [
                    "datos" => $errores,
                    "correcto" => false
                ];
                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;

            }
        }
        else{//devolvemos array con errores

            $resultado = [
                "datos" => $errores,
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;

        }

    }
}


/**
 *  PETICION DELETE en caso de hacerlo por POST oper = 2
 * o DELETE
 * 
 * le enviamso el array con los parametros, otro con los errores
 * y el objeto de acceso a la bbdd
 * 
 * devolvera un echo del array de los datos codificados en JSON
 *
 * @param array $parametros parametros para operar
 * @param array $errores array para mostrar posibles errores
 * @param mysqli $mysqli objeto de acceso a la bbdd
 * @return void echo del array codificado
 */
function peticionDELETE (array $parametros,array $errores , mysqli $mysqli): void{
    if (isset($parametros["cod_categoria"])){ //cogemos cod categoria

        $id = intval($parametros["cod_categoria"]); //cod_categoria a borrar

        //comprobamos que la categoría existe
        $selectCategoria = "SELECT * 
                                FROM categorias
                                    WHERE cod_categoria = '$id'";
        $consultaSelect = $mysqli->query($selectCategoria);
                     
        if ($mysqli->errno != 0){ //se comprueba posible error al hacer consulta
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error en la base de datos",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }


        //Se comprueba si la consutla devuelve false
        if ($consultaSelect === false) {
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error: no se ha devuelto consulta",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }

        //Se comprueba que nos haya devuelto una fila
        if ($consultaSelect->num_rows === 0) {
            //error, no existe id con tal categoria º
            $errores["idCategoria"][] = "Error, no existe la categoría consultada";    
        }
        

        //Se comprueba que ningun producto tenga la categoria
        $selectProctCategorias = "SELECT * FROM cons_productos
                                    WHERE cod_categoria = '$id'";
        $consultaProductos = $mysqli->query($selectProctCategorias);

        if ($mysqli->errno != 0){ //se comprueba posible error al hacer consulta
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error en la base de datos",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }
        
        //Se comprueba si la consutla devuelve false
        if ($consultaProductos === false){
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error: no se ha devuelto consulta",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }


        //se comprueba si se devuelven filas, entonces
        //hay productos con esa categoria, se lanza error
        if ($consultaProductos->num_rows >= 1){

            $errores["idCategoria"][] = "Error, no puede borrar categorías usadas actualmente por otros productos";    
        }

        //Ahora preguntamos por si hay errores
        //si no hay errores, realizamos la sentencia
        //delete a la tabla categorias
        if(count(($errores)) === 0){

            $deleteCategoria = "DELETE FROM categorias
                                where `cod_categoria` = '$id'";
            $sentenciaDelete = $mysqli->query($deleteCategoria);

            //comprobamos que no haya habido errores en la
            //sentencia delete
            if ($mysqli->errno != 0){ //se comprueba posible error al hacer la petición
                header("HTTP/1.0 404 Problemas internos.");
                $resultado = [
                    "datos" => "Error en la base de datos",
                    "correcto" => false
                ];
                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;
            }

            //se comprueba si la petición devuelve false
            if($sentenciaDelete === false){
                header("HTTP/1.0 404 Problemas internos.");
                $resultado = [
                    "datos" => "Error procesar petición de borrado de categoría",
                    "correcto" => false
                ];
                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;
            }


            if ($sentenciaDelete === true){ //la consulta se realiza bien
                //Si llegamos aquí, no ha habido errores
                //Devolvemos el array de respuesta
                $resultado = [
                    "datos" => $id,
                    "correcto" => true
                ];
                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;
            }


        }
        else{ //devuelvo errores en el array res
            $resultado = [
                "datos" => $errores,
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }

    }

}
?>