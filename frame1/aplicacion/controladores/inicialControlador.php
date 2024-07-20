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
			[
				"texto" => "Práctica 1", 
				"enlace" => ["practicas1"]
			]
			,
			[
				"texto" => "Práctica 2", 
				"enlace" => ["practicas2"]
			],
			[
				"texto" => "Registro",
				"enlace" => ["registro", "pedirDatosRegistro"]
			],
			[
				"texto" => "Login",
				"enlace" => ["registro", "login"]
			],
			[
				"texto" => "Index de productos",
				"enlace" => ["productos"]
			]
		];


		//Barra de ubicación
		$this->barraUbi = [
			[
			  "texto" => "inicio",
			  "url" => "/"
		  ]
	  	];


		$producto = new Productos ();

		$productos = $producto->buscarTodos();


	  	/**
		 * Index principal
		 */
		$this->dibujaVista("index",["productos" => $productos],
							"Pagina principal");
	}

	
}
