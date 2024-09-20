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

            // Obtener el último ID insertado en la tabla ts_modelo
            $lastId = $this->conn->lastInsertId();

            // Insertar las fotos asociadas al modelo
            foreach ($fotos as $foto) {
                $this->registrarFotos($lastId, $foto); // Llamar a registrarFotos con el ID del modelo y la foto
            }
        
            return $stmt->rowCount();
        }

        public function registrarFotos($idModelo, $foto){
            // Insertar la foto en la tabla ts_fotos
            $fechaRegistro = date('Y-m-d H:i:s');

            $query = "INSERT INTO `ts_fotos` (`base64`) VALUES (:base64)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':base64', $foto, PDO::PARAM_STR);  // Cambiar a STR ya que base64 es texto
            $stmt->execute();

            // Obtener el último ID insertado en la tabla ts_fotos
            $lastIdFoto = $this->conn->lastInsertId();

            // Insertar en la tabla ts_modelo_has_ts_fotos asociando el modelo con la foto
            $query = "INSERT INTO `ts_modelo_has_ts_fotos`(`ts_modelo_idts_empleado`, `ts_fotos_idts_fotos`) VALUES (:ts_modelo_idts_empleado, :ts_fotos_idts_fotos)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':ts_modelo_idts_empleado', $idModelo, PDO::PARAM_INT); // Asegurarse de que sea un número entero
            $stmt->bindParam(':ts_fotos_idts_fotos', $lastIdFoto, PDO::PARAM_INT);  // Usar el ID de la foto generada
            $stmt->execute();
        }

        public function consultarUsuariomodelos() {
            // Definir la consulta con un parámetro de marcador de posición

            $query = "SELECT * FROM `ts_modelo` tsu 
                      JOIN ts_usuario_has_ts_empleado tsuhe ON tsu.idts_usuario = tsuhe.ts_empleado_idts_empleado 
                      JOIN ts_empleado tse ON tse.idts_empleado = tsuhe.ts_empleado_idts_empleado";
        
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