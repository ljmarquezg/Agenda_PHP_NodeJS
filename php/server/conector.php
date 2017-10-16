<?php
  session_start();

  class ConectorBD
  {
    private $host;
    private $user;
    private $password;
    private $conexion;

    function __construct($host, $user, $password){
      $this->host = $host;
      $this->user = $user;
      $this->password = $password;
    }

    function initConexion($nombre_db){
      $this->conexion = new mysqli($this->host, $this->user, $this->password, $nombre_db);
      if ($this->conexion->connect_error) {
        return "Error:" .$this->conexion->connect_error;
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
    function newTable($nombre_tbl, $campos){
      $sql = 'CREATE TABLE '.$nombre_tbl.' (';
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

    function getViajesUser($user_id){
      $sql = "SELECT co.nombre AS ciudad_origen, cd.nombre AS ciudad_destino, v.placa AS placa, v.fabricante AS fabricante, v.referencia AS referencia, a.fecha_salida AS fecha_salida, a.fecha_llegada AS fecha_llegada, a.hora_salida AS hora_salida, a.hora_llegada AS hora_llegada
              FROM viajes AS a
              JOIN ciudades AS co ON co.id = a.fk_ciudad_origen
              JOIN ciudades AS cd ON cd.id = a.fk_ciudad_destino
              JOIN vehiculos AS v ON v.placa = a.fk_vehiculo
              WHERE a.fk_conductor = ".$user_id.";";
      return $this->ejecutarQuery($sql);
    }

    function getCiudades(){
      $sql = "SELECT id, nombre FROM ciudades";
      return $this->ejecutarQuery($sql);
    }

    function getVehiculo(){
      $sql = "SELECT placa, fabricante, referencia FROM vehiculos";
      return $this->ejecutarQuery($sql);
    }


  }

 ?>
