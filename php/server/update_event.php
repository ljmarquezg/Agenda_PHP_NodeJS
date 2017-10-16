<?php
require('./conector.php');
/*enviar los parámertos de conexión mysqli*/
$con = new ConectorBD('localhost', 'user_agenda', '123456');
/*Conectarse a la base de datos agenda_db*/
$response['conexion'] = $con->initConexion('agenda_db');
if($response['conexion'] == 'OK'){

	$data['id'] = '"'.$_POST['id'].'"';

    $data['fecha_inicio'] = '"'.$_POST['start_date'].'"';
    $data['hora_inicio'] = '"'.$_POST['start_hour'].'"';
    $data['fecha_finalizacion'] = '"'.$_POST['end_date'].'"';
    $data['hora_finalizacion'] = '"'.$_POST['end_hour'].'"';

	$resultado = $con->actualizarRegistro('eventos', $data, 'id ='.$data['id']);


	$response['msg'] = 'OK';
}else{
    /*Mostrar mensaje de error*/
    $response['msg'] = "Error en la comunicacion con la base de datos";
}
/*devolver el arreglo response en formato json*/
echo json_encode($response);
?>