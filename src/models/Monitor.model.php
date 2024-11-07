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
        
        public function registrarComentarioMonitor($idts_modelo, $nombre_registrador, $nombre, $descripcion, $tipo_comentario){
            $fechaRegistro = date('Y-m-d H:i:s');
            $query = "INSERT INTO `ts_notificaciones` (`idts_modelo`, `nombre_registrador`, `nombre`, `descripcion`, tipo_comentario, `fecha_registro`) VALUES (:idts_modelo, :nombre_registrador, :nombre, :descripcion, :tipo_comentario, :fecha_registro)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':idts_modelo', $idts_modelo, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_registrador', $nombre_registrador, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':tipo_comentario', $tipo_comentario, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_registro', $fechaRegistro, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->rowCount();

        }


        public function registrarComentarioAdnmin($idts_modelo,$nombre, $descripcion, $tipo_comentario){

            $fechaRegistro = date('Y-m-d H:i:s');

            if($tipo_comentario == 'conocimiento'){
                $query = "INSERT INTO `ts_conocimientos` (`nombre`, `descripcion`) VALUES (:nombre, :descripcion)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
                $stmt->execute();
    
                $lastId = $this->conn->lastInsertId();
    
                $query = "INSERT INTO `ts_modelo_has_ts_conocimientos`(`ts_modelo_idts_empleado`, `ts_conocimientos_idts_competencia`) VALUES (:ts_modelo_idts_empleado, :ts_conocimientos_idts_competencia)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':ts_modelo_idts_empleado', $idts_modelo, PDO::PARAM_INT); 
                $stmt->bindParam(':ts_conocimientos_idts_competencia', $lastId, PDO::PARAM_INT); 
                $stmt->execute();
    
                if($stmt->rowCount() > 0){
                    return true;
                } else {
                    return false;
                }

            } else {
                $query = "INSERT INTO `ts_habilidades` (`nombre`, `descripcion`) VALUES (:nombre, :descripcion)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                $stmt->bindParam(':descripcion',$descripcion, PDO::PARAM_STR);
                $stmt->execute();
    
                $lastId = $this->conn->lastInsertId();
    
                $query = "INSERT INTO `ts_modelo_has_ts_habilidades`(`ts_modelo_idts_empleado`, `ts_habilidades_idts_habilidades`) VALUES (:ts_modelo_idts_empleado, :ts_habilidades_idts_habilidades)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':ts_modelo_idts_empleado', $idts_modelo, PDO::PARAM_INT); 
                $stmt->bindParam(':ts_habilidades_idts_habilidades', $lastId, PDO::PARAM_INT); 
                $stmt->execute();
    
                if($stmt->rowCount() > 0){
                    return true;
                } else {
                    return false;
                }

                
            }


        }

    } 