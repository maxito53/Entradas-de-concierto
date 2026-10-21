-- Insertar datos en ARTISTA
INSERT INTO `ARTISTA` (`nombre`, `pais`, `genero`) VALUES
('Tiago PZK', 'Argentina', 'Urbano/R&B'),
('Emilia', 'Argentina', 'Pop/Urbano'),
('Aitana', 'España', 'Pop'),
('Chanel', 'España', 'Pop/Dance'),
('Eric Saade', 'Suecia', 'Pop'),
('ABBA', 'Suecia', 'Pop/Disco'),
('Bruno Mars', 'Estados Unidos', 'Pop/R&B/Funk'),
('Lady Gaga', 'Estados Unidos', 'Pop/Dance'),
('Abraham Mateo', 'España', 'Pop/Urbano'),
('La Oreja de Van Gogh', 'España', 'Pop/Rock'),
('The Weeknd', 'Canadá', 'R&B/Pop'),
('Rosalía', 'España', 'Flamenco/Urbano'),
('Metallica', 'Estados Unidos', 'Heavy Metal');

-- Insertar datos en LUGAR
INSERT INTO `LUGAR` (`nombre`, `direccion`, `ciudad`, `capacidad`) VALUES
('Palau Sant Jordi', 'Passeig Olímpic, 5-7', 'Barcelona', 17000),
('WiZink Center', 'Av. Felipe II, s/n', 'Madrid', 15500),
('Estadio La Cartuja', 'Isla de la Cartuja', 'Sevilla', 60000),
('Estadio Santiago Bernabéu', 'Av. de Concha Espina, 1', 'Madrid', 81044),
('Palacio de Deportes José María Martín Carpena', 'C. Miguel de Mérida Nicolich, 2', 'Málaga', 10000),
('Coliseum A Coruña', 'Av. Alfonso Molina, s/n', 'A Coruña', 15000),
('Plaza de Toros de Valencia', 'C. Xàtiva, 28', 'Valencia', 12000),
('Estadio Olímpico de la Cartuja', 'Isla de la Cartuja', 'Sevilla', 57000),
('Wizink Center', 'Av. Felipe II, s/n', 'Madrid', 15500),
('Palacio de Vistalegre', 'C. Utebo, 1', 'Madrid', 14000);

-- Insertar datos en CONCIERTO
INSERT INTO `CONCIERTO` (`nombre`, `descripcion`, `artista_id`, `imagen`) VALUES
('Gotti A Tour', 'Gira internacional de Tiago PZK con sus grandes éxitos', 1, 'tiago.png'),
('MP3 Tour', 'Gira mundial de Emilia presentando su nuevo álbum', 2, 'emilia.png'),
('Alpha Tour', 'Gira de Aitana con su último álbum', 3, 'aitana.png'),
('Agua Tour', 'Gira de Chanel tras su primer disco', 4, 'chanel.png'),
('Melodifestivalen Tour', 'Eric Saade en gira por Europa', 5, 'eric.png'),
('ABBA Voyage', 'Espectáculo conmemorativo de ABBA', 6, 'abba.png'),
('24K Magic World Tour', 'Bruno Mars en gira mundial', 7, 'bruno.png'),
('Chromatica Ball', 'Lady Gaga presenta su álbum Chromatica', 8, 'gaga.png'),
('Insomnio Tour', 'Abraham Mateo en gira con su nuevo álbum', 9, 'abraham.png'),
('Un Susurro en la Tormenta', 'La Oreja de Van Gogh en gira por España', 10, 'gogh.png'),
('After Hours Tour', 'Gira mundial de The Weeknd', 11, 'weeknd.png'),
('Motomami World Tour', 'Gira internacional de Rosalía', 12, 'rosalia.png'),
('M72 World Tour', 'Gira mundial de Metallica', 13, 'metallica.png');

-- Insertar datos en ZONA
INSERT INTO `ZONA` (`nombre`, `descripcion`) VALUES
('Pista', 'Zona de pie'),
('VIP', 'Zona preferente con servicios especiales'),
('Grada', 'Asientos numerados en grada'),
('Golden', 'Primera fila frente al escenario'),
('Platea', 'Asientos centrales con buena visibilidad'),
('Tribuna', 'Asientos elevados con visión panorámica'),
('Fan Pit', 'Zona exclusiva cerca del escenario'),
('Diamond', 'Experiencia premium con meet & greet');

