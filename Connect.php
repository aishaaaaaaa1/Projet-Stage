<?php

class Connect {
    public $conn;
    
    public function __construct() {
        $server = "localhost";
        $user = "root";
        $password = "";
        $db = "users";// Utilise la base de données 'users' comme dans votre login.pHp
        
        $this->conn = mysqli_connect($server, $user, $password, $db);
        
        if (!$this->conn) {
            throw new mysqli_sql_exception("Connection Failed: " . mysqli_connect_error());
        }
        
        $this->conn->set_charset("utf8mb4");
    }
}

?> 