<?php
	 
class practicas2Controlador extends CControlador{

    function __construct()
    {

        //barra ubicacion
        $this->barraUbi = [
            [
                "texto" => "inicio",
                "url" => "/"
            ],
            [
                "texto" => "Índice práctica 2",
                "url" => "index"
            ]
        ];

        //Menú izquierda
        $this->menuizq = [
            [
                "texto" => "Inicio",
                "enlace" => ["practicas1"]
            ],
            [
                "texto" => "índice práctica 2",
                "enlace" => ["practicas2"]
            ],
            [
                "texto" => "Página de error",
                "enlace" => ["practicas2", "error"]
            ],
            [
                "texto" => "Descarga 1 ",
                "enlace" => ["practicas2", "descarga1"]
            ],
            [
                "texto" => "Descarga 2 ",
                "enlace" => ["practicas2", "descarga2"]
            ],
            [
                "texto" => "Petición AJAX",
                "enlace" => ["practicas2", "pedirDatos"]
            ]
        ];
        
    }

    /**
     * Acción de miindice, es la acción por defecto al controlador actual
     * en esta nos apareceran las siguientes acciones
     * 
     * @return void
     */
    public function accionIndex()
    {

        if (isset( $_SERVER["HTTP_REFERER"])){ 
            $anterior = $_SERVER["HTTP_REFERER"];
          }
          else{//Si no existe la primera vez que se carga,  le enviamos a accion por defecto
            $anterior = "index";
          }



        $datos = [
            "error" => "/practicas2/error",
            "descargar1" => "/practicas2/descarga1",
            "descargar2" => "/practicas2/descarga2",
            "petAJAX" => "/practicas2/pedirdatos",
            "anterior" => $anterior, //enlace de página anterior
        ];
    


        $this->dibujaVista("index", $datos, "índice - prácticas 2");
    }


    /**
     * Crear la acción mierror que genere una 
     * página de error con mensaje “no seas malo y no 
     * accedas a esta pagina”.
     *
     * @return void
     */
    public function accionError(){

        $this->barraUbi = [
            [
                "texto" => "inicio",
                "url" => "/"
            ],
            [
                "texto" => "Índice práctica 2",
                "url" => "index"
            ],
            [
                "texto" => "ERROR",
                "url" => "error"
            ]
        ];

        $datos = [
            "anterior" => "index",
            "mensaje" => "no seas malo y no
            accedas a esta pagina"
        ];

        $this->dibujaVista("mierror", $datos, "Práctica 2 - Mi error");

    }



    /**
     * Descarga1 utilizará una vista generar la descarga
     *
     * @return void
     */
    public function accionDescarga1(){

        $datos = [
            "mensaje1" => "descarga1, esta es mi descarga 1",
            "mensaje2" => "desde la descarga 1 puedo descargar lo que yo quiera"
        ];
        $this->dibujaVistaParcial("descarga1", $datos ,true);
    }



    /**
     * Genera una descarga directamente en la acción del controlador
     *
     * @return void
     */
    public function accionDescarga2(){

        header("content-type: text/txt");
        header("content-disposition: attachment; filename = descarga2.txt");
        
        echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++".PHP_EOL;
        echo  "\nEsta es mi descarga2".PHP_EOL;
        
        echo "\nLa descarga se está realizando directamente desde el controlador de la acción".PHP_EOL;
        
        echo "\n++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++".PHP_EOL;
        
        exit();
    }


    /**
     * Acción que simula el suministro de datos por una petición AJAX
     *
     * @return void
     */
    public function accionpedirDatos(){

        //barra ubicacion
        $this->barraUbi = [
            [
                "texto" => "inicio",
                "url" => "/"
            ],
            [
                "texto" => "Índice práctica 2",
                "url" => "index"
            ],
            [
                "texto" => "Petición AJAX",
                "url" => "pedirDatos"
            ]
        ];
        
        $datos = [
            "anterior" => "index",
            "max" => 0,
            "min" => 0,
            "patron" => ""
        ];

        $this->dibujaVista("peticionAJAX", $datos, "Práctica 2 - Peticiones AJAX");

    }


