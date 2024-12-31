-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 31-12-2024 a las 20:36:20
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

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
CREATE DATABASE IF NOT EXISTS `salud` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `salud`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Pacientes`
--

CREATE TABLE `Pacientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `edad` int(11) NOT NULL,
  `genero` enum('Masculino','Femenino') NOT NULL,
  `fecha_registro` date NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `direccion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Pacientes`
--

INSERT INTO `Pacientes` (`id`, `nombre`, `edad`, `genero`, `fecha_registro`, `telefono`, `email`, `direccion`) VALUES
(1, 'Juan Pérez', 34, 'Masculino', '2024-01-01', '555-1234', 'juan.perez@paciente.com', 'Calle del Sol, 123, Sevilla, España'),
(2, 'María Gómez', 29, 'Femenino', '2024-01-03', '555-5678', 'maria.gomez@paciente.com', 'Avenida de los Naranjos, 45, Sevilla, España\n'),
(3, 'Carlos López', 40, 'Masculino', '2024-01-05', '555-9876', 'carlos.lopez@paciente.com', 'Plaza de la Luna, 9, Sevilla, España'),
(4, 'Ana Martínez', 22, 'Femenino', '2024-01-10', '555-6543', 'ana.martinez@paciente.com', 'Calle del Río Guadalquivir, 77, Sevilla, España');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre_rol`) VALUES
(4, 'Administradores'),
(2, 'Personal'),
(3, 'Sanitarios'),
(1, 'Usuarios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `email`, `password`, `id_rol`) VALUES
(1, 'user1@salud.com', '$2y$10$ZjoiXtkHbds2yOZryXFNie7Q2dybjjbUr6gyfNQmNC1Ob8icufLkq', 1),
(2, 'user2@salud.com', '$2y$10$ZjoiXtkHbds2yOZryXFNie7Q2dybjjbUr6gyfNQmNC1Ob8icufLkq', 2),
(3, 'user3@salud.com', '$2y$10$ZjoiXtkHbds2yOZryXFNie7Q2dybjjbUr6gyfNQmNC1Ob8icufLkq', 3),
(4, 'user4@salud.com', '$2y$10$ZjoiXtkHbds2yOZryXFNie7Q2dybjjbUr6gyfNQmNC1Ob8icufLkq', 4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Pacientes`
--
ALTER TABLE `Pacientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Pacientes`
--
ALTER TABLE `Pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
