<?php
require('./conector.php');
$con = new ConectorBD();
if($con->initConexion($con->database) == '1049'){ //Si no existe la base de datos
  $conexion['msg'] = "Creando base de datos ".$con->database;
  $database = $con->newDatabase();
    if ($database != "OK"){
      $conexion['msg'] = "<h6><b>Error de privilegios</b></h6></br>El usuario <b>'$con->user'</b> no posee la permisología requerida para crear la base de datos <b>$con->database</b>. Si desea crear automaticamente la base de datos, ingrese los parámetros de un usuario phpmyadmin con permisos para crear bases de datos en las variables usuario <b>\$user </b> y contraseña <b>\$password</b> respectivamente en el archivo <b>conector.php</b> en la carpeta <b>server</b> del proyecto. O bien puede crearla manualmente desde el panel de control phpmyadmin.";
    }
  }else{
    $conexion['database'] = "OK";
    $conexion['msg'] = "Base de datos <b>".$con->database."</b> encontrada";
}
 echo json_encode($conexion);

?>
