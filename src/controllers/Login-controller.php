<?php
     
    require_once './src/utils/Response-api.php';
    include './src/models/Login.model.php';
    class Login  {
        private $userModel;

        public function login($parametros) {
            $email = $parametros['email'];
            $password = $parametros['contrasena'];
            $this->userModel = new LoginModel();
            $user = $this->userModel->obtenerUsuario($email);
          


            if ($user)  {
                if (password_verify($password, $user['contrasena']) && $user['estado'] == 'ACTIVO') {
                    unset($user['contrasena']);
                    ResponseApi::enviarRespuesta(200, 'Login correcto',$user);
                    
                } else {
                    http_response_code(401);
                    ResponseApi::enviarRespuesta(401, 'Datos de conexiónm incorrectos o usuario inactivo');
                }
            } else {
                ResponseApi::enviarRespuesta(400, 'Datos de conexión incorrectos');
            }
        }

    }