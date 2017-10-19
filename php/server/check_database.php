<?php /*requerir el archivo conector.php*/
require('./conector.php');
/*enviar los parámertos de conexión mysqli*/
$con = new ConectorBD();
/*Conectarse a la base de datos agenda_db*/

$response['msg'] = $con->verifyConexion();
//$response['msg'] = $con->initConexion('agenda_db');

if ($response['msg']=='OK') {
  return $response['msg'];
}else{
  return $response['msg'];
}

?>
