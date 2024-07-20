<?php


echo CHTML::iniciarForm("", "POST", ["class" => "formulario"]).PHP_EOL;

echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;
echo CHTML::dibujaEtiqueta("legend", [], "Formulario de registro", true).PHP_EOL;


//parametros
echo CHTML::modeloLabel($registro, "nick").PHP_EOL;
echo CHTML::modeloText($registro, "nick", ["maxlength" => 40])."<br>".PHP_EOL;
echo CHTML::modeloError($registro, "nick").PHP_EOL;


echo CHTML::modeloLabel($registro, "nif").PHP_EOL;
echo CHTML::modeloText($registro, "nif", ["maxlength" => 10])."<br>".PHP_EOL;
echo CHTML::modeloError($registro, "nif").PHP_EOL;

echo CHTML::modeloLabel($registro, "fecha_nacimiento").PHP_EOL;
echo CHTML::modeloText($registro, "fecha_nacimiento")."<br>".PHP_EOL;
echo CHTML::modeloError($registro, "fecha_nacimiento").PHP_EOL;


echo CHTML::modeloLabel($registro, "provincia").PHP_EOL;
echo CHTML::modeloText($registro, "provincia")."<br>".PHP_EOL;
echo CHTML::modeloError($registro, "provincia").PHP_EOL;

echo CHTML::modeloLabel($registro, "estado").PHP_EOL;
echo CHTML::modeloListaDropDown($registro, "estado",DatosRegistro::dameEstados(), [])."<br>".PHP_EOL;
echo CHTML::modeloError($registro, "estado").PHP_EOL;

echo CHTML::modeloLabel($registro, "contrasenia").PHP_EOL;
echo CHTML::modeloPassword($registro, "contrasenia")."<br>".PHP_EOL;
echo CHTML::modeloError($registro, "contrasenia").PHP_EOL;

echo CHTML::modeloLabel($registro, "confirmar_contrasenia").PHP_EOL;
echo CHTML::modeloPassword($registro, "confirmar_contrasenia")."<br>".PHP_EOL;
echo CHTML::modeloError($registro, "confirmar_contrasenia").PHP_EOL;


echo CHTML::campoBotonSubmit("Enviar").PHP_EOL;

echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;
echo CHTML::finalizarForm().PHP_EOL;





echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;
echo "<br>".PHP_EOL;
echo CHTML::botonHtml(CHTML::link("Volver atrás", ["inicial"]), ["class"=>"boton"]).PHP_EOL;

?>