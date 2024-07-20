<?php
 //PLANTILLA PAGINA DE ERROR
echo CHTML::dibujaEtiqueta("h2",[], "Bienvenido al frame1 ", true ).PHP_EOL;

echo CHTML::dibujaEtiqueta("h2",[], "Productos tienda ", true ).PHP_EOL;


foreach ($productos as $clave => $valor){

    if ( intval($valor["borrado"]) === 0){ //Solo mostramos los productos no borrados
        echo $this->dibujaVistaParcial("divProductos", ["producto" => $valor], true).PHP_EOL;
    }
    

}   

echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;

?>



