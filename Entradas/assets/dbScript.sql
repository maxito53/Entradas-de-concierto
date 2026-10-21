-- Modificación de la base de datos TuraMax_Entradas para resolver el problema de asientos duplicados

-- Recreamos la base de datos para asegurar una implementación limpia
DROP DATABASE IF EXISTS TuraMax_Entradas;
CREATE DATABASE TuraMax_Entradas;
USE TuraMax_Entradas;

-- Creamos todas las tablas básicas primero
CREATE TABLE IF NOT EXISTS `ARTISTA` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `pais` varchar(50),
  `genero` varchar(50)
);

CREATE TABLE IF NOT EXISTS `CONCIERTO` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `artista_id` int(11) NOT NULL,
  `imagen` varchar(100)
);

CREATE TABLE IF NOT EXISTS `LUGAR` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `ciudad` varchar(50) NOT NULL,
  `capacidad` int(11)
);

CREATE TABLE IF NOT EXISTS `ZONA` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `descripcion` varchar(100)
);

CREATE TABLE IF NOT EXISTS `SESION` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `concierto_id` int(11) NOT NULL,
  `lugar_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `estado` enum('PROGRAMADO','CANCELADO','COMPLETADO','POSPUESTO') DEFAULT 'PROGRAMADO'
);

CREATE TABLE IF NOT EXISTS `PRECIO` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `sesion_id` int(11) NOT NULL,
  `zona_id` int(11) NOT NULL,
  `importe` decimal(10,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS `CLIENTE` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `dni` varchar(15) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(15),
  `password` varchar(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS `PAGO` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `metodo` varchar(50),
  `referencia` varchar(100),
  `estado` enum('PENDIENTE', 'EFECTUADO') DEFAULT 'PENDIENTE'
);

-- Creamos una nueva tabla ASIENTO para definir los asientos disponibles en cada zona de cada lugar
CREATE TABLE IF NOT EXISTS `ASIENTO` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `lugar_id` int(11) NOT NULL,
  `zona_id` int(11) NOT NULL,
  `fila` varchar(5) NOT NULL,
  `numero` varchar(5) NOT NULL,
  `estado` enum('DISPONIBLE','NO DISPONIBLE') DEFAULT 'DISPONIBLE',
  UNIQUE KEY `asiento_unico` (`lugar_id`, `zona_id`, `fila`, `numero`)
);

-- Modificamos la tabla ENTRADA para que referencie a un asiento específico
CREATE TABLE IF NOT EXISTS `ENTRADA` (
  `codigo` varchar(30) PRIMARY KEY,
  `pago_id` int(11) NOT NULL,
  `sesion_id` int(11) NOT NULL,
  `asiento_id` int(11) NOT NULL,
  `precio_id` int(11) NOT NULL,
  `estado` enum('VALIDA','USADA','CANCELADA','REEMBOLSADA') DEFAULT 'VALIDA',
  UNIQUE KEY `entrada_unica` (`sesion_id`, `asiento_id`)
);

-- Añadimos todas las relaciones de clave foránea
-- Relaciones de CONCIERTO
ALTER TABLE `CONCIERTO`
ADD FOREIGN KEY (`artista_id`) REFERENCES `ARTISTA` (`id`);

-- Relaciones de SESION
ALTER TABLE `SESION`
ADD FOREIGN KEY (`concierto_id`) REFERENCES `CONCIERTO` (`id`),
ADD FOREIGN KEY (`lugar_id`) REFERENCES `LUGAR` (`id`);

-- Relaciones de PRECIO
ALTER TABLE `PRECIO`
ADD FOREIGN KEY (`sesion_id`) REFERENCES `SESION` (`id`),
ADD FOREIGN KEY (`zona_id`) REFERENCES `ZONA` (`id`);

-- Relaciones de PAGO
ALTER TABLE `PAGO`
ADD FOREIGN KEY (`cliente_id`) REFERENCES `CLIENTE` (`id`);

-- Relaciones de la nueva tabla ASIENTO
ALTER TABLE `ASIENTO`
ADD FOREIGN KEY (`lugar_id`) REFERENCES `LUGAR` (`id`),
ADD FOREIGN KEY (`zona_id`) REFERENCES `ZONA` (`id`);

-- Relaciones de la tabla ENTRADA modificada
ALTER TABLE `ENTRADA`
ADD FOREIGN KEY (`pago_id`) REFERENCES `PAGO` (`id`),
ADD FOREIGN KEY (`sesion_id`) REFERENCES `SESION` (`id`),
ADD FOREIGN KEY (`asiento_id`) REFERENCES `ASIENTO` (`id`),
ADD FOREIGN KEY (`precio_id`) REFERENCES `PRECIO` (`id`);
