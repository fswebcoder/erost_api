<?php 

    class RegistroUsuarios {

       
        public function registrarUsuario(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $datos = json_decode(file_get_contents('php://input'), true);
                
            }else{
                ResponseApi::enviarRespuesta(405, 'Metodo no permitido', null);
            }
        }

    }