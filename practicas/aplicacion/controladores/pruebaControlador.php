<?php
	 
class pruebaControlador extends CControlador
{
	function __construct()
	{
		

		$this->menuizq = [
			[
				"texto" => "indice", 
				"enlace" => ["prueba"]
			],
			[
				"texto" => "primera", 
				"enlace" => ["prueba","primera"]
			],
			[
				"texto" => "vista parcial", 
				"enlace" => ["prueba","parcial"]
			],
			[
				"texto" => "vista AJAX", 
				"enlace" => ["prueba","ajax"]
			],
			[
				"texto" => "nuevo Articulo", 
				"enlace" => ["prueba","nuevoArticulo"]
			],
			[
				"texto" => "Productos", 
				"enlace" => ["prueba","productos"]
			],
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

	public function accionParcial()
	{
		$datos= [
			[
				"texto" => "indice", 
				"enlace" => "prueba"
			],
			[
				"texto" => "primera", 
				"enlace" => "primera"
			],
			[
				"texto" => "vista parcial", 
				"enlace" => "parcial"
			],
			
		];


		$this->dibujaVista("parci",["datos"=>$datos],"prueba de vista parcial");
	}

	public function accionPrimera()
	{

		$texto="este texto se mostrará";

		$this->dibujaVista("pepito",["uno"=>$texto],
							"Pagina primerasssss");
	}


	public function accionAjax(){


		$this->dibujaVista("aj", [], "Prueba Ajax");

	}



	/**
	 * esta enganchado al JS
	 *
	 * @return void
	 */
	public function accionAjaxProveedor (){
		$login =$_POST["name"];



		$resultado=["texto1"=>"Usuario recibido: ",
					"texto2"=>$login." en el server!"];
		
		echo json_encode($resultado);
	}





	public function accionNuevoArticulo()
	{

		//creo un nuevo objeto articulo
		$articulo = new Articulos();
		//nombre del modelo= nombre del array por post
		$nombre = $articulo->getNombre();
		if (isset($_POST[$nombre])) {
			//asigno un codigo de articulo por defecto
			$articulo->cod_articulo = 5;
			//asigno los valores al articulo a partir de lo recogidosel formulario
			$articulo->setValores($_POST[$nombre]);
			//compruebo si son validos los datos del articulo
			if ($articulo->validar()) 
				{ //son validos los datos del articulo
					$this->dibujaVista("mostrararticulo",["modelo" => $articulo], "Crear articulo");
					exit;
				} 
		}
		//muestro la vista inicialmente
		$this->dibujaVista(
			"nuevo",
			array("modelo" => $articulo),
			"Crear articulo"
		);
		


	}



	public function accionProductos (){

		$producto = new Productos ();

		// $resultado = $producto->buscarTodos(["where" => "descripcion in ('juegos', 'CD')",
		// 									"order" => "nombre desc",
		// 									"limit" => "2,3"]);

		// $filas = $producto->buscarTodosNRegistros([
		// 										"select" => "cl.cod_compra, cl.orden, t.*",
		// 										"from" => "join compra_lineas cl using (cod_producto)",
		// 										"where" => "t.descripcion in ('juegos', 'CD')",
		// 										"order" => "t.nombre desc", 
		// 										"limite" => "1,3"]);

		// $filas = $producto->buscarTodos([
		// 	"select" => "cl.cod_compra, cl.orden, t.*",
		// 	"from" => "join compra_lineas cl using (cod_producto)",
		// 	"where" => "t.descripcion in ('juegos', 'CD')",
		// 	"order" => "t.nombre desc", 
		// 	"limite" => "1,3"]);


		// $sentencia = "select * from cons_productos";

		// $resultado = $producto->ejecutarSentencia ($sentencia);
		// $resultado = Sistema::app()->BD()->crearConsulta($sentencia);
		// $producto->fabricante = "El fabricante";
		// if (!$producto->buscarPorId(6)){
		// 	Sistema::app()->paginaError("No se encuentra el producto");
		// 	exit;
		// }

		// if (!$producto->buscarPor(["where" => "unidades = 76"])){
		// 	Sistema::app()->paginaError("No se encuentra el producto");
		// 	exit;
		// }


		
		// if ($producto->validar()){
		// 	$producto->precio_base = ($producto->precio_base * 1.10);
		// 	if ($producto->guardar()){
		// 		Sistema::app()->irAPagina(["prueba"]);
		// 		exit;
		// 	}
		// }
		$filas=$producto->buscarTodos(["select"=>"cl.cod_compra,cl.orden,t.*",
									"from"=>"join compra_lineas cl using (cod_producto)",
									"order"=>"t.nombre desc"]);


		foreach ($filas as $clave=> $fila){
            $fila["oper"]=CHTML::link(CHTML::imagen("/imagenes/24x24/modificar.png")."modificar",
			                                Sistema::app()->generaURL(["prueba","modificarProducto"],
																		["id"=>$fila["cod_producto"]]));


			$filas[$clave] = $fila;
		}


		$cabecera  =[ 
			["ETIQUETA" => "COMPRA",
			"CAMPO" => "cod_compra"],

			["ETIQUETA" => "NOMBRE PRODUCTO",
			"CAMPO" => "nombre"],

			["ETIQUETA"=> "Precio base",
			"CAMPO" => "precio_base",
			"ALINEA" => "der"],


			["ETIQUETA"=> "operaciones",
			"CAMPO" => "oper",]

		];

		$opcPaginador = array(
			"URL" => Sistema::app()->generaURL(array("pruebas", "productos")),
			"TOTAL_REGISTROS" => 120,
			"PAGINA_ACTUAL" => 1,
			"REGISTROS_PAGINA" => 10,
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


		$this->dibujaVista("productos", ["cab" => $cabecera, "fil" => $filas, "opcPaginador" => $opcPaginador], "productos");
	}

}
