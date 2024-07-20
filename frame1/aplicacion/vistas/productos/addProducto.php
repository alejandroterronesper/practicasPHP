<?php


echo CHTML::dibujaEtiqueta("h2", [], "Añadir producto: " . $producto->nombre, true).PHP_EOL;


echo CHTML::iniciarForm("", "post", ["class" => "formulario", "enctype" =>"multipart/form-data"]).PHP_EOL;
echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;
echo CHTML::dibujaEtiqueta("legend",[], "DATOS DEL PRODUCTO", true ).PHP_EOL;

//nombre
echo CHTML::modeloLabel($producto, "nombre", []).PHP_EOL;
echo CHTML::modeloText($producto, "nombre", []).PHP_EOL;
echo CHTML::modeloError($producto, "nombre").PHP_EOL;

echo "<br>".PHP_EOL;

//fabricante
echo CHTML::modeloLabel($producto, "fabricante", []).PHP_EOL;
echo CHTML::modeloText($producto, "fabricante", []).PHP_EOL;
echo CHTML::modeloError($producto, "fabricante").PHP_EOL;


//categoria
echo "<br>".PHP_EOL;
echo CHTML::modeloLabel($producto, "descripcion", []).PHP_EOL;
echo CHTML::modeloListaDropDown($producto, "cod_categoria", $arrayCategorias, []).PHP_EOL;
echo CHTML::modeloError($producto, "cod_categoria").PHP_EOL;

//fecha de alta
echo "<br>".PHP_EOL;
echo CHTML::modeloLabel($producto, "fecha_alta", []).PHP_EOL;
echo CHTML::modeloText($producto, "fecha_alta", []).PHP_EOL;
echo CHTML::modeloError($producto, "fecha_alta").PHP_EOL;


//unidades
echo "<br>".PHP_EOL;
echo CHTML::modeloLabel($producto, "unidades", []).PHP_EOL;
echo CHTML::modeloText($producto, "unidades", []).PHP_EOL;
echo CHTML::modeloError($producto, "unidades").PHP_EOL;

//precio base
echo "<br>".PHP_EOL;
echo CHTML::modeloLabel($producto, "precio_base", []).PHP_EOL;
echo CHTML::modeloText($producto, "precio_base", []).PHP_EOL;
echo CHTML::modeloError($producto, "precio_base").PHP_EOL;


//iva
echo "<br>".PHP_EOL;
echo CHTML::modeloLabel($producto, "iva", []).PHP_EOL;
echo CHTML::modeloText($producto, "iva", []).PHP_EOL;
echo CHTML::modeloError($producto, "iva").PHP_EOL;


//foto
echo "<br>".PHP_EOL;
echo CHTML::modeloLabel($producto, "foto", []).PHP_EOL;
echo CHTML::campoHidden("MAX_FILE_SIZE", 100000000, []).PHP_EOL;
echo CHTML::modeloFile($producto, "foto",["accept" => "image/*"]).PHP_EOL;
echo CHTML::modeloError($producto, "foto").PHP_EOL;

echo "<br>".PHP_EOL;
echo CHTML::imagen("../../imagenes/productos/".$producto->foto, "foto de producto", ["id"=> "fotoVer"]).PHP_EOL;


echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;
echo CHTML::campoBotonSubmit("Crear producto", ["class" => "boton"]).PHP_EOL;


echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
echo CHTML::finalizarForm().PHP_EOL;










echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;
echo CHTML::botonHtml(CHTML::link("Volver atrás", ["productos"]),                   ["class"=>"boton"]).PHP_EOL;



?>  