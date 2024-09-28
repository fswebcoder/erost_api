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

        public function actualizarFotoModelo($parametros){
            $this->modelos = new ModelosModel();
            $actualizar = $this->modelos->actualizarFotoModelo($parametros);
            if($actualizar){
                ResponseApi::enviarRespuesta(200,'Actualización realizada con éxito', $actualizar);    
            } else {
                ResponseApi::enviarRespuesta(400,'No fue posible actualizar la información');
            }
        }   
        

        public function nuevoConocimiento($parametros){
            $this->modelos = new ModelosModel();
            $gaurdar = $this->modelos->nuevoConocimiento($parametros);
            if($gaurdar){
                ResponseApi::enviarRespuesta(200,'Registro almacenado', $gaurdar);    
            } else {
                ResponseApi::enviarRespuesta(400,'No fue posible  registrar la información');
            }
        }

        public function registrarHabilidad($parametros){
            $this->modelos = new ModelosModel();
            $gaurdar = $this->modelos->registrarHabilidad($parametros);
            if($gaurdar){
                ResponseApi::enviarRespuesta(200,'Registro almacenado', $gaurdar);    
            } else {
                ResponseApi::enviarRespuesta(400,'No fue posible  registrar la información');
            }
        }  
        
        public function eliminarConocimiento($parametros){
            $this->modelos = new ModelosModel();
            $eliminar = $this->modelos->eliminarConocimiento($parametros);
            if($eliminar){
                ResponseApi::enviarRespuesta(200,'Registro eliminado', $eliminar);    
            } else {
                ResponseApi::enviarRespuesta(400,'No fue posible  eliminar la información');
            }
        }

        public function eliminarHabilidad($parametros){
            $this->modelos = new ModelosModel();
            $eliminarHabilidad = $this->modelos->eliminarHabilidad($parametros);
            if($eliminarHabilidad){
                ResponseApi::enviarRespuesta('Registro eliminado', $eliminarHabilidad);    
            } else {
                ResponseApi::enviarRespuesta(400,'No fue posible  eliminar la información');
            }
        }

        public function editarConocimiento($parametros){
            $this->modelos = new ModelosModel();
            $editar = $this->modelos->editarConocimiento($parametros);
            if($editar){
                ResponseApi::enviarRespuesta(200,'Registro actualizado', $editar);    
            } else {
                ResponseApi::enviarRespuesta(400,'No fue posible  actualizar la información');
            }
        }

        public function editarHabilidad($parametros){
            $this->modelos = new ModelosModel();
            $editar = $this->modelos->editarHabilidad($parametros);
            if($editar){
                ResponseApi::enviarRespuesta(200,'Registro actualizado', $editar);    
            } else {
                ResponseApi::enviarRespuesta(400,'No fue posible  actualizar la información');
            }
        }

    }