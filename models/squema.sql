-- Eliminar tablas si ya existen
DROP TABLE IF EXISTS preguntas_seguridad;
DROP TABLE IF EXISTS usuarios;

-- Crear tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    correo VARCHAR(150)
);

-- Crear tabla de preguntas de seguridad
CREATE TABLE preguntas_seguridad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    numero_pregunta TINYINT NOT NULL CHECK (numero_pregunta BETWEEN 1 AND 3),
    pregunta VARCHAR(255) NOT NULL,
    pregunta_personalizada VARCHAR(255),
    respuesta TEXT NOT NULL,

    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,

    UNIQUE(usuario_id, numero_pregunta),
    UNIQUE(usuario_id, pregunta, pregunta_personalizada)
);
