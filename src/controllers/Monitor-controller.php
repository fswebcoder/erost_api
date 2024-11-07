
<?php 

    include './src/models/Monitor.model.php';

    class Monitor {

        private $monitor;

        public function guardarComentarioMonitor($parametros){
            $this->monitor = new MonitorModel();
            $idts_modelo = $parametros['idts_modelo'];
            $nombre_registrador = $parametros['nombre_registrador'];
            $nombre = $parametros['nombre'];
            $descripcion = $parametros['descripcion'];
            $tipo_comentario = $parametros['tipo_comentario'];
            $registro = $this->monitor->registrarComentarioMonitor($idts_modelo, $nombre_registrador, $nombre, $descripcion, $tipo_comentario);
           
            if($registro){
                ResponseApi::enviarRespuesta(200,'Registro exitoso', $registro);    
            } else {
                ResponseApi::enviarRespuesta(400,'No fue posible registrar el comentario');
            }
        }


        public function guardarComentarioAdmin($parametros){
            $this->monitor = new MonitorModel();
            $idts_modelo = $parametros['idts_modelo'];
            $nombre = $parametros['nombre'];
            $descripcion = $parametros['descripcion'];
            $tipo_comentario = $parametros['tipo_comentario'];
            $registro = $this->monitor->registrarComentarioAdnmin($idts_modelo, $nombre, $descripcion, $tipo_comentario);
           
            if($registro){
                ResponseApi::enviarRespuesta(200,'Registro exitoso', $registro);    
            } else {
                ResponseApi::enviarRespuesta(400,'No fue posible registrar el comentario');
            }
        }

    }