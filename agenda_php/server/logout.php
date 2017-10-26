<?php

	session_start();
	if (isset($_SESSION['email'])) {
		session_destroy();
		$response['msg'] = 'Redireccionar';
	}else{
		$response['msg'] = 'Sesion no iniciada';
	}
	echo json_encode($response);
 ?>
