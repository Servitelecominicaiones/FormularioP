<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
require '../models/Database.php'; // Asegúrate que el archivo exista y tenga la conexión PDO

class OtpCorreo {
    public static function generarOTP($email) {
        $otp = rand(100000, 999999);
        $fecha = date('Y-m-d H:i:s');
        $db = Database::connection(); // ✅ CORRECTO

        // Eliminar OTPs anteriores
        $stmt = $db->prepare("DELETE FROM tabla_otps WHERE email = ?");
        $stmt->execute([$email]);

        // Insertar nuevo OTP
        $stmt = $db->prepare("INSERT INTO tabla_otps (email, otp, fecha_creacion) VALUES (?, ?, ?)");
        $stmt->execute([$email, $otp, $fecha]);

        return self::enviarOTP($email, $otp);
    }

    private static function enviarOTP($email, $otp) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.office365.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tservitel@servitel.co';
            $mail->Password   = 'pkgydmrbctfvztlg';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Office365 requiere TLS
            $mail->Port       = 587;

            $mail->CharSet    = 'UTF-8';
            $mail->setFrom('tservitel@servitel.co', 'Verificación OTP');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Tu código de verificación';
            $mail->Body    = "Tu código OTP es <b>$otp</b>. Este código vence en 10 minutos.";

            $mail->send();
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => "Error al enviar correo: {$mail->ErrorInfo}"];
        }
    }


    public static function validarOTP($email, $otpIngresado) {
        $db = Database::connection();
        $stmt = $db->prepare("SELECT * FROM tabla_otps WHERE email = ? AND otp = ? AND NOW() <= DATE_ADD(fecha_creacion, INTERVAL 10 MINUTE) AND verificado = 0");
        $stmt->execute([$email, $otpIngresado]);

        if ($stmt->rowCount() > 0) {
            // Marcar como verificado
            $db->prepare("UPDATE tabla_otps SET verificado = 1 WHERE email = ?")->execute([$email]);
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'OTP inválido o expirado'];
    }
}
