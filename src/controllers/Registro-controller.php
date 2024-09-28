<?php 

include './src/models/Registro.model.php';

    class Registro {

        private $registro;
       
        public function registro($parametros){
            $this->registro = new RegistroModel();
            $registro = $this->registro->registro($parametros);
             if($registro){
                http_response_code(200);
                ResponseApi::enviarRespuesta(200,'Registro exitoso', $registro);    
             } else {
               http_response_code(400);
                ResponseApi::enviarRespuesta(400,'No fue posible registrar el usuario');
             }
            
        }

        public function consultarUsuario(){
            $this->registro = new RegistroModel();
            $consultar = $this->registro->consultarUsuarios();
            if($consultar){
               http_response_code(200);
                ResponseApi::enviarRespuesta(200,' Consulta realizada con éxito ', $consultar);    
             } else {
               http_response_code(400);
                ResponseApi::enviarRespuesta(400,'No  hay usuarios registrados.');
             }
        }
      
        public function consultarUsuarioPorId($id){
            $this->registro = new RegistroModel();

            $usuario = $this->registro->consultarUsuarioPorId($id);
            if($usuario){
               http_response_code(200);
                ResponseApi::enviarRespuesta(200,' Consulta realizada con éxito ', $usuario);    
             } else {
               http_response_code(400);
                ResponseApi::enviarRespuesta(400,'No fue posible consultar los usuarios');
             }
        }


        public function inactivarUsuario($parametros){
            $this->registro = new RegistroModel();
            $inactivar = $this->registro->inactivarUsuario($parametros);
            if($inactivar){
               http_response_code(200);
                ResponseApi::enviarRespuesta(200,'Usuario inactivado con éxito', $inactivar);    
             } else {
               http_response_code(400);
                ResponseApi::enviarRespuesta(400,'No fue posible inactivar el usuario');
             }
        }

    }