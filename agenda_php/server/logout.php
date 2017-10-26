<?php

	session_start(); //Iniciar manejador de sesiones
	if (isset($_SESSION['email'])) { //Si existe sesion iniciada
		session_destroy(); //Destruir la sesion
		$response['msg'] = 'Redireccionar'; //Redireccionar
	}else{
		$response['msg'] = 'Sesion no iniciada'; //Mostrar mensaje
	}
	echo json_encode($response); //Devolver respuesta
 ?>