-- Insertar datos en SESION
INSERT INTO `SESION` (`concierto_id`, `lugar_id`, `fecha`, `hora`, `estado`) VALUES
(1, 1, '2025-05-10', '21:00:00', 'PROGRAMADO'),
(2, 2, '2025-05-10', '20:30:00', 'PROGRAMADO'),
(3, 4, '2025-05-10', '22:00:00', 'PROGRAMADO'),

(4, 7, '2025-05-15', '20:00:00', 'PROGRAMADO'),
(5, 8, '2025-05-15', '21:30:00', 'PROGRAMADO'),
(6, 2, '2025-05-15', '19:30:00', 'PROGRAMADO'),

(7, 3, '2025-05-20', '20:00:00', 'PROGRAMADO'),
(8, 4, '2025-05-20', '21:30:00', 'PROGRAMADO'),
(9, 5, '2025-05-20', '19:00:00', 'PROGRAMADO'),
(10, 6, '2025-05-20', '22:00:00', 'PROGRAMADO'),

(11, 9, '2025-06-10', '20:30:00', 'PROGRAMADO'),
(12, 3, '2025-06-10', '21:00:00', 'PROGRAMADO'),
(13, 1, '2025-06-10', '19:00:00', 'PROGRAMADO'),

(1, 3, '2025-07-15', '20:00:00', 'PROGRAMADO'),
(1, 10, '2025-07-15', '22:30:00', 'PROGRAMADO'),

(2, 5, '2025-07-20', '16:00:00', 'PROGRAMADO'),
(2, 5, '2025-07-20', '21:00:00', 'PROGRAMADO'),

(3, 6, '2025-08-05', '21:00:00', 'PROGRAMADO'),
(4, 9, '2025-08-10', '20:30:00', 'PROGRAMADO'),

(5, 10, '2025-08-15', '19:00:00', 'PROGRAMADO'),
(6, 4, '2025-08-15', '21:30:00', 'PROGRAMADO'),
(7, 8, '2025-08-15', '20:00:00', 'PROGRAMADO'),

(8, 1, '2025-09-05', '20:30:00', 'PROGRAMADO'),
(9, 7, '2025-09-05', '22:00:00', 'PROGRAMADO'),
(10, 10, '2025-09-05', '19:00:00', 'PROGRAMADO'),

(11, 8, '2025-10-12', '21:00:00', 'PROGRAMADO'),
(12, 9, '2025-10-12', '20:00:00', 'PROGRAMADO'),

(13, 2, '2025-10-20', '16:00:00', 'PROGRAMADO'),
(13, 2, '2025-10-20', '21:30:00', 'PROGRAMADO'),
(10, 6, '2025-10-20', '20:00:00', 'PROGRAMADO'),
(7, 4, '2025-10-20', '22:00:00', 'PROGRAMADO');

-- Insertar datos en PRECIO
INSERT INTO `PRECIO` (`sesion_id`, `zona_id`, `importe`) VALUES
-- sesiones de Tiago PZK
(1, 1, 55.00),
(1, 2, 110.00),
(1, 3, 40.00),
(1, 4, 180.00),
(2, 1, 60.00),
(2, 2, 120.00),
(2, 3, 45.00),
(2, 4, 200.00),

-- sesiones de Emilia
(3, 1, 50.00),
(3, 2, 105.00),
(3, 3, 38.00),
(3, 4, 175.00),
(4, 1, 48.00),
(4, 2, 100.00),
(4, 3, 35.00),
(4, 4, 165.00),

-- sesiones de Aitana
(5, 1, 65.00),
(5, 2, 130.00),
(5, 3, 50.00),
(5, 4, 220.00),
(5, 7, 150.00),
(6, 1, 60.00),
(6, 2, 125.00),
(6, 3, 45.00),
(6, 4, 210.00),
(6, 7, 140.00),

