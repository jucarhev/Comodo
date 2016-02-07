<?php  
	include_once "comodo/comodo.php";
	$obj = new Comodo("localhost","root","root","test");

	$nombe = $_POST['nombre'];
	$apellido = $_POST['apellido'];
	$edad = $_POST['edad'];

	$data = array('nombre' => $nombe,'apellido'=>  $apellido, 'edad'=>$edad);

	$obj->create("usuarios", $data);

	echo $obj->read("usuarios", "border=1");
?>