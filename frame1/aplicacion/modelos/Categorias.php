<?php


/**
 * Clase del modelo categorias
 * 
 * tiene los atributos: 
 *  - cod_categorias: entero, obligatorio
 *  -descricpion: cadena, 40, obligatoria
 * 
 * metodo de clase dameCategorias (), mismo funcionamiento de dameEstados
 */
class Categorias extends CActiveRecord{


    protected function fijarNombre(): string
    {
        return "categoria";
    }


    protected function fijarTabla(): string
    {
        return "categorias";
    }

    
    protected function fijarId(): string
    {
        return "cod_categoria";
    }

    protected function fijarAtributos (): array 
    {
        return array ("cod_categoria", "descripcion");
    }

    protected function fijarDescripciones(): array
    {
        return array ("cod_categoria" => "Código de la categoría",
                        "descripcion" => "Descripción");
    }

    protected function fijarRestricciones(): array
    {
        return array (
            

            //cod_categoria
            array ("ATRI" => "cod_categoria", 
                "TIPO" => "REQUERIDO", 
                "MENSAJE" => "El código de la categoría no puede ir vacío"
            ),

            array ("ATRI" => "cod_categoria", 
                "TIPO" => "ENTERO"),


            //descripcion
            array ("ATRI" => "descripcion", 
            "TIPO" => "REQUERIDO", 
            "MENSAJE" => "Debe elegir una descripción para la categoría"),

            array ("ATRI" => "descripcion",
            "TIPO" => "CADENA" , 
            "TAMANIO" => 40),
        );
    }



    protected function afterCreate(): void
    {
        $this->cod_categoria = 0;
        $this->descripcion = "";
        
    }


    /**
     * Método estático de la clase categorías
     * tiene un funcionamiento similar a dameEstados
     * 
     * Recibe como parámetro un entero, cod_categoria,
     * 
     * Se comprueba si este es nulo, en caso de serlo, devuelve un array con todas
     * las categorías
     * 
     * Si es entero, se comprueba que existe la posición en el array y devuelve la cadena
     * de la descripción, si no existe devuelve false
     *
     * @param Integer|Null $cod_categoria -> codigo de la categoria entero o nulo
     * @return Array si el cod_categoria es null|
     *              String si existe cod categorias|
     *              False si no existe el cod_categoria
     */
    public static function dameCategorias (?int $cod_categoria = null): array | string | false {

    
        $objCategorias = new Categorias ();

        $arrayCategorias =  [];

        foreach($objCategorias->buscarTodos() as $clave => $valor){//Iteramos el resultado 

            //Guardamos los resultado en el array formato cod_categoria => descripcion
            $arrayCategorias[intval($valor["cod_categoria"])] = $valor["descripcion"]; 

        }

        if ($cod_categoria === null){
            return $arrayCategorias;
        }else{

            if (isset($arrayCategorias[$cod_categoria])){
                return $arrayCategorias[$cod_categoria];
            }
            else{
                return false;
            }
        }

    }
}


?>