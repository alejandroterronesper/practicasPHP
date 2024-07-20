<?php


/**
 * Controlador de partida
 * sera la vista principal
 * 
 * contiene array de partidas
 * 
 * login, logout, descaragr XML y nueva partida
 */
class partidaControlador extends CControlador {

    public int $N_PartidasHoy = 0;

    public function __construct() {
        
        $this->_arrayPartidas = [];


        $partida1 = new Partidas();

        $partida1->cod_partida = 21;
        $partida1->mesa = 1;
        $fecha = new DateTime();
        $fecha = $fecha->format("d/m/Y");
        $partida1->fecha = $fecha;
        $partida1->cod_baraja = 5;
        $partida1->jugadores = 2;
        $partida1->crupier = "Cru-Alejandro";

        if ($partida1->validar() === true){
            $this->_arrayPartidas[$partida1->cod_partida] = $partida1;
        }


        $partida2 = new Partidas();

        $partida2->cod_partida = 25;
        $partida2->mesa = 3;
        $fecha = new DateTime();
        $fecha = $fecha->format("d/m/Y");
        $partida2->fecha = $fecha;
        $partida2->cod_baraja = 6;
        $partida2->jugadores = 4;
        $partida2->crupier = "Cru-Pepe";


        
        if ($partida2->validar() === true){
            $this->_arrayPartidas[$partida2->cod_partida] = $partida2;
        }


        
        $partida3 = new Partidas();

        $partida3->cod_partida = 27;
        $partida3->mesa = 7;
        $fecha = new DateTime("2024-05-01");
        $fecha = $fecha->format("d/m/Y");
        $partida3->fecha = $fecha;
        $partida3->cod_baraja = 7;
        $partida3->jugadores = 4;
        $partida3->crupier = "Cru-Alejandro";


        
        if ($partida3->validar() === true){
            $this->_arrayPartidas[$partida3->cod_partida] = $partida3;
        }


        if (isset($_SESSION["arrayPartidasCreadas"])){

            if (count($_SESSION["arrayPartidasCreadas"]) > 0){


                foreach ($_SESSION["arrayPartidasCreadas"] as $clave => $valor){
                    $this->_arrayPartidas[$valor->cod_partida] = $valor;

                }


            }
        }
       


        //numero de partidas
        $numero = count($this->_arrayPartidas);
        Sistema::app()->N_Partidas =  $numero;


        $fechaHoy = new DateTime();
        $fechaHoy = $fechaHoy->format("d/m/Y");
        //numero de partidas de hoy
        foreach($this->_arrayPartidas as $clave => $valor){

            $fecha = $valor->fecha;
           

            if ($valor->fecha === $fechaHoy){
                $this->N_PartidasHoy++;
            }

        }
        

        $this->accionDefecto="ver";


    }

    /**
     * Vista principal
     * 
     * descargar y añadir partida
     *
     * @return void
     */
    public function accionVer()
	{

        $arrayCrupiers = [];

        $datos = [
            "crupier" => -1
        ];
        foreach($this->_arrayPartidas as $clave => $valor){

            if (count($arrayCrupiers) !== 0){

              
                if (in_array($valor->crupier, $arrayCrupiers) === false){ //no puede haber crupier repetidos
                    array_push($arrayCrupiers, $valor->crupier);
                }
            }
            else{
                array_push($arrayCrupiers, $valor->crupier);
            }


        }
        $arrayCrupierPersona = [];
        if ($_POST){


            if (isset($_POST["formularioPartida"])){


                $crupier = -1;
                if (isset($_POST["crupier"])){


                    $crupier = intval($_POST["crupier"]);

                    if ($crupier !== -1){

                        $valorCrupier = $arrayCrupiers[$crupier];

                       


                        foreach($this->_arrayPartidas as $clave => $parametro){
                            $crupierNombre = $parametro->crupier;

                            if ( $crupierNombre === $valorCrupier){

                                $valorPrueba = $this->_arrayPartidas[$clave];
                                $arrayCrupierPersona[$clave] = $valorPrueba;
                            }
                        }
                    }
                }
                $datos["crupier"] = $crupier;

                $this->dibujaVista("ver", ["arrayCrupiers" => $arrayCrupiers, 
                "datos" => $datos, "arrayCrupierPersona" => $arrayCrupierPersona], "Examen");
                exit;
            }

            

        }
		$this->dibujaVista("ver", ["arrayCrupiers" => $arrayCrupiers, 
        "datos" => $datos, "arrayCrupierPersona" => $arrayCrupierPersona], "Examen");
	}


    /**
     * descarag datos de partida en XML
     *
     * @return void
     */
    public function accionDescargar (){


        if (isset($_SESSION["login"])){


            if ($_SESSION["login"]["validado"] === true){


                $arrayPermisos = $_SESSION["login"]["permidos"];

                if (in_array(6, $arrayPermisos) === true){ //descarga XML

                    if ($_GET){

                        $codigo = -1;
                        if (isset($_GET["id"])){


                            $codigo = intval($_GET["id"]);
                            $objeto = $this->_arrayPartidas[$codigo];

                            //enviamos objeto a vista
                            $this->dibujaVista("descargar", ["objeto" => $objeto], "descargar");
                            
                        }
                    }

                }
                else{
                    Sistema::app()->paginaError(404, "No tienes permiso 6");

                    }
            }
            else{
                Sistema::app()->paginaError(404, "No estas logueado");

            }
        }
        else{
            Sistema::app()->paginaError(404, "No estas logueado");

        }
    }

