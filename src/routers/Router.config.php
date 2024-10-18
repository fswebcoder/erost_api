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
                "eliminar-conocimiento",
                "eliminar-habilidad",
                "consultar-notificaciones",
                "editar-conocimiento",
                "editar-habilidad",
                "inactivar-usuario",
                "notificacion-leida",
                "eliminar-modelo",
                "eliminar-foto"
            );
        }
    }