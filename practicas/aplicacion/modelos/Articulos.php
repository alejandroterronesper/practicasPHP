<?php


class Articulos extends CActiveRecord
{
    protected function fijarNombre():String
    {
        return 'arti';
    }

    protected function fijarAtributos():Array
    {
        return array(
            "cod_articulo", "descripcion",
            "nombre", "cod_fabricante",
            "nombre_fabricante", "fecha_alta", 
            "existencias"
        );
    }

    protected function fijarDescripciones():Array
    {
        return array(
            "fecha_alta" => "Fecha de alta",
            "cod_fabricante" => "Código fabricante",
            "nombre_fabricante" => "Fabricante",
            "existencias" => "Unidades en almacen"
        );
    }

    protected function fijarRestricciones():Array
    {
        return
            array(
                array(
                    "ATRI" => "existencias",
                    "TIPO" => "ENTERO",
                    "MENSAJE" => "debes indicar un numero para las existencias",
                    "DEFECTO" => 0
                ),
                array (
                    "ATRI" => "existencias",
                    "TIPO" => "FUNCION",
                    "FUNCION" => "verficiarExistencias"
                ),
                array("ATRI" => "existencias",
                    "TIPO" => "FUNCION"),
                array(
                    "ATRI" => "cod_articulo,nombre",
                    "TIPO" => "REQUERIDO"
                ),
                array(
                    "ATRI" => "cod_articulo",
                    "TIPO" => "ENTERO",
                    "MIN" => 0
                ),
                array(
                    "ATRI" => "nombre", "TIPO" => "CADENA",
                    "TAMANIO" => 30, 
                    "MENSAJE" => "El nombre es mas corto de lo que has escrito"
                ),
                array(
                    "ATRI" => "descripcion",
                    "TIPO" => "CADENA", "TAMANIO" => 60
                ),
                array(
                    "ATRI" => "cod_fabricante", "TIPO" => "ENTERO",
                    "MIN" => 0
                ),

                array(
                    "ATRI" => "cod_fabricante", "TIPO" => "RANGO",
                    "RANGO"=> array_keys(Articulos::listaFabricantes()),
                    "MENSAJE" => "El fabricante no es uno de la lista"
                ),
                array("ATRI" => "fecha_alta", "TIPO" => "FECHA",
                        "DEFECTO"=>"01/01/2024"),
                array(
                    "ATRI" => "fecha_alta",
                    "TIPO" => "FUNCION",
                    "FUNCION" => "validaFechaAlta"
                ),
                array(
                    "ATRI" => "cod_fabricante",
                    "TIPO" => "FUNCION",
                    "FUNCION" => "rellenarNombre"
                ),

            );
    }

    protected function afterCreate(): void
    {
        $this->cod_articulo = 0;
        $this->nombre = "";
        $this->descripcion = "Articulo";
        $this->cod_fabricante = 1;
        $this->nombre_fabricante = "SIN INDICAR";
    }

    public function validaFechaAlta() 
    {
        $fecha1 = DateTime::createFromFormat(
            'd/m/Y',
            $this->fecha_alta
        );
        $fecha2 = DateTime::createFromFormat(
            'd/m/Y',
            '01/01/2000'
        );
        if ($fecha1 < $fecha2) {
            $this->setError(
                "fecha_alta",
                "La fecha de alta debe ser posterior a 01/01/2000"
            );
        }
    }


    public function rellenarNombre (){

        if (Articulos::listaFabricantes($this->cod_fabricante)){
            $this->nombre_fabricante = Articulos::listaFabricantes($this->cod_fabricante);
        }
        else{ //Si da false, cogemos fabricante por defecto
            $this->nombre_fabricante = "Sin indicar";
        }
    }


    /**
     * Se comprueba el nº de existencias
     *
     * @return void
     */
    public function verficiarExistencias (){

        if (($this->existencias % 2) !== 0){
            $this->setError("existencias", "El número de existencias no es par");
        }
    }

    public static function listaFabricantes(?int $fabricante = null)
    {
        $fabricantes = array(
            1 => "Siemens",
            2 => "Intel",
            3 => "Apple"
        );

        if ($fabricante === null)
            return $fabricantes;
        else {
            if (isset($fabricantes[$fabricante])){
                return $fabricantes[$fabricante];
            }
            else{
                return false;
            }

        }
    }
}
