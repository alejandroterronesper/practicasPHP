<?php


//Accedemos desde la global del main
$this->textoHead = CHTML::scriptFichero("/js/main.js", ["defer" => "defer"]);

?>
<label for="texto">nombre:</label><input type="text" name="texto" id="texto" maxlength="30" length="31">
<br>
<button id="ele">pedir</button>

<br><br>
<p id="pDatos"></p>