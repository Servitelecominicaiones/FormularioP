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
                $cedula  = $_POST['cedula'];
                $telefono  = $_POST['telefono'] ?? null;
                $correo    = $_POST['correo'] ?? null;

                // Verificar si el usuario ya existe
                $stmt = $db->prepare("SELECT id FROM usuarios WHERE username = ? OR correo = ?");
                $stmt->execute([$username, $correo]);
                $usuarioExistente = $stmt->fetch();

                if ($usuarioExistente) {
                    // Si existe, actualiza
                    $usuario_id = $usuarioExistente['id'];
                    $stmt = $db->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, telefono = ?, correo = ?, cedula = ? WHERE id = ?");
                    $stmt->execute([$nombre, $apellido, $telefono, $correo, $cedula , $usuario_id]);

                    // Opcional: eliminar preguntas anteriores
                    $db->prepare("DELETE FROM preguntas_seguridad WHERE usuario_id = ?")->execute([$usuario_id]);
                } else {
                    // Si no existe, inserta
                    $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, username, telefono, correo, cedula) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$nombre, $apellido, $username, $telefono, $correo, $cedula]);
                    $usuario_id = $db->lastInsertId();
                }

                // Guardar las preguntas de seguridad
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
                echo "Error al registrar o actualizar usuario: " . $e->getMessage();
            }

        } else {
            require_once "../views/formulario/index.html";
        }
    }


    // En Registro.php
    public function obtenerUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? null;

            if (!$username) {
                echo json_encode(['success' => false, 'message' => 'Username requerido']);
                return;
            }

            $db = Database::connection();

            $stmt = $db->prepare("SELECT * FROM usuarios WHERE username = ?");
            $stmt->execute([$username]);
            $usuario = $stmt->fetch();

            if (!$usuario) {
                echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
                return;
            }

            $stmtPreguntas = $db->prepare("SELECT * FROM preguntas_seguridad WHERE usuario_id = ?");
            $stmtPreguntas->execute([$usuario['id']]);
            $preguntas = $stmtPreguntas->fetchAll();

            echo json_encode([
                'success' => true,
                'usuario' => $usuario,
                'preguntas' => $preguntas
            ]);
        }
    }


}

