<?php
class ValidarUsuario
{
    public static function validar($username)
    {
        $logFile = dirname(__DIR__) . '/logs/log_usuario.txt';

        // Log inicial
        file_put_contents($logFile, "Iniciando validación para usuario: $username\n", FILE_APPEND);

        $curl = curl_init();

        $data = json_encode(['username' => $username]);

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://modeloautogestion-dev.servitel.co/consultar_usuario',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: session=eyJjc3JmX3Rva2VuIjoiZWUxYWUzYWJiMDI0ZWNhNjJiMmJkNzgyNDM1MWNlYTk5MjllMzc5OCIsIl9mcmVzaCI6ZmFsc2V9.aH_zsA.Q5dLMwzxvOSWKih0_5lykfvYxZk'
            ),
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            file_put_contents($logFile, "Error en cURL: $error\n", FILE_APPEND);
            curl_close($curl);

            http_response_code(500);
            echo json_encode(['error' => 'Error interno al consultar usuario.']);
            return;
        }

        // Log de respuesta
        file_put_contents($logFile, "Respuesta del servidor: $response\n", FILE_APPEND);

        curl_close($curl);

        header('Content-Type: application/json');
        echo $response;
    }
}
