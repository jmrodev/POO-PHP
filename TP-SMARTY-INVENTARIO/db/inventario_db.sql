-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS inventarioRepuestos;

-- Use the inventarioRepuestos database
USE inventarioRepuestos;

-- Drop tables if they exist to ensure a clean slate
DROP TABLE IF EXISTS ventas;
DROP TABLE IF EXISTS repuestos;
-- DROP TABLE IF EXISTS clientes; -- Removed as 'personas' replaces it

-- Table for personas (clients and administrators)
CREATE TABLE IF NOT EXISTS personas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL,
    dni VARCHAR(20) UNIQUE NULL
);

-- Table for spare parts
CREATE TABLE IF NOT EXISTS repuestos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    cantidad INT NOT NULL,
    imagen MEDIUMBLOB NULL
);

-- Insert 5 sample data for repuestos
INSERT INTO repuestos (nombre, precio, cantidad, imagen) VALUES
('Filtro de Aceite', 15.99, 50, NULL),
('Pastillas de Freno', 45.50, 30, NULL),
('Bujía NGK', 8.25, 100, NULL),
('Amortiguador Delantero', 89.99, 20, NULL),
('Batería 12V', 79.00, 15, NULL);

-- Table for sales
CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    repuesto_id INT NOT NULL,
    cliente_id INT NOT NULL,
    cantidad INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (repuesto_id) REFERENCES repuestos(id) ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES personas(id) ON DELETE CASCADE
);

-- Removed sample INSERT statements for clientes and ventas as they will be handled by the new system.