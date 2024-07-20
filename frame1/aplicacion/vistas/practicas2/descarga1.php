<?php
header("content-type: text/txt");
header("content-disposition: attachment; filename = descarga1.txt");

echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++";
echo  "\n". $mensaje1;

echo "\n".$mensaje2;

echo "\n++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++";

exit();

?>