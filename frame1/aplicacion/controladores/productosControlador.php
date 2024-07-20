<?php
require_once(RUTA_BASE.'/scripts/TCPDF/examples/tcpdf_include.php');

class productosControlador extends CControlador {


	function __construct()
	{
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
				"texto" => "Index de productos",
				"enlace" => ["productos"]
			],
			[
				"texto" => "Index de paginador",
				"enlace" => ["productos", "IndexPaginador"]
			]
		];


        //Barra de ubicación
		$this->barraUbi = [
			[
			  "texto" => "inicio",
			  "url" => "/"
		  	],
			[
				"texto" => "Index de productos",
				"url" => ["productos", "index"]
			]
	    ];

	
	
	}


	/**
	 * acción del index
	 *
	 * @return void
	 */
    public function accionIndex (){


		//tenemos que comprobar que haya usuario registrado
		//y que tenga los permisos necesarios para entrar

		$nickUserActual = Sistema::app()->Acceso()->getNick();
		$codUserActual = Sistema::app()->ACL()->getCodUsuario($nickUserActual);
		$borradoActual = Sistema::app()->ACL()->getBorrado($codUserActual); //tiene que ser false, 0, para que podamos entrar

		//Se guarda acción anterior
		$_SESSION["anterior"] = ["productos"];


	
		if (Sistema::app()->Acceso()->hayUsuario()=== true && (!$borradoActual)){

			//Si esta validado, pero no tiene permiso, lo mandamos a página de error
			if (!Sistema::app()->Acceso()->puedePermiso(9)){
				Sistema::app()->paginaError("404", "No tienes permisos para acceder a este sitio");
				exit;

			}
		}
		else{
			//Si no hay usuario registrado, se manda al login
			//cuando se logee regresa a la accción anterior


		
			//Mandamos el usuario al Login
			Sistema::app()->irAPagina(["registro", "login"]);
			exit;

		}


		//creamos array de filtrado
		if (!isset($_SESSION["arrayFiltrado"])){
			$_SESSION["arrayFiltrado"] = [
				"nombre" => "",
				"categoria" => -1,
				"borrado" => -1,
				"sentencia" => "",
				"productos" => []
			];
		}
		

		//datos que recojo del formulario de filtrado
		$datos = [
			"nombre" =>$_SESSION["arrayFiltrado"]["nombre"], 
			"categoria" => $_SESSION["arrayFiltrado"]["categoria"],
			"borrado" => $_SESSION["arrayFiltrado"]["borrado"]
		];


		$selectWhere = "";
		if ($_POST){


			if (isset($_POST["filtrarDatos"])){


				$nombre = "";
				if (isset($_POST["nombre"])){
					$nombre = trim($_POST["nombre"]);
					$nombre = CGeneral::addSlashes($nombre); //Evitamos inyección SQL


					if ($nombre !== ""){
						$selectWhere.= " nombre LIKE '%$nombre%'";
					}

				}
				$datos["nombre"] = $nombre;

				$categoria = -1;
				if (isset($_POST["categoria"])){
					$categoria = intval($_POST["categoria"]);


					if ($selectWhere !== ""){
						if (is_string(Categorias::dameCategorias($categoria))){
							$selectWhere.= " AND cod_categoria = '$categoria' ";
						}
					}
					else{
						if (is_string(Categorias::dameCategorias($categoria))){
							$selectWhere.= " cod_categoria = '$categoria' ";
						}
					}
				}
				$datos["categoria"] = $categoria;

				$borrado = -1;
				if (isset($_POST["borrado"])){
					$borrado = intval($_POST["borrado"]);

					if ($borrado !== -1){ //Descartamos la opcion de búsqueda de TODOS = -1
						if ($selectWhere !== ""){
							
							$selectWhere .= " AND borrado = '$borrado'";
						}
						else{
							$selectWhere .= "  borrado = '$borrado'";

						}
					}	
				}
				$datos["borrado"] = $borrado;
			}



			if (isset($_POST["limpiarFiltrado"])){

				//limpiamos búsqueda
				$datos["nombre"] = "";
				$datos["categoria"] = -1;
				$datos["borrado"] = -1;
				$selectWhere = "";
			}



			$_SESSION["arrayFiltrado"] = [
				"nombre" => $datos["nombre"],
				"categoria" => $datos["categoria"],
				"borrado" => $datos["borrado"],
				"sentencia" => $selectWhere
			];
			
		}


		$categorias = Categorias::dameCategorias(null);


		$numPaginas = 0;
		$numProductos = 4; 
		$limite = "";
		$paginaActual = 1;
		
		if (isset($_GET["reg_pag"]) && isset($_GET["pag"])){
			$paginaActual = intval($_GET["pag"]);   //pagina actua
			$numProductos = intval($_GET["reg_pag"]);
			$numPaginas = $numProductos * ($paginaActual - 1);
			$limite = $numPaginas.",".$numProductos;
		}
		else{
			$paginaActual = 1;
			$limite = $numPaginas.",". $numProductos;
		}


		$productos = new Productos ();

		//guardamos consulta en sesion
		if (isset($_SESSION["arrayFiltrado"]["sentencia"]) && $_SESSION["arrayFiltrado"]["sentencia"] !== "" ){
			$selectWhere = $_SESSION["arrayFiltrado"]["sentencia"];
		}


		if ($selectWhere !== ""){
			
			$filas = $productos->buscarTodos(
				["where" => $selectWhere,
				"limit" => $limite
				]
			);
		}
		else{
			$filas = $productos->buscarTodos(
				[
					"limit" => $limite
				]
			);
		}


		//Añadimos las opciones de ver, modificar y borrar
		foreach($filas as $clave => $fila){

			$fila["oper"] = CHTML::link(CHTML::imagen("/imagenes/24x24/ver.png", "", ["title" => "Ver producto"]), Sistema::app()->generaURL(["productos","verProducto"],["id"=>$fila["cod_producto"]])). " ".
							CHTML::link(CHTML::imagen("/imagenes/24x24/modificar.png", "", ["title" => "Modificar producto"]), Sistema::app()->generaURL(["productos","modificarProducto"],["id"=>$fila["cod_producto"]]));


			if (intval($fila["borrado"]) === 0){
				$fila["oper"] .= CHTML::link(CHTML::imagen("/imagenes/24x24/borrar.png", "", ["title" => "Borrar producto"]), Sistema::app()->generaURL(["productos","borrarProducto"],["id"=>$fila["cod_producto"]]));
			}


			if (intval($fila["borrado"]) === 0){
				$fila["borrado"] = "NO";
			}

			if (intval($fila["borrado"]) === 1){
				$fila["borrado"] = "SI";
			}

			if ($fila["foto"]){
				$fila["foto"] = CHTML::imagen("../../imagenes/productos/".$fila["foto"], "imagen producto", ["style" => "width:30%; margin-left: 35%"]);
			}
			
			$fila["fecha_alta"] = CGeneral::fechaMysqlANormal($fila["fecha_alta"]);

			$filas[$clave] = $fila;

		}

		$filasDescargas = $productos->buscarTodos(
			["where" => $selectWhere]
		);


		
		$_SESSION["arrayFiltrado"]["productos"] = $filasDescargas;

		//No se mostrará el campo cod_producto ni cod_categoría. 
		$cabecera = [
				["ETIQUETA" => "NOMBRE",
				"CAMPO" => "nombre"],
				["ETIQUETA" => "FABRICANTE",
				"CAMPO" => "fabricante"],
				["ETIQUETA" => "FECHA DE ALTA",
				"CAMPO" => "fecha_alta"],
				["ETIQUETA" => "UNIDADES",
				"CAMPO" => "unidades"],
				["ETIQUETA" => "IVA",
				"CAMPO" => "iva"],
				["ETIQUETA" => "PRECIO DE IVA",
				"CAMPO" => "precio_iva"],
				["ETIQUETA" => "PRECIO DE VENTA",
				"CAMPO" => "precio_venta"],
				["ETIQUETA" => "DESCRIPCIÓN",
				"CAMPO" => "descripcion"],
				["ETIQUETA" => "BORRADO",
				"CAMPO" => "borrado"],
				["ETIQUETA" => "FOTO",
				"CAMPO" => "foto"],
				["ETIQUETA"=> "operaciones",
				"CAMPO" => "oper"]
		];



		//opciones del paginador
		$opcPaginador = array(
			"URL" => Sistema::app()->generaURL(array("productos", "index")),
			"TOTAL_REGISTROS" => $productos->buscarTodosNRegistros($selectWhere !== "" ? ["where" => $selectWhere] : []),
			"PAGINA_ACTUAL" => $paginaActual,
			"REGISTROS_PAGINA" => $numProductos,
			"TAMANIOS_PAGINA" => array(
				5 => "5",
				10 => "10",
				20 => "20",
				30 => "30",
				40 => "40",
				50 => "50"
			),
			"MOSTRAR_TAMANIOS" => true,
			"PAGINAS_MOSTRADAS" => 7,
		);


        $this->dibujaVista("index", ["cab" => $cabecera, "fil" => $filas, "categorias" => $categorias, "datos" => $datos, "paginador" => $opcPaginador ], "Index de productos");

    }




	public function accionIndexPaginador (){

		//tenemos que comprobar que haya usuario registrado
		//y que tenga los permisos necesarios para entrar

		$nickUserActual = Sistema::app()->Acceso()->getNick();
		$codUserActual = Sistema::app()->ACL()->getCodUsuario($nickUserActual);
		$borradoActual = Sistema::app()->ACL()->getBorrado($codUserActual); //tiene que ser false, 0, para que podamos entrar
		$_SESSION["anterior"] = ["productos", "IndexPaginador"];
	
		if (Sistema::app()->Acceso()->hayUsuario()=== true && (!$borradoActual)){

			//Si esta validado, pero no tiene permiso, lo mandamos a página de error
			if (!Sistema::app()->Acceso()->puedePermiso(9)){
				Sistema::app()->paginaError("404", "No tienes permisos para acceder a este sitio");
				exit;

			}
		}
		else{
			//Si no hay usuario registrado, se manda al login
		
			//Mandamos el usuario al Login
			Sistema::app()->irAPagina(["registro", "login"]);
			exit;

		}


		//Barra de ubicación
		$this->barraUbi = [
			[
				"texto" => "inicio",
				"url" => "/"
			],
			[
				"texto" => "Index de paginador",
				"url" => ["productos", "IndexPaginador"]
			]
		];


		//creamos array de filtrado
		if (!isset($_SESSION["arrayFiltrado"])) {
			$_SESSION["arrayFiltrado"] = [
					"nombre" => "",
					"categoria" => -1,
					"borrado" => -1,
					"sentencia" => "",
					"productos" => []
				];
		}
		

		//datos que recojo del formulario de filtrado
		$datos = [
			"nombre" =>$_SESSION["arrayFiltrado"]["nombre"], 
			"categoria" => $_SESSION["arrayFiltrado"]["categoria"],
			"borrado" => $_SESSION["arrayFiltrado"]["borrado"]
		];



		$selectWhere = "";
		if ($_POST){


			if (isset($_POST["filtrarDatos"])){


				$nombre = "";
				if (isset($_POST["nombre"])){
					$nombre = trim($_POST["nombre"]);
					$nombre = CGeneral::addSlashes($nombre); //Evitamos inyección SQL


					if ($nombre !== ""){
						$selectWhere.= " nombre LIKE '%$nombre%'";
					}

				}
				$datos["nombre"] = $nombre;

				$categoria = -1;
				if (isset($_POST["categoria"])){
					$categoria = intval($_POST["categoria"]);


					if ($selectWhere !== ""){
						if (is_string(Categorias::dameCategorias($categoria))){
							$selectWhere.= " AND cod_categoria = '$categoria' ";
						}
					}
					else{
						if (is_string(Categorias::dameCategorias($categoria))){
							$selectWhere.= " cod_categoria = '$categoria' ";
						}
					}
				}
				$datos["categoria"] = $categoria;

				$borrado = -1;
				if (isset($_POST["borrado"])){
					$borrado = intval($_POST["borrado"]);

					if ($borrado !== -1){ //Descartamos la opcion de búsqueda de TODOS = -1
						if ($selectWhere !== ""){
							
							$selectWhere .= " AND borrado = '$borrado'";
						}
						else{
							$selectWhere .= "  borrado = '$borrado'";

						}
					}	
				}
				$datos["borrado"] = $borrado;
			}



			if (isset($_POST["limpiarFiltrado"])){

				//limpiamos búsqueda
				$datos["nombre"] = "";
				$datos["categoria"] = -1;
				$datos["borrado"] = -1;
				$selectWhere = "";
			}


			$_SESSION["arrayFiltrado"] = [
				"nombre" => $datos["nombre"],
				"categoria" => $datos["categoria"],
				"borrado" => $datos["borrado"],
				"sentencia" => $selectWhere
			];
			
		}


		$categorias = Categorias::dameCategorias(null);


		$numPaginas = 0;
		$numProductos = 4; 
		$limite = "";
		$paginaActual = 1;
		if (isset($_GET["reg_pag"]) && isset($_GET["pag"])){
			$paginaActual = intval($_GET["pag"]);   //pagina actual
			$numProductos = intval($_GET["reg_pag"]);
			$numPaginas = $numProductos * ($paginaActual - 1);
			$limite = $numPaginas.",".$numProductos;
		}
		else{
			$paginaActual = 1;
			$limite = $numPaginas.",". $numProductos;
		}


		$productos = new Productos ();

		//guardamos consulta en sesion
		if (isset($_SESSION["arrayFiltrado"]["sentencia"]) && $_SESSION["arrayFiltrado"]["sentencia"] !== "" ){
			$selectWhere = $_SESSION["arrayFiltrado"]["sentencia"];
		}
		
		if ($selectWhere !== ""){
			$filas = $productos->buscarTodos(
				["where" => $selectWhere,
				"limit" => $limite
				]
			);
		}
		else{
			$filas = $productos->buscarTodos(
				[
					"limit" => $limite
				]
			);
		}


		//Añadimos las opciones de ver, modificar y borrar
		foreach($filas as $clave => $fila){

			$fila["oper"] = CHTML::link(CHTML::imagen("/imagenes/24x24/ver.png", "", ["title" => "Ver producto"]), Sistema::app()->generaURL(["productos","verProducto"],["id"=>$fila["cod_producto"]])). " ".
							CHTML::link(CHTML::imagen("/imagenes/24x24/modificar.png", "", ["title" => "Modificar producto"]), Sistema::app()->generaURL(["productos","modificarProducto"],["id"=>$fila["cod_producto"]]));


			if (intval($fila["borrado"]) === 0){
				$fila["oper"] .= CHTML::link(CHTML::imagen("/imagenes/24x24/borrar.png", "", ["title" => "Borrar producto"]), Sistema::app()->generaURL(["productos","borrarProducto"],["id"=>$fila["cod_producto"]]));
			}


			if (intval($fila["borrado"]) === 0){
				$fila["borrado"] = "NO";
			}

			if (intval($fila["borrado"]) === 1){
				$fila["borrado"] = "SI";
			}

						
			
			$fila["fecha_alta"] = CGeneral::fechaMysqlANormal($fila["fecha_alta"]);

			$filas[$clave] = $fila;

		}
		

		//No se mostrará el campo cod_producto ni cod_categoría. 
		$cabecera = [
				["ETIQUETA" => "NOMBRE",
				"CAMPO" => "nombre"],
				["ETIQUETA" => "FABRICANTE",
				"CAMPO" => "fabricante"],
				["ETIQUETA" => "FECHA DE ALTA",
				"CAMPO" => "fecha_alta"],
				["ETIQUETA" => "UNIDADES",
				"CAMPO" => "unidades"],
				["ETIQUETA" => "IVA",
				"CAMPO" => "iva"],
				["ETIQUETA" => "PRECIO DE IVA",
				"CAMPO" => "precio_iva"],
				["ETIQUETA" => "PRECIO DE VENTA",
				"CAMPO" => "precio_venta"],
				["ETIQUETA" => "DESCRIPCIÓN",
				"CAMPO" => "descripcion"],
				["ETIQUETA" => "BORRADO",
				"CAMPO" => "borrado"],
				["ETIQUETA" => "FOTO",
				"CAMPO" => "foto"],
				["ETIQUETA"=> "operaciones",
				"CAMPO" => "oper"]
		];



		//opciones del paginador
		$opcPaginador = array(
			"URL" => Sistema::app()->generaURL(array("productos", "IndexPaginador")),
			"TOTAL_REGISTROS" => $productos->buscarTodosNRegistros($selectWhere !== "" ? ["where" => $selectWhere] : []),
			"PAGINA_ACTUAL" => $paginaActual,
			"REGISTROS_PAGINA" => $numProductos,
			"TAMANIOS_PAGINA" => array(
				5 => "5",
				10 => "10",
				20 => "20",
				30 => "30",
				40 => "40",
				50 => "50"
			),
			"MOSTRAR_TAMANIOS" => true,
			"PAGINAS_MOSTRADAS" => 7,
		);


        $this->dibujaVista("paginadorIndex", ["cab" => $cabecera, "fil" => $filas, "categorias" => $categorias, "datos" => $datos, "paginador" => $opcPaginador ], "Index paginados");

	}

	/**
	 * acción ver producto
	 *
	 * @return void
	 */
	public function accionVerProducto (){


		//tenemos que comprobar que haya usuario registrado
		//y que tenga los permisos necesarios para entrar

		$nickUserActual = Sistema::app()->Acceso()->getNick();
		$codUserActual = Sistema::app()->ACL()->getCodUsuario($nickUserActual);
		$borradoActual = Sistema::app()->ACL()->getBorrado($codUserActual); //tiene que ser false, 0, para que podamos entrar


		$id = "";
		if ($_GET){


			if (isset($_GET["id"])){
				$id = intval($_GET["id"]);

			}


			if ($id === ""){ //Si no se recibe parámetro id, le mando a la página de error
				Sistema::app()->paginaError("404", "Error, se ha accedido con un parámetro distinto al id");
				exit;
			}
		}


		$_SESSION["anterior"] = ["productos", "VerProducto/id=".$id];

		if (Sistema::app()->Acceso()->hayUsuario()=== true && (!$borradoActual)){

			//Si esta validado, pero no tiene permiso, lo mandamos a página de error
			if (!Sistema::app()->Acceso()->puedePermiso(9)){
				Sistema::app()->paginaError("404", "No tienes permisos para acceder a este sitio");
				exit;

			}
		}
		else{
			//Si no hay usuario registrado, se manda al login

		
		
			//Mandamos el usuario al Login
			Sistema::app()->irAPagina(["registro", "login"]);
			exit;

		}

		
        	//Barra de ubicación
			$this->barraUbi = [
				[
				  "texto" => "inicio",
				  "url" => "/"
				  ],
				[
					"texto" => "Index de productos",
					"url" => ["productos", "index"]
				],
				[
					"texto" => "Ver producto",
					"url" => ["productos", "VerProducto/id=".$id]
				]
			];


		//Ahora compruebo que el id del producto me devuelve un resultado
		$producto = new Productos ();

	

		if ($producto->buscarPorId($id) === false){
			Sistema::app()->paginaError("404", "Error, no se ha encontrado el producto con el id indicado");
			exit;
		}
		else{
			$this->dibujaVista("verProducto", ["producto" => $producto], "Ver producto");
		}

		
	}




	/**
	 * Acción para modificar un producto
	 *
	 * @return void
	 */
	public function accionModificarProducto (){

		//tenemos que comprobar que haya usuario registrado
		//y que tenga los permisos necesarios para entrar

		$nickUserActual = Sistema::app()->Acceso()->getNick();
		$codUserActual = Sistema::app()->ACL()->getCodUsuario($nickUserActual);
		$borradoActual = Sistema::app()->ACL()->getBorrado($codUserActual); //tiene que ser false, 0, para que podamos entrar

		$id = "";
		if ($_GET){


			if (isset($_GET["id"])){
				$id = intval($_GET["id"]);
			}


			if ($id === ""){ //Si no se recibe parámetro id, le mando a la página de error
				Sistema::app()->paginaError("404", "Error, se ha accedido con un parámetro distinto al id");
				exit;
			}
		}

		$_SESSION["anterior"] = ["productos", "modificarProducto/id=".$id];

		if (Sistema::app()->Acceso()->hayUsuario()=== true && (!$borradoActual)){

			//Si esta validado, pero no tiene permiso, lo mandamos a página de error
			if (!Sistema::app()->Acceso()->puedePermiso(9)){
				Sistema::app()->paginaError("404", "No tienes permisos para acceder a este sitio");
				exit;

			}
		}
		else{
			//Si no hay usuario registrado, se manda al login

		
			//Mandamos el usuario al Login
			Sistema::app()->irAPagina(["registro", "login"]);
			exit;		

		}

		

		
        	//Barra de ubicación
			$this->barraUbi = [
				[
				  "texto" => "inicio",
				  "url" => "/"
				  ],
				[
					"texto" => "Index de productos",
					"url" => ["productos", "index"]
				],
				[
					"texto" => "Modificar producto",
					"url" => ["productos", "modificarProducto/id=".$id]
				]
			];


		//Ahora compruebo que el id del producto me devuelve un resultado
		$producto = new Productos ();

	

		if ($producto->buscarPorId($id) === false){
			Sistema::app()->paginaError("404", "Error, no se ha encontrado el producto con el id indicado");
			exit;
		}
		else{
			$arrayCategorias = Categorias::dameCategorias(null);

			$nombre = $producto->getNombre();
			

			if ($_POST){

				if (isset($_POST[$nombre])){

					$producto->setValores($_POST[$nombre]);
					$producto->descripcion = Categorias::dameCategorias($producto->cod_categoria);

					if (!$producto->validar()){

						$this->dibujaVista("modificarProducto", ["producto"=>$producto, "categorias" => $arrayCategorias], "Modificar producto");
						exit;
					}
					else{

						//ahora cogemos foto
						if ($_FILES){
							$fotoNueva = "";
							if (isset($_FILES["producto"]["name"]["foto"])){
								$fotoNueva = trim($_FILES["producto"]["name"]["foto"]);
								$fotoNueva = CGeneral::addSlashes($fotoNueva);

								if ($fotoNueva !== ""){ 
									$producto->foto = $fotoNueva;
									$rutaImagen = RUTA_BASE. "/imagenes/productos/".$fotoNueva;

									if (!move_uploaded_file($_FILES["producto"]["tmp_name"]["foto"], $rutaImagen)){
										Sistema::app()->paginaError("404", "Error, al subir la foto");
									}
								}
							}
						}
		
						if ($producto->guardar() === true){
							$id = intval($producto->cod_producto);
							header("location:".Sistema::app()->generaURL(["productos","verProducto"],["id"=>$id]));
							exit();
						}
						else{
							$this->dibujaVista("modificarProducto", ["producto"=>$producto, "categorias" => $arrayCategorias], "Modificar producto");
							exit;
						}

					}
				}
			}




			$this->dibujaVista("modificarProducto", ["producto"=>$producto, "categorias" => $arrayCategorias], "Modificar producto");
		}
	}





	/**
	 * Acción para borrar un producto
	 * se hace borrado lógico
	 *
	 * @return void
	 */
	public function accionBorrarProducto()
	{


		//tenemos que comprobar que haya usuario registrado
		//y que tenga los permisos necesarios para entrar

		$nickUserActual = Sistema::app()->Acceso()->getNick();
		$codUserActual = Sistema::app()->ACL()->getCodUsuario($nickUserActual);
		$borradoActual = Sistema::app()->ACL()->getBorrado($codUserActual); //tiene que ser false, 0, para que podamos entrar

	
		



		$id = "";
		if ($_GET){


			if (isset($_GET["id"])){
				$id = intval($_GET["id"]);
			}


			if ($id === ""){ //Si no se recibe parámetro id, le mando a la página de error
				Sistema::app()->paginaError("404", "Error, se ha accedido con un parámetro distinto al id");
				exit;
			}
		}


		$_SESSION["anterior"] = ["productos", "BorrarProducto/id=".$id];

		if (Sistema::app()->Acceso()->hayUsuario()=== true && (!$borradoActual)){

			//Si esta validado, pero no tiene permiso, lo mandamos a página de error
			if (!Sistema::app()->Acceso()->puedePermiso(9)){
				Sistema::app()->paginaError("404", "No tienes permisos para acceder a este sitio");
				exit;

			}
		}
		else{
			//Si no hay usuario registrado, se manda al login

			//Mandamos el usuario al Login
			Sistema::app()->irAPagina(["registro", "login"]);
			exit;


		}


		
        	//Barra de ubicación
			$this->barraUbi = [
				[
				  "texto" => "inicio",
				  "url" => "/"
				  ],
				[
					"texto" => "Index de productos",
					"url" => ["productos", "index"]
				],
				[
					"texto" => "Borrar producto",
					"url" => ["productos", "BorrarProducto/id=".$id]
				]
			];


		//Ahora compruebo que el id del producto me devuelve un resultado
		$producto = new Productos ();

	

		if ($producto->buscarPorId($id) === false){
			Sistema::app()->paginaError("404", "Error, no se ha encontrado el producto con el id indicado");
			exit;
		}

		if($producto->borrado === 1){
			Sistema::app()->paginaError("404", "Error, el producto seleccionado ya está borrado");
			exit;
		}

		else{

			$nombre = $producto->getNombre();

			if ($_POST){
				if (isset($_POST[$nombre])){
					$producto->setValores($_POST[$nombre]);
	
					if (!$producto->validar()){
						$this->dibujaVista("borrarProducto", ["producto" => $producto], "Borrar producto");
						exit;
					}
					else{
		
						if ($producto->guardar() === true){
							header("location:".Sistema::app()->generaURL(["productos","verProducto"],["id"=>$id]));
							exit();
						}
						else{
							$this->dibujaVista("borrarProducto", ["producto" => $producto], "Borrar producto");
							exit;
						}

					}
				}
			}
			
			$this->dibujaVista("borrarProducto", ["producto" => $producto], "Borrar producto");
		}
	}

	

	/**
	 * Acción para añadir un producto nuevo
	 *
	 * @return void
	 */
	public function accionAddProducto (){

		//tenemos que comprobar que haya usuario registrado
		//y que tenga los permisos necesarios para entrar

		$nickUserActual = Sistema::app()->Acceso()->getNick();
		$codUserActual = Sistema::app()->ACL()->getCodUsuario($nickUserActual);
		$borradoActual = Sistema::app()->ACL()->getBorrado($codUserActual); //tiene que ser false, 0, para que podamos entrar
		$_SESSION["anterior"] = ["productos", "AddProducto"];

	
		if (Sistema::app()->Acceso()->hayUsuario()=== true && (!$borradoActual)){

			//Si esta validado, pero no tiene permiso, lo mandamos a página de error
			if (!Sistema::app()->Acceso()->puedePermiso(9)){
				Sistema::app()->paginaError("404", "No tienes permisos para acceder a este sitio");
				exit;

			}
		}
		else{
			//Si no hay usuario registrado, se manda al login
		
			//Mandamos el usuario al Login
			Sistema::app()->irAPagina(["registro", "login"]);
			exit;

		}



		//Barra de ubicación
		$this->barraUbi = [
			[
				"texto" => "inicio",
				"url" => "/"
			],
			[
				"texto" => "Index de productos",
				"url" => ["productos", "index"]
			],
			[
				"texto" => "Añadir producto",
				"url" => ["productos", "addProducto"]
			]
		];

		$producto = new Productos ();
		$producto->fecha_alta = $producto->fecha_alta->format("d/m/Y");
		$arrayCategorias = Categorias::dameCategorias(null);

		$nombre = $producto->getNombre();

			if ($_POST){
				if (isset($_POST[$nombre])){
					$producto->setValores($_POST[$nombre]);
					$producto->descripcion = Categorias::dameCategorias($producto->cod_categoria);
					if (!$producto->validar()){
						$this->dibujaVista("addProducto", ["producto" => $producto,  "arrayCategorias" => $arrayCategorias], "Añadir producto");
						exit;
					}
					else{

						//ahora cogemos foto
						if ($_FILES){
							$fotoNueva = "";
							if (isset($_FILES["producto"]["name"]["foto"])){
								$fotoNueva = trim($_FILES["producto"]["name"]["foto"]);
								$fotoNueva = CGeneral::addSlashes($fotoNueva);

								if ($fotoNueva !== ""){ 
									$producto->foto = $fotoNueva;
									$rutaImagen = RUTA_BASE. "/imagenes/productos/".$fotoNueva;

									if (!move_uploaded_file($_FILES["producto"]["tmp_name"]["foto"], $rutaImagen)){
										Sistema::app()->paginaError("404", "Error, al subir la foto");
									}
								}
							}
						}
		
						if ($producto->guardar() === true){
							$id = intval($producto->cod_producto);
							header("location:".Sistema::app()->generaURL(["productos","verProducto"],["id"=>$id]));
							exit();
						}
						else{
							$this->dibujaVista("addProducto", ["producto" => $producto,  "arrayCategorias" => $arrayCategorias], "Añadir producto");
							exit;
						}

					}
				}
			}

		$this->dibujaVista("addProducto", ["producto"=>$producto, "arrayCategorias" => $arrayCategorias], "Añadir producto");

	}


	/**
	 * Acción para descargar en un txt datos
	 * de todos los productos
	 *
	 * @return void
	 */
	public function accionDescargarMensaje(){


		//tenemos que comprobar que haya usuario registrado
		//y que tenga los permisos necesarios para entrar

		$nickUserActual = Sistema::app()->Acceso()->getNick();
		$codUserActual = Sistema::app()->ACL()->getCodUsuario($nickUserActual);
		$borradoActual = Sistema::app()->ACL()->getBorrado($codUserActual); //tiene que ser false, 0, para que podamos entrar
		$_SESSION["anterior"] = ["productos", "DescargarMensaje"];

	
		if (Sistema::app()->Acceso()->hayUsuario()=== true && (!$borradoActual)){

			//Si esta validado, pero no tiene permiso, lo mandamos a página de error
			if (!Sistema::app()->Acceso()->puedePermiso(9)){
				Sistema::app()->paginaError("404", "No tienes permisos para acceder a este sitio");
				exit;

			}
		}
		else{
			//Si no hay usuario registrado, se manda al login
			//guardamos en sesion la accion actual
			//para que cuando se logee vaya a la acción previa del login



		
			//Mandamos el usuario al Login
			Sistema::app()->irAPagina(["registro", "login"]);
			exit;

		}




		$productos = new Productos ();
		$cadena = "";

		$filas = $productos->buscarTodos();

		foreach ($filas as $clave => $valor){ //iteramos productos, sacamos las propiedades nombre, categoria, fabricante
											//y unidades y concatenamos

			$cadena .= "\n\nProducto: \nNombre: {$valor['nombre']}
						\nCategoria: {$valor['descripcion']}
						\nFabricante: {$valor['fabricante']}
						\nNúmero de unidades: {$valor['unidades']}
						\n******************************************";
		}

		header("content-type: text/txt");
        header("content-disposition: attachment; filename = mensajeProductos.txt");
        
        echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++".PHP_EOL;
        echo "++++++++++++++++++++++DESCARGA DE MENSAJES++++++++++++++++++++++++++++++++".PHP_EOL;
		echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++".PHP_EOL;

		echo  "\n$cadena".PHP_EOL;
  
        
        echo "\n++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++".PHP_EOL;
        
        exit();
	}


	/**
	 * Acción que genera un pdf con 
	 * los productos filtrados, aparecerán todos los productos
	 * Se muestra nombre, fabricante,  Unidades, Descripción,
	 *   Fecha de alta y Borrado 
	 * 
	 * en cabecera aparece el logo de la empresa, autor, nombre de empresa
	 * y fecha de expedición del documento
	 * 
	 * en el footer aparece la página actual con el nº de páginas totales
	 *
	 * @return void
	 */
	public function accionInformePdf(){

		
		//tenemos que comprobar que haya usuario registrado
		//y que tenga los permisos necesarios para entrar

		$nickUserActual = Sistema::app()->Acceso()->getNick();
		$codUserActual = Sistema::app()->ACL()->getCodUsuario($nickUserActual);
		$borradoActual = Sistema::app()->ACL()->getBorrado($codUserActual); //tiene que ser false, 0, para que podamos entrar
		$_SESSION["anterior"] = ["productos", "DescargarMensaje"];

	
		if (Sistema::app()->Acceso()->hayUsuario()=== true && (!$borradoActual)){

			//Si esta validado, pero no tiene permiso, lo mandamos a página de error
			if (!Sistema::app()->Acceso()->puedePermiso(9)){
				Sistema::app()->paginaError("404", "No tienes permisos para acceder a este sitio");
				exit;

			}
		}
		else{
			//Si no hay usuario registrado, se manda al login
			//guardamos en sesion la accion actual
			//para que cuando se logee vaya a la acción previa del login



		
			//Mandamos el usuario al Login
			Sistema::app()->irAPagina(["registro", "login"]);
			exit;

		}


		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->setCreator(PDF_CREATOR);
		$pdf->setAuthor('Alejandro Terrones Pérez');
		$pdf->setTitle('Productos filtrados');
		$pdf->setSubject('El mejor pdf del mundo');
		$pdf->setKeywords('TCPDF, PDF, example, test, guide');
		
		// set default header data


		//HEADER
		$pdf->setHeaderData(PDF_HEADER_LOGO, 30, "Frame 1", PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
		
		//FOOTER
		$pdf->setFooterData(array(0,64,0), array(0,64,128));
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		// set default monospaced font
		$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->setFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// ---------------------------------------------------------
		
		// set default font subsetting mode
		$pdf->setFontSubsetting(true);
		
		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$pdf->setFont('dejavusans', '', 14, '', true);
		
		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();
		
		// set text shadow effect
		$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
		
		

		if (isset($_SESSION["arrayFiltrado"]["productos"])){
		
			$productos = $_SESSION["arrayFiltrado"]["productos"];
		
		}
		else{
			$productos = new Productos ();
			$productos = $productos->buscarTodos();
		}


		foreach($productos as $clave => $fila){

			
			if (intval($fila["borrado"]) === 0){
				$fila["borrado"] = "NO";
			}

			if (intval($fila["borrado"]) === 1){
				$fila["borrado"] = "SI";
			}
			
			$fila["fecha_alta"] = CGeneral::fechaMysqlANormal($fila["fecha_alta"]);

			$productos[$clave]=$fila;

		}

		$numProductos = count($productos);

		$cabecera = [
			[
				"ETIQUETA" => "Nombre",
				"CAMPO" => "nombre"
			],
			[
				"ETIQUETA" => "Fabricante",
				"CAMPO" => "fabricante"
			],
			[
				"ETIQUETA" => "Unidades",
				"CAMPO" => "unidades"
			],
			[
				"ETIQUETA" => "Descripción",
				"CAMPO" => "descripcion"
			],
			[
				"ETIQUETA" => "Fecha de alta",
				"CAMPO" => "fecha_alta"
			],
			[
				"ETIQUETA" => "Borrado",
				"CAMPO" => "borrado"
			],

			[
				"ETIQUETA" => "Precio de venta",
				"CAMPO" => "precio_venta"
			],
		];

		$tabla = new CGrid($cabecera, $productos, ["class" => "tabla1"] );
		$tablaDibujo = $tabla->dibujate().PHP_EOL;

		$parrafoProductos = CHTML::dibujaEtiqueta("p", ["style" => "color:red;"], "Nº de productos filtrados: $numProductos", true).PHP_EOL;

		// Set some content to print
		$html = <<<EOD
			<style>
					table.tabla1 {
						background-color: #b6c7cd;
						border-collapse: collapse;
					}
				
					table.tabla1 th {
						font-size: small;
						background-color: blue;
						color: white;
					}
				
					table.tabla1 tr.par {
						background-color: #edd8d8;
						color: black;
					}
				
					table.tabla1 tr.impar {
						background-color: #f8f0d6;
						color: black;
					}
				
					table.tabla1 tr td {
						padding: 5px;
					}

			</style>
			<br>
			<br>
			<br>
			$parrafoProductos
			<br>
			$tablaDibujo

		EOD;
		
		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
	
		// ---------------------------------------------------------
		
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('descargaProductos.pdf', 'D');
	}
}


/**
 * Funcion que nos permite subir una foto
 *
 * @param array $arrayFoto array de la variable global $_FILES
 * @return boolean true si se pasa la foto false si no
 */
function subeFoto (array $arrayFoto): bool{


    $rutaImagenes = RUTABASE. "\\imagenes\\productos\\" . basename($arrayFoto["name"]);

    if (move_uploaded_file($arrayFoto["tmp_name"], $rutaImagenes)){
        return true;
    }
    else{
        return false;
    }

}
?>