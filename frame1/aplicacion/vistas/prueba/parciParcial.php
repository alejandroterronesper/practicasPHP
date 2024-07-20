<?php

echo CHTML::dibujaEtiqueta("div", ["class" => "parcial "],"", false);
foreach ($fila as $ele=>$dat){
    echo "posición $ele: valor $dat ";
}
echo CHTML::dibujaEtiquetaCierre("div");


?>