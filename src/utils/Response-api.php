<?php

    class ResponseApi {
        public static function enviarRespuesta($status, $message, $data = null) {
            header('Content-Type: application/json');
            $response = array(
                'status' => $status,
                'message' => $message,
                'data' => $data
            );
            echo json_encode($response);
            exit;
        }
    }