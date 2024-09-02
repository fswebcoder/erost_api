<?php 
    namespace Core;
    use Router\Enrutador;
    class Core {
        public function __construct(){
            $this->obtenerUrl($_SERVER["REQUEST_URI"]);
        }

        public function obtenerUrl(string $uri){
            Enrutador::ParseUrl($uri);
        }
    }
