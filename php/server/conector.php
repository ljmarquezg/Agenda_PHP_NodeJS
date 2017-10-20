<?php
session_start();

class Usuarios //Crear objeto Usuario
{
  public $nombreTabla = 'usuarios'; //Definir nombre de la tabla
  /*Matriz con las columnas que componen la tabla usuarios*/
  public $data = ['email' => 'varchar(50) NOT NULL PRIMARY KEY',
  'nombre' => 'varchar(50) NOT NULL',
  'password' => 'varchar(255) NOT NULL',
  'fecha_nacimiento' => 'date NOT NULL'];

}

class Eventos
{
  public $nombreTabla = 'eventos'; //Definir nombre de la tabla
  /*Matriz con las columnas que componen la tabla eventos*/
  public $data = ['id' => 'INT NOT NULL PRIMARY KEY AUTO_INCREMENT',
  'titulo'=> 'VARCHAR(50) NOT NULL',
  'fecha_inicio'=> 'date NOT NULL',
  'hora_inicio' => 'varchar(20)',
  'fecha_finalizacion'=> 'varchar(20)',
  'hora_finalizacion'=> 'varchar(20)',
  'allday'=> 'tinyint(1) NOT NULL',
  'fk_usuarios'=>'varchar(50) NOT NULL'];
}

class ConectorBD
{
  private $host = 'localhost'; //Nombre del servidor
  public $user = 'root'; //Nombre de usuario con permisos para crear bases de datos
  private $password = ''; //Conrtaseña de usuairo con permisos para crear bases de datos
  public $database = 'agenda_db2'; //Nombre de base de datos
  private $conexion;

