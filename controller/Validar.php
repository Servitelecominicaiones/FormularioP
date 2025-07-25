<?php
require_once 'models/ValidarUsuario.php';

class Validar
{
    public function usuario()
    {
        $username = $_POST['username'] ?? '';
        if ($username) {
            ValidarUsuario::validar($username);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no enviado']);
        }
    }
    public function cedula() {
        if (!isset($_POST['cedula'])) {
            echo json_encode(['success' => false, 'message' => 'Cédula no recibida']);
            return;
        }

        $cedula = $_POST['cedula'];

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'https://modeloautogestion-dev.servitel.co/preguntas_seguridad_cedula',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(['cedula' => $cedula]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: session=eyJjc3JmX3Rva2VuIjoiZWUxYWUzYWJiMDI0ZWNhNjJiMmJkNzgyNDM1MWNlYTk5MjllMzc5OCIsIl9mcmVzaCI6ZmFsc2V9.aH_zsA.Q5dLMwzxvOSWKih0_5lykfvYxZk'
            ),
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['success']) && $data['success']) {
            echo json_encode(['success' => true, 'usuario' => $data['usuario'] ?? null]);
        } else {
            echo json_encode(['success' => false]);
        }
    }


}
