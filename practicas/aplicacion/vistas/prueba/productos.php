<?php
$tabla = new CGrid($cab, $fil, ["class" => "tabla1"]);

$paginador = new CPager($opcPaginador);


echo $paginador->dibujate();

echo $tabla->dibujate();


echo "<br><br><br>".PHP_EOL;


echo $tabla->dibujate();

echo "<br><br><br>".PHP_EOL;

echo $tabla->dibujate();


?>