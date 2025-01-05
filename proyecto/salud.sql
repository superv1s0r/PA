-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 04, 2025 at 06:03 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `salud`
--
CREATE DATABASE IF NOT EXISTS `salud` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `salud`;

-- --------------------------------------------------------

--
-- Table structure for table `Citas`
--

CREATE TABLE `Citas` (
  `id` int(11) NOT NULL,
  `id_paciente` int(11) DEFAULT NULL,
  `fecha_cita` datetime DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `estado` enum('Programada','Cancelada','Completada') DEFAULT 'Programada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Pacientes`
--

CREATE TABLE `Pacientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `edad` int(11) NOT NULL,
  `genero` enum('Masculino','Femenino') NOT NULL,
  `fecha_registro` date NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `direccion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Pacientes`
--

INSERT INTO `Pacientes` (`id`, `nombre`, `password`, `edad`, `genero`, `fecha_registro`, `telefono`, `email`, `direccion`) VALUES
(1, 'Juan Pérez', '$2y$10$ZjoiXtkHbds2yOZryXFNie7Q2dybjjbUr6gyfNQmNC1Ob8icufLkq', 34, 'Masculino', '2024-01-01', '5551234', 'juan.perez@paciente.com', 'Calle del Sol, 123, Sevilla, España'),
(2, 'María Gómez', '$2y$10$ZjoiXtkHbds2yOZryXFNie7Q2dybjjbUr6gyfNQmNC1Ob8icufLkq', 29, 'Femenino', '2024-01-03', '5555678', 'maria.gomez@paciente.com', 'Avenida de los Naranjos, 45, Sevilla, España\n'),
(3, 'Carlos López', '$2y$10$ZjoiXtkHbds2yOZryXFNie7Q2dybjjbUr6gyfNQmNC1Ob8icufLkq', 40, 'Masculino', '2024-01-05', '5559876', 'carlos.lopez@paciente.com', 'Plaza de la Luna, 9, Sevilla, España'),
(4, 'Ana Martínez', '$2y$10$ZjoiXtkHbds2yOZryXFNie7Q2dybjjbUr6gyfNQmNC1Ob8icufLkq', 22, 'Femenino', '2024-01-10', '5556543', 'ana.martinez@paciente.com', 'Calle del Río Guadalquivir, 77, Sevilla, España');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Citas`
--
ALTER TABLE `Citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indexes for table `Pacientes`
--
ALTER TABLE `Pacientes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Citas`
--
ALTER TABLE `Citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Pacientes`
--
ALTER TABLE `Pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Citas`
--
ALTER TABLE `Citas`
  ADD CONSTRAINT `Citas_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `Pacientes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
