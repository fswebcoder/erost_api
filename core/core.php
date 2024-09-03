<?php 
    namespace Core;
    use Src\routers\enrutador\Enrutador;

    class Core {
        public function __construct(){
            $this->obtenerUrl($_SERVER["REQUEST_URI"]);
        }

        public function obtenerUrl(string $uri){
            Enrutador::ParseUrl($uri);
        }
    }
