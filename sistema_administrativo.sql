-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-03-2026 a las 23:13:26
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_administrativo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_entrega` datetime NOT NULL,
  `estado` enum('pendiente','en curso','finalizada') DEFAULT 'pendiente',
  `ficha_id` int(11) DEFAULT NULL,
  `creador_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `nombre`, `descripcion`, `fecha_creacion`, `fecha_entrega`, `estado`, `ficha_id`, `creador_id`) VALUES
(1, 'Curso de Cisco', 'Curso que se desarrollara durante el trimestre, y enviar la certificación', '2026-02-25 13:29:07', '2026-03-26 12:00:00', 'en curso', 1, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aprendices`
--

CREATE TABLE `aprendices` (
  `id` int(11) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `tipo_documento` enum('CC','TI','CE') NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `ficha_id` int(11) DEFAULT NULL,
  `estado_academico` enum('activo','en formacion','retirado','egresado') DEFAULT 'activo',
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `aprendices`
--

INSERT INTO `aprendices` (`id`, `documento`, `tipo_documento`, `nombre`, `apellido`, `email`, `telefono`, `direccion`, `ficha_id`, `estado_academico`, `fecha_registro`) VALUES
(1, '100876259', 'CC', 'Juan', 'Mopan', 'Juan34mopa@gmail.com', '5858325', 'Calle 87 # 93 A 93', 1, 'activo', '2026-02-25 12:27:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fichas`
--

CREATE TABLE `fichas` (
  `id` int(11) NOT NULL,
  `numero_ficha` varchar(50) NOT NULL,
  `programa_formacion` varchar(200) NOT NULL,
  `estado` enum('activa','inactiva','finalizada') DEFAULT 'activa',
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `jornada` enum('mañana','tarde','noche') NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fichas`
--

INSERT INTO `fichas` (`id`, `numero_ficha`, `programa_formacion`, `estado`, `fecha_inicio`, `fecha_fin`, `jornada`, `fecha_creacion`) VALUES
(1, '3147208', 'ADSO', 'activa', '2025-02-13', '2027-04-29', 'tarde', '2026-02-25 12:26:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `creado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','instructor','aprendiz') NOT NULL DEFAULT 'admin',
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `token_recuperacion` varchar(255) DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `documento`, `nombre`, `apellido`, `email`, `password`, `rol`, `estado`, `fecha_creacion`, `token_recuperacion`, `token_expira`) VALUES
(1, '1234', 'Administrador', 'SENA', 'admin@sena.com', '1234', 'admin', 'activo', '2026-02-24 13:35:35', NULL, NULL),
(2, '123456778', 'Juan', 'Perez', 'juan@gmail.com', '$2y$10$MzxdBS333c7ES8Ic5vhuEOjCTvlqTm8M3qyryfyKhZN.sTuju.E2m', 'admin', 'activo', '2026-02-24 15:19:04', NULL, NULL),
(5, '1000000000', 'Juan Felipe Quiros', '', 'felipequiros416@gmail.com', '$2y$10$SAP6lDTUfBdCeDdZ8YMDu.hwU5xRQ55/mY5.lu7NzwLlMNUmpwDta', 'admin', 'activo', '2026-02-25 12:58:23', NULL, NULL),
(6, '1234567890', 'Sofia', '', 'sofia@gmail.com', '$2y$10$gK0yIpPd.4A84TH1icy6re74hs/kLTy0cSe/SOC0C5Os5QxaLfVOO', 'instructor', 'activo', '2026-02-25 16:16:54', NULL, NULL),
(7, '12345678901', 'sofia', '', 'sofia12@gmail.com', '$2y$10$vuc12nos4F91o7WpYeHCcO/Xws7y7dELfzxd4eD2wDPYpc0m3zv72', 'instructor', 'activo', '2026-02-25 16:44:09', NULL, NULL),
(8, '0987654321', 'JOSUE HELISEO', '', 'sofiaplu@sena.com', '$2y$10$W3dhupy72TLB8kZXESViTO6A9/LnleFIH0y0jjDqq57d2D/8S3mUC', 'admin', 'activo', '2026-02-25 17:16:36', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ficha_id` (`ficha_id`),
  ADD KEY `creador_id` (`creador_id`);

--
-- Indices de la tabla `aprendices`
--
ALTER TABLE `aprendices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento` (`documento`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `ficha_id` (`ficha_id`);

--
-- Indices de la tabla `fichas`
--
ALTER TABLE `fichas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_ficha` (`numero_ficha`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento` (`documento`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `aprendices`
--
ALTER TABLE `aprendices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `fichas`
--
ALTER TABLE `fichas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `actividades_ibfk_1` FOREIGN KEY (`ficha_id`) REFERENCES `fichas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `actividades_ibfk_2` FOREIGN KEY (`creador_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `aprendices`
--
ALTER TABLE `aprendices`
  ADD CONSTRAINT `aprendices_ibfk_1` FOREIGN KEY (`ficha_id`) REFERENCES `fichas` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
