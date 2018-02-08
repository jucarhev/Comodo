<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Ejemplo</title>
	<link rel="stylesheet" href="">
</head>
<body>
	<form action="test.php" method="POST">
		Nombre<input type="text" name="nombre" id=""><br>
		Apellido<input type="text" name="apellido"><br>
		Edad<input type="text" name="edad">
		<input type="submit" value="Guardar">
	</form>

	<?php 
		include "Comodo.php";
		$obj = new Comodo("localhost","root","lenov35","test");
		$data = array('id','test');
		echo $obj->read("test", "border='1'");
	?>
</body>
</html>