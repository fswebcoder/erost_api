<?php
      require_once './config/Database.php';
    class MonitorModel{

        private $db;
        private $conn;
    
        public function __construct()
        {
            $this->db = new Database;
            $this->conn = $this->db->obtenerConexion();
        }
        
        public function registrarComentarioMonitor($idts_modelo, $nombre_registrador, $descripcion, $tipo_comentario){
            $fechaRegistro = date('Y-m-d H:i:s');
            $query = "INSERT INTO `ts_notificaciones` (`idts_modelo`, `nombre_registrador`, `descripcion`, tipo_comentario, `fecha_registro`) VALUES (:idts_modelo, :nombre_registrador, :descripcion, :tipo_comentario, :fecha_registro)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':idts_modelo', $idts_modelo, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_registrador', $nombre_registrador, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':tipo_comentario', $tipo_comentario, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_registro', $fechaRegistro, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->rowCount();

        }
    }