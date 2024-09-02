<?php 
    namespace Router;
    use RouterConfig\RutasPermitidas;

    class Enrutador {

        public static function ParseUrl($url){
            $url = explode("/", filter_var(rtrim( $url , "/"), FILTER_SANITIZE_URL));
            $url = array_filter($url);
            return self::ValidarRuta($url);
        }

        protected static function ValidarRuta(array $url){
            $metodo = '';
            if(count($url) <= 1){
                return "mostrar la documentación";
            }
            if(in_array($url[2], RutasPermitidas::rutasPermitidas())){

                if(!isset($url[3])){
                    $metodo = "";
                    // return Enrutador::UrlInvalida();
                }else{
                    $metodo = $url[3];
                }
                // Enrutador::EnrutarControlador($url[2], $metodo , []);
            }else{
                // return Enrutador::UrlInvalida();
            }
        }

        protected static function enrutarControlador(string $controlador, string $metodo, array $parametros){
            $controlador = ucwords($controlador);
            $controlador = "App\\Controllers\\" . $controlador;
            if(class_exists($controlador)){
                $controlador = new $controlador;
                if(method_exists($controlador, $metodo)){
                    $controlador->{$metodo}($parametros);
                }else{
                    // return Enrutador::UrlInvalida();
                }
            }else{
                // return Enrutador::UrlInvalida();
            }
            
        }

        protected static function UrlInvalida(){
            return "Url invalida";
        }
    }