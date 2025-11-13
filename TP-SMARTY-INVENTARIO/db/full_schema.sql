-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS inventarioRepuestos;

-- Use the inventarioRepuestos database
USE inventarioRepuestos;

-- Drop tables if they exist to ensure a clean slate
DROP TABLE IF EXISTS detalle_pedido; -- Drop child table first
DROP TABLE IF EXISTS pedidos;        -- Then parent table
DROP TABLE IF EXISTS ventas;
DROP TABLE IF EXISTS repuestos;
-- DROP TABLE IF EXISTS clientes; -- Removed as 'personas' replaces it
DROP TABLE IF EXISTS personas;


-- Table for personas (clients and administrators)
CREATE TABLE IF NOT EXISTS personas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL,
    dni VARCHAR(8) UNIQUE NULL
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

-- Table for sales (legacy, will be replaced by pedidos/detalle_pedido for new sales)
CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    repuesto_id INT NOT NULL,
    cliente_id INT NOT NULL,
    cantidad INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (repuesto_id) REFERENCES repuestos(id) ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES personas(id) ON DELETE CASCADE
);

-- Table for orders (pedidos)
-- Valid estados: 'pendiente', 'completado', 'cancelado'
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2) NOT NULL,
    estado VARCHAR(50) NOT NULL DEFAULT 'pendiente',
    FOREIGN KEY (usuario_id) REFERENCES personas(id) ON DELETE CASCADE
);

-- Table for order details (detalle_pedido)
CREATE TABLE IF NOT EXISTS detalle_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    repuesto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (repuesto_id) REFERENCES repuestos(id) ON DELETE CASCADE
);
