<?php

    /**
     *  clase ACLarray hereda de la clase ACLBase, 
     * gestiona una ACL usando los arrays almacenados
     */
    class ACLArray extends ACLBase
    {
        /**
         * $_roles contiene los roles definidos en la ACL
         * Cada role sera un array asociativo de 
         * cod => [ "cod"=> ,
         *            "nombre" =>  ,
         *            "permisos" => [1=>false, 2=> XX, .........10=> XXX]
         *          ]
         * 
         */
        private array $_roles=[];

        /**
         * $_usuarios contiene los usuarios definidos en la ACL
         * Cada usuario sera un array asociativo de
         * 
         * cod => [
         *          "cod" => ,
         *          "nick" =>  ,
         *          "nombre" => ,
         *          "contrasenia" =>  , usamos una función de cifrado.
         *          "cod_role" => ,
         *          "borrado" =>        
         * ]
         */
        private array $_usuarios=[];
        
        
        /**
         * constructor de la clase ACLArray    
         * al crearse la instancia se tiene dos coles
         * el normal (permiso 1)
         * y el administrador (permiso1 y 2)
         * 
         * se tienen tres users
         * alumno -> normal 
         * profesor -> admin
         * yo mismo -> admin
         */
        function __construct()
        {
            //añade los roles
            $this->anadirRole("normales",[1=>true]);
            $this->anadirRole("administradores",[1=>true,2=>true]);
            
            //añade los usuario

            //user alumno
            $this->anadirUsuario("Es usuario alumno","alumno","alum",
                                      $this->getCodRole("normales"));

            //user profesor
            $this->anadirUsuario("Es usuario profesor","profesor","profe",
                                      $this->getCodRole("administradores"));

            //user yo
            $this->anadirUsuario("Alejandro Terrones","alejandro","daw2023",
                                      $this->getCodRole("administradores"));

        }

        /**
         * Añade un role a nuesta ACL
         * 
         * @param string $nombre Nombre del role a añadir 
         * @param array $permisos Permisos que tendrá el role. Array con hasta 10 permisos
         * @return bool True si se ha podido crear, false en caso contrario
         */
        function anadirRole(string $nombre, array $permisos=array()):bool
        {
            //pongo el nombre en minuscula
            $nombre=mb_strtolower($nombre);
            
            //inicializo un arracon todos los permisos a false
            $per=[1=>false,false,false,false,false,
                    false,false,false,false,false];

            //compruebo si ya existe el role
            if ($this->getCodRole($nombre)!==false)
                 return false;
            
            //reviso los permisos
            foreach ($per as $clave=>$val)
            {
                if (isset($permisos[$clave]))
                    {
                        $per[$clave]=boolval($permisos[$clave]);
                    }
            }

            //busco cual es la ultima clave
            $cont=0;
            foreach($this->_roles as $clave=>$role)
               {
                   if ($role["cod"]>$cont)
                        $cont=$role["cod"];
               }
            
            $cont++;
            //creo el role
            $this->_roles[$cont]=["cod"=>$cont,
                                    "nombre"=>$nombre,
                                    "permisos"=>$per];
            
            return true;
        }

        /**
         * Función que localiza un role y devuelve el código de ese role o false si no
         * lo encuentra
         *
         * @param string $nombre nombre del role
         * @return integer|false Devuelve el código de role para el nombre indicado o false si no encuentra el role
         */
        function getCodRole(string $nombre):int|false
        {
            //pongo el nombre en minuscula
            $nombre=mb_strtolower($nombre);
            foreach($this->_roles as $clave=>$role)
               {
                   if ($role["nombre"]==$nombre)
                        return $role["cod"];
               }
            
            return false;
        }

        /**
         * Función que comprueba la existencia de un role
         *
         * @param integer $codRole role a buscar
         * @return boolean Devuelve true si lo encuentra o false en caso contrario 
         */
        function existeRole(int $codRole):bool
        {
            return array_key_exists($codRole, $this->_roles);
        }

        /**
         * Función que devuelve los permisos de un role dado
         *
         * @param integer $codRole Role a buscar
         * @return array|false Devuelve los permisos o false si no encuentra el role
         */
        function getPermisosRole(int $codRole):array|false
        {
            //compruebo que existe el codigo de role
            if (!$this->existeRole($codRole))
                return false;
            
            return ($this->_roles[$codRole]["permisos"]);     
        }

        /**
         * Función que devuelve si un role tiene o no un permiso concreto
         *
         * @param integer $codRole Role a buscar
         * @param integer $numero Número de permiso
         * @return boolean True si encuentra el role y lo tiene. False en cualquier otro caso
         */
        function getPermisoRole(int $codRole, int $numero): bool
        {
            //llamamos a la funcion getPermisosRole para obtener el array de permisos
            $arrayPermisos = $this->getPermisosRole($codRole);

            if (!$arrayPermisos){
                return false;
            }
            else{
                return array_key_exists($numero, $arrayPermisos);
            }
        }

        /**
         * Función que añade un nuevo usuario a nuestra ACL
         *
         * @param string $nombre Nombre del usuario
         * @param string $nick Nick unico para el usuario
         * @param string $contrasena contraseña del usuario
         * @param integer $codRole Role a asignarle
         * @return boolean Devuelve true si puede crearlo. False en caso contrario
         */
       function anadirUsuario(string $nombre, string $nick, string $contrasena, int $codRole):bool
        {
            //pongo el nick as minuscula
            $nick=mb_strtolower($nick);  
            
            //el nick debe ser único compruebo que no 
            //exista ya
            if ($this->existeUsuario($nick))
                 return false;

            //compruebo que exista el role
            if (!$this->existeRole($codRole))
                 return false;

            //la contraseña la guardo encriptada
			//establecer el método
            $contrasena= password_hash($contrasena, PASSWORD_BCRYPT);
            

            //busco cual es el ultimo codigo
            $cont=0;
            foreach($this->_usuarios as $clave=>$usu)
               {
                   if ($usu["cod"]>$cont)
                        $cont=$usu["cod"];
               }
            
            $cont++;
            
            //añado el usuario
            $this->_usuarios[$cont]=[
                         "cod" => $cont,
                          "nick" =>  $nick,
                         "nombre" => $nombre,
                          "contrasenia" =>  $contrasena,
                          "cod_role" => $codRole,
                          "borrado" => false       
            ];
            
            return true;
        }

        
        /**
         * Obtiene el código de usuario para un nick dado
         *
         * @param string $nick nick del usuario a buscar
         * @return integer|false Devuelve el codigo del usuario o false si no lo encuentra
         */
        function getCodUsuario(string $nick):int|false
        {
            //pongo el nick n minuscula
            $nick=mb_strtolower($nick);  
            
            foreach ($this->_usuarios as $clave=>$usu)
            {
                if ($usu["nick"]==$nick)
                {   //se encuentra el usuario
                    return $clave;
                }

            }

            //no se encuentra
            return false;

        }

        /**
         * Verifica si existe un usuario dado un código
         *
         * @param integer $codUsuario Código del usuario a verificar
         * @return boolean Devuelve si existe o no el usuario
         */
        function existeCodUsuario(int $codUsuario):bool
        {
            return array_key_exists($codUsuario,  $this->_usuarios);
        }

        /**
         * Verifica si existe o no un usuario con el nick dado
         *
         * @param string $nick Nick del usuario a comprobar
         * @return boolean Devuelve true si encuentra el usuario y 
         * false en caso contrario
         */
        function existeUsuario(string $nick):bool
        {   
            $encuentra = false;

            //recorrer array de usuarios
            foreach ($this->_usuarios as $clave => $valor){
                if ($this->_usuarios[$clave]["nick"] === $nick){
                    $encuentra = true;
                }                
            }

            return $encuentra;
        }

        /**
         * Función que comprueba que existe un usuario y la contraseña indicada es la correcta
         *
         * @param string $nick Nick del usuario a comprobar
         * @param string $contrasena Contraseña del usuario a comprobar
         * @return boolean Devuelve true si existe el usuario y tiene la contraseña indicada. 
         * False en otro caso
         */
        function esValido(string $nick, string $contrasena):bool
        {
            //pongo el nick n minuscula
            $nick=mb_strtolower($nick);  
            
            //compruebo si existe el nick
            if (!$this->existeUsuario($nick))
                return false;

            //recojo cual es el codigo
            $codigo=$this->getCodUsuario($nick);
            
			//llamamos a la funcion password_verify 
            //que permite validar una cadena con
            //una contraseña encriptada

            if (!password_verify($contrasena, $this->_usuarios[$codigo]["contrasenia"]))
                 return false;
                
            // es valido
            return true; 
        }

         /**
         * Función que comprueba si un usuario tiene un permiso concreto
         *
         * @param integer $codUsuario Usuario a buscar
         * @param integer $numero Permiso a buscar
         * @return boolean Devuelve true si existe el usuario y tiene el permiso. 
         * False en otro caso
         */
        function getPermiso(int $codUsuario, int $numero):bool
        {
            //accedemos la pos asociativa de cod role para comparar con el role 


            $permisosArray = $this->getPermisos($codUsuario);

            if ($permisosArray === false){
                return false;
            }
            else{
                if (isset($permisosArray[$numero])){
                        return $permisosArray[$numero];
                }
                else{
                    return false;
                }
                
            }
        }

        /**
         * Función que devuelve los permisos de un usuario
         *
         * @param integer $codUsuario Usuario a buscar
         * @return array|false Devuelve los permisos del usuario o false si 
         * no existe el usuario
         */
        function getPermisos(int $codUsuario):array|false
        {
            //posicion cod del array permisos
            $codPermiso = $this->_usuarios[$codUsuario]["cod_role"];

            //llamamos a la funcion que devuelve 
            //el array de permisos a partir del codigo
            return $this->getPermisosRole($codPermiso);

        }

        /**
         * Función que devuelve el nombre de un usuario
         *
         * @param integer $codUsuario Usuario a buscar
         * @return string|false Devuelve el nombre del usuario o false si no existe
         */
        function getNombre(int $codUsuario):string|false
        {
            if (!$this->existeCodUsuario($codUsuario))
                 return false;
    
            return $this->_usuarios[$codUsuario]["nombre"];

        }

        /**
         * Devuelve si un usuario está borrado
         *
         * @param integer $codUsuario Usuario a buscar.
         * @return boolean true si el usuario existe y no está borrado.
         * False en otro caso
         */
        function getBorrado(int $codUsuario):bool
        {   
            if ($this->existeCodUsuario($codUsuario)){
                return  $this->_usuarios[$codUsuario]["borrado"];
            }
            else{
                return false;
            }

        }

        /**
         * Devuelve el role que tiene un usuario concreto
         *
         * @param integer $codUsuario Usuario a buscar
         * @return integer|false Devuelve el role del usuario o false si no existe.
         */
        function getUsuarioRole(int $codUsuario):int|false
        {
            if (!$this->existeCodUsuario($codUsuario))
                 return false;
    
            return $this->_usuarios[$codUsuario]["cod_role"];

        }

        /**
         * Función que asigna un nombre a un usuario
         *
         * @param integer $codUsuario Usuario a buscar
         * @param string $nombre Nombre a asignar
         * @return boolean Devuelve true si ha podido asignar el nombre, false en otro caso
         */
        function setNombre(int $codUsuario,string $nombre):bool
        {
            if (!$this->existeCodUsuario($codUsuario))
                 return false;
    
            $this->_usuarios[$codUsuario]["nombre"]=$nombre;

            return true;
        }

        /**
         * Función que asigna una contraseña a un usuario
         *
         * @param integer $codUsuario usuario a buscar
         * @param string $contrasenia contraseña a asignar
         * @return boolean Devuelve true si ha podido asignar la contraseña
         * False en otro caso
         */
        function setContrasenia(int $codUsuario, string $contrasenia):bool
        {
            //comprobamos que existe el usuario
            if ($this->existeCodUsuario($codUsuario)){

                //contraseña nueva
                $contrasenia = password_hash($contrasenia, PASSWORD_BCRYPT);

                if ($contrasenia === false){//en caso de no cambiarse
                    return $contrasenia;
                }
                else{
                    //accdemos al user con codigo
                    // y asignamos contraseña a traves de pos asociativa
                    $this->_usuarios[$codUsuario]["contrasenia"] = $contrasenia;
                }

            }
            else{//si no existe no podemos cambiarlo
                return false;
            }

        }

        /**
         * Función que borra/desborra lógicamente un usuario 
         *
         * @param integer $codUsuario Usuario a buscar
         * @param boolean $borrado Estado a asignar
         * @return boolean Devuelve true si ha podido asignar el estado. 
         * False en otro caso
         */
        function setBorrado(int $codUsuario, bool $borrado):bool
        {
            if (!$this->existeCodUsuario($codUsuario))
                 return false;
    
                 
            $this->_usuarios[$codUsuario]["borrado"]=$borrado;

            return true;

        }

        /**
         * Función que cambia el role de un usuario
         *
         * @param integer $codUsuario Usuario a buscar
         * @param integer $role Role a asignar
         * @return boolean Devuelve true si ha podido asignar el role al usuario.
         * False si no existe el usuario, role o no ha podido asignarlo
         */
        function setUsuarioRole(int $codUsuario, int $role):bool
        {
            //comprobamos que existe el de usuario
            //y el rol a asignar
            if ($this->existeCodUsuario($codUsuario) &&  $this->existeRole($role)){

                //en caso de existir ambos, se asigna nuevo valor
                //devolvemos true
                $this->_usuarios[$codUsuario]["cod_role"] = $role;
                return true;
            }
            else{
                return false;
            }

        }

        /**
         * Devuelve un array con todos los usuarios existentes. 
         * La clave es el codigo de usuario, el valor es el nick del usuario 
         *
         * @return array Array con todos los usuarios existentes
         */
        function dameUsuarios():array
        {
            $usuarios = [];

            foreach ($this->_usuarios as $clave => $usuario) {
               $usuarios[$clave] = $usuario["nick"];
            }

            return $usuarios;
        }

        /**
         * Devuelve un array con todos los roles existentes. 
         * La clave es el codigo de role, el valor es el nombre del role 
         *
         * @return array Array con todos los roles existentes
         */
        function dameRoles():array
        {
            $roles=[];

            foreach($this->_roles as $clave=>$role)
                $roles[$clave]=$role["nombre"];

            return $roles;
        }

    }