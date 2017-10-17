<?php
  require('lib.php');

  $con = new conectorBD();

  if($con->initConexion('inventario_db') == "OK"){
    if ($con->consultar(['usuarios'], ['nombre', 'telefono', 'email', 'fk_ciudad'], 'WHERE id < 10')){
      echo 'Se ha realizado la consulta correctamente';
    }  else echo "Se produjo un error al realizar la consulta";
    $con->cerrarConexion();
  }else{
    echo "ocurrrio un error al conectarse a la base de datos";
  }

 ?>
