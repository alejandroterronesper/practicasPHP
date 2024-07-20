<?php
	 
class practicas1Controlador extends CControlador{

    function __construct()
    {

        //Menú izquierdo actual
        $this->menuizq = [
          [
            "texto" => "Índice práctica 1", 
            "enlace" => ["practicas1"] //esto se refiere al controlador 
          ],
          [
            "texto" => "Ejercicio 1", 
            "enlace" => ["practicas1", "ejercicio1"] //esto se refiere al controlador 
          ]
        ];

        $this->barraUbi = [
          [
            "texto" => "inicio",
            "url" => "/"
        ],
        ];
        
    }



    public function accionIndex(){
      $mult = 3 *2;
		$this->dibujaVista("miindice", ["datos"=>$mult, "antonio" => "la variable de antonio"], "índice - prácticas 1");

    }


    // public function ejercicio1(){
    //   $mult = 3 *2;
    //   $this->dibujaVista("")
    // }
}


?>