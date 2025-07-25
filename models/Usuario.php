<?php
require_once 'Database.php';

class Usuario {
    private $nombre;
    private $apellido;
    private $username;
    private $telefono;
    private $correo;
    private $preguntas = [];

    public function __construct($nombre, $apellido, $username, $telefono = null, $correo = null) {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->username = $username;
        $this->telefono = $telefono;
        $this->correo = $correo;
    }

    public function agregarPregunta(PreguntaSeguridad $pregunta) {
        $this->preguntas[] = $pregunta;
    }


    public function guardarConPreguntas() {
        $db = Database::connection();

        // Verificar si ya existe
        $stmt = $db->prepare("SELECT id FROM usuarios WHERE username = ? OR correo = ?");
        $stmt->execute([$this->username, $this->correo]);
        if ($stmt->fetch()) {
            throw new Exception("El nombre de usuario o correo ya está registrado.");
        }

        // Insertar usuario
        $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, username, telefono, correo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$this->nombre, $this->apellido, $this->username, $this->telefono, $this->correo]);
        $usuario_id = $db->lastInsertId();

        // Insertar preguntas
        foreach ($this->preguntas as $pregunta) {
            $pregunta->guardar($usuario_id);
        }

        return $usuario_id;
    }
}
