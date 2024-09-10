<?php 
        require_once './config/Database.php';
    class RolModel {
        private $db;
        private $conn;
    
        public function __construct()
        {
            $this->db = new Database;
            $this->conn = $this->db->obtenerConexion();
        }
        
        public function obtenerRoles() {
            $query = "SELECT * FROM ts_rol";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    }