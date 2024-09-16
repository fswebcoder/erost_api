<?php
require_once './config/Database.php';
class RegistroModel
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = new Database;
        $this->conn = $this->db->obtenerConexion();
    }

    public function registro(array $data)
    {
        $fechaRegistro = date('Y-m-d H:i:s');

        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO ts_empleado (nombre, cargo, edad, fecha_registro) 
                          VALUES (:nombre, :cargo, :edad, :fecha_registro)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':cargo', $data['cargo'], PDO::PARAM_STR);

            $stmt->bindParam(':edad', $data['edad'], PDO::PARAM_INT);
            $stmt->bindParam(':fecha_registro', $fechaRegistro, PDO::PARAM_STR);
            $stmt->execute();

            $ultimo_id_empleado = $this->conn->lastInsertId();

            $query = "INSERT INTO ts_fotos (base64) 
                          VALUES (:base64)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':base64', $data['foto'], PDO::PARAM_STR);
            $stmt->execute();

            $ultimo_id_foto = $this->conn->lastInsertId();
            $query = "INSERT INTO ts_fotos_has_ts_empleado (ts_fotos_idts_fotos, ts_empleado_idts_empleado) 
                            VALUES (:ts_fotos_idts_fotos, :ts_empleado_idts_empleado)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':ts_fotos_idts_fotos', $ultimo_id_foto, PDO::PARAM_INT);
            $stmt->bindParam(':ts_empleado_idts_empleado', $ultimo_id_empleado, PDO::PARAM_INT);
            $stmt->execute();



            $query = "INSERT INTO ts_usuario (email, contrasena, ts_rol_idts_rol) 
                          VALUES (:email, :contrasena, :ts_rol_idts_rol)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $contrasena = password_hash($data['contrasena'], PASSWORD_DEFAULT);
            $stmt->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
            $stmt->bindParam(':ts_rol_idts_rol', $data['ts_rol_idts_rol'], PDO::PARAM_INT);
            $stmt->execute();

            $ultimo_id_usuario = $this->conn->lastInsertId();

            $query = "INSERT INTO ts_usuario_has_ts_empleado (ts_usuario_idts_usuario, ts_empleado_idts_empleado) 
                          VALUES (:id_usuario, :id_empleado)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_usuario', $ultimo_id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_empleado', $ultimo_id_empleado, PDO::PARAM_INT);
            $stmt->execute();

            $this->conn->commit();

            $query = "SELECT * FROM ts_empleado WHERE idts_empleado = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $ultimo_id_empleado, PDO::PARAM_INT);
            $stmt->execute();

            $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

            return $empleado ?: false;
        } catch (PDOException $exception) {
            $this->conn->rollBack();
            echo "Error al realizar la consulta: " . $exception->getMessage();
            return false;
        }
    }


    public function consultarUsuarios()
    {
        try {
            $query = "SELECT 
                e.idts_empleado,
                e.nombre AS nombre_empleado,
                e.cargo,
                e.edad,
                tf.base64 as foto,
                u.email AS email_usuario,
                r.tipo_rol AS rol
            FROM 
                ts_empleado e
            JOIN ts_usuario_has_ts_empleado ue ON e.idts_empleado = ue.ts_empleado_idts_empleado
            JOIN  ts_usuario u ON ue.ts_usuario_idts_usuario = u.idts_usuario
            JOIN  ts_rol r ON u.ts_rol_idts_rol = r.idts_rol
            JOIN ts_fotos_has_ts_empleado fu ON fu.ts_empleado_idts_empleado = e.idts_empleado
            JOIN ts_fotos tf ON tf.idts_fotos = fu.ts_fotos_idts_fotos";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $users ?: false;
        } catch (PDOException $exception) {
            echo "Error al realizar la consulta: " . $exception->getMessage();
            return false;
        }
    }

    public function consultarUsuarioPorId($id)
     
    {
        try{
            $query = "SELECT 
                e.nombre AS nombre_empleado,
                e.cargo,
                e.edad,
                tf.base64 as foto,
                u.email AS email_usuario,
                r.tipo_rol AS rol
            FROM 
                ts_empleado e
            JOIN ts_usuario_has_ts_empleado ue ON e.idts_empleado = ue.ts_empleado_idts_empleado
            JOIN  ts_usuario u ON ue.ts_usuario_idts_usuario = u.idts_usuario
            JOIN  ts_rol r ON u.ts_rol_idts_rol = r.idts_rol
            JOIN ts_fotos_has_ts_empleado fu ON fu.ts_empleado_idts_empleado = e.idts_empleado
            JOIN ts_fotos tf ON tf.idts_fotos = fu.ts_fotos_idts_fotos
            WHERE e.idts_empleado = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id['id'], PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ?: false;

            } catch (PDOException $exception) {
                echo "Error al realizar la consulta: " . $exception->getMessage();
                return false;
            }

           
        
    }

}
