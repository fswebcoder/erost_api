<?php
    require_once './config/Database.php';
    class LoginModel {
        private $db;
        private $conn;

        public function __construct(){
            $this->db = new Database;
            $this->conn = $this->db->obtenerConexion();
        }

        public function obtenerUsuario($email){
            try {
                $query = "SELECT * FROM ts_usuario WHERE email = :email LIMIT 1";
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->execute();
    
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                return $user ?: false;
    
            } catch (PDOException $exception) {
                echo new Error(`Error al realizar la consulta: ` . $exception->getMessage());   
                return false;
            }
        
        }
    }