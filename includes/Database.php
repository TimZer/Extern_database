<?php

class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect($dbname){

        $this->conn = new PDO("mysql:host=$this->host;dbname=$dbname",
            $this->username,
            $this->password
        );
    }
}
