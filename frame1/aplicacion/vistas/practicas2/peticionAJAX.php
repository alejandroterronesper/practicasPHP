<?php

$this->textoHead = CHTML::scriptFichero("/js/main.js", ["defer" => "defer"]).PHP_EOL;


echo CHTML::dibujaEtiqueta("h2", [], "Petición AJAX", true);

echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;


//Formulario datos
echo CHTML::iniciarForm("", "POST", ["class" => "formulario", "id" => "formularioAJAX"]).PHP_EOL;
echo CHTML::dibujaEtiqueta("fieldset", [], "", false).PHP_EOL;
echo CHTML::dibujaEtiqueta("legend", [], "FORMULARIO", true).PHP_EOL;
echo CHTML::dibujaEtiqueta("label", [], "Introduce un número máximo", true).PHP_EOL;
echo CHTML::campoNumber("max", "", ["placeholder"=> "Introduce nº", "value" => $max, "id" => "nuMax"]).PHP_EOL;
echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;

echo CHTML::dibujaEtiqueta("label", [], "Introduce un número mínimo", true).PHP_EOL;
echo CHTML::campoNumber("min", "", ["placeholder"=> "Introduce nº", "value"=> $min, "id" => "nuMin"]).PHP_EOL;
echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;

echo CHTML::dibujaEtiqueta("label", [], "Introduce un patrón", true).PHP_EOL;
echo CHTML::campoText("patron", $patron, ["id" => "textPatron", "placeholder"=>"escribe un patrón"]).PHP_EOL;
echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;

echo CHTML::campoBotonSubmit("Pedir datos", ["name" => "subeDatos", "id" => "subeData"]).PHP_EOL;
echo CHTML::campoBotonReset("Borrar datos").PHP_EOL;
echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
echo CHTML::finalizarForm().PHP_EOL;



//creamos div de resultado
echo  CHTML::dibujaEtiqueta("div",["id"=> "resultados"], "", false).PHP_EOL;


echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;


echo CHTML::botonHtml(CHTML::link("Volver atrás", $anterior), ["class"=>"boton"]).PHP_EOL;

?>