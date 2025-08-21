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
    descripcion TEXT,
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
    correo VARCHAR(100) UNIQUE,
    usuario VARCHAR(100),
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

CREATE TABLE profesional (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    profesion VARCHAR(50),
    empresa VARCHAR(100),
    ubicacion VARCHAR(100),
    experiencia VARCHAR(50),
    skills TEXT,
    avatar VARCHAR(255),
    descripcion TEXT,
    conexiones INT DEFAULT 0,
    proyectos INT DEFAULT 0
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
    precio DECIMAL(10,2),
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
    requisitos TEXT,
    beneficios TEXT,
    nivel VARCHAR(100) NOT NULL,
    modalidad VARCHAR(100) NOT NULL,
    publicado_por VARCHAR(100),
    fecha DATE,
    presupuesto DECIMAL(10,2),
    FOREIGN KEY (id_categoria) REFERENCES categoria(id),
    FOREIGN KEY (id_estado) REFERENCES estado(id)
);

-- usuario: admin / clave: 123456
-- usuario: usuario / clave: 123456

INSERT INTO estado (tipo_estado) VALUES
('Activo'), ('Inactivo');

INSERT INTO categoria (id_estado, nombre, descripcion) VALUES
(1, 'Desarrollo Web', 'Domina las tecnologías más demandadas en desarrollo frontend y backend.'), 
(1, 'Diseño UX/UI', 'Crea experiencias digitales intuitivas y atractivas.'), 
(1, 'Marketing Digital', 'Estrategias efectivas para el crecimiento digital.'),
(1, 'Negocios Digitales', 'Transforma ideas en negocios digitales exitosos'),
(1, 'Diseño Gráfico', 'Creación de logotipos, ilustraciones y material visual.'), 
(1, 'Escritura', 'Redacción, corrección y creación de contenido.');

INSERT INTO rol (nombre) VALUES
('Admin'), ('Usuario');

INSERT INTO usuarios (id_estado, id_rol, nombre, correo, usuario, contrasena) VALUES
(1, 2, 'Usuario NetWork', 'usuario@network.com', 'usuario',
'$2y$10$2O5vLHR.GEKQZFRgTAzpiebK0sIw2bZT4E5m4TP3wayqhOQGjhW5.'),

(1, 1, 'Admin NetWork', 'admin@network.com', 'admin',
'$2y$10$2O5vLHR.GEKQZFRgTAzpiebK0sIw2bZT4E5m4TP3wayqhOQGjhW5.');

INSERT INTO evento (id_estado, nombre, descripcion, fecha_hora, ubicacion) VALUES -- Falta añadir los eventos que están creados en la página
(1, 'Rediseñando Experiencias: Taller de UX/UI para Apps Móviles',
'Aprende a detectar problemas de usabilidad y rediseñar interfaces móviles de forma práctica.',
'2025-09-12 10:00:00', 'Ciudad de México, México');

INSERT INTO profesional (nombre, profesion, empresa, ubicacion, experiencia, skills, avatar, descripcion, conexiones, proyectos)
VALUES 
('Ana García', 'Desarrolladora Frontend', 'Tech Solutions', 'Madrid, España', '5 años', 'React,JavaScript,CSS,HTML', 'img/avatar.jpg', 'Desarrolladora Frontend especializada en React y tecnologías modernas.', 150, 23),
('Carlos Ruiz', 'Diseñador UX/UI', 'Design Studio', 'Barcelona, España', '3 años', 'Figma,Adobe XD,Prototyping,User Research', 'img/avatar.jpg', 'Diseñador UX/UI con pasión por crear experiencias digitales excepcionales.', 89, 15),
('Laura Fernández', 'Marketing Digital Manager', 'Growth Agency', 'Valencia, España', '7 años', 'SEM,SEO,Analytics,Content Strategy', 'img/avatar.jpg', 'Experta en marketing digital con enfoque en growth hacking y estrategia.', 245, 42),
('Miguel Torres', 'Full Stack Developer', 'Startup Inc.', 'Sevilla, España', '4 años', 'Node.js,Python,MongoDB,AWS', 'img/avatar.jpg', 'Desarrollador Full Stack con experiencia en arquitecturas escalables.', 120, 18);


INSERT INTO reporte (id_usuario, id_usuario_reportar, id_estado, motivo) VALUES
(2, 1, 1, 'Publicación de oferta laboral falsa.');

INSERT INTO curso (id_categoria, id_estado, nombre, descripcion, precio, tiempo_horas, imagen) VALUES
(1, 1, 'React desde Cero a Experto', 'Domina React.js y crea aplicaciones web modernas con las mejores prácticas.', 49.99, 15, 'img/react.jpg'), 

(2, 1, 'Diseño UX/UI con Figma', 'Aprende a crear interfaces modernas y experiencias de usuar.
io excepcionales.', 39.99, 12, 'img/figma.jpg'), 

(3, 1, 'Marketing Digital Completo', 'Estrategias actualizadas de marketing digital y growth hacking.', 59.99, 20, 'img/marketingdigital.jpg'),

(5, 1, 'Diseño Gráfico de Cero a Experto', 
'Aprende los fundamentos del diseño gráfico, desde teoría del color y tipografía, con herramientas como Photoshop y más.', 
120.00, 25, 'img/diseñoGrafico.jpg');

INSERT INTO oferta (id_categoria, id_estado, nombre, descripcion, requisitos, beneficios, nivel, modalidad, publicado_por, fecha, presupuesto) VALUES
(1, 1, 'Desarrollador Frontend React', 'Buscamos desarrollador frontend con experiencia en React para proyecto de 3 meses.', 
'["Experiencia mínima 1 año con React.", "Conocimiento de REST APIs.", "Trabajo Remoto."]', 
'Pago puntual, flexibilidad horaria.', 'Intermedio', 'Remoto', 'TechStartup Co.', '2024-01-15', 300.00),

(5, 1, 'Diseñador gráfico para branding', 'Necesitamos diseñador para crear identidad visual completa.',
'["Experiencia en Adobe Creative Suite.", "Portafolio de branding.", "Disponiblidad presencial."]', 
'Proyecto creativo, oportunidad de crecimiento.', 'Básico', 'Presencial', 'Marketing Agency', '2024-01-14', 150.00),

(6, 1, 'Copywriter para blog de tecnología', 'Buscamos redactor especializado en contenido tecnológico.', 
'["Experiencia en copywriting tech.", "Conocimiento en SEO.", "Portafolio de artículos."]', 
'Proyecto a largo plazo, buen pago.', 'Avanzado', 'Híbrido', 'Tech Blog Inc.', '2024-01-13', 600.00);