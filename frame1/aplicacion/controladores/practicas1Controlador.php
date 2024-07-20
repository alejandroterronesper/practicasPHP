<?php
	 
class practicas1Controlador extends CControlador{

    function __construct()
    {

          //Menú izquierdo actual
          $this->menuizq = [
              [
                "texto" => "Índice práctica 1", 
                "enlace" => ["practicas1"] 
              ],
              [
                "texto" => "Ejercicio 1", 
                "enlace" => ["practicas1", "ejercicio1"] 
              ],
              [
                "texto" => "Ejercicio 2", 
                "enlace" => ["practicas1", "ejercicio2"] 
              ],
              [
                "texto" => "Ejercicio 3", 
                "enlace" => ["practicas1", "ejercicio3"] 
              ],
              [
                "texto" => "Ejercicio 7", 
                "enlace" => ["practicas1", "ejercicio7"] 
              ]
              ,
              [
                "texto" => "Ejercicio 5", 
                "enlace" => ["practicas1", "ejer5"] 
              ]
          ];

          //Barra de ubicación
          $this->barraUbi = [
                [
                  "texto" => "inicio",
                  "url" => "/"
              ],
              [
                "texto" => "Índice práctica 1", 
                "url" => "practicas1" 
              ]
              
          ];
        
    }

    /**
     * Acción de miindice, es la acción por defecto al controlador actual
     * en esta nos apareceran las siguientes acciones
     *
     * 
     */
    public function accionIndex(){
      
      if (isset( $_SERVER["HTTP_REFERER"])){ 
        $anterior = $_SERVER["HTTP_REFERER"];
      }
      else{//Si no existe la primera vez que se carga,  le enviamos a accion por defecto
        $anterior = "practicas1/index";
      }

      

      $datos = [
        "ejer1" => "/practicas1/ejercicio1",
        "ejer2" => "/practicas1/ejercicio2",
        "ejer3" => "/practicas1/ejercicio3",
        "ejer5" => "/practicas1/ejer5",
        "ejer7" => "/practicas1/ejercicio7",
        "anterior" => $anterior, //enlace de página anterior
      ];

		  $this->dibujaVista("miindice", $datos, "índice - prácticas 1");

    }




    /**
     * Ejercicio 1
     *
     * 
     */
    public function accionEjercicio1(){

    //Barra de ubicación
    $this->barraUbi = [
      [
        "texto" => "inicio",
        "url" => "/"
      ],
      [
        "texto" => "Índice práctica 1",
        "url" => "index"
      ],
      [
        "texto" => "Ejercicio 1",
        "url" => "ejercicio1"
      ]
    ];

    //Menú izquierdo actual
    $this->menuizq = [
      [
        "texto" => "Índice práctica 1",
        "enlace" => ["practicas1"] //esto se refiere al controlador 
      ],
      [
        "texto" => "Ejercicio 2",
        "enlace" => ["practicas1", "ejercicio2"]
      ],
      [
        "texto" => "Ejercicio 3",
        "enlace" => ["practicas1", "ejercicio3"]
      ],
      [
        "texto" => "Ejercicio 7",
        "enlace" => ["practicas1", "ejercicio7"]
      ],
      [
        "texto" => "Ejercicio 5",
        "enlace" => ["practicas1", "ejer5"]
      ]
    ];



      $datos=[
        "anterior" => "index",
        "numeroRound" => 45.49,
        "numeroRound2" => 74.587,
        "numeroFloor" => 45.14,
        "numeroFloor2" => -9.99,
        "numeroFloor3" => 0.1,
        "numeroPow" => 8,
        "numeroPow2" => 41,
        "numeroPow3" => -5,
        "numeroRaiz" => 2,
        "numeroRaiz2" => 64,
        "numeroRaiz3" => 458,
        "numeroHexa" => 10,
        "numeroHexa2" => 741,
        "numeroHexa3" => 9,
        "numeroBase4a" => 12,
        "numeroBase4b" => 203,
        "numeroBase4c" => 113,
        "numeroBinario" => 11,
        "numeroOctal" => 1047,
        "numeroHexadecimal" => 10
    ];
    


      $this->dibujaVista("ejercicio1", $datos, "Práctica 1 - Ejercicio 1");
    }


