<?php



require_once './src/utils/Response-api.php';
include 'Router.config.php';

class Enrutador {

    public static function parseUrl($url)
    {
        $url = explode("/", filter_var(rtrim($url, "/"), FILTER_SANITIZE_URL));
        // http://localhost/http/backend-erost/api/registro
        
        $url = array_filter($url);
        return self::ValidarRuta($url);
    }

    protected static function ValidarRuta(array $url){
        if (count($url) <= 1) {
            return Enrutador::UrlInvalida();
        }
        if (isset($url[4])) {
            // Separar la parte de la URL antes del '?'
            $endpoint = explode('?', $url[4])[0];
        
            // Verificar si el endpoint está en las rutas permitidas
            if (in_array($endpoint, RutasPermitidas::rutasPermitidas())) {
                $methodHttp = $_SERVER['REQUEST_METHOD'];
        
                switch ($endpoint) {
                    case 'login':
                        if ($methodHttp == 'POST') {
                            $json = file_get_contents('php://input');
                            $data = json_decode($json, true);
                            if (isset($data['email']) && isset($data['contrasena'])) {
                                $email = $data['email'];
                                $contrasena = $data['contrasena'];
                                $clase = 'Login';
                                Enrutador::EnrutarControlador($endpoint, $clase, ["email" => $email, "contrasena" => $contrasena]);
                            } else {
                                ResponseApi::enviarRespuesta(400, 'Bad Request, faltan datos');
                            }
                        } else {
                            ResponseApi::enviarRespuesta(405, 'Method Not Allowed');
                        }
                        break;
        
                    case 'registro':
                        if ($methodHttp == 'POST') {
                            $json = file_get_contents('php://input');
                            $data = json_decode($json, true);
        
                            if (isset($data['nombre']) && isset($data['cargo']) && isset($data['foto']) && isset($data['edad']) && isset($data['email']) && isset($data['contrasena'])) {
                                $nombre = $data['nombre'];
                                $cargo = $data['cargo'];
                                $foto = $data['foto'];
                                $edad = $data['edad'];
                                $email = $data['email'];
                                $contrasena = $data['contrasena'];
                                $clase = 'registro';
                                $rol = 3;
                                $arrayInfo = array("nombre" => $nombre, "cargo" => $cargo, "foto" => $foto, "edad" => $edad, "email" => $email, "contrasena" => $contrasena, "ts_rol_idts_rol" => $rol);
                                Enrutador::EnrutarControlador($endpoint, $clase, $arrayInfo);
                            } else {
                                ResponseApi::enviarRespuesta(400, 'Bad Request, faltan datos');
                            }
                        }
                        break;
        
                    case 'consultar-usuarios':
                        if ($methodHttp == 'GET') {
                            $clase = 'consultarUsuario';
                            Enrutador::EnrutarControlador('Registro', $clase, []);
                        }
                        break;
        
                    case 'consultaid':
                        if ($methodHttp == 'GET' && isset($_GET['id'])) {
                            $id = $_GET['id'];
                            $clase = 'consultarUsuarioPorId';
                            $arrayParametros = array("id" => $id);
                            Enrutador::EnrutarControlador('Registro', $clase, $arrayParametros);
                        } else {
                            ResponseApi::enviarRespuesta(400, 'Bad Request, falta el parámetro id');
                        }
                        break;
        
                    default:
                        return Enrutador::UrlInvalida();
                }
            } else {
                return Enrutador::UrlInvalida();
            }
        } else {
            return Enrutador::UrlInvalida();
        }
    }

    protected static function enrutarControlador(string $controlador, string $metodo, array $parametros)
    {
        $controlador = ucwords(str_replace('-', '', $controlador));
        $controladorPath = "./src/controllers/". $controlador . "-controller.php";
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
            ResponseApi::enviarRespuesta(404, 'Controlador no encontrado');
        }
    }

    protected static function UrlInvalida()
    {
        ResponseApi::enviarRespuesta(404, 'Not Found');
    }
}
