CREATE DATABASE IF NOT EXISTS network;
USE network;

CREATE TABLE estado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_estado VARCHAR(100)
);

CREATE TABLE categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_estado INT NOT NULL,
    nombre VARCHAR(100),
    FOREIGN KEY (id_estado) REFERENCES estado(id)
);

CREATE TABLE rol (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) 
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_estado INT NOT NULL,
    id_rol INT NOT NULL,
    nombre VARCHAR(100),
    usuario VARCHAR(100),
    correo VARCHAR(100) UNIQUE,
    contrasena VARCHAR(255),
    FOREIGN KEY (id_estado) REFERENCES estado(id),
    FOREIGN KEY (id_rol) REFERENCES rol(id)
);

CREATE TABLE evento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_estado INT NOT NULL,
    nombre VARCHAR(100),
    descripcion TEXT,
    fecha_hora DATETIME,
    ubicacion VARCHAR(100),
    FOREIGN KEY (id_estado) REFERENCES estado(id)
);

CREATE TABLE reporte (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_usuario_reportar INT NOT NULL,
    id_estado INT NOT NULL,
    motivo VARCHAR(100),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_usuario_reportar) REFERENCES usuarios(id),
    FOREIGN KEY (id_estado) REFERENCES estado(id)
);

CREATE TABLE curso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT NOT NULL,
    id_estado INT NOT NULL,
    nombre VARCHAR(100),
    descripcion TEXT,
    precio DOUBLE,
    tiempo_horas INT,
    imagen VARCHAR(1024),
    FOREIGN KEY (id_categoria) REFERENCES categoria(id),
    FOREIGN KEY (id_estado) REFERENCES estado(id)
);

CREATE TABLE oferta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT NOT NULL,
    id_estado INT NOT NULL,
    nombre VARCHAR(100),
    descripcion TEXT,
    nivel VARCHAR(100) NOT NULL,
    presupuesto DOUBLE,
    modalidad VARCHAR(100) NOT NULL,
    nombre_empresa VARCHAR(100),
    fecha DATE,
    FOREIGN KEY (id_categoria) REFERENCES categoria(id),
    FOREIGN KEY (id_estado) REFERENCES estado(id)
);

-- usuario: admin / clave: 123456
-- usuario: usuario / clave: 123456

INSERT INTO estado (tipo_estado) VALUES
('Activo'), ('Inactivo');

INSERT INTO categoria (id_estado, nombre) VALUES
(1, 'Desarrollo Web'), (1, 'Diseño UX/UI'), (1, 'Marketing Digital'),
(1, 'Diseño Gráfico'), (1, 'Escritura'), (1, 'Otra');

INSERT INTO rol (nombre) VALUES
('Admin'), ('Usuario');

INSERT INTO usuarios (id_estado, id_rol, nombre, correo, usuario, contrasena) VALUES
(1, 2, 'Usuario NetWork', 'usuario@network.com', 'usuario',
'$2y$10$2O5vLHR.GEKQZFRgTAzpiebK0sIw2bZT4E5m4TP3wayqhOQGjhW5.'),
(1, 1, 'Admin NetWork', 'admin@network.com', 'admin',
'$2y$10$2O5vLHR.GEKQZFRgTAzpiebK0sIw2bZT4E5m4TP3wayqhOQGjhW5.');

INSERT INTO evento (id_estado, nombre, descripcion, fecha_hora, ubicacion) VALUES
(1, 'Rediseñando Experiencias: Taller de UX/UI para Apps Móviles',
'Aprende a detectar problemas de usabilidad y rediseñar interfaces móviles de forma práctica.',
'2025-09-12 10:00:00', 'Ciudad de México, México');

INSERT INTO reporte (id_usuario, id_usuario_reportar, id_estado, motivo) VALUES
(1, 2, 1, 'Publicación de oferta laboral falsa.');

INSERT INTO curso (id_categoria, id_estado, nombre, descripcion, precio, tiempo_horas, imagen) VALUES
(4, 1, 'Diseño Gráfico de Cero a Experto', 
'Aprende los fundamentos del diseño gráfico, desde teoría del color y tipografía hasta el uso de herramientas como Photoshop y más.', 
120, 25, '../img/disenoGrafico');

INSERT INTO oferta (id_categoria, id_estado, nombre, descripcion, nivel, presupuesto, modalidad, nombre_empresa, fecha) VALUES
(1, 1, 'Desarrollador Backend', 
'Desarrollador backend con experiencia en Node.js y MongoDB para proyecto de 2 meses. Se valorará experiencia en API REST y buenas prácticas de código.', 
'Avanzado', 1200, 'Remoto', 'Innovatech CR', '2025-08-18');