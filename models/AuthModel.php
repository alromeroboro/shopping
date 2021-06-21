<?php

class AuthModel {

    //DB properties
    private $conn;
    private $table = 'users';

    //Post properties
    public $id;
    public $name;
    public $email;
    public $password;
    public $status;
    public $created_at;

    //Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getUser($email, $password) {
        if (!$email || !$password)
            return false;
        // Get query
        $query = 'SELECT
                u.id,
                u.name,
                u.email,
                u.password,
                u.status,
                u.created_at
            FROM 
                ' . $this->table .' u 
            WHERE u.email = :email';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind 
        $stmt->bindValue(':email', $email);        

        // Execute query
        $stmt->execute();


        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->status = $row['status'];
            $this->created_at = $row['created_at'];
            return true;
        } else {
            return false;
        }       

    }

    public function insertToken() {
        $value = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16, $value));
        $date = date("Y-m-d H:i");
        $status = "active";

        $query = 'INSERT INTO user_tokens 
            (user_id, token, status, date) 
            VALUES (:user_id, :token, :status, :date);';
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind 
        $stmt->bindValue(':user_id', $this->id);                 
        $stmt->bindValue(':token', $token);     
        $stmt->bindValue(':status', $status);     
        $stmt->bindValue(':date', $date);   
        
        //Execute
        if ($stmt->execute())
            return $this->conn->lastInsertId();

        printf("Error: %s.\n", $stmt->error);
        
        return false;        

    }

}

?>