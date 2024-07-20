<?php

foreach ($datos as $clave => $valor){
    if(is_array($valor)){ //Comprobamos si uno de los valores es array, si lo es, lo iteramos
        echo CHTML::dibujaEtiqueta("span", [], "$clave=>", true).PHP_EOL;
        foreach($valor as $key => $value){
            echo CHTML::dibujaEtiqueta("span", [], "\n$key => $value", true).PHP_EOL;
            echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;

        }
    }
    else{
        echo CHTML::dibujaEtiqueta("span", [], "\n$clave => $valor", true).PHP_EOL;
        echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;

    }
}

?>