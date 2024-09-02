<?php 
    namespace RouterConfig;
    class RutasPermitidas {

        public function __construct(){
            
        }

        public static function rutasPermitidas(){
            return array(
                'home' ,
                'login' ,
                'register' ,
                'error'
            );
        }
    }