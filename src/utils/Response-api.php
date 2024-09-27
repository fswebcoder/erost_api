<?php

class ResponseApi {
    public static function enviarRespuesta($statusCode = 200, $message, $data = null) {
        http_response_code($statusCode);

        $response = [
            'status' => $statusCode,
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        echo json_encode($response);
        exit; 
    }
}