-- sesiones de Chanel
(7, 1, 58.00),
(7, 2, 115.00),
(7, 3, 42.00),
(7, 4, 190.00),
(8, 1, 62.00),
(8, 2, 125.00),
(8, 3, 48.00),
(8, 4, 205.00),

-- sesiones de Eric Saade
(9, 1, 52.00),
(9, 2, 105.00),
(9, 3, 40.00),
(9, 4, 170.00),
(10, 1, 50.00),
(10, 2, 100.00),
(10, 3, 38.00),
(10, 4, 165.00),

-- sesiones de ABBA
(11, 1, 75.00),
(11, 2, 150.00),
(11, 3, 60.00),
(11, 4, 250.00),
(11, 8, 300.00),
(12, 1, 80.00),
(12, 2, 160.00),
(12, 3, 65.00),
(12, 4, 270.00),
(12, 8, 320.00),

-- sesiones de Bruno Mars
(13, 1, 85.00),
(13, 2, 170.00),
(13, 3, 70.00),
(13, 4, 280.00),
(13, 8, 350.00),
(14, 1, 90.00),
(14, 2, 180.00),
(14, 3, 75.00),
(14, 4, 300.00),
(14, 8, 380.00),

-- sesiones de Lady Gaga
(15, 1, 95.00),
(15, 2, 190.00),
(15, 3, 80.00),
(15, 4, 320.00),
(15, 8, 400.00),
(16, 1, 90.00),
(16, 2, 180.00),
(16, 3, 75.00),
(16, 4, 300.00),
(16, 8, 380.00),

-- sesiones de Abraham Mateo
(17, 1, 45.00),
(17, 2, 90.00),
(17, 3, 35.00),
(17, 4, 150.00),
(18, 1, 48.00),
(18, 2, 95.00),
(18, 3, 38.00),
(18, 4, 160.00),

-- sesiones de La Oreja de Van Gogh
(19, 1, 55.00),
(19, 2, 110.00),
(19, 3, 42.00),
(19, 4, 180.00),
(20, 1, 58.00),
(20, 2, 115.00),
(20, 3, 45.00),
(20, 4, 190.00),

-- Psesiones de The Weeknd
(21, 1, 100.00),
(21, 2, 200.00),
(21, 3, 85.00),
(21, 4, 350.00),
(21, 8, 450.00),
(22, 1, 105.00),
(22, 2, 210.00),
(22, 3, 90.00),
(22, 4, 370.00),
(22, 8, 480.00),

-- sesiones de Rosalía
(23, 1, 75.00),
(23, 2, 150.00),
(23, 3, 60.00),
(23, 4, 250.00),
(24, 1, 80.00),
(24, 2, 160.00),
(24, 3, 65.00),
(24, 4, 270.00),

-- sesiones de Metallica
(25, 1, 90.00),
(25, 2, 180.00),
(25, 3, 70.00),
(25, 4, 300.00),
(26, 1, 95.00),
(26, 2, 190.00),
(26, 3, 75.00),
(26, 4, 320.00);

-- Insertar datos en CLIENTE
INSERT INTO `CLIENTE` (`dni`, `nombre`, `apellidos`, `email`, `telefono`) VALUES
('12345678A', 'Juan', 'García López', 'juan.garcia@ejemplo.com', '666111222'),
('23456789B', 'María', 'Martínez Pérez', 'maria.martinez@ejemplo.com', '666222333'),
('34567890C', 'Carlos', 'Rodríguez Sánchez', 'carlos.rodriguez@ejemplo.com', '666333444'),
('45678901D', 'Ana', 'Fernández Gómez', 'ana.fernandez@ejemplo.com', '666444555'),
('56789012E', 'Pedro', 'González Ruiz', 'pedro.gonzalez@ejemplo.com', '666555666'),
('67890123F', 'Laura', 'López Castro', 'laura.lopez@ejemplo.com', '666666777'),
('78901234G', 'Miguel', 'Sánchez Torres', 'miguel.sanchez@ejemplo.com', '666777888'),
('89012345H', 'Carmen', 'Díaz Romero', 'carmen.diaz@ejemplo.com', '666888999'),
('90123456I', 'Javier', 'Moreno Ortega', 'javier.moreno@ejemplo.com', '666999000'),
('01234567J', 'Lucía', 'Jiménez Navarro', 'lucia.jimenez@ejemplo.com', '677000111');

