-- Base de datos: `prog`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alquileres`
--

CREATE TABLE `alquileres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehiculo_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_inicio` varchar(50) NOT NULL,
  `fecha_fin` varchar(50) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') DEFAULT 'Activo',
  PRIMARY KEY (`id`),
  KEY `vehiculo_id` (`vehiculo_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `alquileres_ibfk_1` FOREIGN KEY (`vehiculo_id`) REFERENCES `vehiculos` (`id`),
  CONSTRAINT `alquileres_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `devoluciones`(

)

--
-- Volcado de datos para la tabla `alquileres`
--

INSERT INTO `alquileres` (`id`, `vehiculo_id`, `usuario_id`, `fecha_inicio`, `fecha_fin`, `estado`) VALUES
(68, 35, 11, '2024-12-02', NULL, 'Activo'),
(69, 49, 118, '2024-12-02', NULL, 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarifas`
--

CREATE TABLE `tarifas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_vehiculo` varchar(50) NOT NULL,
  `duracion_alquiler` int(11) NOT NULL,
  `temporada` enum('alta','baja') NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tarifas`
--

INSERT INTO `tarifas` (`id`, `tipo_vehiculo`, `duracion_alquiler`, `temporada`, `precio`) VALUES
(1, 'camioneta', 3, 'alta', 100.00),
(2, 'camioneta', 3, 'alta', 100.00),
(3, 'camion', 3, 'alta', 1.00),
(4, 'maquinaria', 1, 'alta', 123.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `tipo_usuario` enum('administrador','empleado','cliente') DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `tipo_usuario`, `fecha_registro`) VALUES
(11, 'natsj', 'natsupfb@gmail.com', '$2y$10$E34N71qdCiiC0U6wobw3PO/j70xqmP1FS/IGjcBsU4M/ulDCgWlTy', 'empleado', '2024-09-27 04:21:00'),
(20, 'clienta', 'cliente@gmail.com', '$2y$10$/C8O1AT3ZpKPbOVfYIHGue/EsN6y30Tx0AeYKDuA8A/QUkiOlKA8i', 'cliente', '2024-10-14 20:20:32'),
(49, 'xd', 'xd@gmail.com', '$2y$10$MGy8NqMcnoNJhNYdZN8En.V0bPT.L1LMQgtnpf5YybK.cfR8TS4v.', 'administrador', '2024-10-18 01:23:39'),
(112, 'steeven', 'steevenpfb@gmail.com', '$2y$10$Lin7dP2vWLPSFjGDGuOXlO3LsAR8u2MCHlrXexRn2L/I26qbLLbNC', 'administrador', '2024-12-02 12:32:15'),
(113, 'juan', 'juan@gmail.com', '$2y$10$1axNvfGIE7O7rcf.vzh4GeCpqtiEmjL3mbEYYrfspFuInHYozyUpS', 'administrador', '2024-12-02 17:22:49'),
(118, 'euroman1', 'euromansistemas@gmail.com', '$2y$10$rsA7o6/WTYlBzxEPW2hawOk4y7he7iZIQNTUAuEW149RQaVOUqJ5i', 'cliente', '2024-12-14 00:16:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `matricula` (`matricula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




CREATE TABLE `devoluciones`(

)

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id`, `marca`, `modelo`, `matricula`, `disponibilidad`, `tarifa`, `estado`, `color`, `fecha_registro`, `tipo_vehiculo`, `imagen`, `tipo_transmision`, `tipo_combustible`, `cilindraje`, `descripcion`) VALUES
(35, 'CHEVROLET', 'MONTANA', 'MTN-123', 'No disponible', 123.00, 'Excelente', 'NEGRO', '2024-12-02 12:37:35', 'camioneta', '674daa0f20042_montana.avif', NULL, NULL, '', ''),
(36, 'CHEVROLET', 'FERRA', 'FRA-123', 'Disponible', 123.00, 'Bueno', 'ROJO', '2024-12-02 12:39:39', 'auto', '674daa8b818ad_deportivo.jpg', 'automatico', 'extra', '2L', 'Buen vehiculo'),
(37, 'REDWAR', 'H3', 'ABC-123', 'Disponible', 123.00, 'Excelente', 'NEGRO', '2024-12-02 17:27:26', 'camioneta', '674dedfe28b55_auto.jpg', NULL, NULL, '', ''),
(38, 'CHEVROLET', 'SPARK', 'SPK-123', 'Disponible', 100.00, 'Excelente', 'VERDE', '2024-12-14 19:28:53', NULL, '675ddc759a904_images.jpeg', NULL, NULL, '', ''),
(49, 'CHEVROLET', 'AVEO', 'AVE-123', 'No disponible', 111.00, 'Excelente', NULL, '2024-12-15 18:06:22', 'auto', '675f1a9e41c02_images (1).jpeg', 'automatico', 'extra', '2L', 'LOCO');

-- --------------------------------------------------------

-- AUTO_INCREMENT de la tabla `alquileres`
ALTER TABLE `alquileres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

-- AUTO_INCREMENT de la tabla `tarifas`
ALTER TABLE `tarifas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

-- AUTO_INCREMENT de la tabla `usuarios`
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

-- AUTO_INCREMENT de la tabla `vehiculos`
ALTER TABLE `vehiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

ALTER TABLE alquileres
ADD COLUMN metodo_pago ENUM('tarjeta', 'efectivo') DEFAULT 'efectivo';
ALTER TABLE `alquileres`
CHANGE `monto_total` `monto_esperado` DECIMAL(10, 2) COMMENT 'Monto esperado del alquiler (tarifa base sin cargos adicionales)';

ALTER TABLE `alquileres` 
MODIFY COLUMN `estado` ENUM('Activo', 'Inactivo', 'Reservado') DEFAULT 'Activo';

COMMIT;
