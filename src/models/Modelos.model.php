<?php 
    require_once './config/Database.php';
    
    class ModelosModel {
        private $db;
        private $conn;
    
        public function __construct()
        {
            $this->db = new Database;
            $this->conn = $this->db->obtenerConexion();
        }
        
        public function registrarModelo($nombre, $edad, $email, $fotos){
            $query = "INSERT INTO `ts_modelo` (`nombre`, `edad`, `email`, `fecha_registro` ) VALUES (:nombre, :edad, :email, :fecha_registro)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':edad', $edad, PDO::PARAM_INT);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_registro', $fechaRegistro, PDO::PARAM_STR);

            $stmt->execute();

            $lastId = $this->conn->lastInsertId();

            foreach ($fotos as $foto) {
                $this->registrarFotos($lastId, $foto); 
            }
        
            return $stmt->rowCount();
        }

        public function registrarFotos($idModelo, $foto){
            $fechaRegistro = date('Y-m-d H:i:s');

            $query = "INSERT INTO `ts_fotos` (`base64`) VALUES (:base64)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':base64', $foto, PDO::PARAM_STR);  
            $stmt->execute();

            $lastIdFoto = $this->conn->lastInsertId();

            $query = "INSERT INTO `ts_modelo_has_ts_fotos`(`ts_modelo_idts_empleado`, `ts_fotos_idts_fotos`) VALUES (:ts_modelo_idts_empleado, :ts_fotos_idts_fotos)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':ts_modelo_idts_empleado', $idModelo, PDO::PARAM_INT); 
            $stmt->bindParam(':ts_fotos_idts_fotos', $lastIdFoto, PDO::PARAM_INT); 
            $stmt->execute();
        }

        public function consultarUsuariomodelos() {
            $query = "SELECT tsm.idts_empleado, tsm.nombre, tsm.edad, tsm.email, tsf.base64 
                      FROM ts_modelo as tsm
                      INNER JOIN ts_modelo_has_ts_fotos tshf ON tsm.idts_empleado = tshf.ts_modelo_idts_empleado
                      INNER JOIN ts_fotos as tsf ON tshf.ts_fotos_idts_fotos = tsf.idts_fotos";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        
            $empleados = [];
        
            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $idts_empleado = $fila['idts_empleado'];
        
                $indiceEmpleado = array_search($idts_empleado, array_column($empleados, 'idts_empleado'));
        
                if ($indiceEmpleado === false) {
                    $empleados[] = [
                        'idts_empleado' => $idts_empleado,
                        'nombre' => $fila['nombre'],
                        'edad' => $fila['edad'],
                        'email' => $fila['email'],
                        'fotos' => []
                    ];
                    $indiceEmpleado = array_key_last($empleados);
                }
        
                $empleados[$indiceEmpleado]['fotos'][] = $fila['base64'];
            }
        
            if (empty($empleados)) {
                return false;
            }
        
            return $empleados;
        }
        

    }