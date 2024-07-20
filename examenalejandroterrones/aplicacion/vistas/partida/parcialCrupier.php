<?php


$prueba = $persona;
echo CHTML::dibujaEtiqueta("div", ["class"=> "crupier"], null, true).PHP_EOL;
    
    echo CHTML::dibujaEtiqueta("label", [], "Cod_partida: " . $prueba->cod_partida).PHP_EOL;
    echo "<br>".PHP_EOL;
    echo CHTML::dibujaEtiqueta("label", [], "mesa: " . $prueba->mesa).PHP_EOL;
    echo "<br>".PHP_EOL;
    echo CHTML::dibujaEtiqueta("label", [], "fecha: " . $prueba->fecha).PHP_EOL;
    echo "<br>".PHP_EOL;
    echo CHTML::dibujaEtiqueta("label", [], "cod_baraja: " . $prueba->cod_baraja).PHP_EOL;
    echo "<br>".PHP_EOL;
    echo CHTML::dibujaEtiqueta("label", [], "nombre_baraja: " . $prueba->nombre_baraja).PHP_EOL;
    echo "<br>".PHP_EOL;

    echo CHTML::dibujaEtiqueta("label", [], "jugadores: " . $prueba->jugadores).PHP_EOL;
    echo "<br>".PHP_EOL;

    echo CHTML::dibujaEtiqueta("label", [], "crupier: " . $prueba->crupier).PHP_EOL;
    echo "<br>".PHP_EOL;


    echo CHTML::botonHtml(CHTML::link("Descargar datos", Sistema::app()->generaURL(["partida", "descargar/id=$codigo"])), ["class"=>"boton"]).PHP_EOL;


echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;






?>