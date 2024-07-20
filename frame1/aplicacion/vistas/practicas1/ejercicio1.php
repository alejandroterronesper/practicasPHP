<?php


    echo CHTML::dibujaEtiqueta("h4", [], "Vista para el ejercicio 1", true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;

    echo CHTML::dibujaEtiqueta("h1", [], "Ejercicio 1", true).PHP_EOL;

    //Enunciado del ejercicio
    echo CHTML::dibujaEtiqueta("p", [], "Mostrar el funcionamiento de diversas funciones Matemáticas (round, floor, pow, sqrt, entero a <br>". PHP_EOL.
                                        "hexadecimal, de base 4 a base 8) (buscar la información sobre las funciones matemáticas en <br>" .PHP_EOL.
                                        "http://php.net/manual/es/book.math.php). Definir variables inicializadas con valores en binario, octal y <br>" .PHP_EOL.
                                        "hexadecimal. Mostrar el valor de esas variables<br>", true).PHP_EOL;

    


    //comprobamos los diferentes métodos
    echo CHTML::dibujaEtiqueta("h4", [], "Uso del método ROUND", true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroRound con método Round a la alza: ". round($numeroRound,  $precision = 1,  $mode = PHP_ROUND_HALF_UP) ."<br>", true ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroRound con método Round a la baja: ". round($numeroRound,  $precision = 0,  $mode = PHP_ROUND_HALF_DOWN) ."<br>", true ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroRound2 con método Round a la alza: ". round($numeroRound2,  $precision = 2,  $mode = PHP_ROUND_HALF_UP) ."<br>", true ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroRound2 con método Round a la baja: ". round($numeroRound2,  $precision = 1,  $mode = PHP_ROUND_HALF_DOWN) ."<br>", true ).PHP_EOL;
    

    
    

    echo CHTML::dibujaEtiqueta("h4", [], "Uso del método FLOOR", true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroFloor con método Floor: ". floor( $numeroFloor) ."<br>", true ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroFloor2 con método Floor: ". floor( $numeroFloor2) ."<br>", true ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroFloor3 con método Floor: ". floor( $numeroFloor3) ."<br>", true ).PHP_EOL;



    echo CHTML::dibujaEtiqueta("h4", [], "Uso del método POW", true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroPow con método POW (elevado a 4): ". pow($numeroPow,4) ."<br>", true ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroPow2 con método POW (elevado a 6): ". pow($numeroPow2,6) ."<br>", true ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroPow3 con método POW (elevado a -2): ". pow($numeroPow3,-2) ."<br>", true ).PHP_EOL;



    echo CHTML::dibujaEtiqueta("h4", [], "Uso del método SQRT", true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroRaiz con método SQRT: ". sqrt($numeroRaiz) ."<br>", true ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroRaiz2 con método SQRT: ". sqrt($numeroRaiz2) ."<br>", true ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroRaiz3 con método SQRT: ". sqrt($numeroRaiz3) ."<br>", true ).PHP_EOL;




    echo CHTML::dibujaEtiqueta("h4", [], "Uso del método HEXDEC", true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroHexa con método HEXDEC: ". hexdec($numeroHexa) ."<br>", true ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroHexa2 con método HEXDEC: ". hexdec($numeroHexa2)."<br>", true ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroHexa3 con método HEXDEC: ". hexdec($numeroHexa3) ."<br>", true ).PHP_EOL;


   
    echo CHTML::dibujaEtiqueta("h4", [], "Uso del método BASE_CONVERT", true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroBase4a con método BASE_CONVERT (de base 4 a base 8): ". base_convert($numeroBase4a,4,8) ."<br>", true ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroBase4b con método BASE_CONVERT (de base 4 a base 8): ". base_convert($numeroBase4b,4,8)."<br>", true ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroBase4c con método BASE_CONVERT (de base 4 a base 8): ". base_convert($numeroBase4c,4,8) ."<br>", true ).PHP_EOL;



    echo CHTML::dibujaEtiqueta("h4", [], "Uso del método bindec, octdec, hexdec", true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroBinario con método bindec (de binario a decimal): ". bindec($numeroBinario) ."<br>", true ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroOctal con método octdec (de octal a decimal): ". octdec($numeroOctal) ."<br>", true ).PHP_EOL;
    echo CHTML::dibujaEtiqueta("span",[],"Número original: $numeroHexadecimal con método hexdec (de hexa a decimal): ". hexdec($numeroHexadecimal) ."<br>", true ).PHP_EOL;

    echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;


    

    //Habilitamos enlace a opción por defecto
    /**
     * Definir en la página por defecto del sitio un enlace a la acción anterior.
     */
    echo CHTML::botonHtml(CHTML::link("Volver atrás", $anterior), ["class"=>"boton"]).PHP_EOL;

?>




