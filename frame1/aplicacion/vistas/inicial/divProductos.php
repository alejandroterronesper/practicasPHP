<?php
//dibujamos los divs




    echo CHTML::dibujaEtiqueta("div", ["class"=> "productos"], null, true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("label", [], $producto["nombre"], true)."<br>".PHP_EOL;
    echo CHTML::imagen( "../../../imagenes/productos/".$producto["foto"])."<br>".PHP_EOL;
    echo CHTML::dibujaEtiqueta("label", [], "Precio: ". $producto["precio_venta"]. " €")."<br>".PHP_EOL;
    echo CHTML::dibujaEtiqueta("label", [], "Unidades: ". $producto["unidades"])."<br>".PHP_EOL;
    echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

 


?>