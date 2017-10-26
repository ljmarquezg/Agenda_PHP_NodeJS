<?php
require('./conector.php');
/*enviar los parámertos de conexión mysqli*/
$con = new ConectorBD();
/*Conectarse a la base de datos agenda_db*/
$response['conexion'] = $con->initConexion($con->database);
if($response['conexion'] == 'OK'){
			$data['id'] = '"'.$_POST['id'].'"';
	    $data['fecha_inicio'] = '"'.$_POST['start_date'].'"';
	    $data['hora_inicio'] = '"'.$_POST['start_hour'].'"';
	    $data['fecha_finalizacion'] = '"'.$_POST['end_date'].'"';
	    $data['hora_finalizacion'] = '"'.$_POST['end_hour'].'"';

			if($data['id'] != 'undefined'){
				$resultado = $con->actualizarRegistro('eventos', $data, 'id ='.$data['id']); //Actualizar el registro que coincida con el id del evento a actualizar
				$response['msg'] = 'OK';
			}else{
				$response['msg'] = "Ha ocurrido un error al actualizar el evento";
			}
}else{
    /*Mostrar mensaje de error*/
    $response['msg'] = "Error en la comunicacion con la base de datos";
}
/*devolver el arreglo response en formato json*/
echo json_encode($response);
?>
