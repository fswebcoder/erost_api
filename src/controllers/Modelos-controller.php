<?php 
 include './src/models/Modelos.model.php';

    class Modelos {
        
        private $modelos;
        public function consultarModelos(){
            $this->modelos = new ModelosModel();
            $modelos = $this->modelos->consultarUsuariomodelos();
            if($modelos){
                http_response_code(200);
                ResponseApi::enviarRespuesta(200,'Consulta realizada con éxito', $usuario);    
            } else {
                http_response_code(400);
                ResponseApi::enviarRespuesta(400,'No se encontraron registros');
            }
        }
    }