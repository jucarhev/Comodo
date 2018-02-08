<?php  
	include_once "Comodo.php";
	$obj = new Comodo("localhost","root","lenov35","test");
/*
	$nombe = $_POST['nombre'];
	$apellido = $_POST['apellido'];
	$edad = $_POST['edad'];
*/
	// guardar datos
	//$data = array('test' => 'Test2');
	//echo $obj->create("test", $data);

	//Eliminar dato
	//echo $obj->delete('test','id=1');

	//Truncar una tabla de base de datos
	//$obj->truncate('test');

	//Update table
	//$array = array('test'=>'Test1');
	//$obj->update('test',$array,'id=2');

	$datos=$obj->read('select * from test');
	foreach ($datos as $row):
		echo $row['id'];
	endforeach;

//	echo $obj->read("usuarios", "border=1");
?>