<?php
class ValidarUsuario
{
    public static function validar($username)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://modeloautogestion-dev.servitel.co/consultar_usuario',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(['username' => $username]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: ' . 'session=eyJjc3JmX3Rva2VuIjoiZWUxYWUzYWJiMDI0ZWNhNjJiMmJkNzgyNDM1MWNlYTk5MjllMzc5OCIsIl9mcmVzaCI6ZmFsc2V9.aH_zsA.Q5dLMwzxvOSWKih0_5lykfvYxZk'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        header('Content-Type: application/json');
        echo $response;
    }
}
