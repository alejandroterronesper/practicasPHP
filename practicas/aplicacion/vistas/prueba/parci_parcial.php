<?php

echo CHTML::dibujaEtiqueta("div", ["class"=>"parcial"],"",false);
foreach ($fila as $ele=>$dat)
echo "pos $ele: val $dat ";
echo CHTML::dibujaEtiquetaCierre("div"); 