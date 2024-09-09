<?php 

include './src/models/Registro.model.php';

    class Registro {

        private $registro;
       
        public function registro($parametros){
            $this->registro = new RegistroModel();
            $registro = $this->registro->registro($parametros);
             if($registro){
                ResponseApi::enviarRespuesta(200,'Registro exitoso', $registro);    
             } else {
                ResponseApi::enviarRespuesta(400,'No fue posible registrar el usuario');
             }
            
        }

        public function consultarUsuario($parametros){
            $this->registro = new RegistroModel();
            $consultar = $this->registro->consultarUsuarios($parametros);
            if($consultar){
                ResponseApi::enviarRespuesta(200,' Consulta realizada con éxito ', $consultar);    
             } else {
                ResponseApi::enviarRespuesta(400,'No fue posible consultar los usuarios');
             }
        }
      

    }