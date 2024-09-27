<?php 
 include './src/models/Usuarios.model.php';

    class Usuarios {

        private $usuarios;
        public function consultarUsuarioPorID($parametros){
            $this->usuarios = new UsuariosModel();
            $usuario = $this->usuarios->consultarUsuarioPorID($parametros);
            if($usuario){
                ResponseApi::enviarRespuesta(200,'Consulta realizada con éxito', $usuario);    
            } else {
                ResponseApi::enviarRespuesta(400,'No se encontraron registros');
            }
        }

        public function cambiarContrasena($parametros){
            $this->usuarios = new UsuariosModel();
            $actualizar = $this->usuarios->cambiarContrasena($parametros);
            if($actualizar){
                ResponseApi::enviarRespuesta(200,'Actualización realizada con éxito', $actualizar);    
            } else {
                ResponseApi::enviarRespuesta(400,'No fue posible actualizar la información');
            }
        }


        public function consultarNotificaciones(){
            $this->usuarios = new UsuariosModel();
            $notificaciones = $this->usuarios->consultarNotificaciones();
            if($notificaciones){
                ResponseApi::enviarRespuesta(200,'Consulta realizada con éxito', $notificaciones);    
            } else {
                ResponseApi::enviarRespuesta(400,'No se encontraron registros');
            }
        }

        public function marcarNotificacionLeida($parametros){
            $this->usuarios = new UsuariosModel();
            $actualizar = $this->usuarios->marcarNotificacionLeida($parametros);
            if($actualizar){
                ResponseApi::enviarRespuesta(200,'Actualización realizada con éxito', $actualizar);    
            } else {
                ResponseApi::enviarRespuesta(400,'No fue posible actualizar la información');
            }
        }


    }