<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

//Al ir al index de relacion11
//Siempre se nos redirige al index de catastro

$_posPagina = [
    [
        "texto" => "catastro",
        "url" => "/aplicacion/catastro/index.php"
    ],
];

inicioCabecera("Inicio");
cabecera();
finCabecera();
inicioCuerpo("Catastro", $_posPagina);
cuerpo();
finCuerpo();


// **********************************************************
function cabecera()
{
}
function cuerpo()
{
?>
    <!--Enlaces de catastro -->
    <ul>
        <li><a href="consulta_municipios.php">Consultar municipios</a></li>
        <li><a href="consulta_por_datos.php">Consultar por datos</a></li>
        <li><a href="consulta_por_datos_ajax.php">Consultar por datos por AJAX</a></li>
        <li><a href="consulta_rustica.php">Consulta rústica</a></li>
    </ul>

    
    
    <?php
}
