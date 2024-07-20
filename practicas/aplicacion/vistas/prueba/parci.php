<?php

    echo "los datos que me han pasado son: <br>".PHP_EOL;
    foreach($datos as $fila)
       {
         $this->dibujaVistaParcial("parci_parcial",["fila"=>$fila]);
       }