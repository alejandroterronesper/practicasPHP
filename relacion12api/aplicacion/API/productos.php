<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

//API PRODUCTOS

//Construimos la sentencia de consulta
$sentenciaProductos = "SELECT * FROM cons_productos";
$selectWhere  =""; //en caso de las consultas por GET para poder filtrar
$errores = []; //array de los posibles errores que enviamos al cliente

//sacamos array de categorias para validaciones
$arrayCategorias = dameArrayCategorias($mysqli);

//se comprueba si hay posibles errores,
//si los hay lanzamos error
if ($arrayCategorias === false){ 
    header("Problemas internos");
    $resultado = [
        "datos" => "Problemas con la base de datos",
        "correcto" => false
    ];
    $res = json_encode($resultado, JSON_PRETTY_PRINT);
    echo $res;
    exit;
}


//PETICION GET(CONSULTA)
if ($_SERVER["REQUEST_METHOD"] === "GET"){

    //petición por GET-> es una consulta
    if (isset($_GET["cod_producto"])){
        //Se indica elemento en consulta
        $id = intval($_GET["cod_producto"]);

        $selectWhere = " WHERE cod_producto = '$id'";
    }

    //nombre
    if (isset($_GET["nombre"])  && ($_GET["nombre"] !== "")){
        $nombre = trim($_GET["nombre"]);
        $nombre = $mysqli->escape_string($nombre); //evitamos inyección de código
        $selectWhere = " WHERE nombre LIKE '%$nombre%'";
    }

    //categoria
    if (isset($_GET["cod_categoria"]) && ($_GET["cod_categoria"] !== "")){

        $categoria = intval($_GET["cod_categoria"]); 

        //Se verifica que sea una categoria de la tabla categorias
        if (validaRango($categoria, $arrayCategorias, 1)){

            if ($selectWhere !== ""){
                $selectWhere.= " AND cod_categoria = '$categoria'";
            }
            else{
                $selectWhere.= " WHERE cod_categoria = '$categoria'";
            }
        }
    }


    //borrado
    if(isset($_GET["borrado"]) && ($_GET["borrado"]) !== ""){

        $borrado = intval($_GET["borrado"]);


        //comprobamos que borrado sea 0 o 1
        if (validaEntero($borrado, 0, 1, 0) === true){
            if ($selectWhere !== ""){
                $selectWhere.= " AND borrado = '$borrado'";
            }
            else{
                $selectWhere = " WHERE borrado = '$borrado'";
            }
    
        }
    }


    //concatenamos consulta con WHERE
    $sentenciaProductos .= $selectWhere;

    $filas = [];
    $consulta = $mysqli->query($sentenciaProductos);

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

    //Comprobamos los posibles errores
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


    //Iteramos el array con cada fila de la consulta realizada
    while ($fila = $consulta->fetch_assoc()) {
        $fila["foto"] =   "http://www.relacion12api.es/imagenes/productos/" . $fila["foto"]; //ruta completa de la foto
        $fila["fecha_alta"] =   MYSQLaFecha($fila["fecha_alta"]); //formateamos fecha

        $filas[] = $fila;
    }


    $resultado = [
        "datos" => $filas,
        "correcto" => true
    ];

    $res = json_encode($resultado, JSON_PRETTY_PRINT);
    echo $res;
    exit;

}

