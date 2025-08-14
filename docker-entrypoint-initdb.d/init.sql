-- Crear base de datos
CREATE DATABASE IF NOT EXISTS restaurante;

-- Crear usuario y darle permisos
CREATE USER IF NOT EXISTS 'restaurante'@'%' IDENTIFIED BY 'restaurante';
GRANT ALL PRIVILEGES ON restaurante.* TO 'restaurante'@'%';
FLUSH PRIVILEGES;
