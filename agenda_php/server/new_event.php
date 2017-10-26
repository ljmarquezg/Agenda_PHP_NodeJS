<?php

require('./conector.php');
/*enviar los parámertos de conexión mysqli*/
$con = new ConectorBD();
/*Conectarse a la base de datos agenda_db*/
$response['conexion'] = $con->initConexion($con->database);
if($response['conexion'] == 'OK'){
    /*Generar un arreglo con la información a enviar*/
    $data['titulo'] = '"'.$_POST['titulo'].'"';
    $data['fecha_inicio'] = '"'.$_POST['start_date'].'"';
    $data['hora_inicio'] = '"'.$_POST['start_hour'].':00"';/*Add ":00" to fill datetime format*/
    $data['fecha_finalizacion'] = '"'.$_POST['end_date'].'"';
    $data['hora_finalizacion'] = '"'.$_POST['end_hour'].':00"'; /*Add ":00" to fill datetime format*/
    $data['allday'] = $_POST['allDay'];
    $data['fk_usuarios'] = '"'.$_SESSION['email'].'"';

    /*Enviar los parámetros de inserción de información a la tabla eventos*/
    if($con->insertData('eventos', $data)){ //Insertar la información en la base de datos
        /*Mostrar mensaje success*/
        $resultado = $con->consultar(['eventos'],['MAX(id)']); //Obtener el id registrado perteneciente al nuevo registro
        while($fila = $resultado->fetch_assoc()){
          $response['id']=$fila['MAX(id)']; //Enviar ultimo Id guardado como parámetro para el calendario
        }
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
