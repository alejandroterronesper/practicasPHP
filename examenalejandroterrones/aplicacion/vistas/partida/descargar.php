<?php


$prueba = $objeto;

header("content-type:  application/xml");
header("content-disposition: attachment; filename = partida.xml");

$cadenaXML = "<parti>
                <cod_partida> " .$objeto->cod_partida . "</cod_partida>
                <mesa>" .$objeto->mesa . "  </mesa>
                <fecha>" .$objeto->fecha . " </fecha>
                <cod_baraja> " .$objeto->cod_baraja . " </cod_baraja>
                <nombre_baraja>  " .$objeto->nombre_baraja . " </nombre_baraja>    
                <jugadores>  " .$objeto->jugadores . " </jugadores> 
                <crupier>  " .$objeto->crupier . " </crupier>                             
        </parti>";

echo $cadenaXML;

exit();

?>