    /**
     * Ejercicio 2
     *
     * 
     */
    public function accionEjercicio2(){


    //Barra de ubicación
    $this->barraUbi = [
      [
        "texto" => "inicio",
        "url" => "/"
      ],
      [
        "texto" => "Índice práctica 1",
        "url" => "index"
      ],
      [
        "texto" => "Ejercicio 2",
        "url" => "ejercicio2"
      ]
    ];

    //Menú izquierdo actual
    $this->menuizq = [
      [
        "texto" => "Índice práctica 1",
        "enlace" => ["practicas1"] //esto se refiere al controlador 
      ],
      [
        "texto" => "Ejercicio 1",
        "enlace" => ["practicas1", "ejercicio1"]
      ],
      [
        "texto" => "Ejercicio 3",
        "enlace" => ["practicas1", "ejercicio3"]
      ],
      [
        "texto" => "Ejercicio 7",
        "enlace" => ["practicas1", "ejercicio7"]
      ],
      [
        "texto" => "Ejercicio 5",
        "enlace" => ["practicas1", "ejer5"]
      ]
    ];


      $datos = [
        "anterior" => "index",
        "probabilidad" => 0,
        "dado1000" => array ()
      ];

      $this->dibujaVista("ejercicio2",  $datos, "Práctica 1 - Ejercicio 2");

    }


    /**
     * Ejercicio 3
     *
     */
    public function accionEjercicio3(){


    //Barra de ubicación
    $this->barraUbi = [
      [
        "texto" => "inicio",
        "url" => "/"
      ],
      [
        "texto" => "Índice práctica 1",
        "url" => "index"
      ],
      [
        "texto" => "Ejercicio 3",
        "url" => "ejercicio3"
      ]
    ];

    //Menú izquierdo actual
    $this->menuizq = [
      [
        "texto" => "Índice práctica 1",
        "enlace" => ["practicas1"] //esto se refiere al controlador 
      ],
      [
        "texto" => "Ejercicio 1",
        "enlace" => ["practicas1", "ejercicio1"]
      ],
      [
        "texto" => "Ejercicio 2",
        "enlace" => ["practicas1", "ejercicio2"]
      ],
      [
        "texto" => "Ejercicio 7",
        "enlace" => ["practicas1", "ejercicio7"]
      ],
      [
        "texto" => "Ejercicio 5",
        "enlace" => ["practicas1", "ejer5"]
      ]
    ];


      $datos = [
        "anterior" => "index",
        "vector" => [
          1 => "hola",
          16 => "que tal",
          54 => 77,
          "uno" => "cadena",
          "dos" => true,
          "tres" => 1.345,
          "ultima" => [1,34, "nueva"]
        ],
        "array1" => array (
          1 => "hola",
          16 => "que tal",
          56 => "PHP",
          34,
          "uno" => "cadena",
          "dos" => true,
          "tres" => 1.345,
          "ultima" => array (1,34,"nueva")
        ),
        "array2" => [
          1 => "hola",
          16 => "que tal",
          56 => "PHP",
          34,
          "uno" => "cadena",
          "dos" => true,
          "tres" => 1.345,
          "ultima" => array (1,34,"nueva")
        ]
      ];
    
    $this->dibujaVista("ejercicio3", $datos , "Práctica 1 - Ejercicio 3");


    }


