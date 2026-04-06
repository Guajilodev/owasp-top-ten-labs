CREATE DATABASE IF NOT EXISTS owasp_labs DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE owasp_labs;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- En un escenario real, usar password_hash()
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Tabla de notas (para el lab de IDOR)
CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Insertar datos de ejemplo
INSERT INTO users (username, password, email) VALUES
('alice', 'password123', 'alice@example.com'),
('bob', 'password456', 'bob@example.com'),
('charlie', 'password789', 'charlie@example.com');

INSERT INTO notes (user_id, title, content) VALUES
(1, 'Mi Clave Secreta', 'Mi clave del banco es 1234-5678-9012-3456'),
(1, 'Plan de Dominación Mundial', 'Paso 1: Crear un laboratorio de pentesting. Paso 2: ???. Paso 3: Profit.'),
(2, 'Receta de Tarta', 'Ingredientes: Harina, huevos, azúcar. Instrucciones: Mezclar todo y hornear.'),
(2, 'Ideas de Proyectos', 'Un clon de Twitter pero para mascotas.'),
(3, 'Recordatorio', 'Comprar leche y pan. No olvidar el cumpleaños de Bob.');

-- Tabla para el laboratorio A02: Cryptographic Failures
CREATE TABLE IF NOT EXISTS users_crypto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    md5_password VARCHAR(32) NOT NULL -- MD5 siempre tiene 32 caracteres hexadecimales
);

-- Insertar usuarios con contraseñas hasheadas en MD5 (contraseñas débiles a propósito)
-- 'password' -> 5f4dcc3b5aa765d61d8327deb882cf99
-- '123456' -> e10adc3949ba59abbe56e057f20f883e
-- 'admin' -> 21232f297a57a5a743894a0e4a801fc3
INSERT INTO users_crypto (username, md5_password) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3'),
('testuser', '5f4dcc3b5aa765d61d8327deb882cf99'),
('guest', 'e10adc3949ba59abbe56e057f20f883e');

-- Tabla para el laboratorio A03: Injection (SQL Injection)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0
);

-- Productos de ejemplo para el buscador vulnerable
INSERT INTO products (name, description, price, stock) VALUES
('Laptop ProMax 15', 'Laptop de alto rendimiento con 16GB RAM y 512GB SSD', 1299.99, 15),
('Teclado Mecánico RGB', 'Teclado mecánico con switches Cherry MX Blue', 89.99, 50),
('Monitor UltraWide 34"', 'Monitor curvo 34 pulgadas 144Hz', 549.99, 8),
('Mouse Inalámbrico', 'Mouse ergonómico con sensor óptico 16000 DPI', 45.99, 120),
('Auriculares Noise Cancel', 'Auriculares over-ear con cancelación activa de ruido', 199.99, 25),
('Webcam HD 1080p', 'Cámara web con micrófono integrado y autoenfoque', 69.99, 40),
('Hub USB-C 7 en 1', 'Adaptador multipuerto con HDMI, USB 3.0 y lector SD', 34.99, 75),
('SSD Externo 1TB', 'Disco de estado sólido portátil USB 3.2', 109.99, 30);

-- Tabla para el laboratorio A07: Identification and Authentication Failures
CREATE TABLE IF NOT EXISTS users_auth (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL -- Contraseñas en texto plano para demostrar la vulnerabilidad
);

-- Insertar usuarios con contraseñas débiles/conocidas
INSERT INTO users_auth (username, password) VALUES
('user1', 'password'),
('admin', '123456'),
('test', 'test');
