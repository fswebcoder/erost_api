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
        
        public function registrarModelo($nombre, $edad, $email, $fotos, $conocimientos, $habilidades){
            $fechaRegistro = date('Y-m-d H:i:s');
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

            if (!empty($conocimientos) ) {
                foreach ($conocimientos as $conocimiento) {
                    $this->registrarConocimientos($lastId, $conocimiento['nombre'], $conocimiento['descripcion']);
                }   
            }
           
            if (!empty($habilidades) ) {
                foreach ($habilidades as $habilidad) {
                    $this->registrarHabilidades($lastId, $habilidad['nombre'], $habilidad['descripcion']);
                }
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

        public function registrarConocimientos($idModelo, $nombreConocimiento, $descripcionConocimiento){
            $query = "INSERT INTO `ts_conocimientos` (`nombre`, `descripcion`) VALUES (:nombre, :descripcion)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $nombreConocimiento, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcionConocimiento, PDO::PARAM_STR);
            $stmt->execute();

            $lastIdConocimiento = $this->conn->lastInsertId();

            $query = "INSERT INTO `ts_modelo_has_ts_conocimientos`(`ts_modelo_idts_empleado`, `ts_conocimientos_idts_competencia`) VALUES (:ts_modelo_idts_empleado, :ts_conocimientos_idts_competencia)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':ts_modelo_idts_empleado', $idModelo, PDO::PARAM_INT); 
            $stmt->bindParam(':ts_conocimientos_idts_competencia', $lastIdConocimiento, PDO::PARAM_INT); 
            $stmt->execute();
        }

        public function registrarHabilidades($idModelo, $nombreHabilidad, $descripcionHabilidad){
            $query = "INSERT INTO `ts_habilidades` (`nombre`, `descripcion`) VALUES (:nombre, :descripcion)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $nombreHabilidad, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcionHabilidad, PDO::PARAM_STR);
            $stmt->execute();

            $lastIdHabilidad = $this->conn->lastInsertId();

            $query = "INSERT INTO `ts_modelo_has_ts_habilidades`(`ts_modelo_idts_empleado`, `ts_habilidades_idts_habilidades`) VALUES (:ts_modelo_idts_empleado, :ts_habilidades_idts_habilidades)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':ts_modelo_idts_empleado', $idModelo, PDO::PARAM_INT); 
            $stmt->bindParam(':ts_habilidades_idts_habilidades', $lastIdHabilidad, PDO::PARAM_INT); 
            $stmt->execute();
        }

        public function consultarModelos() {
            $query = "SELECT tsm.idts_empleado, tsm.nombre, tsm.edad, tsm.email, tsf.idts_fotos, tsf.base64, 
                             tsc.nombre as conocimiento, tsc.idts_conocimiento, tsc.descripcion as desc_conocimiento, 
                             tshd.idts_habilidades,  tshd.nombre as nom_habilidad, tshd.descripcion as desc_habilidad,
                             tsa.actitud_positiva, tsa.profesionalismo, tsa.adaptabilidad
                      FROM ts_modelo as tsm
                      LEFT JOIN ts_modelo_has_ts_fotos tshf ON tsm.idts_empleado = tshf.ts_modelo_idts_empleado
                      LEFT JOIN ts_fotos as tsf ON tshf.ts_fotos_idts_fotos = tsf.idts_fotos
                      LEFT JOIN ts_modelo_has_ts_conocimientos as tsmc ON tsm.idts_empleado = tsmc.ts_modelo_idts_empleado
                      LEFT JOIN ts_conocimientos as tsc ON tsc.idts_conocimiento = tsmc.ts_conocimientos_idts_competencia
                      LEFT JOIN ts_modelo_has_ts_habilidades as tsh ON tsm.idts_empleado = tsh.ts_modelo_idts_empleado
                      LEFT JOIN ts_habilidades as tshd ON tshd.idts_habilidades = tsh.ts_habilidades_idts_habilidades
                      LEFT JOIN ts_actitudes tsa ON tsa.ts_modelo_idts_empleado = tsm.idts_empleado";
        
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
                        'fotos' => [],
                        'conocimientos' => [],
                        "actitud_positiva" => $fila['actitud_positiva'],
                        "profesionalismo" => $fila['profesionalismo'],
                        "adaptabilidad" => $fila['adaptabilidad'],
                        'habilidades' => []
                    ];
                    $indiceEmpleado = array_key_last($empleados);
                }
        
                $nuevaFoto = [
                    'idts_fotos' => $fila['idts_fotos'],
                    'base64' => $fila['base64']
                ];
        
                if (!in_array($nuevaFoto, $empleados[$indiceEmpleado]['fotos'])) {
                    $empleados[$indiceEmpleado]['fotos'][] = $nuevaFoto;
                }
        
                $nuevoConocimiento = [
                    'idts_conocimiento' => $fila['idts_conocimiento'],
                    'nombre' => $fila['conocimiento'],
                    'descripcion' => $fila['desc_conocimiento']
                ];
        
                if (!in_array($nuevoConocimiento, $empleados[$indiceEmpleado]['conocimientos'])) {
                    $empleados[$indiceEmpleado]['conocimientos'][] = $nuevoConocimiento;
                }
        
                $nuevaHabilidad = [
                    'idts_habilidades' => $fila['idts_habilidades'],
                    'nombre' => $fila['nom_habilidad'],
                    'descripcion' => $fila['desc_habilidad']
                ];
        
                if (!in_array($nuevaHabilidad, $empleados[$indiceEmpleado]['habilidades'])) {
                    $empleados[$indiceEmpleado]['habilidades'][] = $nuevaHabilidad;
                }
            }
        
            if (empty($empleados)) {
                return false;
            }
        
            return $empleados;
        }

        public function actualizarInformacionModelo($parametros){
            $fechaActalizacion = date('Y-m-d H:i:s');
            $idts_empleado = $parametros['idts_empleado'];
            $nombre = $parametros['nombre'];
            $edad = $parametros['edad'];
            $email = $parametros['email'];

            $query = "UPDATE `ts_modelo` SET `nombre` = :nombre, `edad` = :edad, `email` = :email, `fecha_actualizacion` = :fecha_actualizacion WHERE `idts_empleado` = :idts_empleado";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':edad', $edad, PDO::PARAM_INT);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_actualizacion', $fechaActalizacion, PDO::PARAM_STR);
            $stmt->bindParam(':idts_empleado', $idts_empleado, PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
  
        }

        public function guardarActitud($parametros) {
            $idts_empleado = $parametros['idts_empleado'];
            $dato = $parametros['dato'];
        
            $stmtUpdate = null;
            $stmtInsert = null;
        
            $queryCheck = "SELECT COUNT(*) FROM `ts_actitudes` WHERE `ts_modelo_idts_empleado` = :idts_empleado";
            $stmtCheck = $this->conn->prepare($queryCheck);
            $stmtCheck->bindParam(':idts_empleado', $idts_empleado, PDO::PARAM_INT);
            $stmtCheck->execute();
            
            $exists = $stmtCheck->fetchColumn(); 
            if ($exists > 0) {
                $queryUpdate = "UPDATE `ts_actitudes` SET `actitud_positiva` = :actitud_positiva WHERE `ts_modelo_idts_empleado` = :idts_empleado";
                $stmtUpdate = $this->conn->prepare($queryUpdate);
                $stmtUpdate->bindParam(':actitud_positiva', $dato, PDO::PARAM_STR);
                $stmtUpdate->bindParam(':idts_empleado', $idts_empleado, PDO::PARAM_INT);
                $stmtUpdate->execute();
                if ($stmtUpdate->rowCount() > 0) {
                    return true;
                }
            } else {
                $queryInsert = "INSERT INTO `ts_actitudes` (`ts_modelo_idts_empleado`, `actitud_positiva`) 
                                VALUES (:idts_empleado, :actitud_positiva)";
                $stmtInsert = $this->conn->prepare($queryInsert);
                $stmtInsert->bindParam(':actitud_positiva', $dato, PDO::PARAM_STR);
                $stmtInsert->bindParam(':idts_empleado', $idts_empleado, PDO::PARAM_INT);
                $stmtInsert->execute();
                if ($stmtInsert->rowCount() > 0) {
                    return true;
                }
            }
        
            return false;
        }
        
         

        public function  guardarProfesionalismo($parametros){
            $idts_empleado = $parametros['idts_empleado'];
            $dato = $parametros['dato'];
        
            $stmtUpdate = null;
            $stmtInsert = null;
        
            $queryCheck = "SELECT COUNT(*) FROM `ts_actitudes` WHERE `ts_modelo_idts_empleado` = :idts_empleado";
            $stmtCheck = $this->conn->prepare($queryCheck);
            $stmtCheck->bindParam(':idts_empleado', $idts_empleado, PDO::PARAM_INT);
            $stmtCheck->execute();
            
            $exists = $stmtCheck->fetchColumn(); 
        
            if ($exists > 0) {
                $queryUpdate = "UPDATE `ts_actitudes` SET `profesionalismo` = :profesionalismo WHERE `ts_modelo_idts_empleado` = :idts_empleado";
                $stmtUpdate = $this->conn->prepare($queryUpdate);
                $stmtUpdate->bindParam(':profesionalismo', $dato, PDO::PARAM_STR);
                $stmtUpdate->bindParam(':idts_empleado', $idts_empleado, PDO::PARAM_INT);
                $stmtUpdate->execute();
                if ($stmtUpdate->rowCount() > 0) {
                    return true;
                }
            } else {
                $queryInsert = "INSERT INTO `ts_actitudes` (`ts_modelo_idts_empleado`, `profesionalismo`) 
                                VALUES (:idts_empleado, :profesionalismo)";
                $stmtInsert = $this->conn->prepare($queryInsert);
                $stmtInsert->bindParam(':profesionalismo', $dato, PDO::PARAM_STR);
                $stmtInsert->bindParam(':idts_empleado', $idts_empleado, PDO::PARAM_INT);
                $stmtInsert->execute();
                if ($stmtInsert->rowCount() > 0) {
                    return true;
                }
            }
        
          return false;
  
        }

        public function  guardarAdaptabilidad($parametros){
            $idts_empleado = $parametros['idts_empleado'];
            $dato = $parametros['dato'];
        
            $stmtUpdate = null;
            $stmtInsert = null;
        
            $queryCheck = "SELECT COUNT(*) FROM `ts_actitudes` WHERE `ts_modelo_idts_empleado` = :idts_empleado";
            $stmtCheck = $this->conn->prepare($queryCheck);
            $stmtCheck->bindParam(':idts_empleado', $idts_empleado, PDO::PARAM_INT);
            $stmtCheck->execute();
            
            $exists = $stmtCheck->fetchColumn(); 
        
            if ($exists > 0) {
                $queryUpdate = "UPDATE `ts_actitudes` SET `adaptabilidad` = :adaptabilidad WHERE `ts_modelo_idts_empleado` = :idts_empleado";
                $stmtUpdate = $this->conn->prepare($queryUpdate);
                $stmtUpdate->bindParam(':adaptabilidad', $dato, PDO::PARAM_STR);
                $stmtUpdate->bindParam(':idts_empleado', $idts_empleado, PDO::PARAM_INT);
                $stmtUpdate->execute();
                if ($stmtUpdate->rowCount() > 0) {
                    return true;
                }
            } else {
                $queryInsert = "INSERT INTO `ts_actitudes` (`ts_modelo_idts_empleado`, `adaptabilidad`) 
                                VALUES (:idts_empleado, :adaptabilidad)";
                $stmtInsert = $this->conn->prepare($queryInsert);
                $stmtInsert->bindParam(':adaptabilidad', $dato, PDO::PARAM_STR);
                $stmtInsert->bindParam(':idts_empleado', $idts_empleado, PDO::PARAM_INT);
                $stmtInsert->execute();
                if ($stmtInsert->rowCount() > 0) {
                    return true;
                }
            }
        
            return false;
  
        }

        public function actualizarFotoModelo($parametros){
            $query = "UPDATE `ts_fotos` SET `base64` = :base64 WHERE `idts_fotos` = :idts_fotos";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':base64', $parametros['base64'], PDO::PARAM_STR);  // Nueva imagen en base64
            $stmt->bindParam(':idts_fotos', $parametros['idts_foto'], PDO::PARAM_INT);  // ID de la foto que se va a actualizar
            $stmt->execute();

            return $stmt->rowCount();
        }


        public function nuevoConocimiento($parametros){
            $query = "INSERT INTO `ts_conocimientos` (`nombre`, `descripcion`) VALUES (:nombre, :descripcion)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $parametros['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $parametros['descripcion'], PDO::PARAM_STR);
            $stmt->execute();

            $lastId = $this->conn->lastInsertId();

            $query = "INSERT INTO `ts_modelo_has_ts_conocimientos`(`ts_modelo_idts_empleado`, `ts_conocimientos_idts_competencia`) VALUES (:ts_modelo_idts_empleado, :ts_conocimientos_idts_competencia)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':ts_modelo_idts_empleado', $parametros['idts_modelo'], PDO::PARAM_INT); 
            $stmt->bindParam(':ts_conocimientos_idts_competencia', $lastId, PDO::PARAM_INT); 
            $stmt->execute();

            return $stmt->rowCount();
        } 


        public function registrarHabilidad($parametros){
            $query = "INSERT INTO `ts_habilidades` (`nombre`, `descripcion`) VALUES (:nombre, :descripcion)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $parametros['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $parametros['descripcion'], PDO::PARAM_STR);
            $stmt->execute();

            $lastId = $this->conn->lastInsertId();

            $query = "INSERT INTO `ts_modelo_has_ts_habilidades`(`ts_modelo_idts_empleado`, `ts_habilidades_idts_habilidades`) VALUES (:ts_modelo_idts_empleado, :ts_habilidades_idts_habilidades)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':ts_modelo_idts_empleado', $parametros['idts_modelo'], PDO::PARAM_INT); 
            $stmt->bindParam(':ts_habilidades_idts_habilidades', $lastId, PDO::PARAM_INT); 
            $stmt->execute();

            return $stmt->rowCount();
        }
        
        public function eliminarConocimiento($parametros){
            $query = "DELETE FROM `ts_modelo_has_ts_conocimientos` WHERE `ts_modelo_idts_empleado` = :ts_modelo_idts_empleado AND `ts_conocimientos_idts_competencia` = :ts_conocimientos_idts_competencia";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':ts_modelo_idts_empleado', $parametros['idts_empleado'], PDO::PARAM_INT);
            $stmt->bindParam(':ts_conocimientos_idts_competencia', $parametros['idts_conocimiento'], PDO::PARAM_INT);

            $stmt->execute();

            $query = "DELETE FROM `ts_conocimientos` WHERE `idts_conocimiento` = :idts_conocimiento";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':idts_conocimiento', $parametros['idts_conocimiento'], PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        }

        public function eliminarHabilidad($parametros) {
            $query = "DELETE FROM `ts_modelo_has_ts_habilidades` WHERE `ts_modelo_idts_empleado` = :ts_modelo_idts_empleado AND `ts_habilidades_idts_habilidades` = :ts_habilidades_idts_habilidades";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':ts_modelo_idts_empleado', $parametros['idts_empleado'], PDO::PARAM_INT);
            $stmt->bindParam(':ts_habilidades_idts_habilidades', $parametros['idts_habilidad'], PDO::PARAM_INT);
            $stmt->execute();
        
            $query = "DELETE FROM `ts_habilidades` WHERE `idts_habilidades` = :idts_habilidades";
            $stmt1 = $this->conn->prepare($query);
            $stmt1->bindParam(':idts_habilidades', $parametros['idts_habilidad'], PDO::PARAM_INT);
            $stmt1->execute();
        
            if ( $stmt1->execute()) {
                return true; // Éxito
            } else {
                return false; // No se eliminaron filas
            }
        }
        
        
 }
