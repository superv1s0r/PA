-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
-- Servidor: localhost
-- Tiempo de generación: 21-11-2024 a las 16:30:05
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET
SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET
time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Base de datos: `EPD6`
CREATE
DATABASE IF NOT EXISTS `EPD6` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE
`EPD6`;

-- --------------------------------------------------------

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs`
(
    `id`          INT(11) NOT NULL AUTO_INCREMENT,
    `descripcion` TEXT NOT NULL,
    `id_usuario`  INT(11) NOT NULL,
    `tipo_accion` ENUM('crear', 'actualizar', 'leer', 'listar', 'borrar') NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `producto`
DROP TABLE IF EXISTS `producto`;
CREATE TABLE `producto`
(
    `sku`            INT(11) NOT NULL AUTO_INCREMENT,
    `descripcion`    VARCHAR(255) NOT NULL,
    `num_pasillo`    INT(11) NOT NULL,
    `num_estanteria` INT(11) NOT NULL,
    `cantidad`       INT(11) NOT NULL,
    PRIMARY KEY (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `rol`
DROP TABLE IF EXISTS `rol`;
CREATE TABLE `rol`
(
    `id_rol`     INT(11) NOT NULL AUTO_INCREMENT,
    `nombre_rol` ENUM('administrativo', 'operario', 'administrador') NOT NULL,
    PRIMARY KEY (`id_rol`),
    UNIQUE KEY `nombre_rol` (`nombre_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `rol` (id_rol, nombre_rol)
VALUES (1, 'Administrador'),
       (2, 'Administrativo'),
       (3, 'Operario');

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `usuario`
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario`
(
    `id_usuario` INT(11) NOT NULL AUTO_INCREMENT,
    `email`      VARCHAR(50)  NOT NULL,
    `password`   VARCHAR(255) NOT NULL,
    `nombre`     VARCHAR(255) NOT NULL,
    `apellidos`  VARCHAR(255) NOT NULL,
    `id_rol`     INT(11) NOT NULL,
    PRIMARY KEY (`id_usuario`),
    UNIQUE KEY `email` (`email`),
    FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- (login: "1234")
INSERT INTO `usuario` (id_usuario, email, password, nombre, apellidos, id_rol)
VALUES (1, 'admin@almacen.com', '$2y$10$ZjoiXtkHbds2yOZryXFNie7Q2dybjjbUr6gyfNQmNC1Ob8icufLkq', 'Admin', 'Principal',
        1),
       (2, 'operario@almacen.com', '$2y$10$ZjoiXtkHbds2yOZryXFNie7Q2dybjjbUr6gyfNQmNC1Ob8icufLkq', 'Operario',
        'Secundario', 3);

-- --------------------------------------------------------

-- AUTO_INCREMENT para las tablas
ALTER TABLE `logs` MODIFY `id` INT (11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `producto` MODIFY `sku` INT (11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `rol` MODIFY `id_rol` INT (11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `usuario` MODIFY `id_usuario` INT (11) NOT NULL AUTO_INCREMENT;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
