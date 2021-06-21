<?php

class Database {
    
    //DB params
    private $host;
    private $db_name;
    private $username;
    private $password;
    public  $conn;

    public function __construct()
    {
        $this->host =       constant('HOST');       
        $this->db_name =         constant('DB');       
        $this->username =       constant('USER');       
        $this->password =   constant('PASSWORD');       
    }    
    
    //DB Connect
    public function connect() {
        $this->conn  = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';port=3306;dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();        
        }
        return $this->conn;
    }
}

?>