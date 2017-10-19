<?php
  session_start();

  class ConectorBD
  {
    private $host = 'localhost'; //Nombre del servidor
    private $user = 'user_agenda'; //Nombre de usuario con permisos restringidos
    private $password = '123456'; //Conrtaseña de usuairo con permisos restringidos
    private $root = 'root'; //Nobre de usuario con permisos administrativos
    private $rootpw = '';//Contraseña de usuario con permisos administrativos
    public $database = 'agenda_db2'; //Nombre de base de datos
    private $conexion;

    function verifyConexion(){
      @$this->conexion = new mysqli($this->host, $this->user, $this->password);
        if( ! $this->conexion ){
          $conexion['msg'] = "<h3>Error al conectarse a la base de datos.</h3>";
        }
        if( $this->conexion->connect_errno ){
          $response=  "<h6>Error al conectarse a la base de datos.</h6> ";
          if ($this->conexion->connect_errno == "2002" ){
              $response.="<p>Vefirique que el <b style='font-size:1em'>nombre del servidor</b> corresponda al parámetro localhost en el archivo <b>conector.php</b> dentro de la <b>carpeta server</b> del proyecto</p>";
              $conexion['msg'].="<p class='small'>Vefirique que el <b style='font-size:1em'>nombre del servidor</b> corresponda al parámetro localhost en el archivo <b>conector.php</b> dentro de la <b>carpeta server</b> del proyecto</p>";
          }
          if ($this->conexion->connect_errno == "1045" ){
            $response.="<p>Vefirique que los parámetros de conexion <b>username y password </b> del archivo <b>conector.php</b> dentro de la <b>carpeta server</b> del proyecto correspondan a un <b>usuario y contraseña válido de phpmyadmin</b></br>". $this->conexion->connect_error . "\n</p>";
            $conexion['msg'].="<p>Vefirique que el <b style='font-size:1em'>nombre del servidor</b> corresponda al parámetro localhost en el archivo <b>conector.php</b> dentro de la <b>carpeta server</b> del proyecto</p>";
          }
        }else{
          /*Si los parametros de conexion a phpMyadmin son correctos continuar*/
          $conexion['phpmyadmin'] =  "OK";
          $conexion['msg'] =  "<p>Conexion establecida con phpMyadmin</p>";
          /*Verificar que existe la base de datos agenda_db en phpmyadmin*/
          if($this->initConexion($this->database) == '1049'){//Si no existe la base de datos
            $database = $this->newDatabase();
            if ($database != "OK"){
              $conexion['msg'] .= "Ha ocurrido un error al crear la base de datos. Por favor verifique que la base de datos existe. Si desea crear automaticamente la base de datos, ingrese los parámetros de un usuario phpmyadmin con permisos para crear bases de datos en el archivo <b>conector.php</b> en la carpeta <b>server</b> del proyecto. ";
            }else{
              $conexion['msg'] .= "Base de datos encontrada";
            }
              //$conexion['msg'] .= "No se encontró la base de datos <b>".$this->database.'</b>.</br>Verifique que existe o <a href="#" id="creardb" style="color:white; text-decoration:underline">Presione para Crear</a>';
          }
        }
        echo json_encode($conexion);
     }


    function initConexion($nombre_db){
    @$this->conexion = new mysqli($this->host, $this->user, $this->password, $nombre_db);
      if ($this->conexion->connect_error) {
        return $this->conexion->connect_errno;
      }else {
        return "OK";
      }
    }

    function userSession(){
      if (isset($_SESSION['username'])) {
        $response['msg'] = $_SESSION['username'];
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
        $this->conexion = new mysqli($this->host, $this->root, $this->rootpw);
        $query = $this->conexion->query('CREATE DATABASE IF NOT EXISTS '.$this->database);
        if($query == 1)
        {
          return "OK"; //Devolver respuesta positiva correcta
        }
        else{
         return $query->connect_errno; //Devolver error;
      }
    }

    function newTable($nombre_tbl, $campos){
      $sql = 'CREATE TABLE IF NOT EXISTS'.$nombre_tbl.' (';
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
      return $this->ejecutarQuery($sql);
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
