<?php
	 
class inicialControlador extends CControlador
{
	public function accionIndex()
	{

		//guardamos acción
		$_SESSION["anterior"] = ["inicial", "index"];

		//Menú izquierda
		$this->menuizq = [
			[
				"texto" => "Inicio", 
				"enlace" => ["inicial"]
			],
			
		];


		//Barra de ubicación
		$this->barraUbi = [
			[
			  "texto" => "inicio",
			  "url" => "/"
		  ]
	  	];

		//$this->accionDefecto="inical";

		$this->dibujaVista("index", [], "Inicio").PHP_EOL;
	}

	
}
