<?php 
    require_once './src/routers/Enrutador.php';
    
    class Core {
        public function __construct(){
            $this->obtenerUrl($_SERVER["REQUEST_URI"]);
        }

        public function obtenerUrl(string $uri){
            Enrutador::ParseUrl($uri);
        }
    }
