<?php

echo CHTML::dibujaEtiqueta("h4", [], "Vista para el ejercicio 3", true).PHP_EOL;
echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;


echo CHTML::dibujaEtiqueta("h1", [], "Ejercicio 3", true).PHP_EOL;
echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;



echo CHTML::dibujaEtiqueta("p", [], "Array. Hacer: <br>
        a) Crear una variable de tipo array que se llame Vector<br>
        b) Rellenar las posiciones 1, 16, 54 con los valores que quieras.<br>
        c) Añadir el valor 34 al final<br>
        d) Añadir los valores “cadena”, true, 1.345 en las posiciones “uno”, “dos” y “tres”<br>
        e) Rellenar la posición “ultima” con el array (1,34,”nueva”)<br>
        <br>
        - Hacer lo anterior usando una sola sentencia con array;<br>
        - Hacer lo anterior usando una sola sentencia con []<br>
        - Recorrer los tres arrays usando foreach<br>
        ", true)."<br>".PHP_EOL;

echo  CHTML::dibujaEtiqueta("p", ["style" => " font-weight: bold"], "Recorremos array Vector:", true).PHP_EOL;

//Recorremos VECTOR
foreach ($vector as $valor) {
    if (gettype($valor) != "array") { //para comprar si es un array
        echo CHTML::dibujaEtiqueta("span", [], $valor, true)."<br>".PHP_EOL;
    } else {
        foreach ($valor as $arrayVector) { //si Vector contiene un objeto tipo array, lo volvemos a recorrer
            echo CHTML::dibujaEtiqueta("span", [], $arrayVector, true)."<br>".PHP_EOL;

        }
    }
}

echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
echo  CHTML::dibujaEtiqueta("p", ["style" => " font-weight: bold"], "Recorremos array ():", true).PHP_EOL;
//Recorremos array ()
foreach($array1 as $valor){
        if (gettype($valor) != "array"){ //para comprar si es un array
                echo CHTML::dibujaEtiqueta("span", [], $valor, true)."<br>".PHP_EOL;

        }
        else{
                foreach($valor as $arrayVector){ //si Vector contiene un objeto tipo array, lo volvemos a recorrer
                        echo CHTML::dibujaEtiqueta("span", [], $arrayVector, true)."<br>".PHP_EOL;

                }
        }  
}



echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
echo  CHTML::dibujaEtiqueta("p", ["style" => " font-weight: bold"], "Recorremos array []:", true).PHP_EOL;



//Recorremos array []
foreach($array2 as $valor){
        if (gettype($valor) != "array"){ //para comprar si es un array
                echo CHTML::dibujaEtiqueta("span", [], $valor, true)."<br>".PHP_EOL;
        }
        else{
                foreach($valor as $arrayVector){ //si Vector contiene un objeto tipo array, lo volvemos a recorrer
                        echo CHTML::dibujaEtiqueta("span", [], $arrayVector, true)."<br>".PHP_EOL;
                }
        }  
}


echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;



//Habilitamos enlace a opción por defecto
/**
 * Definir en la página por defecto del sitio un enlace a la acción anterior.
 */
echo CHTML::botonHtml(CHTML::link("Volver atrás", $anterior)).PHP_EOL;
?>