<?php

// Definitie van de klasse Database
class Database
{
    // Hostnaam van de database
    private $host = "localhost";

    // Gebruikersnaam voor de database
    private $username = "root";

    // Wachtwoord voor de database
    private $password = "";

    // Variabele waarin de databaseverbinding wordt opgeslagen
    public $conn;

    // Maakt verbinding met de opgegeven database
    public function connect($dbname){

        // Maakt een nieuwe PDO-verbinding met MySQL
        $this->conn = new PDO(
            "mysql:host=$this->host;dbname=$dbname",
            $this->username,
            $this->password
        );
    }
}