<?php

    class ResponseApi {
        public static function enviarRespuesta($status, $message, $data = null) {
            $response = array(
                'status' => $status,
                'message' => $message,
                'data' => $data
            );
            echo json_encode($response);
            exit;
        }
    }