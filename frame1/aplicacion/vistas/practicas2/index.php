<?php
    echo CHTML::dibujaEtiqueta("h1", [], "índice de la práctica 2").PHP_EOL;

    echo CHTML::dibujaEtiqueta("ul", [], null,false ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("li",[], CHTML::link("ERROR", $error), true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("li",[], CHTML::link("DESCARGA 1", $descargar1), true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("li",[], CHTML::link("DESCARGA 2", $descargar2), true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("li",[], CHTML::link("PETICION AJAX", $petAJAX), true).PHP_EOL;


    echo CHTML::dibujaEtiquetaCierre("ul").PHP_EOL;

    /**
     * Definir en la página por defecto del sitio un enlace a la acción anterior.
     */
    echo CHTML::botonHtml(CHTML::link("Acción anterior", $anterior));



?>