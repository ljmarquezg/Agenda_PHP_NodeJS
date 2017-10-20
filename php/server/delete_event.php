<?php

require('./conector.php');
/*enviar los parámertos de conexión mysqli*/
$con = new ConectorBD();
/*Conectarse a la base de datos agenda_db*/
$response['conexion'] = $con->initConexion($con->database);
if($response['conexion'] == 'OK'){
    /*Generar un arreglo con la información a enviar*/
    if($con->eliminarRegistro('eventos', 'id='.$_POST['id'])){
        /*Mostrar mensaje success*/
        $response['msg'] = 'OK';
    }else{
        /*Mostrar mensaje de error*/
        $response['msg'] = "Ha ocurrido un error al Eliminar el evento";
    }
}else{
    /*Mostrar mensaje de error*/
    $response['msg'] = "Error en la comunicacion con la base de datos";
}
/*devolver el arreglo response en formato json*/
echo json_encode($response);
?>
