<?php

class Database {
    private $host   = "localhost";
    private $dbname = "exchangemgt";
    private $user   = "root";
    private $pass   = "";

    protected function connect() {
        try {
            $conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}",$this->user,$this->pass);
            return $conn;
        }catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}


?>