<?php

  class Connect{

    // Properties
    
    private $dbname = "store";
    private $host = 'localhost';
    private $username = "root";
    private $password = "";
    private $conn;

    // Methods

    public function connectDB(){
      $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
      // Check connection
      if ($this->conn->connect_error) {
        die("Connection failed: " . $this->conn->connect_error);
          }
      return $this->conn;
    }

    public function showLastID(){
      return $this->conn->insert_id;
    }

    public function getLastError(){
      return (isset($this->conn->error))? $this->conn->error : null;
    }

    public static function getLastConnError($conn){
      return (isset($conn->error))? $conn->error : null;
    }
    
  }
?>
