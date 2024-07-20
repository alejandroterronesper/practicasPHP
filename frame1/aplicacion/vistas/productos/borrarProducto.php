<?php


echo CHTML::dibujaEtiqueta("h2", [], "Borrar producto: " . $producto->nombre, true).PHP_EOL;

echo "<br>".PHP_EOL;


echo CHTML::iniciarForm("", "post", ["class" => "formulario"]).PHP_EOL;
echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;
echo CHTML::dibujaEtiqueta("legend",[], "Borrar producto", true ).PHP_EOL;


echo CHTML::dibujaEtiqueta("h3", [], "¿Quieres borrar el producto: " . $producto->nombre. " ?").PHP_EOL;



echo CHTML::modeloListaRadioButton($producto, "borrado", [0=>"NO", 1=> "SI"], " ", []).PHP_EOL;

echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;
echo CHTML::campoBotonSubmit("Modificar", ["class" => "boton"]).PHP_EOL;
echo CHTML::botonHtml(CHTML::link("Cancelar acción", ["productos"]), ["class"=>"boton"]).PHP_EOL;


echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
echo CHTML::finalizarForm().PHP_EOL;

echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;

echo CHTML::iniciarForm("", "", ["class" => "formulario"]).PHP_EOL;
echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;
echo CHTML::dibujaEtiqueta("legend",[], "DATOS DEL PRODUCTO", true ).PHP_EOL;

//nombre
echo CHTML::modeloLabel($producto, "nombre", []).PHP_EOL;
echo CHTML::modeloText($producto, "nombre", ["readonly" => true]).PHP_EOL;
echo "<br>".PHP_EOL;

//fabricante
echo CHTML::modeloLabel($producto, "fabricante", []).PHP_EOL;
echo CHTML::modeloText($producto, "fabricante", ["readonly" => true]).PHP_EOL;


//categoria
echo "<br>".PHP_EOL;
echo CHTML::modeloLabel($producto, "descripcion", []).PHP_EOL;
echo CHTML::modeloText($producto, "descripcion", ["readonly" => true]).PHP_EOL;


//fecha de alta
echo "<br>".PHP_EOL;
echo CHTML::modeloLabel($producto, "fecha_alta", []).PHP_EOL;
echo CHTML::modeloText($producto, "fecha_alta", ["readonly" => true]).PHP_EOL;


//unidades
echo "<br>".PHP_EOL;
echo CHTML::modeloLabel($producto, "unidades", []).PHP_EOL;
echo CHTML::modeloText($producto, "unidades", ["readonly" => true]).PHP_EOL;

//precio base
echo "<br>".PHP_EOL;
echo CHTML::modeloLabel($producto, "precio_base", []).PHP_EOL;
echo CHTML::modeloText($producto, "precio_base", ["readonly" => true]).PHP_EOL;


//iva
echo "<br>".PHP_EOL;
echo CHTML::modeloLabel($producto, "iva", []).PHP_EOL;
echo CHTML::modeloText($producto, "iva", ["readonly" => true]).PHP_EOL;


//precio iva
echo "<br>".PHP_EOL;
echo CHTML::modeloLabel($producto, "precio_iva", []).PHP_EOL;
echo CHTML::modeloText($producto, "precio_iva", ["readonly" => true]).PHP_EOL;


//precio venta
echo "<br>".PHP_EOL;
echo CHTML::modeloLabel($producto, "precio_venta", []).PHP_EOL;
echo CHTML::modeloText($producto, "precio_venta", ["readonly" => true]).PHP_EOL;

//foto
echo "<br>".PHP_EOL;
echo CHTML::modeloLabel($producto, "foto", []).PHP_EOL;
echo CHTML::imagen("../../imagenes/productos/".$producto->foto, "foto de producto", ["id"=> "fotoVer"]).PHP_EOL;


echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;

echo CHTML::finalizarForm().PHP_EOL;



echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;
echo CHTML::iniciarForm("", "", ["class" => "formulario"]).PHP_EOL;
echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;
echo CHTML::dibujaEtiqueta("legend",[], "Otras operaciones", true ).PHP_EOL;

echo CHTML::botonHtml(CHTML::link("Ver producto", ["productos", "verProducto/id=". $producto->cod_categoria]), ["class"=>"boton"]).PHP_EOL;

echo CHTML::botonHtml(CHTML::link("Modificar producto", ["productos", "modificarProducto/id=". $producto->cod_categoria]), ["class"=>"boton"]).PHP_EOL;



echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;



echo CHTML::finalizarForm().PHP_EOL;


echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;
echo CHTML::botonHtml(CHTML::link("Volver atrás", ["productos"]),["class"=>"boton"]).PHP_EOL;



?>  