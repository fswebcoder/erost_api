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

            $query = "SELECT tsu.ts_rol_idts_rol, tsu.idts_usuario, tse.nombre, tse.cargo, tsu.email, tsu.temporar, tse.foto, tse.edad, tse.fecha_registro FROM `ts_usuario` tsu JOIN ts_usuario_has_ts_empleado tsuhe ON tsu.idts_usuario = tsuhe.ts_empleado_idts_empleado JOIN ts_empleado tse ON tse.idts_empleado = tsuhe.ts_empleado_idts_empleado WHERE tsu.idts_usuario = :idUsuario";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':idUsuario',  $params['idUsuario'], PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        


        

    }