<?php 

    class RutasPermitidas {

        public function __construct(){
            $this->rutasPermitidas();
        }

        public static function rutasPermitidas(){
            return array(
                'home' ,
                'login' ,
                'registro' ,
                'error'
            );
        }
    }