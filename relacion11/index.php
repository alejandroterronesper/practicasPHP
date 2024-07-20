<?php
include_once(dirname(__FILE__) . "/cabecera.php");



$_posPagina = [
    [
        "texto" => "inicio",
        "url" => "/"
    ],
];

//Al ir al index, nos va a redirigir a aplicacion/catastro/index.php
//que es donde estan los diferentes enlaces
header("location: aplicacion/catastro/index.php");

inicioCabecera("Inicio");
cabecera();
finCabecera();
inicioCuerpo("Relacion 11", $_posPagina);
cuerpo();
finCuerpo();


// **********************************************************
function cabecera()
{
}
function cuerpo()
{
?>


    <?php
}
