<?php
$user="root";
$password="bimbo1234";
$server="localhost";
$bd="app";


$cadena=mysql_connect($server,$user,$password,$bd)or die("Error en la conexion: ".mysql_error());
mysql_select_db($bd, $cadena);
?>
