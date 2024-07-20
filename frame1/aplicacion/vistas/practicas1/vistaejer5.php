<?php

echo CHTML::dibujaEtiqueta("h2", [], "Vista del ejercicio 5", true).PHP_EOL;

foreach ($vector as $clave => $valor){
    echo $this->dibujaVistaParcial("vistaejer5-parte", ["datos" => $datos =  [$clave => $valor]],true);
}

echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;

echo CHTML::botonHtml(CHTML::link("Volver atrás", $anterior)).PHP_EOL;
?>