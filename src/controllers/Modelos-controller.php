<?php 
 include './src/models/Modelos.model.php';

    class Modelos {
        
        private $modelos;

        public function registroModelo($parametros){
            $this->modelos = new ModelosModel();
            $nombre = $parametros['nombre'];
            $edad = $parametros['edad'];
            $email = $parametros['email'];
            $fotos = $parametros['fotos'];
            $conocimientos = $parametros['conocimientos'];
            $habilidades = $parametros['habilidades'];
            $registro = $this->modelos->registrarModelo($nombre, $edad, $email, $fotos, $conocimientos, $habilidades);
            if($registro){
                http_response_code(200);
                ResponseApi::enviarRespuesta(200,'Registro exitoso', $registro);    
            } else {
                http_response_code(400);
                ResponseApi::enviarRespuesta(400,'No fue posible registrar el modelo');
            }
        }


        public function consultarModelos(){
            $this->modelos = new ModelosModel();
            $modelos = $this->modelos->consultarModelos();
            if($modelos){
                http_response_code(200);
                ResponseApi::enviarRespuesta(200,'Consulta realizada con éxito', $modelos);    
            } else {
                http_response_code(400);
                ResponseApi::enviarRespuesta(400,'No se encontraron registros');
            }
        }

  
    }