//PETICION POST (INSERTAR)
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (isset($_POST["oper"])){ //parametro oper para hacer update o delete
        $oper = "";


        $oper = intval($_POST["oper"]);


        if ($oper === 1){ //PETICION PUT
            
            peticionPUT($_POST, $errores, $arrayCategorias, $mysqli);

        }
        
        if ($oper === 2){ //PETICION DELETE
            peticionDELETE($_POST, $errores, $mysqli);
        }



    }
    else{ //petición POST INSERCCION
        
    //nombre
    $nombre = "";
    if(isset($_POST["nombre"])){


        $nombre = trim($_POST["nombre"]);
        $nombre = $mysqli->escape_string($nombre); //evitamos inyección código

        if ($nombre === ""){
            $errores["nombre"][] = "El nombre no puede ir vacio";
        }

        if (!validaCadena($nombre, 20, "")) {
            $errores["nombre"][] = "El nombre del producto no debe superar los 20 caracteres";
        }

        //comprobamos que el nombre del producto sea único
        $select = "SELECT `nombre` FROM `cons_productos` WHERE nombre = '$nombre'";
        $selectProductos = $mysqli->query($select);

        //controlamos posibles errores
        if ($mysqli->errno != 0) {
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error en la base de datos",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }

        if($selectProductos === false){//en caso de que devuelva false
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error no se ha podido realizar la consulta",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }

        //Si hay más de 1 fila, es que existe un producto con el nombre ingresado
        if ($selectProductos->num_rows > 0) {
            $errores["nombre"][] = "Producto existente, ingrese un producto con otro nombre distinto";
        }
    }

    //fabricante
    $fabricante = "";
    if(isset($_POST["fabricante"])){

        $fabricante = trim($_POST["fabricante"]);
        $fabricante = $mysqli->escape_string($fabricante); //evitamos inyección de código


        if ($fabricante === ""){
            $errores["fabricante"][] = "Debe introducir un fabricante";
        }

        if (!validaCadena($fabricante, 20, "")){
            $errores["fabricante"][] = "El nombre del fabricante no debe superar los 20 caracteres";
        }
    }


    //cod_categoria
    $categoria = "";
    if (isset($_POST["categoria"])){
        $categoria = intval($_POST["categoria"]);

        if (!validaRango($categoria, $arrayCategorias, 1)){
            $errores["categoria"][] = "No existe la categoría seleccionada";
        }
    }

    // fecha_alta
    $fechaAlta = "";
    if (isset($_POST["fecha_alta"])){
        $fechaAlta = trim(($_POST["fecha_alta"]));
        $fechaAlta = $mysqli->escape_string($_POST["fecha_alta"]); //evitamos inyección SQL

        //comprobamos que no esté vacio 
        if ($fechaAlta === ""){
            $errores["fecha_alta"][] = "Debe introducir una fecha";
        }

        //validamos formato fecha
        if (!validaFecha($fechaAlta, "")){
            $fechaAlta = "";
            $errores["fecha_alta"][] = "Formato de fecha incorrecto, debe ser día/mes/año";
        }

        $fechaDate = DateTime::createFromFormat("d/m/Y", $fechaAlta);

        //Validamos que la fecha no sea menor de 28/2/2010 
        $fechaMenor = DateTime::createFromFormat("d/m/Y", "28/02/2010");
        if ($fechaDate < $fechaMenor){ //Si la fecha del form es anterior a 28-2-2010
            $errores["fecha_alta"][] = "La fecha no puede ser anterior a 28/02/2010";
            $fechaAlta = "";
        }


        //validamos que la fecha no sea mayor a la actual
        $fechaActual = new DateTime();
        if ($fechaDate > $fechaActual){ //Si la fecha es posterior a la actual
            $errores["fecha_alta"][] = "La fecha no puede ser posterior a la actual";
            $fechaAlta = "";
        }
    }

    // unidades
    $unidades = 0;
    if (isset($_POST["unidades"])){
        $unidades = intval($_POST["unidades"]);

        if ($unidades < 0){
            $errores["unidades"][] = "El nº de unidades debe ser mayor o igual a 0";
        }
    }


    // precio base
    $precioBase = 0;
    if (isset($_POST["precio_base"])){
        $precioBase = floatval($_POST["precio_base"]);

        if ($precioBase < 0){
            $errores["precio_base"][] = "El precio del producto debe ser mayor o igual a 0";
        }
    }


    // iva
    $iva = 21;
    if (isset($_POST["iva"])){
        $iva = floatval($_POST["iva"]);

        if ($iva < 0){
            $errores["iva"][] = "El IVA del producto debe ser mayor o igual a 0";
        }
    }


    //borrado
    $borrado = 0;
    if (isset($_POST["borrado"])) {
        $borrado = intval($_POST["borrado"]);

        if ($borrado !== 0) { //el borrado va a ser 0 por defecto
            $borrado = 0;
        }
    }


    // foto   
    $foto = "foto.png";  //valor por defecto
    if (isset($_POST["foto"])){
        $foto = trim($_POST["foto"]);
        $foto = $mysqli->escape_string($foto); //evitamos inyeccion de código

        if ($foto === ""){
            $errores["foto"][] = "No se ha introducido el nombre de la foto";
        }
    }

    //Si no hay errores,
    //se hace sentencia INSERT
    if(count($errores) === 0){

        //formateo fecha alta
        $fechaAlta = fechaAMYSQL($fechaAlta);

        //sentencia INSERT
        $insertSentencia = "INSERT INTO `productos`  (`cod_categoria`, `nombre`,
                                                    `fabricante`, `fecha_alta`,
                                                    `unidades`, `precio_base`,
                                                     `iva`, `foto`, `borrado`)
                            VALUES ('$categoria', '$nombre', '$fabricante', '$fechaAlta', 
                            '$unidades', '$precioBase', '$iva', '$foto', '$borrado')";


        //realizo sentencia insert
        $insertarProducto = $mysqli->query($insertSentencia);


        if ($mysqli->errno != 0){ //comprobamos si hubo errores durante la petición
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error al procesar la petición",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }


        if ($insertarProducto === false){ //comprobamos si la petición devuelve false
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error al insertar producto",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }


        //al procesar petición,
        //devolvemos id de producto insertado
        //y correcto a true
        $id = intval($mysqli->insert_id);
        $resultado = [
            "datos" => $id,
            "correcto" => true
        ];

        $res = json_encode($resultado, JSON_PRETTY_PRINT);
        echo $res;
        exit;
    }
    else{ //Si hay errores, no se realiza INSERT, devolvemos correcto => false y datos => array errores
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

//PETICIÓN PUT (MODIFICAR)
if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    //en esta petición validamos solamente los campos que se nos piden
    //nombre, fabricante, categoria, unidades y precio

    //petición por PUT-> es una modificación
    //recojo los parámetros
    $parametros = recogerParametros();
    peticionPUT($parametros, $errores, $arrayCategorias, $mysqli);


}



if ($_SERVER["REQUEST_METHOD"] == "DELETE"){
    //petición DELETE es borrado

    //Hacemos un borrado lógico
    //en la tabla de productos

    //recojo parámetros
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
function recogerParametros(): Array
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
 * Función que llamamos
 * y que nos devuelve un array
 * con las categorias existentes de la tabla categorias
 * con formato cod_categoria => descripcion
 * 
 * en caso de error a la petición a la bbdd devolvemos false
 *
 *
 * @param mysqli $mysqli -> objeto mysqli para establecer conexión a la bbdd
 * @return Array -> cod_categoria y descripcion o false si hay errores
 *          para hacer petición a la bbdd
 */
function dameArrayCategorias(mysqli $mysqli): Array| False{

    $selectCategorias = "SELECT * FROM categorias";
    $realizaSelect = $mysqli->query($selectCategorias);

    if ($mysqli->errno != 0) { //si hay errores
        return false;
    }

    if (!$realizaSelect){ //si la consulta devuelve false
        return false;
    }


    while ($fila = $realizaSelect->fetch_assoc()){
        $arrayCategorias[(int)$fila["cod_categoria"]] = $fila["descripcion"];
    }

    return $arrayCategorias; //se devuelve array con categorias

}

/**
 * Peticion PUT en caso de realizar la consulta
 * por POST con parámetro oper = 1 o una peticion por CURL de tipo PUT
 * se realizara una peticion put, con los datos pasados en el array de parámetros
 * y los errores se guardaran en el array de errores
 *
 * @param array $parametros parametros que enviamos para la peticion
 * @param array $errores array con los posibles errores
 * @param array $arrayCategorias array de las categorias para validar cod categorias
 * @param mysqli $mysqli objeto para acceder a la bbdd y realizar consultas o peticiones a las tablas
 * @return void se devolvera un echo con el array codificado de las respuestas
 */
function peticionPUT(array $parametros, array &$errores, array $arrayCategorias ,mysqli $mysqli): void{

    if (isset($parametros["cod_producto"])){
        $id = intval($parametros["cod_producto"]);

        //consulta para comprobar que el producto existe
        $selectProductos = "SELECT *
                            FROM cons_productos
                            WHERE cod_producto = '$id'";
        $consultaCodProducto = $mysqli->query($selectProductos);

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
        if ($consultaCodProducto === false) {
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error: no se ha devuelto consulta",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }

        
        //Comprobamos que la consulta nos haya devuelto una fila
        //en caso contrario, no existe el producto, lanzamos error
        //y no seguimos validando datos
        if ($consultaCodProducto->num_rows !== 1){
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error: No existe producto con id enviado",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;        
        }
    }

    //nombre 
    $nombre = "";
    if (isset($parametros["nombre"])){


        $nombre = trim($parametros["nombre"]);
        $nombre = $mysqli->escape_string($nombre); //se evita inyeccion código


        if ($nombre === ""){
            $errores["nombre"][] = "El nombre no puede ir vacio";
        }

        if (!validaCadena($nombre, 20, "")) {
            $errores["nombre"][] = "El nombre del producto no debe superar los 20 caracteres";
        }

        //comprobamos que el nombre del producto sea único
        //y que el id sea distinto
        //asi se comprueba que si dejamos el mismo nombre,
        // no salte excepción
        //y podamos ver si coge el nombre de otros productos
        //y no del que estamos modificando
        $select = "SELECT `nombre` FROM `cons_productos` WHERE nombre = '$nombre' AND cod_producto <> '$id'";
        $selectProductos = $mysqli->query($select);

        //controlamos posibles errores
        if ($mysqli->errno != 0) {
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error en la base de datos",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }

        if($selectProductos === false){//en caso de que devuelva false
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error no se ha podido realizar la consulta",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }

        //Si hay más de 1 fila, es que existe un producto con el nombre ingresado
        //devolvemos error
        if ($selectProductos->num_rows > 0) {
            $errores["nombre"][] = "Producto existente, ingrese un producto con otro nombre distinto";
        }
    }

    //fabricante
    $fabricante = "";
    if (isset($parametros["fabricante"])) {

        $fabricante = trim($parametros["fabricante"]);
        $fabricante = $mysqli->escape_string($fabricante); //evitamos inyección de código


        if ($fabricante === "") {
            $errores["fabricante"][] = "Debe introducir un fabricante";
        }

        if (!validaCadena($fabricante, 20, "")) {
            $errores["fabricante"][] = "El nombre del fabricante no debe superar los 20 caracteres";
        }
    }

    //cod_categoria
    $categoria = "";
    if (isset($parametros["cod_categoria"])) {
        $categoria = intval($parametros["cod_categoria"]);

        if (!validaRango($categoria, $arrayCategorias, 1)) {
            $errores["cod_categoria"][] = "No existe la categoría seleccionada";
        }
    }


    // unidades
    $unidades = 0;
    if (isset($parametros["unidades"])) {
        $unidades = intval($parametros["unidades"]);

        if ($unidades < 0) {
            $errores["unidades"][] = "El nº de unidades debe ser mayor o igual a 0";
        }
    }


    // precio base
    $precioBase = 0;
    if (isset($parametros["precio_base"])) {
        $precioBase = floatval($parametros["precio_base"]);

        if ($precioBase < 0) {
            $errores["precio_base"][] = "El precio del producto debe ser mayor o igual a 0";
        }
    }


    
    //Se comprueban los errores
    //Si no hay errores, se hace sentencia UPDATE
    if(count($errores) === 0){

        //sentencia UPDATE
        $updateProducto = "UPDATE `productos`  
                            SET `nombre`='$nombre', `fabricante`= '$fabricante',
                            `cod_categoria` = '$categoria', `unidades`=  '$unidades',
                             `precio_base`= '$precioBase'
                            WHERE `cod_producto` = '$id'";
        
        $sentenciaUPDATE = $mysqli->query($updateProducto);


        if ($mysqli->errno != 0){ //comprobamos si hubo errores durante la petición
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error al procesar la petición",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }        

        if ($sentenciaUPDATE === false){ //comprobamos si la petición devuelve false
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error al modificar producto",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }


        //-----
        //Si llegamos hasta aquí es que se ha procesado bien la petición
        //Al procesar sentencia UPDATE
        //si no hay errores, se devuelve
        //datos => id del producto
        //correcto a true
        $resultado = [
            "datos" => $id,
            "correcto" => true
        ];
        $res = json_encode($resultado, JSON_PRETTY_PRINT);
        echo $res;
        exit;


    }
    else{ //si hay errores devolvemos correcto = false y datos => errores
        $resultado = [
            "datos" => $errores,
            "correcto" => false
        ];

        $res = json_encode($resultado, JSON_PRETTY_PRINT);
        echo $res;
        exit;
    }


}




/**
 * Petición DELETE en caso de realizar una consulta por POST
 * con parametro oper = 2 o una petición por CURL de tipo DELETE
 * 
 * se realiza petición delete, con los datos del array parametros
 * y si hay errores los guardo en array de errores
 *
 * @param array $parametros parametros que enviamos para la peticion
 * @param array $errores array con los posibles errores
 * @param mysqli $mysqli objeto para acceder a la bbdd y realizar consultas o peticiones a las tablas
 * @return void e devolvera un echo con el array codificado de las respuestas
 */
function peticionDELETE(array $parametros, array &$errores, mysqli $mysqli): void {
     //parametro cod producto
     if (isset($parametros["cod_producto"])){

        $id = intval($parametros["cod_producto"]);


        //comprobamos que el producto existe
        $selectProducto = "SELECT * 
                            FROM cons_productos
                                WHERE cod_producto = '$id'";

        $consultaProducto = $mysqli->query($selectProducto);

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
        if ($consultaProducto === false) {
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error: no se ha devuelto consulta",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }


        //Se comprueba que la consulta devuelva fila
        //a partir del id de cod producto
        if ($consultaProducto->num_rows === 0) {
            //error, no existe id con tal categoria º
            $errores["deleteProducto"][] = "Error, no existe el producto a consultar";
        }

        
    }


    //borrado
    $borrado = "";
    if (isset($parametros["borrado"])){

        $borrado = intval($parametros["borrado"]);

        if (!validaEntero($borrado, 0,1,0)){
            $errores["deleteProducto"][] = "Error, debe elegir una opción del formulario";
        }
    }

    //Si se ha recodigo el cod producto y no hay errores
    //hacemos sentencia update a la tabla de producto
    //a partir del id
    if (count($errores) === 0){

        //Se hace un borrado lógico del producto
        //Se cambia de 0 a 1 o viceversa el campo 
        //de borrado

        $deleteProducto = "UPDATE `productos`
                            SET `borrado` = '$borrado'
                            WHERE `cod_producto` = $id";
        $ejecutaDELETE = $mysqli->query($deleteProducto);


        if ($mysqli->errno != 0){ //comprobamos si hubo errores durante la petición
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error al procesar la petición",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        } 
        
        if ($ejecutaDELETE === false){ //comprobamos si la petición devuelve false
            header("HTTP/1.0 404 Problemas internos.");
            $resultado = [
                "datos" => "Error al borrar producto",
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }

        //-----
        //Si llegamos hasta aquí es que se ha procesado bien la petición
        //Al procesar sentencia UPDATE
        //si no hay errores, se devuelve
        //datos => id del producto
        //correcto a true
        $resultado = [
            "datos" => $id,
            "correcto" => true
        ];
        $res = json_encode($resultado, JSON_PRETTY_PRINT);
        echo $res;
        exit;
    }
    else{//si hay errores devolvemos correcto = false y datos => errores
        $resultado = [
            "datos" => $errores,
            "correcto" => false
        ];

        $res = json_encode($resultado, JSON_PRETTY_PRINT);
        echo $res;
        exit;
    }

}
?>


