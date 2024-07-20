<?php


/**
 * le he tenido que poner partidas con S porque me cogia una clase
 * de otro sitio y no podia asigar valores
 */
class Partidas extends CActiveRecord {


    protected function fijarNombre(): string
    {
        return "partida";
    }



    protected function fijarAtributos(): array
    {
        return array ("cod_partida", 
                        "mesa",
                         "fecha",
                    "cod_baraja", 
                    "nombre_baraja",
                     "jugadores",
                      "crupier");
    }


    protected  function fijarRestricciones(): array
    {

       
        return array ( 


            //cod_partida
            array ("ATRI" => "cod_partida", "TIPO" => "REQUERIDO"),
            array ("ATRI" => "cod_partida", "TIPO" => "ENTERO", "MIN" =>20),
                    

            //mesa
            array ("ATRI" => "mesa" , "TIPO" => "REQUERIDO"),
            array ("ATRI" => "mesa", "TIPO" => "ENTERO", "MIN" => 1, "MAX" => 20, "DEFECTO" => 1),


            //fecha
            array ("ATRI" => "fecha", "TIPO" => "FECHA","DEFECTO" => $this->fechaManiana ()),
            array ("ATRI" => "fecha", "TIPO" => "FUNCION", "FUNCION" => "validaFecha"),
                    
            

            //cod_baraja
            array ("ATRI" => "cod_baraja", "TIPO" => "REQUERIDO"),
            array ("ATRI" >= "cod_baraja", "TIPO" => "ENTERO" , "DEFECTO" => $this->defectoCodBaraja()),
            array ("ATRI" => "cod_baraja", "TIPO" => "FUNCION", "FUNCION" => "validaCodBaraja"),


            //nombre_baraja
            array ("ATRI" => "nombre_baraja", "TIPO" => "CADENA" , "TAMANIO" => 30),


            //jugadores
            array ("ATRI" => "jugadores", "TIPO" => "ENTERO", "MIN" => $this->sacaMinJugadores (),
             "MAX" =>  $this->sacaMaxJugadores (), "DEFECTO" => $this->sacaMinJugadores ()),


            //crupier
            array ("ATRI" => "crupier", "TIPO" => "CADENA", "TAMANIO" => 30),
            array ("ATRI" => "crupier", "TIPO" => "FUNCION", "FUNCION" => "validaCrupier")

        );
    }


    /**
     * funcion que devuelve la fecha actual + 1 dia
     * es decir mañana
     *
     * @return void
     */
    public function fechaManiana (){
    
        $fechaManana = new DateTime();
        $fechaManana->add(new DateInterval("P1D")); //defecto fecha mañana


        return $fechaManana;
    
    }


    /**
     * Funcion que saca el maximo de jugadores de una baraja
     *
     * @return void
     */
    public function sacaMaxJugadores (){

        $arrayCodBarajas = Listas::listaTipoBarajas(true, null);

        $cod = $this->cod_baraja;

        if ($cod !== ""){
            return $arrayCodBarajas[$cod]["max_juga"];

        }



    }

    /**
     * funcion que saca el min de jugadores de una baraja
     *
     * @return void
     */
    public function sacaMinJugadores (){

        $arrayCodBarajas = Listas::listaTipoBarajas(true, null);

        $cod = $this->cod_baraja;

        if ($cod !== ""){
            return $arrayCodBarajas[$cod]["min_juga"];

        }



    }


    /**
     * funcion que comprueba que crupier empieza por Cru-
     *
     * @return void
     */
    public function validaCrupier (){



        $arrayCrupier = explode("-", $this->crupier);


        $cruLetra = mb_strtolower($arrayCrupier[0]);

        if ($cruLetra !== "cru"){
            $this->setError(
                "crupier", "Crupier debe comenzar por Cru-"
            );
        }

       
    }

    /**
     * funcion que saca la posicion
     * de la listas de baraja, saca la que esta en medio
     *
     * @return void
     */
    public function defectoCodBaraja (){

        $arrayCodBarajas = Listas::listaTipoBarajas(true, null);

        $codigosArray = array_keys($arrayCodBarajas);

        $longitud = count($codigosArray);

        $mitad = intval($longitud/2);


        $damePosicionDefecto = 0;
        for ($cont = 0; $cont <= $mitad; $cont++){

            if ($cont === $mitad){
                $damePosicionDefecto = $codigosArray[$cont];
            }
        }


        return $damePosicionDefecto;

    }


    /**
     * funcion que comprueba que el codigo de baraja sea valido
     *
     * @return void
     */
    public function validaCodBaraja (){

        $arrayCodBarajas = Listas::listaTipoBarajas(true, null);


        //primero comprobamos que existe
        if (array_key_exists($this->cod_baraja, $arrayCodBarajas)){ //asignamos nombre_baraja

            $this->nombre_baraja = $arrayCodBarajas[$this->cod_baraja]["nombre"];

        }
        else{
            $this->setError(
                "cod_baraja", "No existe el código de la baraja"
            );
        }

    }


    /**
     * funcion que valida si la fecha 
     * es posterior al de hoy
     *
     * @return void
     */
    public function validaFecha()
    {
        $fechaHoy = new DateTime();
        $fechaHoy = $fechaHoy->format("d/m/Y");


        
        if ($fechaHoy !== $this->fecha){


            $fecha = new DateTime();
            $fechaPartida = DateTime::createFromFormat("d/m/Y", $this->fecha);
            if ($fechaPartida < $fecha) {
                $this->setError(
                    "fecha", "La fecha debe ser posterior a  al de hoy"
                );
            }

        }

        
    }


    /**
     * funcion que fija descripciones con Parti-
     *
     * @return array
     */
    protected  function fijarDescripciones(): array
    {
        return array ("cod_partida" => "Parti-cod_partida",
                       "mesa" => "Parti-mesa",
                    "fecha" => "Parti-fecha",
                        "cod_baraja" => "Parti-cod_baraja",
                        "jugadores" => "Parti-jugadores",
                        "crupier" => "Parti-crupier" );
    }


    /**
     * funcion para inicializar los valores por defecto
     *
     * @return void
     */
    protected function afterCreate(): void
    {
        $arrayCodBarajas = Listas::listaTipoBarajas(true, null);

        $this->cod_partida = 0;
        $this->mesa = 1;
        $this->fecha = $this->fechaManiana()->format("d/m/Y");
        $this->cod_baraja = $this->defectoCodBaraja();
        $this->nombre_baraja = $arrayCodBarajas[$this->cod_baraja]["nombre"];
        $this->jugadores = $this->sacaMinJugadores ();
        $this->crupier = "Cru-Juan";

    }
}




?>