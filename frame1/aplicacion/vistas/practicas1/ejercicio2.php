<?php

    echo CHTML::dibujaEtiqueta("h4", [], "Vista para el ejercicio 2", true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;


    echo CHTML::dibujaEtiqueta("h1", [], "Ejercicio 2", true).PHP_EOL;

    //Enunciado del ejercicio
    echo CHTML::dibujaEtiqueta("p", [], "Simular el lanzamiento de un dado (6 veces) (usar un bucle for). Además contar el número de veces <br>" . PHP_EOL .
        "que aparece cada lado si se hicieran 1000 lanzamientos al estilo. Para generar el valor aleatorio usar <br>" . PHP_EOL .
        "rand. Repetir el ejercicio usando mt_rand sin parámetros. Se deben usar arrays para almacenar los <br> " . PHP_EOL .
        "resultados.<br>", true) . PHP_EOL;
    

    echo CHTML::dibujaEtiqueta("h2", [], "LANZAMIENTO DE UN DADO con rand", true).PHP_EOL;

    for ($cont = 0; $cont <= 5; $cont++) {//usamos el bucle for para simular que tiramos el dado 6 veces
        $aleatorio = rand(1,6); //inicializamos la variable aleatorio con el metodo rand, con un rango del 1 al 6
        echo CHTML::dibujaEtiqueta("span",[],"Lanzamiento ".  ($cont + 1) . " del dado: ". $aleatorio, true ).PHP_EOL;
        echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
        $d["dado"][$cont] =  $aleatorio; //el nº que haya salido lo guardamos en el array dado
    }

    echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;



    
    echo CHTML::dibujaEtiqueta("h2", [], "lanzado el dado 1000 veces", true).PHP_EOL;

    for ($cont = 0; $cont <= 999; $cont++) { //simulamos con bucle for que lanzamos un dado 1000 veces
        $aleatorio = rand(1,6); //inicializamos la variable aleatorio con el metodo rand, con un rango del 1 al 6
        $d["dado1000"][$cont] =  $aleatorio;  //el nº que haya salido lo guardamos en el array dado1000
    }

    
    for ($cont = 0; $cont <= 5; $cont++){ //recorremos con este bucle del 0 al 5, para mostrar el orden de cara de dados: 1,2,3,4,5,6
        for ($cont2 = 0; $cont2 <= (count($dado1000) - 1); $cont2++){ //con este bucle recorremos el array dado1000
            if (($cont + 1) == $dado1000[$cont2]){ //buscamos el nº de la cara del dado y si lo encontramos en el array
                $probabilidad ++; //lo contamos en la variable probabilidad
            }
        }
        echo CHTML::dibujaEtiqueta("span",[],"El ". ($cont + 1) ." ha salido ".   $probabilidad. ", con un porcentajes de " .  
                                            ( $probabilidad / 1000 ) * 100 ."%<br>", true ).PHP_EOL;
            $probabilidad = 0; //lo inicializamos a 0 para la siguiente cara del dado
    }
    


    /**************************************************************************************************** */




    echo CHTML::dibujaEtiqueta("h2", [], "LANZAMIENTO DE UN DADO con mt_rand", true).PHP_EOL;

    for ($cont = 0; $cont <= 5; $cont++) {//usamos el bucle for para simular que tiramos el dado 6 veces
        $aleatorio = (mt_rand() % 6) + 1; //en este caso usamos el metodo mt_rand
        echo CHTML::dibujaEtiqueta("span", [], "Lanzamiento ". ($cont +1). " del dado: ". $aleatorio . "<br>", true).PHP_EOL;
        $d["dado"][$cont] =  $aleatorio; //el nº que haya salido lo guardamos en el array dado
    }

    echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;



 
    echo CHTML::dibujaEtiqueta("h2", [], "lanzado el dado 1000 veces", true).PHP_EOL;

    for ($cont = 0; $cont <= 999; $cont++) { //simulamos con bucle for que lanzamos un dado 1000 veces
        $aleatorio = (mt_rand() % 6) + 1; //inicializamos la variable aleatorio con el metodo rand, con un rango del 1 al 6
        $dado1000[$cont] =  $aleatorio;  //el nº que haya salido lo guardamos en el array dado1000
    }

    
    for ($cont = 0; $cont <= 5; $cont++){ //recorremos con este bucle del 0 al 5, para mostrar el orden de cara de dados: 1,2,3,4,5,6
        for ($cont2 = 0; $cont2 <= (count($dado1000) - 1); $cont2++){ //con este bucle recorremos el array dado1000
            if (($cont + 1) == $dado1000[$cont2]){ //buscamos el nº de la cara del dado y si lo encontramos en el array
                $probabilidad ++; //lo contamos en la variable probabilidad
            }
        }

        echo  CHTML::dibujaEtiqueta("span", [], "El ". ($cont + 1). " ha salido ". 
                                                $probabilidad . ", con un porcentaje de ".
                                                 ($probabilidad / 1000) / 10 ."%", true).PHP_EOL;
        echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
        $probabilidad = 0; //lo inicializamos a 0 para la siguiente cara del dado
    }


    echo CHTML::dibujaEtiqueta("br",[],"", false ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("br",[],"", false ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("br",[], "",false ).PHP_EOL;


    //Habilitamos enlace a opción por defecto
    /**
     * Definir en la página por defecto del sitio un enlace a la acción anterior.
     */
    echo CHTML::botonHtml(CHTML::link("Volver atrás", $anterior)).PHP_EOL;
?>