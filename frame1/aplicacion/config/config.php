<?php

	$config=array("CONTROLADOR"=> array("inicial"), //miindice como acción por defecto
				  "RUTAS_INCLUDE"=>array("aplicacion/modelos", "aplicacion/clases"), //para mis clases
				  "URL_AMIGABLES"=>true,
				  "VARIABLES"=>array("autor"=>"Alejandro",
				  					"direccion"=>"C/ Mesones nº 18",
									"grupo"=>"2daw"
								),
				  "BD"=>array("hay"=>true,
								"servidor"=>"localhost",
								"usuario"=>"root", //usuario
								"contra"=>"2daw", //contraseña
								"basedatos"=>"tienda"),
				
					"Acceso" => array ("controlAutomatico" => true),

					"SESION" => array ("controlAutomatico" => true),

					"ACL" => array ("controlAutomatico" => true)
				  );

