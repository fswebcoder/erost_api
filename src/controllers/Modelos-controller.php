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
                ResponseApi::enviarRespuesta(200,'Consulta realizada con éxito', $modelos);    
            } else {
                ResponseApi::enviarRespuesta(400,'No se encontraron registros');
            }
        }

        public function actualziarInformacionModelo($parametros){
            $this->modelos = new ModelosModel();
            $actualizar = $this->modelos->actualizarInformacionModelo($parametros);
            if($actualizar){
                ResponseApi::enviarRespuesta(200,'Actualización realizada con éxito', $actualizar);    
            } else {
                ResponseApi::enviarRespuesta(400,'No fue posible actualizar la informa ción');
            }
        }

        public function  guardarActitud($parametros){
            $this->modelos = new ModelosModel();
            $actualizar = $this->modelos->guardarActitud($parametros);

            if($actualizar){
                ResponseApi::enviarRespuesta(200,'Registro almacenado', $actualizar);    
            } else {
                ResponseApi::enviarRespuesta(400,'No fue posible  registrar la información');
            }
        }

        public function  guardarProfesionalismo($parametros){
            $this->modelos = new ModelosModel();
            $actualizar = $this->modelos->guardarProfesionalismo($parametros);
            if($actualizar){
                ResponseApi::enviarRespuesta(200,'Registro almacenado', $actualizar);    
            } else {
                ResponseApi::enviarRespuesta(400,'No fue posible  registrar la información');
            }
        }

        public function  guardarAdaptabilidad($parametros){
            $this->modelos = new ModelosModel();
            $actualizar = $this->modelos->guardarAdaptabilidad($parametros);
            if($actualizar){
                ResponseApi::enviarRespuesta(200,'Registro almacenado', $actualizar);    
            } else {
                ResponseApi::enviarRespuesta(400,'No fue posible  registrar la información');
            }
        }
  
    }