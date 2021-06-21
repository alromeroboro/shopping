<?php

class CategoryModel extends Model {
    //DB properties
    private $conn;
    private $table = 'product_categories';
    private $first;
    private $last;
    private $page;

    //Category properties
    public $category_id;
    public $category_name;
    public $created_at;
    public $updated_at;

    //Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get Categories
    public function get($page = 0) {

        $this->page = $page;
        $this->setLimits();
        // Get query
        $query = 'SELECT *
        FROM 
            ' . $this->table . ' c  
        ORDER BY
            c.category_name ASC 
        LIMIT ' . $this->first . ', ' . $this->last;

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        //  get row count
        $num = $stmt->rowCount();

        //Check if any post 
        if ($num > 0)
            //Post array
            $post_arr = $this->getData($stmt);

        // Return data!
        return $post_arr;

    }

    // Get Single Post
    public function getSingle($id) {

        // Create query
        $query = 'SELECT *
            FROM 
                ' . $this->table . ' c 
            WHERE c.category_id = :id
            LIMIT 0, 1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind id
        $stmt->bindValue(':id', $id);

        // Execute query
        $stmt->execute();

        $row = $this->getDataRow($stmt);

        // Set properties
        if ($row) {
            $this->category_id = $row['category_id'];
            $this->category_name = $row['category_name'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
        }
    }

    //Create Post
    public function create() {
        if (!$this->category_name)
            return false;        
        // Create query
        $query = 'INSERT INTO ' . $this->table . ' 
            SET
                category_name = :name';
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        //Clean data
        $this->category_name = htmlspecialchars(strip_tags($this->category_name));  

        //Bind data
        $stmt->bindValue(':name', $this->category_name);

        //Execute
        if ($stmt->execute())
            return $this->conn->lastInsertId();
       
        $result = new AppResponse();
        $result->error_500($stmt->error); 
    }

    //Update Post
    public function update() {

        if (!$this->category_name)
            return false;
        // Create query
        $query = 'UPDATE ' . $this->table . ' 
            SET
                category_name = :category_name
            WHERE 
                category_id= :category_id';
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        //Clean data
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));      
        $this->category_name = htmlspecialchars(strip_tags($this->category_name));  

        //Bind data
        $stmt->bindValue(':category_id', $this->category_id);
        $stmt->bindValue(':category_name', $this->category_name);

        //Execute
        if ($stmt->execute())
            return true;

        return false;

    }

    // Delete post
    public function delete() {
        if (!$this->category_id)
            return false; 
        // Create query
        $query = 'DELETE FROM ' . $this->table . 
                ' WHERE category_id = :category_id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // Bind data
        $stmt->bindValue(':category_id', $this->category_id);

        //Execute
        if ($stmt->execute())
            return true;

        return false;    
        
    }

    private function setLimits() {
        $page = $this->page <= 1 ? 1 : $this->page;
        $rows_number = constant('MAX_ROWS');
        if ($page > 1) {
            $this->first = ($this->page - 1) * $rows_number;        
            $this->last  = ($this->page * $rows_number) - 1;
        } else {
            $this->first = 0;
            $this->last  = $rows_number - 1;
        }
    }

}
?>