  function verifyConexion(){ //Funcion de verificación de conexión
    @$this->conexion = new mysqli($this->host, $this->user, $this->password); //Iniciar conexion con el servidor

    if( ! $this->conexion ){ //Si existe error de conexión
      $conexion['msg'] = "<h3>Error al conectarse a la base de datos.</h3>";
    }
    if( $this->conexion->connect_errno ){ //Verificar si el sistema devuelve un error al conectar con el servidor
      $response =  "<h6>Error al conectarse a la base de datos.</h6> "; //Mensaje de error
      if ($this->conexion->connect_errno == "2002" ){ //Verificar que el error sea por resolución de nombre de servidor a través de la respuesta del servidor numero "202"
        $response.="<p><h6><b>Error de conexión</b></h6> Vefirique que el <b style='font-size:1em'>nombre del servidor</b> corresponda al parámetro localhost en el archivo <b>conector.php</b> dentro de la <b>carpeta server</b> del proyecto</p>";
      }
      if ($this->conexion->connect_errno == "1045" ){ //Verificar que el error sea por error de usuario y/o contraseña a través de la respuesta del servidor numero "1045"
        $response.="<h6><b>Error de conexión</b></h6><p>Vefirique que los parámetros de conexion <b>username y password </b> del archivo <b>conector.php</b> dentro de la <b>carpeta server</b> del proyecto correspondan a un <b>usuario y contraseña válido de phpmyadmin</b></br>". $this->conexion->connect_error . "\n</p>";
      }
      $conexion['phpmyadmin'] = "Error"; //Guardar el estado Si existe un error durante la conexión en el índice "phpmyadmin"
      $conexion['msg'] = $response; //Guardar el error error durante la conexión en el índice "msg"

    }else{
      /*Si los parametros de conexion a phpMyadmin son correctos continuar*/
      $conexion['phpmyadmin'] =  "OK"; //Guardar el estado Si existe un error durante la conexión en el índice "phpmyadmin"
      $conexion['msg'] =  "<p>Conexion establecida con phpMyadmin</p>"; //Guardar el mensaje si existe un error durante la conexión en el índice "msg"
      /*Verificar que existe la base de datos  en phpmyadmin*/
      if($this->initConexion($this->database) == '1049'){ //Verifiar si existe la base de datos a través de la respuesta del servidor numero "1049"
        $database = $this->newDatabase(); //Iniciar la función newDatabase para crear la base de datos.
        if ($database != "OK"){ //Verificar que la respuesta de la creación de la base de datos no sea igual a OK
          /*Guardar el mensaje a mostrar si ocurre un error al guardar la base de datos*/
          $conexion['msg'] .=  "<h6><b>Error de privilegios</b></h6></br>El usuario <b>'$this->user'</b> no posee la permisología requerida para crear la base de datos <b>$this->database</b>. Si desea crear automaticamente la base de datos, ingrese los parámetros de un usuario phpmyadmin con permisos para crear bases de datos en las variables usuario <b>\$user </b> y contraseña <b>\$password</b> respectivamente en el archivo <b>conector.php</b> en la carpeta <b>server</b> del proyecto. O bien puede crearla manualmente desde el panel de control phpmyadmin.";
        }
      }else{
        $conexion['database'] = "OK"; //Guardar el estado positivo si se creó la base de datos correctamente en el índice "database"
        $conexion['msg'] .= "Base de datos encontrada"; //Guardar mensaje en el índice msg
      }
  }
  echo json_encode($conexion); //Devolver respuesta
}


function initConexion($nombre_db){ //Función de inicialización de conexión
  @$this->conexion = new mysqli($this->host, $this->user, $this->password, $nombre_db); //Enviar parámetros de servidor, usuario, contraseña y nombre de base de datos
  if ($this->conexion->connect_error) { //Verificar que no existan errores
    return $this->conexion->connect_errno; //Devolver el numero del error
  }else {
    return "OK"; //Devolver estado OK si la coexión fué exitosa
  }
}

function userSession(){ //Función para validar la sesión del usuario
  if (isset($_SESSION['email'])) { //Verificar que la sesión no sea vacía
    $response['msg'] = $_SESSION['email']; //Si hay una sesión iniciada guardar el nombre del usuario
  }else{
    $response['msg'] = '';
  }
  return json_encode($response);
}

function verifyUsers(){
  $sql = 'SELECT COUNT(email) FROM usuarios;';
  $totalUsers = $this->ejecutarQuery($sql);
  while ($row = $totalUsers->fetch_assoc()) {
    return $row['COUNT(email)'];
  }
}

function getConexion(){
  return $this->conexion;
}

function newDatabase(){
  $this->conexion = new mysqli($this->host, $this->user, $this->password);
  $query = $this->conexion->query('CREATE DATABASE IF NOT EXISTS '.$this->database);
  if($query == 1)
  {
    return "OK"; //Devolver respuesta positiva correcta
  }
  else{
    return "Error"; //Devolver error;
  }
}

function createTable($nombre_tbl, $campos){
  $this->conexion = new mysqli($this->host, $this->user, $this->password, $this->database);
  $sql = 'CREATE TABLE IF NOT EXISTS '.$nombre_tbl.' (';
  $length_array = count($campos);
  $i = 1;
  foreach ($campos as $key => $value) {
    $sql .= $key.' '.$value;
    if ($i!= $length_array) {
      $sql .= ', ';
    }else {
      $sql .= ');';
    }
    $i++;
  }

  $query =  $this->ejecutarQuery($sql);

  if($query == 1)
  {
    return "OK"; //Devolver respuesta positiva correcta
  }
  else{
    return "Error"; //Devolver error;
  }
}

function ejecutarQuery($query){
  return $this->conexion->query($query);
}

function cerrarConexion(){
  $this->conexion->close();
}

function nuevaRestriccion($tabla, $restriccion){
  $sql = 'ALTER TABLE '.$tabla.' '.$restriccion;
  return $this->ejecutarQuery($sql);
}

function nuevaRelacion($from_tbl, $to_tbl, $from_field, $to_field){
  $sql = 'ALTER TABLE '.$from_tbl.' ADD FOREIGN KEY ('.$from_field.') REFERENCES '.$to_tbl.'('.$to_field.');';
  return $this->ejecutarQuery($sql);
}

function insertData($tabla, $data){
  $sql = 'INSERT INTO '.$tabla.' (';
  $i = 1;
  foreach ($data as $key => $value) {
    $sql .= $key;
    if ($i<count($data)) {
      $sql .= ', ';
    }else $sql .= ')';
    $i++;
  }
  $sql .= ' VALUES (';
  $i = 1;
  foreach ($data as $key => $value) {
    $sql .= $value;
    if ($i<count($data)) {
      $sql .= ', ';
    }else $sql .= ');';
    $i++;
  }
  return $this->ejecutarQuery($sql);
}


function actualizarRegistro($tabla, $data, $condicion){
  $sql = 'UPDATE '.$tabla.' SET ';
  $i=1;
  foreach ($data as $key => $value) {
    $sql .= $key.'='.$value;
    if ($i<sizeof($data)) {
      $sql .= ', ';
    }else $sql .= ' WHERE '.$condicion.';';
    $i++;
  }
  return $this->ejecutarQuery($sql);
}

function eliminarRegistro($tabla, $condicion){
  $sql = "DELETE FROM ".$tabla." WHERE ".$condicion.";";
  return $this->ejecutarQuery($sql);
}

function consultar($tablas, $campos, $condicion = ""){
  $sql = "SELECT ";
  $result = array_keys($campos);
  $ultima_key = end($result);
  foreach ($campos as $key => $value) {
    $sql .= $value;
    if ($key!=$ultima_key) {
      $sql.=", ";
    }else $sql .=" FROM ";
  }

  $result = array_keys($tablas);
  $ultima_key = end($result);
  foreach ($tablas as $key => $value) {
    $sql .= $value;
    if ($key!=$ultima_key) {
      $sql.=", ";
    }else $sql .= " ";
  }

  if ($condicion == "") {
    $sql .= ";";
  }else {
    $sql .= $condicion.";";
  }
  return $this->ejecutarQuery($sql);
}


}

?>
