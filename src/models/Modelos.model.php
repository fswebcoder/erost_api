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
            $query = "SELECT tsm.idts_empleado, tsm.nombre, tsm.edad, tsm.email, tsf.base64, 
                             tsc.nombre as conocimiento, tsc.descripcion as desc_conocimiento, 
                             tshd.nombre as nom_habilidad, tshd.descripcion as desc_habilidad
                      FROM ts_modelo as tsm
                      INNER JOIN ts_modelo_has_ts_fotos tshf ON tsm.idts_empleado = tshf.ts_modelo_idts_empleado
                      INNER JOIN ts_fotos as tsf ON tshf.ts_fotos_idts_fotos = tsf.idts_fotos
                      INNER JOIN ts_modelo_has_ts_conocimientos  as tsmc ON tsm.idts_empleado = tsmc.ts_modelo_idts_empleado
                      INNER JOIN ts_conocimientos as tsc  ON tsc.idts_conocimiento = tsmc.ts_conocimientos_idts_competencia
                      INNER JOIN ts_modelo_has_ts_habilidades as tsh ON tsm.idts_empleado = tsh.ts_modelo_idts_empleado
                      INNER JOIN ts_habilidades as tshd ON tshd.idts_habilidades = tsh.ts_habilidades_idts_habilidades";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        
            $empleados = [];
        
            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $idts_empleado = $fila['idts_empleado'];
        
                // Buscar si el empleado ya está en el array
                $indiceEmpleado = array_search($idts_empleado, array_column($empleados, 'idts_empleado'));
        
                // Si el empleado no está en el array, lo agregamos
                if ($indiceEmpleado === false) {
                    $empleados[] = [
                        'idts_empleado' => $idts_empleado,
                        'nombre' => $fila['nombre'],
                        'edad' => $fila['edad'],
                        'email' => $fila['email'],
                        'fotos' => [],
                        'conocimientos' => [],
                        'habilidades' => []
                    ];
                    $indiceEmpleado = array_key_last($empleados);
                }
        
                // Agregar las fotos
                if (!in_array($fila['base64'], $empleados[$indiceEmpleado]['fotos'])) {
                    $empleados[$indiceEmpleado]['fotos'][] = $fila['base64'];
                }
        
                // Agregar los conocimientos
                $nuevoConocimiento = [
                    'nombre' => $fila['conocimiento'],
                    'descripcion' => $fila['desc_conocimiento']
                ];
        
                // Verificar si el conocimiento ya fue agregado
                if (!in_array($nuevoConocimiento, $empleados[$indiceEmpleado]['conocimientos'])) {
                    $empleados[$indiceEmpleado]['conocimientos'][] = $nuevoConocimiento;
                }
        
                // Agregar las habilidades
                $nuevaHabilidad = [
                    'nombre' => $fila['nom_habilidad'],
                    'descripcion' => $fila['desc_habilidad']
                ];
        
                // Verificar si la habilidad ya fue agregada
                if (!in_array($nuevaHabilidad, $empleados[$indiceEmpleado]['habilidades'])) {
                    $empleados[$indiceEmpleado]['habilidades'][] = $nuevaHabilidad;
                }
            }
        
            // Si no se encontraron empleados, devolver false
            if (empty($empleados)) {
                return false;
            }
        
            return $empleados;
        }
        
  

    }