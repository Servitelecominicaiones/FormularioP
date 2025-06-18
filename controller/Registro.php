<?php
require_once "models/Database.php";
require_once "models/Usuario.php";
require_once "models/PreguntaSeguridad.php";

class Registro {
    public function registrarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = Database::connection();

            try {
                $nombre    = $_POST['nombre'];
                $apellido  = $_POST['apellido'];
                $username  = $_POST['username'];
                $telefono  = $_POST['telefono'] ?? null;
                $correo    = $_POST['correo'] ?? null;

               
                $stmt = $db->prepare("SELECT id FROM usuarios WHERE username = ? OR correo = ?");
                $stmt->execute([$username, $correo]);

                if ($stmt->fetch()) {
                    echo "El nombre de usuario o correo electrónico ya está registrado.";
                    return;
                }

                // Registrar nuevo usuario
                $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, username, telefono, correo) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$nombre, $apellido, $username, $telefono, $correo]);

                $usuario_id = $db->lastInsertId();

                
                for ($i = 1; $i <= 3; $i++) {
                    $pregunta = $_POST["pregunta{$i}"];
                    $personalizada = isset($_POST["custom-question{$i}"]) ? trim($_POST["custom-question{$i}"]) : null;
                    $respuesta = $_POST["respuesta{$i}"];

                    $stmt = $db->prepare("INSERT INTO preguntas_seguridad (usuario_id, numero_pregunta, pregunta, pregunta_personalizada, respuesta)
                                          VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$usuario_id, $i, $pregunta, $personalizada, $respuesta]);
                }

                header("Location:?c=Menu");
                exit();
            } catch (PDOException $e) {
                echo "Error al registrar usuario: " . $e->getMessage();
            }

        } else {
            require_once "../views/formulario/index.html";
        }
    }
}
?>
