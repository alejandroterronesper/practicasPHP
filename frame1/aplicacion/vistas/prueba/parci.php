<?php

//Aqui llamar a dibujaVistaParcial cuantoas veces sean necesario
//En una vista no llamar a una vista, solo a vistaParcial
 
 echo "los datos que me han pasado son: <br>".PHP_EOL ; 

 foreach ($datos as $fila){
    $this->dibujaVistaParcial("parciParcial", ["fila"=>$fila]);
 }


?>