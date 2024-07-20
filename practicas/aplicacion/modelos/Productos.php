<?php

class Productos extends CActiveRecord
{
    protected function fijarNombre(): string
    {
        return 'pro';
    }

    protected function fijarTabla():string
    {
        return "cons_productos";
    }
    protected function fijarId():string
    {
        return "cod_producto";
    }

    protected function fijarAtributos(): array
    {
        return array(
            "cod_producto", "cod_categoria",
            "nombre", "fabricante","descripcion",
            "unidades", "fecha_alta","precio_base",
            "iva","precio_iva","precio_venta","foto",
            "borrado"
        );
    }
    protected function fijarDescripciones(): array
    {
        return array(
            "fecha_alta" => "Fecha de alta ",
            "descripcion" => "Descripcion categoria "
        );
    }
    protected function fijarRestricciones(): array
    {
        return
            array(
                array(
                    "ATRI" => "cod_producto,nombre",
                    "TIPO" => "REQUERIDO"
                ),
                array(
                    "ATRI" => "cod_producto", "TIPO" => "ENTERO",
                    "MIN" => 0
                ),
                array(
                    "ATRI" => "nombre", "TIPO" => "CADENA",
                    "TAMANIO" => 250,
                    "MENSAJE" => "el nombre es mas corto de lo que has escrito"
                ),
                array("ATRI" => "precio_base", "TIPO"=> "REAL",
                        "MIN" => 0),
                array("ATRI" => "unidades", "TIPO" => "ENTERO"
                )
            );
    }



    protected function afterCreate(): void
    {
        $this->cod_producto=0;
        $this->cod_categoria=0;
        $this->nombre="";
        $this->fabricante="";
        $this->descripcion="";
        $this->unidades=0;
        $this->fecha_alta="01/01/2024";
        $this->precio_base=0;
        $this->iva=21;
        $this->precio_iva=0;
        $this->precio_venta=0;
        $this->foto="";
        $this->borrado=false;
        
    }


    protected function afterBuscar(): void
    {

       // $this->precio_base = floatval($this->precio_base);
        if ($this->fabricante === "sony"){
            $this->fabricante = $this->fabricante ." MUSIC";
        }


        $this->fecha_alta = CGeneral::fechaMysqlANormal($this->fecha_alta);
    } 



    protected function fijarSentenciaInsert(): string
    {
        return "";
    }


    protected function fijarSentenciaUpdate(): string
    {
        //Sistema::app()->BD()->getEnlace()->escape_string("escapa esta cadena");
        $fabricante = CGeneral::addSlashes($this->fabricante); //escape_string
        $precio_base = floatval($this->precio_base);

        $sentencia = "update productos set 
                        fabricante = '$fabricante',
                        precio_base ='$precio_base'
                        WHERE cod_producto = {$this->cod_producto}";

        return $sentencia;
    }
}

