<?php 

    class RutasPermitidas {

        public function __construct(){
            $this->rutasPermitidas();
        }

        public static function rutasPermitidas(){
            return array(
                'login' ,
                'registro' ,
                'consultar-usuarios' ,
                "consultaid",
                "consultar-roles",
                "modelos",
                "registro-modelo",
                "modelo-por-id",
                "usuario-por-id",
            );
        }
    }