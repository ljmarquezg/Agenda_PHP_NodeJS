<?php
/*requerir el archivo conector.php*/
require('./conector.php');
$con = new ConectorBD(); //Iniciar el objeto ConectorBD
$response['msg'] = $con->verifyConexion();//Iniciar la función verifyConexion
return $response['msg']; //Devolver resultado


?>
