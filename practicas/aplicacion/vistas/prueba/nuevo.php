<H1>ALTA DE UN ARTICULO</H1>
<br />
<?php

    echo CHTML::modeloErrorSumario($modelo, []).PHP_EOL;


    echo CHTML::dibujaEtiqueta("fieldset", [], null, false).PHP_EOL;
    
    echo CHTML::dibujaEtiqueta("legend", [], "Datos del articulo", true).PHP_EOL;
    echo CHTML::iniciarForm().PHP_EOL;
    echo CHTML::modeloLabel($modelo, "nombre").PHP_EOL;
    echo CHTML::modeloText(
        $modelo,
        "nombre",
        array("maxlength" => 30, "size" => 31)
    ).PHP_EOL;
    echo CHTML::modeloError($modelo, "nombre").PHP_EOL;
    echo "<br>".PHP_EOL;
    echo CHTML::modeloLabel($modelo, "descripcion").PHP_EOL;
    echo CHTML::modeloText(
        $modelo,
        "descripcion",
        array("maxlength" => 60, "size" => 61)
    ).PHP_EOL;
    echo CHTML::modeloError($modelo, "descripcion").PHP_EOL;
    echo "<br>".PHP_EOL;
    echo CHTML::modeloLabel($modelo, "cod_fabricante").PHP_EOL;
    echo CHTML::modeloListaDropDown(
        $modelo,
        "cod_fabricante",
        Articulos::listaFabricantes(),
        array("linea" => "Selecciona el fabricante")
    ).PHP_EOL;
    echo CHTML::modeloError($modelo, "cod_fabricante").PHP_EOL;
    echo "<br>".PHP_EOL;
    echo CHTML::modeloLabel($modelo, "fecha_alta").PHP_EOL;
    echo CHTML::modeloText(
        $modelo,
        "fecha_alta",
        array("maxlength" => 10, "size" => 11)
    ).PHP_EOL;
    echo CHTML::modeloError($modelo, "fecha_alta").PHP_EOL;
    echo "<br>".PHP_EOL;

    echo CHTML::modeloLabel($modelo, "cod_fabricante");
    echo CHTML::modeloListaRadioButton($modelo, "cod_fabricante", Articulos::listaFabricantes(), "", array("uncheckValor"=>-1)).PHP_EOL;
    echo "<br>".PHP_EOL;


    //Sin usar el modelo
    echo CHTML::campoLabel("Nombre: ", "minombre") . PHP_EOL;
    echo CHTML::campoText("minombre", "Valor por defecto", ["size" => 15, "id" => "nom"]) . PHP_EOL;
    echo "<br>" . PHP_EOL;


    //NUMERO DE EXISTENCIAS
    echo CHTML::modeloLabel($modelo, "existencias").PHP_EOL;
    echo CHTML::modeloText($modelo, "existencias", ["style"=>"border-radius: 20px"])."<br>".PHP_EOL;



    
    echo CHTML::campoBotonSubmit("Crear").PHP_EOL;
    echo CHTML::finalizarForm().PHP_EOL;


    echo CHTML::dibujaEtiquetaCierre("fieldset").PHP_EOL;

?>