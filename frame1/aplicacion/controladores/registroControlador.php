<?php

class registroControlador extends CControlador {


    function __construct()
    {

            //MENU IZQUIERD
            $this->menuizq = [
                [
                    "texto" => "Índice práctica 1", 
                    "enlace" => ["practicas1"] 
                ],
                [
                    "texto" => "Registro",
                    "enlace" => ["registro", "pedirDatosRegistro"]
                ],
                [
                    "texto" => "Login",
                    "enlace" => ["registro", "login"]
                ]
            ];


            $this->barraUbi = [
                [
                    "texto" => "inicio",
                    "url" => "/"
                ],
                
            ];


    }



    /**
     * Acción registro
     *
     * @return void
     */
    public function accionpedirDatosRegistro (){


        $this->barraUbi = [
            [
                "texto" => "inicio",
                "url" => "/"
            ],
            [
                "texto" => "Registro",
                "url" => ["registro", "pedirDatosRegistro"]
            ]
        ];

        $datosRegistro = new DatosRegistro ();

        $nombre = $datosRegistro->getNombre();
      
        if (isset($_POST[$nombre])){


            //asigno los valores del registro
            $datosRegistro->setValores($_POST[$nombre]);
            if ($datosRegistro->validar()){

                header("content-type: text/txt");
                header("content-disposition: attachment; filename = datosRegistro.txt");

                echo "********************************************************";
                echo "\nDatos de registro: ";
                echo "\n-Nick: ". $datosRegistro->nick;
                echo "\n-NIF: " . $datosRegistro->nif;
                echo "\n-Fecha de nacimiento: ".$datosRegistro->fecha_nacimiento;
                echo "\n-Provincia: " .$datosRegistro->provincia;
                echo "\n-Estado: ". DatosRegistro::dameEstados($datosRegistro->estado);
                echo "\n-Contraseña: ". $datosRegistro->contrasenia;
                exit;


            }
            // else{
            //     $this->dibujaVista("pedirDatos", ["registro" => $datosRegistro], "Pedir datos");

            // }
        }

        $this->dibujaVista("pedirDatos", ["registro" => $datosRegistro], "Pedir datos");
    }


    /**
     * Acción para el formulario de login
     *
     * @return void
     */
    public function accionLogin (){

        $this->barraUbi = [
            [
                "texto" => "inicio",
                "url" => "/"
            ],
            [
                "texto" => "Login",
                "url" => ["registro", "login"]
            ]
        ];


        //Comprobamos si hay usuario logeado, en tal caso
        //lo redirigimos a la acción anterior

		$nickUserActual = Sistema::app()->Acceso()->getNick();
		$codUserActual = Sistema::app()->ACL()->getCodUsuario($nickUserActual);
		$borradoActual = Sistema::app()->ACL()->getBorrado($codUserActual); 

	

        //Si hay usuario y no está borrado, hay login, lo mandamos a la acción anterior
		if (Sistema::app()->Acceso()->hayUsuario()=== true && (!$borradoActual)){

			Sistema::app()->irAPagina(["inicial"]);
            exit();
		}


        $datosLogin = new Login ();

        $nombre = $datosLogin->getNombre();

        if (isset($_POST[$nombre])){
            //asigno los valores del registro
            $datosLogin->setValores($_POST[$nombre]);
            
            if($datosLogin->validar()){ //si da true el validar, guardamos el nick, cod nick y los permisos


                $codUser = Sistema::app()->ACL()->getCodUsuario($datosLogin->nick);
                $nombreUser = Sistema::app()->ACL()->getNombre($codUser); //Nombre de usuario
                $arrayPermisos = Sistema::app()->ACL()->getPermisos($codUser); //Lista de permisos


                if (Sistema::app()->ACL()->getBorrado($codUser) === true){ //Si da true, es borrado, no accede
                    Sistema::app()->paginaError("404", "El usuario está borrado, no puede acceder");
                    exit;
                }
                else{ //Si no está borrado

                    if (Sistema::app()->Acceso() !== null) {

                        $registro = Sistema::app()->Acceso()->registrarUsuario($datosLogin->nick, $nombreUser, $arrayPermisos);


                        if ($registro === true){ //Si da true, le mandamos a la acción anterior
                           
                            
                            if (isset($_SESSION["anterior"])){
                                Sistema::app()->irAPagina($_SESSION["anterior"]);
                                exit;
                            }
                            else{
                                Sistema::app()->irAPagina(["inicial"]);
                                exit;
                            }

                        }
                        else{
                            Sistema::app()->paginaError("404", "No se ha podido registrar el usuario");
                            exit;
                        }

                    }
                }


            }
            else{
                $this->dibujaVista("login", ["logeo" => $datosLogin], "Formulario de login");
                exit;
            }
            
        }

        $this->dibujaVista("login", ["logeo" => $datosLogin], "Formulario de login");


    }
}









?>