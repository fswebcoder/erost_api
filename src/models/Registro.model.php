<?php
    require_once './config/Database.php';
    class RegistroModel {
        private $db;
        private $conn;

        public function __construct(){
            $this->db = new Database;
            $this->conn = $this->db->obtenerConexion();
        }

        public function registro(array $data){
            $fechaRegistro = date('Y-m-d H:i:s');
            try {
                $query = "INSERT INTO ts_empleado ( nombre, cargo, foto, edad, fecha_registro) VALUES (:nombre, :cargo, :foto, :edad, :fecha_registro)";
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
                $stmt->bindParam(':cargo', $data['cargo'], PDO::PARAM_STR);
                $stmt->bindParam(':foto', $data['foto'], PDO::PARAM_STR);
                $stmt->bindParam(':edad', $data['edad'], PDO::PARAM_INT);
                $stmt->bindParam(':fecha_registro', $fechaRegistro, PDO::PARAM_STR);  

                $stmt->execute();
    
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                return $user ?: false;
    
            } catch (PDOException $exception) {
                echo new Error(`Error al realizar la consulta: ` . $exception->getMessage());   
                return false;
            }
        
        }
    }