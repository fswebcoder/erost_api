<?php

    class Database {
        private $host = $_ENV['DB_HOST'];
        private $db_name = $_ENV['DB_DATABASE'];
        private $username = $_ENV['DB_USERNAME'];
        private $password = $_ENV['DB_PASSWORD'];
        public $conn;


        public function obtenerConexion(){
            $this->conn = null;
            try {
                $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->db_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
            } catch (PDOException $exception) {
                echo new Error(`Error conectando con la base de datos: ` . $exception->getMessage());   
            }
            return $this->conn;
        }
    }