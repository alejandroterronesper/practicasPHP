<?php
	 
class pruebaControlador extends CControlador
{
	function __construct()
	{
		

		$this->menuizq = [ //esto crea el menu izquierdo de esa pagina
			[
				"texto" => "indice", 
				"enlace" => ["prueba"] //esto se refiere al controlador 
			],
			[
				"texto" => "primera", 
				"enlace" => ["prueba","primera"] //controlador - accion (accion -> funcio)
			]
			,
			[
				"texto" => "vista parcial", 
				"enlace" => ["prueba","parcial"]
			]
		];

		
	}
	public function accionIndex()
	{
		$numero=rand(1,10);
		if ($numero<=5)
				$this->dibujaVista("indice",["n"=>$numero],"pruebas");
			   else
			    $this->dibujaVista("alternativo",["n"=>$numero],"pruebas");
			   
		

		
	}

	public function accionParcial(){
		$datos = [
			[
				"texto" => "indice", 
				"enlace" => "prueba" //esto se refiere al controlador 
			],
			[
				"texto" => "primera", 
				"enlace" => "primera" //controlador - accion (accion -> funcio)
			]
			,
			[
				"texto" => "vista parcial", 
				"enlace" => "parcial"
			]
			];


		//VISTA PARCIAL -> DESCARGAR ALGO, PETICION CURL, AQUELLO QUE NO TENGO QUE GENERAR SALIDA
		// $this->dibujaVistaParcial("parci", ["datos"=>$datos], false);
		$this->dibujaVista("parci", ["datos"=>$datos], "Prueba de vista parcial");



	}

	public function accionPrimera()
	{

		$texto="este texto se mostrará";

		$this->dibujaVista("pepito",["uno"=>$texto],
							"Pagina primerasssss");
	}

}