    /**
     * Ejercicio 7
     *
     */
    public function accionEjercicio7(){

    //Barra de ubicación
    $this->barraUbi = [
      [
        "texto" => "inicio",
        "url" => "/"
      ],
      [
        "texto" => "Índice práctica 1",
        "url" => "index"
      ],
      [
        "texto" => "Ejercicio 7",
        "url" => "ejercicio7"
      ]
    ];

    //Menú izquierdo actual
    $this->menuizq = [
      [
        "texto" => "Índice práctica 1",
        "enlace" => ["practicas1"] //esto se refiere al controlador 
      ],
      [
        "texto" => "Ejercicio 1",
        "enlace" => ["practicas1", "ejercicio1"]
      ],
      [
        "texto" => "Ejercicio 2",
        "enlace" => ["practicas1", "ejercicio2"]
      ],
      [
        "texto" => "Ejercicio 3",
        "enlace" => ["practicas1", "ejercicio3"]
      ],
      [
        "texto" => "Ejercicio 5",
        "enlace" => ["practicas1", "ejer5"]
      ]
    ];



      $ahora2 = new DateTime();
      $datos = [
        "anterior" => "index",
        "ahora"=> new DateTime(),
        "fechaMarzoDateTime" => new DateTime("2012-03-29 12:45"),
        "dateTimeMenos12" => $ahora2->sub(new DateInterval("P12DT4H")),
        "ahoraDate" =>date("d/m/Y"),
        "fechaMarzoDate" => strtotime("2012-03-29 12:45"),
        "ahoraDate2" =>  date ("\D\i\a  d \d\\e F  \d\\e Y \, \d\i\a \d\\e \l\a \s\\e\m\a\\n\a l"),
        "ahoraDate3" => date("H\:i\:s\:u"),
        "dateMenos12" => strtotime ("-12 days -4 hours"),
        "cadenaFecha1" => "d/m/Y",
        "cadenaFecha2" => "\D\i\a  d \d\\e F  \d\\e Y \, \d\i\a \d\\e \l\a \s\\e\m\a\\n\a l",
        "cadenaFecha3" => "H\:i\:s\:u"
      ];

      $this->dibujaVista("ejercicio7", $datos , "Práctica 1 - Ejercicio 7");

    }


    /**
     * Acción ejercicio 5
     * En la acción se definirá el array con los 
     * valores y se llamará a la vista vistaejer5.
     *  En la vista vistaejer5 se recorrerán los 
     * elementos del array y, para cada elemento, se llamará a la vista vistaejer5-parte que será 
     * la encargada de mostrar la línea correspondiente a cada elemento.
     *
     */
    public function accionEjer5(){

    //Barra de ubicación
    $this->barraUbi = [
      [
        "texto" => "inicio",
        "url" => "/"
      ],
      [
        "texto" => "Índice práctica 1",
        "url" => "index"
      ],
      [
        "texto" => "Ejercicio 5",
        "url" => "ejer5"
      ]
    ];

    //Menú izquierdo actual
    $this->menuizq = [
      [
        "texto" => "Índice práctica 1",
        "enlace" => ["practicas1"] //esto se refiere al controlador 
      ],
      [
        "texto" => "Ejercicio 1",
        "enlace" => ["practicas1", "ejercicio1"]
      ],
      [
        "texto" => "Ejercicio 2",
        "enlace" => ["practicas1", "ejercicio2"]
      ],
      [
        "texto" => "Ejercicio 3",
        "enlace" => ["practicas1", "ejercicio3"]
      ],
      [
        "texto" => "Ejercicio 7",
        "enlace" => ["practicas1", "ejercicio7"]
      ]
    ];


      $vector=array();
      $vector[1]="esto es una cadena";
      $vector["posi1"]=25.67;
      $vector[]=false; //posicion 2
      $vector["ultima"]=array(2,5,96);
      $vector[56]=23;


      $datos = [
        "anterior" => "index",
        "vector" => $vector
      ];

      $this->dibujaVista("vistaejer5", $datos  , "Práctica 1 - Ejercicio 5");

    }

}


?>