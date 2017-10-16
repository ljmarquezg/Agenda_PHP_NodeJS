<?php
  	require('./conector.php');

    $con = new ConectorBD('localhost', 'user_agenda', '123456');

    $response['msg'] = $con->initConexion('agenda_db');

    if($response['msg'] == 'OK'){
    	$data['titulo'] = '"'.$_POST['titulo'].'"';
    	$data['fecha_inicio'] = '"'.$_POST['start_date'].'"';
    	$data['hora_inicio'] = '"'.$_POST['start_hour'].'"';
    	$data['fecha_finalizacion'] = '"'.$_POST['end_date'].'"';
    	$data['hora_finalizacion'] = '"'.$_POST['end_hour'].'"';
    	$data['allDay'] = $_POST['allDay'];
    	$data['fk_usuarios'] = '"'.$_POST['end_hour'].'"';
    	$con->insertData('eventos', $data);
    	
    }else{

    }
    echo json_encode($response);
 ?>
