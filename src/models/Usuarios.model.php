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

        public function cambiarContrasena($params) {
            $query = "SELECT `contrasena` FROM `ts_usuario` WHERE `idts_usuario` = :idts_usuario";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':idts_usuario', $params['idts_usuario'], PDO::PARAM_INT);
            $stmt->execute();
            
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if (!$usuario) {
                return "Usuario no encontrado";
            }
        
            if (!password_verify($params['contrasena_actual'], $usuario['contrasena'])) {
                ResponseApi::enviarRespuesta(401,'La contraseña actual es incorrecta', null);  
            }
        
            $nuevaContrasenaHash = password_hash($params['contrasena_nueva'], PASSWORD_DEFAULT);
            
            $query = "UPDATE `ts_usuario` SET `contrasena` = :contrasena_nueva , `temporar` = false  WHERE `idts_usuario` = :idts_usuario";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':contrasena_nueva', $nuevaContrasenaHash, PDO::PARAM_STR);
            $stmt->bindParam(':idts_usuario', $params['idts_usuario'], PDO::PARAM_INT);
            
            $stmt->execute();
            
            return $stmt->rowCount(); 
        }
        
        public function consultarNotificaciones() {
            $query = "SELECT * FROM `ts_notificaciones` WHERE `leido` = 0"; ;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function marcarNotificacionLeida($params) {
            $query = "UPDATE `ts_notificaciones` SET `leido` = 1 WHERE `idts_notificaciones` = :idts_notificaciones";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':idts_notificaciones', $params['idts_notificaciones'], PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        }
        

    }