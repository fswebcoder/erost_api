<?php 
    require_once './config/Database.php';
    
    class UsuariosModel {
        private $db;
        private $conn;
    
        public function __construct()
        {
            $this->db = new Database;
            $this->conn = $this->db->obtenerConexion();
        }
        
        public function consultarUsuarioPorID($params) {
            // Definir la consulta con un parámetro de marcador de posición

            $query = "SELECT * FROM `ts_usuario` tsu 
                      JOIN ts_usuario_has_ts_empleado tsuhe ON tsu.idts_usuario = tsuhe.ts_empleado_idts_empleado 
                      JOIN ts_empleado tse ON tse.idts_empleado = tsuhe.ts_empleado_idts_empleado 
                      WHERE tsu.idts_usuario = :idUsuario";
        
            // Preparar la consulta
            $stmt = $this->conn->prepare($query);
        
            // Asignar el valor del parámetro al marcador de posición
            $stmt->bindParam(':idUsuario',  $params['idUsuario'], PDO::PARAM_INT);
        
            // Ejecutar la consulta
            $stmt->execute();
        
            // Retornar los resultados
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        

    }