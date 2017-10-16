<?php
	require('./conector.php');

	$con = new ConectorBD('localhost','t_general','123456');

	$response['conexion'] = $con->initConexion('agenda_db');

  	if ($response['conexion']=='OK') {
  		
  	}
?>