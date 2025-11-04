-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS inventarioRepuestos;

-- Use the inventarioRepuestos database
USE inventarioRepuestos;

-- Drop tables if they exist to ensure a clean slate
DROP TABLE IF EXISTS ventas;
DROP TABLE IF EXISTS repuestos;
DROP TABLE IF EXISTS clientes;

-- Table for clients
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    dni VARCHAR(20) UNIQUE NOT NULL
);

-- Table for spare parts
CREATE TABLE IF NOT EXISTS repuestos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    cantidad INT NOT NULL,
    imagen MEDIUMBLOB NULL
);

-- Table for sales
CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    repuesto_id INT NOT NULL,
    cliente_id INT NOT NULL,
    cantidad INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (repuesto_id) REFERENCES repuestos(id) ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);

-- Insert 5 sample data for clients
INSERT INTO clientes (nombre, dni) VALUES
('Juan Pérez', '12345678A'),
('María García', '87654321B'),
('Carlos Sánchez', '11223344C'),
('Laura Fernández', '44332211D'),
('Pedro Ramírez', '55667788E');

-- Insert 5 sample data for repuestos
INSERT INTO repuestos (nombre, precio, cantidad, imagen) VALUES
('Filtro de Aceite', 15.99, 50, NULL),
('Pastillas de Freno', 45.50, 30, NULL),
('Bujía NGK', 8.25, 100, NULL),
('Amortiguador Delantero', 89.99, 20, NULL),
('Batería 12V', 79.00, 15, NULL);

-- Insert 5 sample data for ventas
-- Assuming client_id 1-5 and repuesto_id 1-5 exist from above inserts
INSERT INTO ventas (repuesto_id, cliente_id, cantidad, fecha) VALUES
(1, 1, 2, '2023-10-26 10:00:00'),
(2, 2, 1, '2023-10-26 11:30:00'),
(3, 3, 4, '2023-10-27 09:15:00'),
(4, 4, 1, '2023-10-27 14:00:00'),
(5, 5, 1, '2023-10-28 16:45:00');