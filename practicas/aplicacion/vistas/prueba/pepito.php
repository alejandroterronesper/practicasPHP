<?php

$this->textoHead="<script>alert('mensaje');</script>";

echo CHTML::dibujaEtiqueta("div",["class"=>"error"],"esto va en un div",false).PHP_EOL;
echo "y demás cosas ".CHTML::imagen("/imagenes/error_320x320.png");
echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
echo CHTML::boton("pulsa el boton",["id"=>"accion"]).PHP_EOL;




echo "Esto es lo que va en la vista pepito";
echo "<br>";
echo $uno;
echo "<br>".PHP_EOL;
echo "estas en el grupo ".Sistema::app()->grupo."<br>".PHP_EOL;

echo Sistema::app()->generaURL(["inicial","otra"],["id"=>12,"nombre"=>"vicente"])."<br>".PHP_EOL;
echo "<br>";
echo "<a href=\"".Sistema::app()->generaURL(["inicial","otra"],["id"=>12,"nombre"=>"vicente"]).
      "\">direccion</a>"."<br>".PHP_EOL;

echo CHTML::link("texto enlace",["inicial"],["id"=>24, "class"=>"prueba", "style"=>"background-color:yellow;"])."<br>".PHP_EOL;
echo CHTML::link("ies pedro espinosa","https://www.iespedroespinosa.es",["id"=>24, "class"=>"prueba", "style"=>"background-color:yellow;"])."<br>".PHP_EOL;
echo CHTML::link("ies pedro espinosa",
                Sistema::app()->generaURL(["inicial","otra"],["id"=>12,"nombre"=>"vicente"]),
                ["id"=>24, "class"=>"prueba", "style"=>"background-color:yellow;"])."<br>".PHP_EOL;

