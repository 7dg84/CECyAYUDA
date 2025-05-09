-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 20-03-2025 a las 13:15:49
-- Versión del servidor: 5.1.41
-- Versión de PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `cecyayuda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `denuncias`
--

CREATE TABLE IF NOT EXISTS `denuncias` (
  `Folio` int(20) NOT NULL,
  `Descripcion` varchar(2000) NOT NULL,
  `Fecha` date NOT NULL,
  `Hora` time NOT NULL,
  `Ubicacion` varchar(200) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `CURP` varchar(18) NOT NULL,
  `Correo` varchar(50) NOT NULL,
  `Numtelefono` varchar(10) NOT NULL,
  `Tipo` int(50) NOT NULL,
  PRIMARY KEY (`Folio`,`CURP`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `denuncias`
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
