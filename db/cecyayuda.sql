-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 20-03-2025 a las 13:16:22
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
  `Folio` varchar(64) NOT NULL,
  `Descripcion` varchar(2000) NOT NULL,
  `Fecha` date NOT NULL,
  `Hora` time NOT NULL,
  `Ubicacion` varchar(200) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `CURP` varchar(18) NOT NULL,
  `Correo` varchar(50) NOT NULL,
  `Numtelefono` varchar(10) NOT NULL,
  `Tipo` varchar(15) NOT NULL,
  `Verified` BOOLEAN NOT NULL DEFAULT FALSE,
  `Status` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Folio`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `Nombre` varchar(20) NOT NULL,
  `ApellidoP` varchar(20) NOT NULL,
  `ApellidoM` varchar(20) NOT NULL,
  `CURP` varchar(18) NOT NULL,
  `Edad` int(3) NOT NULL,
  `Sexo` varchar(10) NOT NULL,
  `FechaNac` date NOT NULL,
  `Estado` varchar(100) NOT NULL,
  `Municipio` varchar(100) NOT NULL,
  `Colonia` varchar(100) NOT NULL,
  `Calle` varchar(100) NOT NULL,
  `NoExterior` int(3) NOT NULL,
  `NoInterior` int(5) NOT NULL,
  PRIMARY KEY (`CURP`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `User` varchar(20) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Rol` varchar(20) NOT NULL,
  PRIMARY KEY (`User`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
