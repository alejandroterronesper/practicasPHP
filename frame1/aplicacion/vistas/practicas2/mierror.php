<?php

Sistema::app()->paginaError(404,"no seas malo y no accedas a esta pagina");


echo CHTML::botonHtml(CHTML::link("Volver atrás", $anterior), ["class"=>"boton"]).PHP_EOL;
?>