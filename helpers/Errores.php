<?php
    namespace Helpers;

    class Errores {

        public static function rutaNoEncontrada(){
            $json = array(
                "status" => 404,
                "error" => "Ruta no encontrada",
                "message" => "La ruta solicitada no existe"
            );
            echo json_encode($json, true);
        }
    }