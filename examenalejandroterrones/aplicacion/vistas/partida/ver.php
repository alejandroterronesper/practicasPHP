<?php


echo CHTML::link("NUEVA PARTIDA  " . CHTML::imagen("/imagenes/16x16/nuevo.png", "",
 ["title" => "Nueva partida"]), Sistema::app()->generaURL(["partida","nueva"])).PHP_EOL;


//formulario
echo CHTML::iniciarForm().PHP_EOL;

echo CHTML::dibujaEtiqueta("label", ["for" => "crupier"], "Crupiers disponibles", true).PHP_EOL;
echo CHTML::campoListaDropDown("crupier", $datos["crupier"], $arrayCrupiers, []).PHP_EOL;

echo "<br>".PHP_EOL;
echo CHTML::campoBotonSubmit("Aceptar",["name" => "formularioPartida"]).PHP_EOL;

echo CHTML::finalizarForm();


//vistas
if (count($arrayCrupierPersona) > 0){

    //vitas parciales
    foreach ($arrayCrupierPersona as $codigo => $persona){
        echo $this->dibujaVistaParcial("parcialCrupier", 
        ["persona" => $persona, "codigo" => $codigo], true).PHP_EOL;
    }

}





?>