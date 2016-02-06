<?php  
	include_once "comodo.php";

	$obj = new Comodo("localhost","root","root","test");
	$obj->read("usuarios");
?>