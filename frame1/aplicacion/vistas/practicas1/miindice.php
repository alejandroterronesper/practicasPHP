<?php
    echo CHTML::dibujaEtiqueta("h1", [], "índice de la práctica 1").PHP_EOL;

    echo CHTML::dibujaEtiqueta("ul", [], null,false ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("li",[], CHTML::link("EJERCICIO 1", $ejer1), true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("li",[], CHTML::link("EJERCICIO 2", $ejer2), true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("li",[], CHTML::link("EJERCICIO 3", $ejer3), true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("li",[], CHTML::link("EJERCICIO 5", $ejer5), true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("li",[], CHTML::link("EJERCICIO 7", $ejer7), true).PHP_EOL;

    echo CHTML::dibujaEtiquetaCierre("ul").PHP_EOL;
 

    /**
     * Definir en la página por defecto del sitio un enlace a la acción anterior.
     */
    echo CHTML::botonHtml(CHTML::link("Acción anterior", $anterior)).PHP_EOL;



?>