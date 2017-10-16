<?php
  require('./conector.php');

  $con = new ConectorBD('localhost','t_general','123456');

  $response['msg'] = $con->initConexion('agenda_db');

  if($response['msg'] == 'OK'){
  	
  }

  echo json_encode($response);
 ?>
