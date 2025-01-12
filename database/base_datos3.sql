CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `tipo_usuario` enum('administrador','empleado','cliente') DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Datos para la tabla `usuarios`
INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `tipo_usuario`, `fecha_registro`) VALUES
(11, 'natsj', 'natsupfb@gmail.com', '$2y$10$E34N71qdCiiC0U6wobw3PO/j70xqmP1FS/IGjcBsU4M/ulDCgWlTy', 'empleado', '2024-09-27 04:21:00'),
(112, 'steeven', 'steevenpfb@gmail.com', '$2y$10$zj8U.tenMpQZ3Fyq9YUDcuaS8gN0yuOrasjlXDFYTgHakh5ram2zC', 'administrador', '2024-12-02 12:32:15'),
(118, 'euroman1', 'euromansistemas@gmail.com', '$2y$10$rsA7o6/WTYlBzxEPW2hawOk4y7he7iZIQNTUAuEW149RQaVOUqJ5i', 'cliente', '2024-12-14 00:16:25'),
(122, 'juan', 'juan@gmail.com', '$2y$10$e53jxIMf6zRT0HYI2FMNVultdubGE5ZUzT9wITN.rVUijI0T.mL4q', 'empleado', '2024-12-18 07:59:44');

-- Índices para la tabla `usuarios`
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `vehiculos`
CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `matricula` varchar(20) NOT NULL,
  `disponibilidad` enum('Disponible','No disponible') DEFAULT 'Disponible',
  `tarifa` decimal(10,2) NOT NULL,
  `estado` enum('Excelente','Bueno','Regular','Malo') DEFAULT 'Bueno',
  `color` varchar(30) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `tipo_vehiculo` enum('auto','camioneta','camion','maquinaria') DEFAULT NULL,
  `imagen` varchar(50) DEFAULT NULL,
  `tipo_transmision` enum('automatico','manual') DEFAULT NULL,
  `tipo_combustible` enum('extra','super','diesel','electricidad') DEFAULT NULL,
  `cilindraje` varchar(50) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Datos para la tabla `vehiculos`
INSERT INTO `vehiculos` (`id`, `marca`, `modelo`, `matricula`, `disponibilidad`, `tarifa`, `estado`, `color`, `fecha_registro`, `tipo_vehiculo`, `imagen`, `tipo_transmision`, `tipo_combustible`, `cilindraje`, `descripcion`) VALUES
(35, 'CHEVROLET', 'MONTANA', 'MTN-123', 'No disponible', 123.00, 'Excelente', 'NEGRO', '2024-12-02 12:37:35', 'camioneta', '674daa0f20042_montana.avif', 'automatico', 'extra', '2L', 'S'),
(36, 'CHEVROLET', 'FERRA', 'FRA-123', 'No disponible', 123.00, 'Bueno', 'ROJO', '2024-12-02 12:39:39', 'auto', '674daa8b818ad_deportivo.jpg', 'automatico', 'extra', '2L', 'Buen vehiculo'),
(37, 'REDWAR', 'H3', 'ABC-123', 'No disponible', 123.00, 'Excelente', 'NEGRO', '2024-12-02 17:27:26', 'camioneta', '674dedfe28b55_auto.jpg', 'manual', 'extra', '2L', 'D'),
(38, 'CHEVROLET', 'SPARK', 'SPK-123', 'No disponible', 100.00, 'Excelente', 'VERDE', '2024-12-14 19:28:53', 'camioneta', '675ddc759a904_images.jpeg', 'automatico', 'extra', '2L', 'CX'),
(49, 'CHEVROLET', 'AVEO', 'AVE-123', 'Disponible', 111.00, 'Excelente', NULL, '2024-12-15 18:06:22', 'auto', '675f1a9e41c02_images (1).jpeg', 'automatico', 'extra', '2L', 'CYU'),
(50, 'Toyota', 'Corolla', 'TCC-123', 'Disponible', 123.00, 'Excelente', NULL, '2025-01-06 15:35:29', 'auto', '677bf841aa373_tga1.jpeg', 'automatico', 'extra', '2L', 'Buen vehiculo');

