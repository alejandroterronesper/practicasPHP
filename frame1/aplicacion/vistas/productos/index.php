<?php

$this->textoHead = CPager::requisitos();
$this->textoHead .= "    ". CCaja::requisitos() ;

//paginador
$varPagina = new CPager($paginador, []);

echo CHTML::dibujaEtiqueta( "h1", [], "Index de productos", true).PHP_EOL;



//FILTROS DE BÚSQUEDA
//nombre, categoría y borrado
//FILTRADO CON CAJA

$objCaja = new CCaja("Criterios de filtrado (en caja)", "", ["style" => "width: fit-content"]);
echo $objCaja->dibujaApertura().PHP_EOL;

echo CHTML::iniciarForm("", "post", ["class" => "formulario", "style" => "width: fit-content;"]).PHP_EOL;
echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;
echo CHTML::dibujaEtiqueta("legend", [], "Criterios de filtrado", true).PHP_EOL;

//Nombre
echo CHTML::dibujaEtiqueta("label", ["for" => "nombre"], "Nombre: ", true).PHP_EOL;
echo CHTML::campoText("nombre", $datos["nombre"], []).PHP_EOL;

echo "<br>".PHP_EOL;
//Categorias
echo CHTML::dibujaEtiqueta("label", ["for" => "categoria"], "Categoría: ", true).PHP_EOL;
echo CHTML::campoListaDropDown("categoria", $datos["categoria"], $categorias, []).PHP_EOL;

echo "<br>".PHP_EOL;
//BORRADO
echo CHTML::dibujaEtiqueta("label", ["for" => "borrado"], "Borrado: ", true).PHP_EOL;
echo CHTML::campoListaRadioButton("borrado", $datos["borrado"], [-1=> "Todos",0 => "No" ,1 => "Si", ], "").PHP_EOL;


echo "<br>".PHP_EOL;

echo CHTML::campoBotonSubmit("Filtrar", ["class"=> "boton", "name"=> "filtrarDatos"]).PHP_EOL;
echo CHTML::campoBotonSubmit("Restaurar búsqueda", ["class"=> "boton", "name"=> "limpiarFiltrado"]).PHP_EOL; //reinicia la tabla y los campos
echo CHTML::botonHtml(CHTML::link("Descargar búsqueda", ["productos", "InformePdf"]), ["class"=>"boton"]).PHP_EOL;
echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
echo CHTML::finalizarForm().PHP_EOL;


echo $objCaja->dibujaFin().PHP_EOL;







echo CHTML::dibujaEtiqueta("hr", [], null, true).PHP_EOL;

echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;
$tabla = new CGrid($cab, $fil, ["class" => "tabla1"]);

echo $varPagina->dibujate().PHP_EOL;
echo $tabla->dibujate().PHP_EOL;
echo $varPagina->dibujate().PHP_EOL;

echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;

//OPERACIONES
echo CHTML::iniciarForm("", "post", ["class" => "formulario", "style" => "width: fit-content;"]).PHP_EOL;
echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;
echo CHTML::dibujaEtiqueta("legend", [], "OPERACIONES", true).PHP_EOL;
echo CHTML::botonHtml(CHTML::link("Añadir producto", ["productos", "addProducto"]), ["class"=>"boton"]).PHP_EOL;
echo CHTML::botonHtml(CHTML::link("Descargar mensajes", ["productos","DescargarMensaje"]), ["class"=>"boton"]).PHP_EOL;
echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
echo CHTML::finalizarForm().PHP_EOL;

echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;



echo CHTML::botonHtml(CHTML::link("Volver atrás", ["inicial"]), ["class"=>"boton"]).PHP_EOL;

?>