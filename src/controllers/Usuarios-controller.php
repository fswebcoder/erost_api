<?php 
 include './src/models/Usuarios.model.php';

    class Usuarios {

        private $usuarios;
        public function consultarUsuarioPorID($parametros){
            $this->usuarios = new UsuariosModel();
            $usuario = $this->usuarios->consultarUsuarioPorID($parametros);
            if($usuario){
                http_response_code(200);
                ResponseApi::enviarRespuesta(200,'Consulta realizada con éxito', $usuario);    
            } else {
                http_response_code(400);
                ResponseApi::enviarRespuesta(400,'No fue posible consultar los roles');
            }
        }
    }