<?php


/**
 * Clase del modelo productos
 * 
 * se usa la vista cons_productos
 * 
 * Atributos:
 * nombre: cadena, 30, obligatoria
 * cod_categoria: entero, obligatorio, debe tener una categoria valida
 * fabricante: cadena, 30, defecto ""
 * fecha_alta: date, defecto hoy
 * unidades: entero, defecto 0, unidades pueden ser negativas
 * precio_base: real, no puede ser negativo, defecto 0 
 * iva: real, posibles valores: 4,10,21. defecto 21
 * precio_iva: real, calculado precio_base * iva/100 
 * precio_venta: real, calculado como precio_base + precio_iva
 * foto: cadena, 40, defecto base.png 
 * borrado: bool, entero con valores 0 y 1
 * descripcion_categoria: descripcion categoria
 */
class Productos extends CActiveRecord {


    protected function fijarNombre(): string
    {
        return "producto";
    }


    protected function fijarAtributos(): array
    {
        return array (
            "cod_producto", "cod_categoria", "nombre", "fabricante",
            "fecha_alta", "unidades", "precio_base", "iva", "precio_iva",
            "precio_venta","foto", "borrado", "descripcion"
        );
    }


    protected function fijarTabla(): string
    {
        return "cons_productos";
    }


    protected function fijarId(): string
    {
        return "cod_producto";
    }


    protected function fijarDescripciones(): array
    {
        return array ("cod_categoria" => "Código de categoría", 
                "fecha_alta" => "Fecha de alta", 
                "precio_base" => "Precio base",
                "precio_iva" => "Precio iva",
                "precio_venta" => "Precio de venta",
                "descripcion" => "Descripcion categoria");
    }


    protected function fijarRestricciones(): array
    {
        return array(

            
            //nombre
            array ("ATRI" => "nombre", "TIPO" => "REQUERIDO"),
            array ("ATRI" => "nombre", "TIPO" => "CADENA", "TAMANIO" => 30),


            //cod_categoria
            array ("ATRI" => "cod_categoria", "TIPO" => "REQUERIDO"),
            array ("ATRI" => "cod_categoria", "TIPO" => "ENTERO"),
            array ("ATRI" => "cod_categoria" , "TIPO" => "RANGO",
            "RANGO" => array_keys(Categorias::dameCategorias(null))),


            //fabricante
            array ("ATRI" => "fabricante", "TIPO" => "CADENA",
            "TAMANIO"=> 30, "DEFECTO" => ""),

            //fecha_alta
            array ("ATRI" => "fecha_alta", "TIPO" => "FECHA", 
                "DEFECTO" => new DateTime()),

            //unidades
            array ("ATRI" => "unidades", "TIPO" => "ENTERO",
            "MIN" => -10000, "DEFECTO" => 0),

            //precio base
            array ("ATRI" => "precio_base", "TIPO" => "REAL",
            "MIN" => 0, "DEFECTO" => 0),

            //iva
            array ("ATRI" => "iva", "TIPO" => "REAL",
            "DEFECTO" => 21),
            array ("ATRI" => "iva", "TIPO" => "RANGO",
            "RANGO" => array(4, 10, 21)),


            //precio_iva
            array ("ATRI" => "precio_iva", "TIPO" => "REAL",
            "DEFECTO" =>$this->calculaPrecioIva()),


            //precio venta
            array ("ATRI" => "precio_venta", "TIPO" => "REAL",
            "DEFECTO" => $this->calculaPrecioVenta()),

            //FOTO
            array ("ATRI" => "foto", "TIPO" => "CADENA", 
            "TAMANIO" => 40, "DEFECTO" => "base.png"),

            //BORRADO
            array("ATRI" => "BORRADO", "TIPO" => "ENTERO",
             "DEFECTO" => 0),
            array ("ATRI" => "BORRADO", "TIPO" => "RANGO",
            "RANGO" => array(0,1)),

            //descripcion categoria
            array ("ATRI" => "descripcion", "TIPO" => "CADENA", 
            "MAXIMO" => 40)

        );
    }


    protected function afterCreate(): void
    {
        $this->cod_producto = 0;
        $this->nombre = "";
        $this->cod_categoria = 0;
        $this->fabricante ="";
        $this->fecha_alta = new DateTime ();
        $this->unidades = 0;
        $this->precio_base = 0;
        $this->iva = 21;
        $this->precio_iva = $this->calculaPrecioIva();
        $this->precio_venta = $this->calculaPrecioVenta();
        $this->foto = "base.png";
        $this->borrado = 0;
        $this->descripcion = Categorias::dameCategorias($this->cod_categoria);
    }