    /**
     * formulario para crear una nueva partida
     * 
     * y te actualiza el array de partidas
     *
     * @return void
     */
    public function accionNueva(){

        $partida = new Partidas();
        $nombre = $partida->getNombre();


        $arrayCodBarajas = Listas::listaTipoBarajas(true, null);

      

        $arrayNombresBarajas = [];
        $datos = ["max_juga" => -1];
        //numero máximo de jugadores
        $arrayMaximo = [];
        $errores = [];

        foreach($arrayCodBarajas as $clave => $valor){
            $arrayMaximo[] = $valor["max_juga"];
        }

        $valor = $arrayMaximo;
        $valorMaximo = max($arrayMaximo);


        $totalJugadores = [];

        for ($cont = 1; $cont <= $valorMaximo; $cont ++){

            $totalJugadores[] = $cont;
        }

        foreach ($arrayCodBarajas as $clave => $valor){
            $arrayNombresBarajas[$clave] = $valor["nombre"];
        }


        if ($_POST){

            if (isset($_POST["formularioCrear"])){

              

                if ($_POST[$nombre]){

                    //ASIGNAR COD_PARTIDA
                    $partida->cod_partida = 28;

                    $codPartidasUsadas = [];

                    
                    
                    $codPartidasUsadas = array_keys($this->_arrayPartidas);

                    //COD_PARTIDA PRIMERO DISPONIBLE AL FINAL
                    $codMax = max($codPartidasUsadas);
                    $codMax++;  //este es codigo partida que se asigna

                    $partida->setValores($_POST[$nombre]);
                    $partida->cod_partida = $codMax;


                    //SE COMPRUEBA MAX JUGADORES
                    $max_jugadores = 0;
                    if (isset($_POST["max_juga"])){
                        $max_jugadores = intval($_POST["max_juga"]);

                        if ($max_jugadores === -1){
                            $errores["max_juga"][] = " Debes elegir una opción de max jugadores";

                        }
                        else{
                            $valorNum =  $totalJugadores[$max_jugadores];


                            //comprobamos si coincide numero maximo de jugadores
                            $valorOficialMax = $arrayCodBarajas[intval($partida->cod_baraja)]["max_juga"];
    
                            if ($valorNum !== $valorOficialMax){
                                $errores["max_juga"][] = "Nº de jugadores maximos incorrecto";
    
                            }
                        }

                       
                    }
                    $datos["max_juga"] = $max_jugadores;

                    if ($max_jugadores === 0){
                        $errores["max_juga"][] = " Debes elegir una opción de max jugadores";
                    }


                    //SE COMPRUEBA CRUPIER
                    $longitudCadena = mb_strlen($partida->crupier);
                    if($longitudCadena < 10){
                        $errores["crupier"][] = "La cadena crupier debe tener al menos 10 caracteres";

                    }


                    if ($partida->validar() === true && (count($errores) === 0)){ //CORRECTO

                        //Si se valida añadimos a la sesion
                        if (isset($_SESSION["arrayPartidasCreadas"])){
                            array_push($_SESSION["arrayPartidasCreadas"], $partida);

                        }
                        else{ //si no existe se inicializa

                            $arrayPartida = [];
                            array_push($arrayPartida, $partida);
                            $_SESSION["arrayPartidasCreadas"] = $arrayPartida;
                        }

                        header("location:".Sistema::app()->generaURL(["partida"]));
                        exit;

                    }
                    else{ //ERRORES


                        $this->dibujaVista("nueva", [
                            "partida" => $partida, "arrayNombresBarajas" => $arrayNombresBarajas,
                            "valorMaximo" => $valorMaximo, "totalJugadores" =>  $totalJugadores, "datos" => $datos, "errores" => $errores
                        ], "Nueva partida");
                        exit;
                    }

                }
                


            }
        }


		$this->dibujaVista("nueva", ["partida" => $partida, "arrayNombresBarajas" => $arrayNombresBarajas,
         "valorMaximo" => $valorMaximo, "totalJugadores" =>  $totalJugadores, "datos" => $datos, "errores" => $errores ], "Nueva partida");
    }



    /**
     * se puede logear si tenemos 2 o mas partidas a dia de hoy
     *
     * @return void
     */
    public function accionLogin (){


        if (Sistema::app()->N_Partidas >= 1){

            if(isset($_SESSION["login"])){
                $_SESSION["login"] = [
                    "nombre" => "alejandro",
                    "validado" => true,
                    "permidos" => [2,4,6]
                ];
                header("location:".Sistema::app()->generaURL(["partida"]));
                exit;
            }
            else{
                $_SESSION["login"] = [
                    "nombre" => "alejandro",
                    "validado" => true,
                    "permidos" => [2,4,6]
                ];
                header("location:".Sistema::app()->generaURL(["partida"]));
                exit;
            }

        }
        else{
            Sistema::app()->paginaError(404, "No puedes loguearte no hay partidas para hoy");
        }
        
    }



    /**
     * quitamos login si hay 2 partidas o mas
     *
     * @return void
     */
    public function accionQuitarLogin(){

        $numPartidas = count($this->_arrayPartidas);

        if ($numPartidas>= 2){
            if (isset($_SESSION["login"])){

                $_SESSION["login"] = [
                    "nombre" => "alejandro",
                    "validado" => false,
                    "permidos" => [2,4,6]
                ];
                header("location:".Sistema::app()->generaURL(["partida"]));
                exit;
    
            }
            else{
                $_SESSION["login"] = [
                    "nombre" => "alejandro",
                    "validado" => false,
                    "permidos" => [2,4,6]
                ];
                header("location:".Sistema::app()->generaURL(["partida"]));
                exit;
            }
        }
        else{
            Sistema::app()->paginaError(404, "No puedes quitar el registro, hay 2 partidas o mas");
        }

       


    }
}










?>