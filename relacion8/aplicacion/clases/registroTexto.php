<?php

/**
 * Clase registro texto
 * 
 * almacena una cadena y la fecha / hora de la creacion de isntancia
 * propiedades privadas
 * metodos get, no sets porque no queremos modificar
 */
class registroTexto{

    //Variables privadas de instancia
    private string $_cadena = "";
    private DateTime $_fechaHora;


    /**
     * Constructor de la clase registro texto
     * se le pasa el parámetro cadena
     * 
     * Cada vez que se declare una instancia de esta clase
     * se utilizará la hora actual del sistema para rellenar el campo
     * 
     *
     * @param String $cadena cadena que le pasamos como parámetro
     */
    public function __construct(string $cadena)
    {

        $this->_cadena = $cadena;
        $this->_fechaHora = new DateTime(); //fecha y hora actual

    }


    //------------------------------------------------------------//
    //-------------------Método mágito toString()-----------------//
    //------------------------------------------------------------//

    /**
     * Método mágico to String
     * se llamara a este método cuando
     * convirtamos en cadena una instancia de la clase
     * registro Texto
     *
     * @return string devuele una cadena con el texto y la fecha de creacion de la instancia
     */
    public function __toString():string
    {
        return "Texto: " . $this->_cadena . " fecha/hora: " . $this->_fechaHora->format("d/m/Y -- H:i:s");
    }


    //-----------------------------------------------------------//
    //--------------------Métodos Gets---------------------------//
    //-----------------------------------------------------------//

    /**
     * Método get cadena
     * cuando llamamos a este método
     * nos devuelve el valor de la instancia
     * privada cadena
     *
     * @return String devuelve el valor de cadena
     */
    public function getcadena():string{
        return $this->_cadena;
    }


    /**
     * Método get fecha hora
     * devuelve un objeto de tipo datetime
     * con la fecha y la hora actual de la creacion
     * de la instancia registro texro
     *
     * @return DateTime devuelve la fecha/hora de la creación de la instancia
     */
    public function getfechahora():DateTime{
        return $this->_fechaHora;
    }






    //------------------------------------------------------------------------//
    //-------------------No se pueden crear propiedades dinámicas-------------//
    //------------------------------------------------------------------------//

     /**
     * Método mágico set, lo usamos para quitar 
     * la carga dinamica de propiedades
     *
     * @param String $propiedad propiedad que queremos modificar
     * @param Mixed $valor el valor nuevo que va a tomar la propiedad
     * @return Void no devuelve nada, pero si no la encuentra lanza excepción
     */
    public function __set(string $propiedad, mixed $valor):void
    {
        throw new Exception('No se puede modificar la propiedad ' . $propiedad);
    }


    /**
     * Método mágico get, 
     * se usa para consultar datos de propiedades no accesibles. 
     *
     * @param String $propiedad propiedad a la que queremos acceder
     * @return Mixed devuelve el valor de propiedad o se lanza excepcion si no se encuentra
     */
    public function __get(String $propiedad): mixed
    {
        throw new Exception('No se puede obtener el valor de ' . $propiedad);
    }


    /**
     * 
     * Método mágico isset
     * 
     * Comprueba que una propiedad existe o no
     * 
     * @param String $propiedad propiedad que queremos que compruebe que exista
     * @return Bool  false para evitar que se añadan propiedades dinámicas
     */
    public function __isset(string $propiedad): bool
    {
        return false;
    }


    /**
     * Método mágico unset
     * Se invoca cuando se usa unset() sobre propiedades inaccesibles
     *
     * @param String $propiedad es la variable sobrecargada
     * @return void no devuelve nada, solo excepción si no la encuentra
     */
    public function __unset(String $propiedad): void
    {
        throw new Exception("No existe la propiedad ".$propiedad);
    }



}