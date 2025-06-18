<?php
require_once 'Database.php';

class PreguntaSeguridad {
    private $numero;
    private $pregunta;
    private $preguntaPersonalizada;
    private $respuesta;

    public function __construct($numero, $pregunta, $respuesta, $preguntaPersonalizada = null) {
        $this->numero = $numero;
        $this->pregunta = $pregunta;
        $this->respuesta = $respuesta;
        $this->preguntaPersonalizada = $preguntaPersonalizada;
    }

    public function guardar($usuario_id) {
        $db = Database::connection();
        $stmt = $db->prepare("INSERT INTO preguntas_seguridad (usuario_id, numero_pregunta, pregunta, pregunta_personalizada, respuesta)
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $usuario_id,
            $this->numero,
            $this->pregunta,
            $this->preguntaPersonalizada,
            $this->respuesta
        ]);
    }
}
