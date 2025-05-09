-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-05-2025 a las 17:11:37
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
-- Base de datos: `petlog`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascotas`
--

CREATE TABLE `mascotas` (
  `id_mascota` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `especie` varchar(50) NOT NULL,
  `raza` varchar(50) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `color_principal` varchar(50) DEFAULT NULL,
  `color_secundario` varchar(50) DEFAULT NULL,
  `senas_particulares` text DEFAULT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `nombre_propietario` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mascotas`
--

INSERT INTO `mascotas` (`id_mascota`, `nombre`, `especie`, `raza`, `fecha_nacimiento`, `sexo`, `color_principal`, `color_secundario`, `senas_particulares`, `foto_url`, `nombre_propietario`) VALUES
(1, 'Pelusa', 'Gato', 'Persa', '2024-03-10', 'H', 'Blanco', 'Gris', 'Ojos azules', 'pelusa.jpg', 'Ana Rodríguez'),
(2, 'Rocky', 'Perro', 'Boxer', '2023-11-22', 'M', 'Marrón', 'Blanco', 'Cola cortada', 'rocky.jpg', 'Carlos López'),
(3, 'Piolín', 'Pájaro', 'Canario', '2024-07-01', 'M', 'Amarillo', NULL, 'Canta mucho', 'piolin.jpg', 'Sofía Gómez'),
(4, 'Luna', 'Gato', 'Siames', '2025-01-05', 'H', 'Crema', 'Marrón oscuro', 'Muy cariñosa', 'luna.jpg', 'Javier Pérez'),
(5, 'Max', 'Perro', 'Golden Retriever', '2023-05-18', 'M', 'Dorado', NULL, 'Le encanta jugar a la pelota', 'max.jpg', 'Elena Vargas'),
(6, 'Valentina 21', 'Perro321', 'Pincher Miniatura321', '2025-05-29', 'M', 'Negra2321', '23', '123', NULL, 'Gabriel Veizaga212'),
(7, 'Valentina ', 'Perro', 'Pincher Miniatura', '2021-04-04', 'H', 'Negra', '', '', NULL, 'Miguel Veizaga');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD PRIMARY KEY (`id_mascota`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  MODIFY `id_mascota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
