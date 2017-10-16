<?php

require('./conector.php');
/*enviar los parámertos de conexión mysqli*/
$con = new ConectorBD('localhost', 'user_agenda', '123456');
/*Conectarse a la base de datos agenda_db*/
$response['conexion'] = $con->initConexion('agenda_db');
if($response['conexion'] == 'OK'){
    /*Generar un arreglo con la información a enviar*/
    $data['titulo'] = '"'.$_POST['titulo'].'"';
    $data['fecha_inicio'] = '"'.$_POST['start_date'].'"';
    $data['hora_inicio'] = '"'.$_POST['start_hour'].'"';
    $data['fecha_finalizacion'] = '"'.$_POST['end_date'].'"';
    $data['hora_finalizacion'] = '"'.$_POST['end_hour'].'"';
    $data['allday'] = $_POST['allDay'];
    $data['fk_usuarios'] = '"'.$_SESSION['username'].'"';

    /*Enviar los parámetros de inserción de información a la tabla eventos*/
    if($con->insertData('eventos', $data)){
        /*Mostrar mensaje success*/
        $response['msg'] = 'OK';
    }else{
        /*Mostrar mensaje de error*/
        $response['msg'] = "Ha ocurrido un error al guardar el evento";
    }
}else{
    /*Mostrar mensaje de error*/
    $response['msg'] = "Error en la comunicacion con la base de datos";
}
/*devolver el arreglo response en formato json*/
echo json_encode($response);
?>
