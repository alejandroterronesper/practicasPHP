<?php

echo CHTML::dibujaEtiqueta("h4", [], "Vista para el ejercicio 7", true).PHP_EOL;
echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;


echo CHTML::dibujaEtiqueta("h1", [], "Ejercicio 7", true).PHP_EOL;
echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;



    //enunciado
    echo CHTML::dibujaEtiqueta("p", [], "7.- Mostrar funcionamiento de las fechas. Se harán todos los apartados usando la serie de funciones para<br>
    gestión de fecha. Se repetirán todos los ejercicios usando la clase DateTime.<br>
    - Mostrar la fecha actual en el formato “d/m/Y”<br>
    - Mostrar la fecha actual en el formato “dia d de mmmm de yyyy, dia de la semana dd”.<br>
    - Mostrar la hora actual en el formato “hh:mm:ss.mmm”<br>
    - Mostrar los tres apartados anteriores para la fecha 29/3/2012 a 12:45.<br>
    - Mostrar los tres apartados anteriores para la fecha actual menos 12 días y 4 horas;<br>
    ", true)."<br>".PHP_EOL;

    
    echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;


    echo CHTML::dibujaEtiqueta("h2", [], "Usando dateTime", true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("h2", [], "Fecha actual", true).PHP_EOL;


    echo  CHTML::dibujaEtiqueta("span", [], $ahora->format($cadenaFecha1), true)."<br>".PHP_EOL;//“d/m/Y”
    echo  CHTML::dibujaEtiqueta("span", [], $ahora->format($cadenaFecha2), true)."<br>".PHP_EOL; //“dia d de mmmm de yyyy, dia de la semana dd”
    echo  CHTML::dibujaEtiqueta("span", [], $ahora ->format($cadenaFecha3), true)."<br>".PHP_EOL; //hh:mm:ss.mmm

  
    echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("h2", [], "Fecha 29/3/2012", true).PHP_EOL;
    echo  CHTML::dibujaEtiqueta("span", [], $fechaMarzoDateTime->format($cadenaFecha1), true)."<br>".PHP_EOL; //“d/m/Y”
    echo  CHTML::dibujaEtiqueta("span", [],  $fechaMarzoDateTime->format($cadenaFecha2), true)."<br>".PHP_EOL; //“dia d de mmmm de yyyy, dia de la semana dd”
    echo  CHTML::dibujaEtiqueta("span", [], $fechaMarzoDateTime->format($cadenaFecha3), true)."<br>".PHP_EOL;//hh:mm:ss.mmm


    echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("h2", [], "Fecha actual menos 12 dias y 4 horas", true).PHP_EOL;
    echo  CHTML::dibujaEtiqueta("span", [], $dateTimeMenos12->format($cadenaFecha1), true)."<br>".PHP_EOL; //“d/m/Y”
    echo  CHTML::dibujaEtiqueta("span", [], $dateTimeMenos12->format($cadenaFecha2), true)."<br>".PHP_EOL; //“dia d de mmmm de yyyy, dia de la semana dd”
    echo  CHTML::dibujaEtiqueta("span", [], $dateTimeMenos12 ->format($cadenaFecha3), true)."<br>".PHP_EOL; //hh:mm:ss.mmm

    echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("h2", [], "Usando funciones date, strotime", true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("h4", [], "Fecha actual", true).PHP_EOL;
    echo  CHTML::dibujaEtiqueta("span", [], $ahoraDate, true)."<br>".PHP_EOL; //“d/m/Y”
    echo  CHTML::dibujaEtiqueta("span", [], $ahoraDate2, true)."<br>".PHP_EOL; //“dia d de mmmm de yyyy, dia de la semana dd”
    echo  CHTML::dibujaEtiqueta("span", [], $ahoraDate3, true)."<br>".PHP_EOL; //hh:mm:ss.mmm


    echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("h4", [], "Fecha 29/3/2012", true).PHP_EOL;
    echo  CHTML::dibujaEtiqueta("span", [], date($cadenaFecha1,$fechaMarzoDate), true)."<br>".PHP_EOL; //“d/m/Y”
    echo  CHTML::dibujaEtiqueta("span", [], date($cadenaFecha2,$fechaMarzoDate), true)."<br>".PHP_EOL; //“dia d de mmmm de yyyy, dia de la semana dd”
    echo  CHTML::dibujaEtiqueta("span", [], date($cadenaFecha3,$fechaMarzoDate), true)."<br>".PHP_EOL; //hh:mm:ss.mmm





    echo CHTML::dibujaEtiqueta("h4", [], "Fecha actual menos 12 dias y 4 horas", true).PHP_EOL;
    echo  CHTML::dibujaEtiqueta("span", [], date ($cadenaFecha1, $dateMenos12), true)."<br>".PHP_EOL; //“d/m/Y”
    echo  CHTML::dibujaEtiqueta("span", [],  date($cadenaFecha2,$dateMenos12), true)."<br>".PHP_EOL;//“dia d de mmmm de yyyy, dia de la semana dd”
    echo  CHTML::dibujaEtiqueta("span", [], date($cadenaFecha3,$dateMenos12), true)."<br>".PHP_EOL; //hh:mm:ss.mmm

    echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;
    echo CHTML::dibujaEtiqueta("br", [], null, true).PHP_EOL;


    //Habilitamos enlace a opción por defecto
    echo CHTML::botonHtml(CHTML::link("Volver atrás", $anterior)).PHP_EOL;
?>