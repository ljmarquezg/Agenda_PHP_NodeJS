<?php
	require('./conector.php');
	$con = new ConectorBD();
	echo $con->userSession(); //Devolver respuesta
?>
