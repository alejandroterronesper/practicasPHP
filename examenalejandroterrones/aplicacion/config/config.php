<?php

	$config=array("CONTROLADOR"=> array("partida"), //miindice como acción por defecto
				  "RUTAS_INCLUDE"=>array("aplicacion/modelos", "aplicacion/clases", "aplicacion/mislibrerias"), //para mis clases
				  "URL_AMIGABLES"=>true,
				  "VARIABLES"=>array("autor"=>"Alejandro",
				  					"direccion"=>"C/ Mesones nº 18",
									"grupo"=>"2daw",
									"N_Partidas" => 0 //variable de aplicacion
								),
				  "BD"=>array("hay"=>false,
								"servidor"=>"localhost",
								"usuario"=>"root", //usuario
								"contra"=>"2daw", //contraseña
								"basedatos"=>"tienda"),
				
					"Acceso" => array ("controlAutomatico" => true),

					"SESION" => array ("controlAutomatico" => true),

					"ACL" => array ("controlAutomatico" => false)
				  );

