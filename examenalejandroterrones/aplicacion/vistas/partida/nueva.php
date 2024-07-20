<?php


echo CHTML::dibujaEtiqueta("h2", [], "Formulario", true).PHP_EOL;


echo CHTML::iniciarForm().PHP_EOL;
$prueba = $arrayNombresBarajas;
$objeto = $partida;


echo CHTML::modeloLabel($objeto, "cod_baraja", []).PHP_EOL;
echo CHTML::modeloListaDropDown($objeto, "cod_baraja", $arrayNombresBarajas, []).PHP_EOL;
echo CHTML::modeloError($objeto, "cod_baraja", []).PHP_EOL;
echo "<br>".PHP_EOL;

echo CHTML::modeloLabel($objeto, "fecha", []).PHP_EOL;
echo CHTML::modeloText($objeto, "fecha", []).PHP_EOL;
echo CHTML::modeloError($objeto, "fecha", []).PHP_EOL;

echo "<br>".PHP_EOL;

echo CHTML::modeloLabel($objeto, "mesa", []).PHP_EOL;
echo CHTML::modeloText($objeto, "mesa", []).PHP_EOL;
echo CHTML::modeloError($objeto, "mesa", []).PHP_EOL;

echo "<br>".PHP_EOL;

echo CHTML::modeloLabel($objeto, "crupier", []).PHP_EOL;
echo CHTML::modeloText($objeto, "crupier", []).PHP_EOL;
echo CHTML::modeloError($objeto, "crupier", []).PHP_EOL;
echo "<br>".PHP_EOL;

if(isset($errores["crupier"])){

    echo CHTML::dibujaEtiqueta("div", ["class" => "error"], "".$errores["crupier"][0], true).PHP_EOL;
   


}
echo "<br>".PHP_EOL;
echo CHTML::dibujaEtiqueta("span", ["for" => "numJugadores"], "Numero de jugadores: ", true).PHP_EOL;

// for ($cont = 1; $cont <= $valorMaximo; $cont ++){

//     echo CHTML::dibujaEtiqueta("label", [], "".$cont, true).PHP_EOL;
//     echo CHTML::campoRadioButton("".$cont, false, ["uncheckValor" => -1]);
// }

echo CHTML::campoListaRadioButton("max_juga", $datos["max_juga"], $totalJugadores, " ", ["uncheckValor" => -1] ).PHP_EOL;
echo "<br>".PHP_EOL;

if(isset($errores["max_juga"])){
echo CHTML::dibujaEtiqueta("div", ["class" => "error"], null, false).PHP_EOL;
    
    foreach($errores["max_juga"] as $cod => $error){

        echo $error.PHP_EOL;
        echo "<br>".PHP_EOL;
    }
}
echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

echo CHTML::campoBotonSubmit("Añadir",["name" => "formularioCrear", "style" => "text-align:center"]).PHP_EOL;

echo CHTML::finalizarForm();

?>