-- Índices para la tabla `vehiculos`
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricula` (`matricula`);

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `tarifas`
CREATE TABLE `tarifas` (
  `id` int(11) NOT NULL,
  `tipo_vehiculo` varchar(50) NOT NULL,
  `duracion_alquiler` int(11) NOT NULL,
  `temporada` enum('alta','baja') NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Datos para la tabla `tarifas`
INSERT INTO `tarifas` (`id`, `tipo_vehiculo`, `duracion_alquiler`, `temporada`, `precio`) VALUES
(1, 'camioneta', 3, 'alta', 100.00),
(2, 'camioneta', 3, 'alta', 100.00),
(3, 'camion', 3, 'alta', 1.00),
(4, 'maquinaria', 1, 'alta', 123.00);

-- Índices para la tabla `tarifas`
ALTER TABLE `tarifas`
  ADD PRIMARY KEY (`id`);

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `alquileres`
CREATE TABLE `alquileres` (
  `id` int(11) NOT NULL,
  `vehiculo_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_inicio` varchar(50) NOT NULL,
  `fecha_fin` varchar(50) DEFAULT NULL,
  `estado` enum('Activo','Inactivo','Reservado') DEFAULT 'Activo',
  `devuelto` enum('si','no') DEFAULT 'no',
  `monto_esperado` decimal(10,2) DEFAULT NULL COMMENT 'Monto esperado del alquiler (tarifa base sin cargos adicionales)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Datos para la tabla `alquileres`
INSERT INTO `alquileres` (`id`, `vehiculo_id`, `usuario_id`, `fecha_inicio`, `fecha_fin`, `estado`, `devuelto`, `monto_esperado`) VALUES
(104, 38, 118, '2025-01-10 12:00:00', '2025-01-10 15:00:00', 'Activo', 'no', 300.00),
(112, 35, 11, '2025-01-10 12:00:00', '2025-01-10 12:01:00', 'Activo', 'no', 123.00),
(117, 36, 118, '2025-01-11 12:00:00', '2025-01-11 14:00:00', 'Activo', 'no', 246.00),
(118, 37, 118, '2025-01-11 12:00:00', '2025-01-11 15:00:00', 'Reservado', 'no', 369.00);













CREATE TABLE `devoluciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alquiler_id` int(11) NOT NULL,
  `fecha_devolucion` datetime NOT NULL COMMENT 'Fecha y hora de la devolución',
  `estado_vehiculo` enum('Excelente', 'Bueno', 'Regular', 'Malo') DEFAULT 'Excelente' COMMENT 'Estado general del vehículo al devolverlo',
  `limpieza` enum('Limpio', 'Sucia') DEFAULT 'Limpio' COMMENT 'Estado de limpieza del vehículo',
  `nivel_combustible` enum('Lleno', '3/4', '1/2', '1/4', 'Vacío') DEFAULT 'Lleno' COMMENT 'Nivel de combustible al devolver el vehículo',  
  `daños_visibles` enum('Ninguno', 'Rayones', 'Abolladuras', 'Cristales rotos', 'Otros') DEFAULT 'Ninguno' COMMENT 'Daños visibles reportados al devolver el vehículo',
  `costo_limpieza` decimal(10,2) DEFAULT 0.00 COMMENT 'Costo por limpieza del vehículo',
  `costo_danos_leves` decimal(10,2) DEFAULT 0.00 COMMENT 'Costo por daños leves en el vehículo',
  `costo_danos_graves` decimal(10,2) DEFAULT 0.00 COMMENT 'Costo por daños graves en el vehículo',
  `costo_combustible` decimal(10,2) DEFAULT 0.00 COMMENT 'Costo por falta de combustible al devolver el vehículo',
  `tiempo_retraso` int(11) DEFAULT 0 COMMENT 'Horas de retraso en la devolución respecto al contrato',
  `cargo_por_retraso` decimal(10,2) DEFAULT 0.00 COMMENT 'Cargos adicionales por retraso en la devolución',
  `cargos_adicionales` decimal(10,2) DEFAULT 0.00 COMMENT 'Cargos adicionales totales (daños, limpieza, combustible, retrasos)',
  `observaciones` text DEFAULT NULL COMMENT 'Comentarios adicionales sobre la devolución',
  `costo_total` decimal(10,2) GENERATED ALWAYS AS (`costo_limpieza` + `costo_danos_leves` + `costo_danos_graves` + `costo_combustible` + `cargo_por_retraso`) STORED COMMENT 'Costo total calculado automáticamente',
  PRIMARY KEY (`id`),
  KEY `alquiler_id` (`alquiler_id`),
  CONSTRAINT `devoluciones_ibfk_1` FOREIGN KEY (`alquiler_id`) REFERENCES `alquileres` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