    protected function afterBuscar () :void{

        //Convertimos fecha
        $fecha = $this->fecha_alta;
        $fecha= CGeneral::fechaMysqlANormal($fecha);
        $this->fecha_alta = $fecha;


        //pasamos cadena a entero o real, en caso de ser números
        $this->cod_producto = intval($this->cod_producto);
        $this->cod_categoria = intval($this->cod_categoria);
        $this->unidades = intval($this->unidades);
        $this->precio_base = floatval($this->precio_base);
        $this->iva = floatval($this->iva);
        $this->precio_iva = floatval($this->precio_iva);
        $this->precio_venta = floatval($this->precio_venta);
        $this->borrado = intval($this->borrado);
        $this->foto = $this->foto;

    }



    /**
     * Método para calcular el precio de venta
     * a partir de la suma del precio base y del precio iva
     *
     * @return float precio_iva + precio_base
     */
    public function calculaPrecioVenta (): float {
        return floatval($this->precio_base) + floatval($this->precio_iva);
    }


    /**
     * Método para calcular el precio del iva
     * a partir del precio base y del iva
     *
     * @return Float precio iva  = precio_base * iva/100
     */
    public function calculaPrecioIva (): Float {

        $precio = (floatval($this->precio_base) * (floatval($this->iva) / 100));

        return $precio;
    }


    /**
     * Función que devuelve la cadena de la sentencia
     * INSERT que se utilizará para insertar un producto
     * en la tabla PRODUCTOS, se sanean los datos previamente
     * antes de devolver la sentencia insert
     *
     * @return string
     */
    protected function fijarSentenciaInsert(): string
    {
      
        $nombre = trim($this->nombre);
        $nombre = CGeneral::addSlashes($nombre);

        $fabricante = trim($this->fabricante);
        $fabricante = CGeneral::addSlashes($fabricante);

        $cod_categoria = intval($this->cod_categoria);

        $fecha_alta = CGeneral::fechaNormalAMysql($this->fecha_alta);

        $unidades = intval($this->unidades);
        $precio_base = floatval($this->precio_base);
        $iva = intval($this->iva); //porque coge valores enteros validados en rango
        $borrado = intval( $this->borrado);
        
        $foto = trim($this->foto);
        $foto = CGeneral::addSlashes($foto); 

        $sentencia = "INSERT INTO `productos` (`cod_categoria`, `nombre`, `fabricante`, 
                                            `fecha_alta`, `unidades`, `precio_base`,
                                             `iva`, `foto`, `borrado`)
                                            VALUES ('$cod_categoria','$nombre',
                                                    '$fabricante','$fecha_alta',
                                                    '$unidades','$precio_base',
                                                    '$iva','$foto',
                                                    '$borrado')"; 

        return $sentencia;
    }


    /**
     * Función que devuelve la sentencia UPDATE
     * 
     * para modificar un producto de la tabla productos
     *
     * Antes de mandar la sentencia, se sanean los datos
     * 
     * @return String sentencia del update
     */
    protected function fijarSentenciaUpdate(): string
    {
        $cod_producto = intval($this->cod_producto);
        $nombre = trim($this->nombre);
        $nombre = CGeneral::addSlashes($nombre);

        $fabricante = trim($this->fabricante);
        $fabricante = CGeneral::addSlashes($fabricante);

        $cod_categoria = intval($this->cod_categoria);

        $fecha_alta = CGeneral::fechaNormalAMysql($this->fecha_alta);

        $unidades = intval($this->unidades);
        $precio_base = floatval($this->precio_base);
        $iva = intval($this->iva); //porque coge valores enteros validados en rango
        $borrado = intval( $this->borrado);
        
        $foto = trim($this->foto);
        $foto = CGeneral::addSlashes($foto); 


        $sentencia =  "UPDATE `productos`  SET `nombre`= '$nombre', `fabricante`= '$fabricante',
                                        `cod_categoria` = '$cod_categoria', `fecha_alta`= '$fecha_alta', 
                                        `unidades`=  '$unidades', `precio_base`= '$precio_base',
                                        `iva`= '$iva', `borrado`='$borrado',`foto`= '$foto'
                        
                        WHERE `cod_producto` = '$cod_producto'";

        return $sentencia;  
    } 


}
?>