<?php

echo CHTML::iniciarForm("", "POST", ["class" => "formulario"]).PHP_EOL;

echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;
echo CHTML::dibujaEtiqueta("legend", [], "Formulario de login", true).PHP_EOL;


echo CHTML::modeloLabel($logeo, "nick").PHP_EOL;
echo CHTML::modeloText($logeo, "nick", ["maxlength" => 20])."<br>".PHP_EOL;
echo CHTML::modeloError($logeo, "nick").PHP_EOL;

echo CHTML::modeloLabel($logeo, "contrasenia").PHP_EOL;
echo CHTML::modeloPassword($logeo, "contrasenia", ["maxlength" => 20])."<br>".PHP_EOL;
echo CHTML::modeloError($logeo, "contrasenia").PHP_EOL;


echo CHTML::campoBotonSubmit("Logearse").PHP_EOL;

echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
echo CHTML::finalizarForm().PHP_EOL;



echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;
echo CHTML::botonHtml(CHTML::link("Volver atrás", ["inicial"]), ["class"=>"boton"]).PHP_EOL;

?> 