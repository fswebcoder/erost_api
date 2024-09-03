<?php 

    namespace Src\routers\enrutador;

use ResponseApi;
use RutasPermitidas;
    include 'Router.config.php';
    include './src/utils/Response-api.php';

    class Enrutador {

        public static function ParseUrl($url){
            $url = explode("/", filter_var(rtrim( $url , "/"), FILTER_SANITIZE_URL));
            $url = array_filter($url);
            return self::ValidarRuta($url);
        }

        protected static function ValidarRuta(array $url){
            $metodo = '';
            if(count($url) <= 1){
                echo "mostrar la documentación";
            }
            if(in_array($url[4], RutasPermitidas::rutasPermitidas())){
                $methodHttp = $_SERVER['REQUEST_METHOD'];  
                switch($url[4]){
                    case 'login':
                        if($methodHttp == 'POST'){
                            $metodo = 'Login';
                            Enrutador::EnrutarControlador($url[4], $metodo , ["email" =>"FABIO", "password"=> "123"]);
                        }else{
                            ResponseApi::enviarRespuesta(405, 'Method Not Allowed');
                        }
                        break;
                    case 'login':
                        $metodo = 'login';
                        break;
                    case 'register':
                        $metodo = 'register';
                        break;
                    case 'error':
                        $metodo = 'error';
                        break;
                    default:
                        return Enrutador::UrlInvalida();
                }


               
            }else{
                // return Enrutador::UrlInvalida();
            }
        }

        protected static function enrutarControlador(string $controlador, string $metodo, array $parametros){
            $controlador = ucwords(str_replace('-', '', $controlador));
            $controladorPath = "./src/controllers/auth/" . $controlador . "-controller.php";
            echo file_exists($controladorPath);
            if (file_exists($controladorPath)) {
                require_once $controladorPath;
    
                if (class_exists($controlador)) {
                    $controller = new $controlador;
                    if (method_exists($controller, $metodo)) {
                        return $controller->{$metodo}($parametros);
                    } else {
                        return Enrutador::UrlInvalida();
                    }
                } else {
                    return Enrutador::UrlInvalida();
                }
            } else {
                echo "Controlador no encontrado";
            }
            
        }

        protected static function UrlInvalida(){
            ResponseApi::enviarRespuesta(404, 'Not Found');
        }
    }