-- Insertar datos en PAGO
INSERT INTO `PAGO` (`cliente_id`, `fecha`, `total`, `metodo`, `referencia`, `estado`) VALUES
(1, '2025-04-15', 220.00, 'Tarjeta', 'PAGO12345', 'EFECTUADO'),
(2, '2025-04-16', 110.00, 'PayPal', 'PAGO23456', 'EFECTUADO'),
(3, '2025-04-17', 140.00, 'Transferencia', 'PAGO34567', 'EFECTUADO'),
(4, '2025-04-18', 180.00, 'Tarjeta', 'PAGO45678', 'EFECTUADO'),
(5, '2025-04-19', 250.00, 'PayPal', 'PAGO56789', 'EFECTUADO'),
(6, '2025-04-20', 350.00, 'Tarjeta', 'PAGO67890', 'EFECTUADO'),
(7, '2025-04-21', 190.00, 'Transferencia', 'PAGO78901', 'EFECTUADO'),
(8, '2025-04-22', 300.00, 'Tarjeta', 'PAGO89012', 'EFECTUADO'),
(9, '2025-04-23', 160.00, 'PayPal', 'PAGO90123', 'EFECTUADO'),
(10, '2025-04-24', 210.00, 'Tarjeta', 'PAGO01234', 'EFECTUADO');

-- Insertar datos en ENTRADA
INSERT INTO `ENTRADA` (`codigo`, `pago_id`, `sesion_id`, `zona_id`, `fila`, `asiento`, `precio_id`, `estado`) VALUES
-- conciertos de Tiago PZK
('ENT-2025-00001', 1, 1, 4, 'A', '15', 4, 'VALIDA'),
('ENT-2025-00002', 1, 1, 4, 'A', '16', 4, 'VALIDA'),
-- conciertos de Emilia
('ENT-2025-00003', 2, 3, 2, 'B', '10', 10, 'VALIDA'),
-- conciertos de Aitana
('ENT-2025-00004', 3, 5, 3, 'C', '5', 18, 'VALIDA'),
('ENT-2025-00005', 3, 5, 3, 'C', '6', 18, 'VALIDA'),
-- conciertos de Chanel
('ENT-2025-00006', 4, 7, 4, 'A', '8', 28, 'VALIDA'),
-- conciertos de Eric Saade
('ENT-2025-00007', 5, 9, 2, 'B', '20', 34, 'VALIDA'),
('ENT-2025-00008', 5, 9, 2, 'B', '21', 34, 'VALIDA'),
-- conciertos de ABBA
('ENT-2025-00009', 6, 11, 8, 'A', '5', 47, 'VALIDA'),
-- conciertos de Bruno Mars
('ENT-2025-00010', 7, 13, 3, 'D', '12', 53, 'VALIDA'),
('ENT-2025-00011', 7, 13, 3, 'D', '13', 53, 'VALIDA'),
-- conciertos de Lady Gaga
('ENT-2025-00012', 8, 15, 4, 'A', '1', 64, 'VALIDA'),
-- conciertos de Abraham Mateo
('ENT-2025-00013', 9, 17, 2, 'C', '15', 72, 'VALIDA'),
('ENT-2025-00014', 9, 17, 2, 'C', '16', 72, 'VALIDA'),
-- conciertos de La Oreja de Van Gogh
('ENT-2025-00015', 10, 19, 1, NULL, NULL, 79, 'VALIDA'),
('ENT-2025-00016', 10, 19, 1, NULL, NULL, 79, 'VALIDA'),
-- conciertos de The Weeknd
('ENT-2025-00017', 1, 21, 8, 'A', '10', 85, 'VALIDA'),
-- conciertos de Rosalía
('ENT-2025-00018', 2, 23, 4, 'B', '5', 94, 'VALIDA'),
-- conciertos de Metallica
('ENT-2025-00019', 3, 25, 2, 'C', '20', 102, 'VALIDA'),
('ENT-2025-00020', 3, 25, 2, 'C', '21', 102, 'VALIDA');