    /**
     * Acción que recibe los datos por javascript
     * a través de la petición AJAX
     * 
     * devolvera un json que tendra un array de numeros
     * que contendra los numeros entre el minimo y el máximo
     * que se ha pasado
     * 
     * y un array de 10 palabras
     * que tiene la misma longitud del patron
     * y empieza y acaba igual que este pero el resto
     * de caracteres es aleatorio
     * 
     * En caso de posibles errores, por parte de los numeros
     * o del patrón, se mostraran en un div los posibles errores
     *
     * @return void
     */
    public function acciondatosAJAX(){
        $numeros = [];
        $palabras = [];
        
        $errores = []; //para validar posibles errores
       
        $minimo = "";

        if (isset($_POST["minimo"])){
            
            $minimo = intval($_POST["minimo"]);

            if ($minimo === ""){  //Si tras pasarlo a entero nos llega una cadena "", mandamos error
                $errores["numeros"][] = ["El nº mínimo está vacío, introduce un número"];

            }
            
        }

        $maximo = "";
        if (isset($_POST["maximo"])){
            $maximo = intval($_POST["maximo"]);

            if ($maximo === ""){ //Si tras pasarlo a entero nos llega una cadena "", mandamos error
                $errores["numeros"][] = ["El nº máximo está vacío, introduce un número"];

            }
        }


        if ($maximo < $minimo){ //Si el maximo es menor que el mínimo, lanzamos error
            $errores["numeros"][] = ["El nº máximo es menor que el nº mínimo"];

        }

        if ($maximo === $minimo){ //Si ambos números son iguales, lanzamos error
            $errores["numeros"][] = ["Los números no pueden ser iguales"];

        }

        $patron = "";
        if (isset($_POST["patron"])){
            $patron = trim($_POST["patron"]);

            if ($patron === ""){ //Si en patrón nos llega una cadena vacía.
                $errores["patron"][] = ["Debe introducir un valor en el patrón"];

            }
        }

        if (count($errores) !== 0){ //Se comprueba si hay errores
            $resultado = [
                "errores" => $errores
            ];
            echo json_encode( $resultado); //en caso de haber errores, los devolvemos y los iteramos en el js

        }
        else{ //Si no hay errores, devolvemos la respuesta el json con array de palabras y numeros 
            
            //rellenamos el array con números
            for($cont = $minimo; $cont <= $maximo; $cont ++ ){
                array_push($numeros, $cont);
            }

            $primeraLetra = $patron[0];
            $ultimaLetra = $patron[ mb_strlen($patron) - 1];
            $numLetrasRellena = mb_strlen($patron) - 2;
            $palabra = "";
            for($cont = 0; $cont <= 10; $cont ++){ //10 palabras
                $palabra = $primeraLetra;
                for ($cont2 = 0; $cont2 <= $numLetrasRellena; $cont2++){ //rellenamos la palabra con caracteres aleatorios
                    if ($cont2 === $numLetrasRellena){ //si es la posición final, ponemos la letra final
                        $palabra.= $ultimaLetra;
                    }
                    else{ //rellenamos de palabras aleatorias
                        $caracterAleatorio = mb_chr(rand(0, 255)); //vamos a sacar de manera aleatoria todos los caraceres ASCII
                        $palabra.=$caracterAleatorio;
                    }
                }

                array_push($palabras, $palabra);
                $palabra = ""; //vaciamos la variable para la proxima palabra
            }


            //Array con los arrays de numeros y palabras, lo convertimos a JSON
            $resultado = [
                "numeros" => $numeros,
                "palabras" => $palabras
            ];
        
            echo json_encode($resultado);  
        }
    }
}


?>