<?php
	 
class inicialControlador extends CControlador
{
	public function accionIndex()
	{
		

		$this->menuizq = [
			[
				"texto" => "Inicio", 
				"enlace" => ["inicial"]
			]
		];

		

		$this->dibujaVista("index",[],
							"Pagina principal");
	}

	
}
