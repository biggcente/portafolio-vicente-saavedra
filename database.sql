USE vsaavedra_db3;


CREATE TABLE IF NOT EXISTS usuarios (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100)  NOT NULL,
    email       VARCHAR(150)  NOT NULL UNIQUE,
    password    VARCHAR(255)  NOT NULL,  -- bcrypt hash
    creado_en   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS biografia (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100)  NOT NULL,
    titulo      VARCHAR(150)  NOT NULL,
    descripcion TEXT          NOT NULL,
    foto        VARCHAR(255)  DEFAULT NULL,
    cv_url      VARCHAR(255)  DEFAULT NULL,
    github_url  VARCHAR(255)  DEFAULT NULL,
    linkedin_url VARCHAR(255) DEFAULT NULL,
    instagram_url VARCHAR(255) DEFAULT NULL,
    email       VARCHAR(150)  DEFAULT NULL,
    telefono    VARCHAR(30)   DEFAULT NULL,
    ciudad      VARCHAR(100)  DEFAULT NULL,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS habilidades (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100)  NOT NULL,
    icono       VARCHAR(50)   DEFAULT NULL,  
    orden       INT           DEFAULT 0,
    activo      TINYINT(1)    DEFAULT 1,
    creado_en   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS tecnologias (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100)  NOT NULL,
    nivel       INT           NOT NULL DEFAULT 50,  
    orden       INT           DEFAULT 0,
    activo      TINYINT(1)    DEFAULT 1,
    creado_en   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS proyectos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    titulo      VARCHAR(150)  NOT NULL,
    descripcion TEXT          NOT NULL,
    imagen      VARCHAR(255)  DEFAULT NULL,
    demo_url    VARCHAR(255)  DEFAULT NULL,
    github_url  VARCHAR(255)  DEFAULT NULL,
    orden       INT           DEFAULT 0,
    activo      TINYINT(1)    DEFAULT 1,
    creado_en   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS mensajes (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100)  NOT NULL,
    email       VARCHAR(150)  NOT NULL,
    asunto      VARCHAR(200)  NOT NULL,
    mensaje     TEXT          NOT NULL,
    leido       TINYINT(1)    DEFAULT 0,
    creado_en   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);




INSERT INTO usuarios (nombre, email, password) VALUES
('Vicente Saavedra', 'rockitchy@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO biografia (nombre, titulo, descripcion, email, telefono, ciudad, github_url, linkedin_url) VALUES
(
    'Vicente Saavedra',
    'Desarrollador Web Full Stack',
    'Estudiante de Desarrollo Web con pasión por crear aplicaciones modernas, funcionales y eficientes. Me especializo en tecnologías como PHP, MySQL, JavaScript y Bootstrap. Siempre buscando aprender y mejorar cada día.',
    'vicente@email.com',
    '+56 9 1234 5678',
    'Santiago, Chile',
    'https://github.com/vicente',
    'https://linkedin.com/in/vicente'
);


INSERT INTO habilidades (nombre, icono, orden) VALUES
('HTML',        '🌐', 1),
('CSS',         '🎨', 2),
('JavaScript',  '⚡', 3),
('PHP',         '🐘', 4),
('MySQL',       '🗄️', 5),
('Bootstrap',   '🅱️', 6),
('GitHub',      '🐙', 7),
('IA Aplicada', '🤖', 8);


INSERT INTO tecnologias (nombre, nivel, orden) VALUES
('HTML',       90, 1),
('CSS',        80, 2),
('JavaScript', 75, 3),
('PHP',        70, 4),
('MySQL',      80, 5),
('Bootstrap',  85, 6),
('GitHub',     70, 7),
('AJAX',       60, 8);


INSERT INTO proyectos (titulo, descripcion, demo_url, github_url, orden) VALUES
('Sistema de Tareas',  'Aplicación web para gestionar tareas pendientes con PHP, MySQL y AJAX.', '#', '#', 1),
('Tienda Online',      'Sistema de comercio electrónico con carrito de compras y pasarela de pago.',  '#', '#', 2),
('Portafolio Web',     'Sitio web personal autoadministrable con PHP, MySQL, Bootstrap y JavaScript.', '#', '#', 3);
