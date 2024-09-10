<?php 
    include './src/models/Rol.model.php';
    class Rol {

        private $rol;
        public function consultarRoles(){
            $this->rol = new RolModel();
            $listaRoles = $this->rol->obtenerRoles();
            if($listaRoles){
                http_response_code(200);
                ResponseApi::enviarRespuesta(200,'Consulta realizada con éxito', $listaRoles);    
            } else {
                http_response_code(400);
                ResponseApi::enviarRespuesta(400,'No fue posible consultar los roles');
            }
        }
    }