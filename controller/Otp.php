<?php
require '../models/OtpCorreo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'enviar') {
        $email = $_POST['email'] ?? '';
        $respuesta = OtpCorreo::generarOTP($email);
        echo json_encode($respuesta);
    }

    if ($accion === 'validar') {
        $email = $_POST['email'] ?? '';
        $otp = $_POST['otp'] ?? '';
        $respuesta = OtpCorreo::validarOTP($email, $otp);
        echo json_encode($respuesta);
    }
}
