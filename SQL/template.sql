CREATE DATABASE IF NOT EXISTS network;
USE network;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    usuario VARCHAR(100),
    correo VARCHAR(100) UNIQUE,
    contrasena VARCHAR(255),
    rol ENUM('admin', 'usuario') NOT NULL
);

-- usuario: admin / clave: 123456
-- usuario: usuario / clave: 123456

INSERT INTO usuarios (nombre, correo, usuario, contrasena, rol)
VALUES ('Usuario NetWork', 'usuario@network.com', 'usuario',
        '$2y$10$2O5vLHR.GEKQZFRgTAzpiebK0sIw2bZT4E5m4TP3wayqhOQGjhW5.', 'usuario');

INSERT INTO usuarios (nombre, correo, usuario, contrasena, rol)
VALUES ('Admin NetWork', 'admin@network.com', 'admin',
        '$2y$10$2O5vLHR.GEKQZFRgTAzpiebK0sIw2bZT4E5m4TP3wayqhOQGjhW5.', 'admin');