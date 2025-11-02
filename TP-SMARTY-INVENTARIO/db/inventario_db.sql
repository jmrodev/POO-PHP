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
    cantidad INT NOT NULL
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

-- Insert some sample data for clients
INSERT INTO clientes (nombre, dni) VALUES
('Ana Torres', '11111111'),
('Pedro Gomez', '22222222'),
('Laura Fernandez', '33333333');

-- More sample data for clients
INSERT INTO clientes (nombre, dni) VALUES
('Carlos Ruiz', '44444444'),
('Marta Lopez', '55555555'),
('Javier Perez', '66666666'),
('Sofia Garcia', '77777777'),
('Diego Martinez', '88888888'),
('Elena Sanchez', '99999999'),
('Fernando Diaz', '10101010'),
('Patricia Romero', '11223344'),
('Ricardo Torres', '22334455'),
('Gabriela Vargas', '33445566'),
('Andres Castro', '44556677'),
('Valeria Morales', '55667788'),
('Sergio Herrera', '66778899'),
('Mariana Ortiz', '77889900'),
('Pablo Guerrero', '88990011'),
('Lucia Mendoza', '99001122'),
('Manuel Rojas', '00112233'),
('Daniela Soto', '11223300'),
('Jorge Ramos', '22330011'),
('Camila Flores', '33001122');

-- Insert some sample data for repuestos
INSERT INTO repuestos (nombre, precio, cantidad) VALUES
('Filtro de Aceite', 12.99, 150),
('Batería', 75.00, 30),
('Neumático', 120.50, 80);

-- More sample data for repuestos
INSERT INTO repuestos (nombre, precio, cantidad) VALUES
('Bujía', 5.50, 200),
('Pastillas de Freno', 35.75, 70),
('Amortiguador', 90.00, 40),
('Aceite de Motor 5W-30', 25.99, 100),
('Filtro de Aire', 18.20, 120),
('Correa de Distribución', 50.00, 60),
('Disco de Freno', 45.00, 80),
('Líquido de Frenos', 8.50, 150),
('Limpiaparabrisas', 10.00, 200),
('Radiador', 150.00, 20),
('Bomba de Agua', 70.00, 30),
('Termostato', 15.00, 90),
('Sensor de Oxígeno', 40.00, 50),
('Alternador', 180.00, 15),
('Motor de Arranque', 160.00, 18),
('Embrague', 200.00, 25),
('Caja de Cambios', 500.00, 10),
('Faros Delanteros', 100.00, 30),
('Pilotos Traseros', 80.00, 35),
('Espejo Retrovisor', 25.00, 60);

-- Insert some sample data for ventas
-- Assuming client_id 1 and repuesto_id 1 exist
INSERT INTO ventas (repuesto_id, cliente_id, cantidad) VALUES
(1, 1, 2),
(2, 2, 1),
(3, 1, 4);

-- More sample data for ventas (assuming existing client and repuesto IDs)
INSERT INTO ventas (repuesto_id, cliente_id, cantidad) VALUES
(4, 3, 1),
(5, 4, 3),
(6, 5, 1),
(7, 6, 2),
(8, 7, 1),
(9, 8, 4),
(10, 9, 1),
(11, 10, 2),
(12, 11, 1),
(13, 12, 3),
(14, 13, 1),
(15, 14, 2),
(16, 15, 1),
(17, 16, 1),
(18, 17, 2),
(19, 18, 1),
(20, 19, 3),
(1, 20, 1),
(2, 1, 2),
(3, 2, 1),
(4, 3, 1),
(5, 4, 2),
(6, 5, 1),
(7, 6, 3),
(8, 7, 1),
(9, 8, 2),
(10, 9, 1),
(11, 10, 1),
(12, 11, 2),
(13, 12, 1),
(14, 13, 3),
(15, 14, 1),
(16, 15, 2),
(17, 16, 1),
(18, 17, 1),
(19, 18, 2),
(20, 19, 1),
(1, 20, 3),
(2, 1, 1),
(3, 2, 2);