<?php
  require('./conector.php'); //Requiere el archivo conector.php
  $con = new ConectorBD(); //Iniciar el objeto ConectorBD
  $usuarios = new Usuarios(); //Iniciar el objeto Usuario
  $eventos = new Eventos(); //Iniciar el objeto Eventos

try { //Iniciar la función crear tabla usuarios con la información del objeto Usuarios
    $con->createTable($usuarios->nombreTabla, $usuarios->data);
    $response['msg'] = 'OK';
} catch(PDOException $e) {
    $response['msg'] = 'Error';
    $response['detalle'] = $e->getMessage();//Remove or change message in production code
}

try { //Iniciar la función crear tabla createTable con la información del objeto Eventos
    $con->createTable($eventos->nombreTabla, $eventos->data);
    $response['msg'] = 'OK';
} catch(PDOException $e) {
    $response['msg'] = 'Error';
    $response['detalle'] = $e->getMessage();//Remove or change message in production code
}

try { //Crear un Índice (index) en la columna fk_usuarios de la tabla eventos
    $con->nuevaRestriccion($eventos->nombreTabla, 'ADD KEY fk_usuarios (fk_usuarios)');
    $response['msg'] = 'OK';
} catch(PDOException $e) {
    $response['msg'] = 'Error';
    $response['detalle'] = $e->getMessage();//Remove or change message in production code
}


try { //Crear una relación entre las tablas eventos y usuarios asignando a la tabla eventos el valor email a través de una clave foránea
    $con->nuevaRelacion($eventos->nombreTabla, $usuarios->nombreTabla, 'fk_usuarios', 'email');
    $response['msg'] = 'OK';
} catch(PDOException $e) {
    $response['msg'] = 'Error';
    $response['detalle'] = $e->getMessage();//Remove or change message in production code
}

  echo json_encode($response); //Devolver resultado
?>
