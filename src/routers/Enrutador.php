<?php

require_once './src/utils/Response-api.php';
include 'Router.config.php';

class Enrutador
{

    public static function ParseUrl($url)
    {
        $url = explode("/", filter_var(rtrim($url, "/"), FILTER_SANITIZE_URL));
        $url = array_filter($url);
        return self::ValidarRuta($url);
    }

    protected static function ValidarRuta(array $url)
    {
        $metodo = '';
        if (count($url) <= 1) {
            return Enrutador::UrlInvalida();
        }
        if (in_array($url[4], RutasPermitidas::rutasPermitidas())) {
            $methodHttp = $_SERVER['REQUEST_METHOD'];
            switch ($url[4]) {
                case 'login':
                    if ($methodHttp == 'POST') {
                        $metodo = 'Login';
                        $json = file_get_contents('php://input');
                        $data = json_decode($json, true);
                        if (isset($data['email']) && isset($data['contrasena'])) {
                            $email = $data['email'];
                            $contrasena = $data['contrasena'];
                            $metodo = 'Login';
                            Enrutador::EnrutarControlador($url[4], "auth", $metodo, ["email" => $email, "contrasena" => $contrasena]);
                        } else {
                            ResponseApi::enviarRespuesta(400, 'Bad Request, faltan datos');
                        }
                    } else {
                        ResponseApi::enviarRespuesta(405, 'Method Not Allowed');
                    }
                    break;
                case 'registro':
                    if ($methodHttp == 'POST') {
                        $metodo = 'RegistroUsuarios';
                        $json = file_get_contents('php://input');
                        $data = json_decode($json, true);

                        if (isset($data['nombre']) && isset($data['cargo']) && isset($data['foto']) && isset($data['edad'])) {
                            $nombre = $data['nombre'];
                            $cargo = $data['cargo'];
                            $foto = $data['foto'];
                            $edad = $data['edad'];
                            $arrayInfo = array("nombre" => $nombre, "cargo" => $cargo, "foto" => $foto, "edad" => $edad);
                            ResponseApi::enviarRespuesta(200, 'OK', $arrayInfo);
                            Enrutador::EnrutarControlador($url[4], "usuarios", $metodo, $arrayInfo);
                        } else {
                            ResponseApi::enviarRespuesta(400, 'Bad Request, faltan datos');
                        }
                    }


                    break;
                case 'error':
                    $metodo = 'error';
                    break;
                default:
                    return Enrutador::UrlInvalida();
            }
        } else {
            return Enrutador::UrlInvalida();
        }
    }

    protected static function enrutarControlador(string $controlador, string $folder, string $metodo, array $parametros)
    {
        $controlador = ucwords(str_replace('-', '', $controlador));
        $controladorPath = "./src/controllers/" . $folder . "/" . $controlador . "-controller.php";
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
