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
                "registro-modelo",
                "consultar-modelos",
                "modelo-por-id",
                "usuario-por-id",
                "comentario-monitor",
                "actualizar-informacion",
                "guardar-actitudes",
                "cambiar-contrasena",
                "actualizar-foto-modelo",
                "nuevo-conocimiento",
                "nueva-habilidad",
                "actualizar-conocimiento",
                "actualizar-habilidad",
                "eliminar-conocimiento",
                "eliminar-habilidad",
                "consultar-notificaciones",
                "notificacion-leida"
            );
        }
    }