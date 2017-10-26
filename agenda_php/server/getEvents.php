<?php
/*requerir el archivo conector.php*/
require('./conector.php');
/*enviar los parámertos de conexión mysqli*/
$con = new ConectorBD();
/*Conectarse a la base de datos agenda_db*/
$response['msg'] = $con->initConexion($con->database);

if ($response['msg']=='OK') {
  $resultado = $con->consultar(['eventos'],['*'], "WHERE fk_usuarios ='".$_SESSION['email']."'",'');
  /*Crear un arreglo asociativo con los objetos obtenidos*/
  $i = 0;

  /*Recorrer el arreglo de resultados*/
  while($fila = $resultado->fetch_assoc()){
    $response['eventos'][$i]['id']=$fila['id'];
    $response['eventos'][$i]['title']=$fila['titulo'];
    if ($fila['allday'] == 0){ /*Verificar si el registro es fullday*/
      $allDay = false;
       /*Si no es full day, agregar hora de inicio al parametro start*/
      $response['eventos'][$i]['start']=$fila['fecha_inicio'].'T'.$fila['hora_inicio'];
      /*Si no es full day, agregar hora de inicio al parametro end*/
      $response['eventos'][$i]['end']=$fila['fecha_finalizacion'].'T'.$fila['hora_finalizacion'];
    }else{
      $allDay= true;
       /*Si no es full day, no agregar la hora en el parametro start*/
      $response['eventos'][$i]['start']=$fila['fecha_inicio'];
       /*Si no es full day, el parametro end debe ser vacio*/
      $response['eventos'][$i]['end']="";
    }


    $response['eventos'][$i]['allDay']=$allDay;
    /*sumar 1 al contador*/
    $i++;
  }
  /*Devolver respuesta positiva al obtener registros*/
 $response['getData'] = "OK";
}
/*devolver el arreglo response en formato json*/
echo json_encode($response);
?>
