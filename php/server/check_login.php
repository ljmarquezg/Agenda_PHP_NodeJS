<?php
  
  require('./conector.php');
  $con = new ConectorBD('localhost','user_agenda','123456');

  $response['conexion'] = $con->initConexion('agenda_db');

  if(isset($_SESSION['username'])){
    $response['acceso'] = "Usuario Autorizado";
  }else{
    
  if ($response['conexion']=='OK') {
    if($con->verifyUsers() > 0){

    $resultado_consulta = $con->consultar(['usuarios'],
    ['email', 'password'], 'WHERE email="'.$_POST['username'].'"');


    if ($resultado_consulta->num_rows != 0) {
      $fila = $resultado_consulta->fetch_assoc();
      if (password_verify($_POST['password'], $fila['password'])) {
      //if ($_POST['pass'] == $fila['password']) {
        $response['msg'] = 'Redireccionando';
        $response['acceso'] = 'Usuario Autorizado';
        $_SESSION['username']=$fila['email'];
      }else {
        $response['msg'] = 'Contraseña incorrecta';
        $response['acceso'] = 'Acceso rechazado';
      }
    }else{
      $response['msg'] = 'Email incorrecto';
      $response['acceso'] = 'Acceso rechazado';
    }
  }else{
    $response['acceso'] = 'No existen usuarios registrados';
    $response['msg'] = 'Presione el botón Inicializar Usuarios';
  }
  }else{
  	$response['conexion'] = 'Error al iniciar la conexion';
  }

  }
  echo json_encode($response);

  $con->cerrarConexion();
 ?>
