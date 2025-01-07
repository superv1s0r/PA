-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 06-01-2025 a las 13:10:36
-- Versión del servidor: 8.0.15
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `salud`
--

-- --------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `salud` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `salud`;
--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `id_paciente` int(11) DEFAULT NULL,
  `fecha_cita` datetime DEFAULT NULL,
  `motivo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` enum('Programada','Cancelada','Completada') COLLATE utf8mb4_general_ci DEFAULT 'Programada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `edad` int(11) NOT NULL,
  `genero` enum('Masculino','Femenino') COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_registro` date NOT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `direccion` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id`, `nombre`, `edad`, `genero`, `fecha_registro`, `telefono`, `email`, `direccion`) VALUES
(1, 'Juan Pérez', 34, 'Masculino', '2024-01-01', '5551234', 'juan.perez@paciente.com', 'Calle del Sol, 123, Sevilla, España'),
(2, 'María Gómez', 29, 'Femenino', '2024-01-03', '5555678', 'maria.gomez@paciente.com', 'Avenida de los Naranjos, 45, Sevilla, España\n'),
(3, 'Carlos López', 40, 'Masculino', '2024-01-05', '5559876', 'carlos.lopez@paciente.com', 'Plaza de la Luna, 9, Sevilla, España'),
(4, 'Ana Martínez', 22, 'Femenino', '2024-01-10', '5556543', 'ana.martinez@paciente.com', 'Calle del Río Guadalquivir, 77, Sevilla, España');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `id` int(11) NOT NULL,
  `rol` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`id`, `rol`, `email`, `password`) VALUES
(1, 'paciente', 'juan.perez@paciente.com', '$2y$10$ZjoiXtkHbds2yOZryXFNie7Q2dybjjbUr6gyfNQmNC1Ob8icufLkq'),
(2, 'admin', 'maria.gomez@paciente.com', '$2y$10$ZjoiXtkHbds2yOZryXFNie7Q2dybjjbUr6gyfNQmNC1Ob8icufLkq');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--
ALTER TABLE `perfil`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `Citas_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
