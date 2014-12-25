-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 25-12-2014 a las 13:24:53
-- Versión del servidor: 5.5.35
-- Versión de PHP: 5.4.35-0+deb7u2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `sico1.0`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bc_carga`
--

CREATE TABLE IF NOT EXISTS `bc_carga` (
  `id_carga` int(11) NOT NULL AUTO_INCREMENT,
  `nombreMat` varchar(100) NOT NULL,
  `nombreSedes` varchar(100) NOT NULL,
  `mes` varchar(10) NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id_carga`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bc_contrato`
--

CREATE TABLE IF NOT EXISTS `bc_contrato` (
  `id_contrato` bigint(20) NOT NULL,
  `id_oferente` int(11) NOT NULL,
  `id_modalidad` int(11) NOT NULL,
  `fechaInicio` date NOT NULL,
  `fechaFin` date NOT NULL,
  `estado` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_contrato`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bc_empleado`
--

CREATE TABLE IF NOT EXISTS `bc_empleado` (
  `id_sede_contrato` bigint(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `numDocumento` bigint(20) NOT NULL,
  `cargo` varchar(80) NOT NULL,
  `horaInicio` time NOT NULL,
  `horaFin` time NOT NULL,
  `diasLaborales` varchar(15) NOT NULL,
  `estado` tinyint(4) NOT NULL,
  KEY `id_sede_contrato` (`id_sede_contrato`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bc_empleado_cargo`
--

CREATE TABLE IF NOT EXISTS `bc_empleado_cargo` (
  `id_empleado_cargo` int(11) NOT NULL AUTO_INCREMENT,
  `cargo` varchar(80) NOT NULL,
  PRIMARY KEY (`id_empleado_cargo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bc_modalidad`
--

CREATE TABLE IF NOT EXISTS `bc_modalidad` (
  `id_modalidad` tinyint(4) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `abreviacion` varchar(10) NOT NULL,
  PRIMARY KEY (`id_modalidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bc_oferente`
--

CREATE TABLE IF NOT EXISTS `bc_oferente` (
  `id_oferente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `abreviacion` varchar(15) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefonos` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `estado` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_oferente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bc_permiso`
--

CREATE TABLE IF NOT EXISTS `bc_permiso` (
  `id_permiso` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_sede_contrato` bigint(20) NOT NULL,
  `id_permiso_vinculado` bigint(20) NOT NULL,
  `fecha` date NOT NULL,
  `horaInicio` time NOT NULL,
  `horaFin` time NOT NULL,
  `titulo` text NOT NULL,
  PRIMARY KEY (`id_permiso`),
  KEY `id_sede_contrato` (`id_sede_contrato`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bc_sede_contrato`
--

CREATE TABLE IF NOT EXISTS `bc_sede_contrato` (
  `id_sede_contrato` bigint(20) NOT NULL,
  `id_oferente` int(11) NOT NULL,
  `oferente_nombre` varchar(100) NOT NULL,
  `id_contrato` bigint(20) NOT NULL,
  `id_sede` int(11) NOT NULL,
  `sede_nombre` varchar(80) NOT NULL,
  `sede_barrio` varchar(80) NOT NULL,
  `sede_comuna` varchar(80) NOT NULL,
  `sede_direccion` varchar(100) NOT NULL,
  `sede_telefono` varchar(80) NOT NULL,
  `id_modalidad` int(11) NOT NULL,
  `modalidad_nombre` varchar(50) NOT NULL,
  `estado` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_sede_contrato`),
  KEY `id_oferente` (`id_oferente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cob_actaconteo`
--

CREATE TABLE IF NOT EXISTS `cob_actaconteo` (
  `id_actaconteo` int(11) NOT NULL AUTO_INCREMENT,
  `id_periodo` int(11) NOT NULL,
  `id_carga` int(11) NOT NULL,
  `recorrido` tinyint(4) NOT NULL,
  `id_sede_contrato` bigint(20) NOT NULL,
  `id_contrato` bigint(20) NOT NULL,
  `id_modalidad` int(11) NOT NULL,
  `modalidad_nombre` varchar(50) NOT NULL,
  `id_sede` int(11) NOT NULL,
  `sede_nombre` varchar(80) NOT NULL,
  `sede_barrio` varchar(80) NOT NULL,
  `sede_comuna` varchar(80) NOT NULL,
  `sede_direccion` varchar(100) NOT NULL,
  `sede_telefono` varchar(80) NOT NULL,
  `id_oferente` int(11) NOT NULL,
  `oferente_nombre` varchar(100) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `estado` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_actaconteo`),
  UNIQUE KEY `id_periodo_2` (`id_periodo`,`recorrido`,`id_sede_contrato`),
  KEY `id_periodo` (`id_periodo`),
  KEY `id_contrato` (`id_contrato`),
  KEY `id_sede_contrato` (`id_sede_contrato`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_carga` (`id_carga`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=148478 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cob_actaconteo_cronograma`
--

CREATE TABLE IF NOT EXISTS `cob_actaconteo_cronograma` (
  `id_actaconteo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `horaInicio` time NOT NULL,
  `horaFin` time NOT NULL,
  `id_grupo` bigint(20) NOT NULL,
  `grupo` varchar(80) NOT NULL,
  KEY `id_actaconteo` (`id_actaconteo`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cob_actaconteo_datos`
--

CREATE TABLE IF NOT EXISTS `cob_actaconteo_datos` (
  `id_actaconteo` int(11) NOT NULL,
  `correccionDireccion` varchar(100) DEFAULT NULL,
  `vallaClasificacion` tinyint(4) NOT NULL,
  `nombreEncargado` varchar(100) NOT NULL,
  `observacionEncargado` longtext NOT NULL,
  `observacionUsuario` longtext NOT NULL,
  `mosaicoDigital` tinyint(4) NOT NULL,
  `mosaicoFisico` tinyint(4) NOT NULL,
  `fecha` date NOT NULL,
  `horaInicio` time NOT NULL,
  `horaFin` time NOT NULL,
  `id_usuario` int(11) NOT NULL,
  UNIQUE KEY `id_actaconteo` (`id_actaconteo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cob_actaconteo_empleado`
--

CREATE TABLE IF NOT EXISTS `cob_actaconteo_empleado` (
  `id_actaconteo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `numDocumento` bigint(20) NOT NULL,
  `cargo` varchar(80) NOT NULL,
  `horaInicio` time NOT NULL,
  `horaFin` time NOT NULL,
  `diasLaborales` varchar(15) NOT NULL,
  `asistencia` tinyint(4) NOT NULL,
  `dotacion` tinyint(4) NOT NULL,
  `observacion` text NOT NULL,
  KEY `id_actaconteo` (`id_actaconteo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cob_actaconteo_persona`
--

CREATE TABLE IF NOT EXISTS `cob_actaconteo_persona` (
  `id_actaconteo_persona` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_actaconteo` int(11) NOT NULL,
  `id_persona` bigint(20) NOT NULL,
  `numDocumento` varchar(100) NOT NULL,
  `primerNombre` varchar(20) NOT NULL,
  `segundoNombre` varchar(20) DEFAULT NULL,
  `primerApellido` varchar(20) NOT NULL,
  `segundoApellido` varchar(20) NOT NULL,
  `id_grupo` bigint(20) NOT NULL,
  `grupo` varchar(80) NOT NULL,
  `fechaInterventoria` date NOT NULL,
  `asistencia` tinyint(4) NOT NULL,
  `tipoPersona` tinyint(4) NOT NULL COMMENT '0: General; 1: Adicional',
  `urlAdicional` varchar(80) NOT NULL,
  `observacionAdicional` longtext,
  `certificacion` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_actaconteo_persona`),
  UNIQUE KEY `id_actaconteo_2` (`id_actaconteo`,`numDocumento`),
  UNIQUE KEY `id_actaconteo_3` (`id_actaconteo`,`numDocumento`,`tipoPersona`),
  KEY `id_actaconteo` (`id_actaconteo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1277956 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cob_actaconteo_persona_excusa`
--

CREATE TABLE IF NOT EXISTS `cob_actaconteo_persona_excusa` (
  `id_actaconteo_persona` bigint(20) NOT NULL,
  `motivo` varchar(40) NOT NULL,
  `fecha` date NOT NULL,
  `acudiente` varchar(100) NOT NULL,
  `telefono` int(11) NOT NULL,
  PRIMARY KEY (`id_actaconteo_persona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cob_actaconteo_persona_facturacion`
--

CREATE TABLE IF NOT EXISTS `cob_actaconteo_persona_facturacion` (
  `id_periodo` int(11) NOT NULL,
  `id_sede_contrato` bigint(20) NOT NULL,
  `id_contrato` bigint(20) NOT NULL,
  `id_sede` int(11) NOT NULL,
  `id_persona` bigint(20) NOT NULL,
  `numDocumento` varchar(100) NOT NULL,
  `primerNombre` varchar(20) NOT NULL,
  `segundoNombre` varchar(20) NOT NULL,
  `primerApellido` varchar(20) NOT NULL,
  `segundoApellido` varchar(20) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  `grupo` varchar(80) NOT NULL,
  `fechaInicioAtencion` date NOT NULL,
  `fechaRegistro` date NOT NULL,
  `fechaRetiro` date NOT NULL,
  `fechaNacimiento` date NOT NULL,
  `peso` varchar(10) NOT NULL,
  `estatura` varchar(10) NOT NULL,
  `fechaControl` date NOT NULL,
  `certificacion` tinyint(4) NOT NULL,
  UNIQUE KEY `id_periodo_2` (`id_periodo`,`id_sede_contrato`),
  KEY `id_periodo` (`id_periodo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cob_actaconteo_persona_metrosalud`
--

CREATE TABLE IF NOT EXISTS `cob_actaconteo_persona_metrosalud` (
  `id_actaconteo_persona` bigint(20) NOT NULL,
  `cicloVital` varchar(20) NOT NULL,
  `fechaAtencion` date NOT NULL,
  `horaAtencion` time NOT NULL,
  `tipoParticipante` varchar(50) NOT NULL,
  KEY `id_actaconteo_persona` (`id_actaconteo_persona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cob_actadocumentacion`
--

CREATE TABLE IF NOT EXISTS `cob_actadocumentacion` (
  `id_actadocumentacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_sede_contrato` bigint(20) NOT NULL,
  `id_contrato` bigint(20) NOT NULL,
  `id_modalidad` int(11) NOT NULL,
  `modalidad_nombre` varchar(50) NOT NULL,
  `id_sede` bigint(20) NOT NULL,
  `sede_nombre` varchar(80) NOT NULL,
  `sede_barrio` varchar(80) NOT NULL,
  `sede_comuna` varchar(80) NOT NULL,
  `sede_direccion` varchar(100) NOT NULL,
  `sede_telefono` varchar(80) NOT NULL,
  `id_oferente` int(11) NOT NULL,
  `oferente_nombre` varchar(100) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `estado` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_actadocumentacion`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cob_actadocumentacion_dato`
--

CREATE TABLE IF NOT EXISTS `cob_actadocumentacion_dato` (
  `id_actadocumentacion` int(11) NOT NULL,
  `nombreEncargado` varchar(100) NOT NULL,
  `observacionEncargado` longtext NOT NULL,
  `observacionUsuario` longtext NOT NULL,
  `fecha` date NOT NULL,
  `horaInicio` time NOT NULL,
  `horaFin` time NOT NULL,
  KEY `id_actadocumentacion` (`id_actadocumentacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cob_actadocumentacion_persona`
--

CREATE TABLE IF NOT EXISTS `cob_actadocumentacion_persona` (
  `id_actadocumentacion_persona` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_actadocumentacion` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `numDocumento` varchar(100) NOT NULL,
  `docnombreCoincide` tinyint(4) NOT NULL,
  `telCoincide` tinyint(4) NOT NULL,
  `certsgsCoincide` tinyint(4) NOT NULL,
  `certsisbenCoincide` tinyint(4) NOT NULL,
  `matriculaFirmada` tinyint(4) NOT NULL,
  `fechaMatricula` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_actadocumentacion_persona`),
  KEY `id_actadocumentacion` (`id_actadocumentacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cob_documentacion`
--

CREATE TABLE IF NOT EXISTS `cob_documentacion` (
  `id_actadocumentacion_persona` bigint(20) NOT NULL,
  `id_contrato` bigint(20) NOT NULL,
  `id_sede` int(11) NOT NULL,
  `id_persona` bigint(20) NOT NULL,
  `numDocumento` varchar(100) NOT NULL,
  KEY `id_actadocumentacion_persona` (`id_actadocumentacion_persona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cob_periodo`
--

CREATE TABLE IF NOT EXISTS `cob_periodo` (
  `id_periodo` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id_periodo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cob_periodo_contratosedecupos`
--

CREATE TABLE IF NOT EXISTS `cob_periodo_contratosedecupos` (
  `id_periodo` int(11) NOT NULL,
  `id_sede_contrato` bigint(20) NOT NULL,
  `cuposSede` int(11) NOT NULL,
  `cuposSostenibilidad` int(11) NOT NULL,
  `cuposAmpliacion` int(11) NOT NULL,
  `cuposTotal` int(11) NOT NULL,
  UNIQUE KEY `id_periodo_2` (`id_periodo`,`id_sede_contrato`),
  KEY `id_periodo` (`id_periodo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ibc_anuncio`
--

CREATE TABLE IF NOT EXISTS `ibc_anuncio` (
  `id_anuncio` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_componente` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo` text NOT NULL,
  `contenido` longtext NOT NULL,
  `fechahora` datetime NOT NULL,
  PRIMARY KEY (`id_anuncio`),
  KEY `id_componente` (`id_componente`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_grupo` (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ibc_anuncio_comentario`
--

CREATE TABLE IF NOT EXISTS `ibc_anuncio_comentario` (
  `id_anuncio_comentario` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_anuncio` bigint(20) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `contenido` longtext NOT NULL,
  `fechahora` datetime NOT NULL,
  PRIMARY KEY (`id_anuncio_comentario`),
  KEY `id_anuncio` (`id_anuncio`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ibc_componente`
--

CREATE TABLE IF NOT EXISTS `ibc_componente` (
  `id_componente` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `abreviacion` varchar(15) NOT NULL,
  PRIMARY KEY (`id_componente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ibc_grupo`
--

CREATE TABLE IF NOT EXISTS `ibc_grupo` (
  `id_grupo` int(11) NOT NULL AUTO_INCREMENT,
  `id_grupotipo` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  PRIMARY KEY (`id_grupo`),
  KEY `id_grupotipo` (`id_grupotipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ibc_grupotipo`
--

CREATE TABLE IF NOT EXISTS `ibc_grupotipo` (
  `id_grupotipo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) NOT NULL,
  PRIMARY KEY (`id_grupotipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ibc_manual`
--

CREATE TABLE IF NOT EXISTS `ibc_manual` (
  `id_manual` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_manual_categoria` bigint(20) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo` text NOT NULL,
  `contenido` longtext NOT NULL,
  `fechahora` datetime NOT NULL,
  PRIMARY KEY (`id_manual`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_manual_categoria` (`id_manual_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ibc_manual_categoria`
--

CREATE TABLE IF NOT EXISTS `ibc_manual_categoria` (
  `id_manual_categoria` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_manual_categoria_padre` bigint(20) NOT NULL,
  `id_componente` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id_manual_categoria`),
  KEY `id_componente` (`id_componente`),
  KEY `id_grupo` (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ibc_usuario`
--

CREATE TABLE IF NOT EXISTS `ibc_usuario` (
  `id_usuario` int(11) NOT NULL,
  `id_componente` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` bigint(20) NOT NULL,
  `celular` bigint(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `estado` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `usuario` (`usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ibc_usuario_cargo`
--

CREATE TABLE IF NOT EXISTS `ibc_usuario_cargo` (
  `id_usuario_cargo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id_usuario_cargo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ibc_usuario_grupo`
--

CREATE TABLE IF NOT EXISTS `ibc_usuario_grupo` (
  `id_usuario` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  KEY `id_grupo` (`id_grupo`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bc_empleado`
--
ALTER TABLE `bc_empleado`
  ADD CONSTRAINT `bc_empleado_ibfk_1` FOREIGN KEY (`id_sede_contrato`) REFERENCES `cob_actaconteo` (`id_sede_contrato`);

--
-- Filtros para la tabla `bc_permiso`
--
ALTER TABLE `bc_permiso`
  ADD CONSTRAINT `bc_permiso_ibfk_1` FOREIGN KEY (`id_sede_contrato`) REFERENCES `bc_sede_contrato` (`id_sede_contrato`);

--
-- Filtros para la tabla `cob_actaconteo`
--
ALTER TABLE `cob_actaconteo`
  ADD CONSTRAINT `cob_actaconteo_ibfk_4` FOREIGN KEY (`id_carga`) REFERENCES `bc_carga` (`id_carga`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `cob_actaconteo_ibfk_1` FOREIGN KEY (`id_periodo`) REFERENCES `cob_periodo` (`id_periodo`) ON DELETE CASCADE,
  ADD CONSTRAINT `cob_actaconteo_ibfk_2` FOREIGN KEY (`id_contrato`) REFERENCES `bc_contrato` (`id_contrato`),
  ADD CONSTRAINT `cob_actaconteo_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `ibc_usuario` (`id_usuario`);

--
-- Filtros para la tabla `cob_actaconteo_cronograma`
--
ALTER TABLE `cob_actaconteo_cronograma`
  ADD CONSTRAINT `cob_actaconteo_cronograma_ibfk_1` FOREIGN KEY (`id_actaconteo`) REFERENCES `cob_actaconteo` (`id_actaconteo`) ON DELETE CASCADE,
  ADD CONSTRAINT `cob_actaconteo_cronograma_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `ibc_usuario` (`id_usuario`);

--
-- Filtros para la tabla `cob_actaconteo_datos`
--
ALTER TABLE `cob_actaconteo_datos`
  ADD CONSTRAINT `cob_actaconteo_datos_ibfk_1` FOREIGN KEY (`id_actaconteo`) REFERENCES `cob_actaconteo` (`id_actaconteo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cob_actaconteo_empleado`
--
ALTER TABLE `cob_actaconteo_empleado`
  ADD CONSTRAINT `cob_actaconteo_empleado_ibfk_1` FOREIGN KEY (`id_actaconteo`) REFERENCES `cob_actaconteo` (`id_actaconteo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cob_actaconteo_persona_facturacion`
--
ALTER TABLE `cob_actaconteo_persona_facturacion`
  ADD CONSTRAINT `cob_actaconteo_persona_facturacion_ibfk_1` FOREIGN KEY (`id_periodo`) REFERENCES `cob_periodo` (`id_periodo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cob_actaconteo_persona_metrosalud`
--
ALTER TABLE `cob_actaconteo_persona_metrosalud`
  ADD CONSTRAINT `cob_actaconteo_persona_metrosalud_ibfk_1` FOREIGN KEY (`id_actaconteo_persona`) REFERENCES `cob_actaconteo_persona` (`id_actaconteo_persona`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cob_actadocumentacion`
--
ALTER TABLE `cob_actadocumentacion`
  ADD CONSTRAINT `cob_actadocumentacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `ibc_usuario` (`id_usuario`);

--
-- Filtros para la tabla `cob_actadocumentacion_dato`
--
ALTER TABLE `cob_actadocumentacion_dato`
  ADD CONSTRAINT `cob_actadocumentacion_dato_ibfk_1` FOREIGN KEY (`id_actadocumentacion`) REFERENCES `cob_actadocumentacion` (`id_actadocumentacion`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cob_actadocumentacion_persona`
--
ALTER TABLE `cob_actadocumentacion_persona`
  ADD CONSTRAINT `cob_actadocumentacion_persona_ibfk_1` FOREIGN KEY (`id_actadocumentacion`) REFERENCES `cob_actadocumentacion` (`id_actadocumentacion`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cob_documentacion`
--
ALTER TABLE `cob_documentacion`
  ADD CONSTRAINT `cob_documentacion_ibfk_1` FOREIGN KEY (`id_actadocumentacion_persona`) REFERENCES `cob_actadocumentacion_persona` (`id_actadocumentacion_persona`);

--
-- Filtros para la tabla `cob_periodo_contratosedecupos`
--
ALTER TABLE `cob_periodo_contratosedecupos`
  ADD CONSTRAINT `cob_periodo_contratosedecupos_ibfk_1` FOREIGN KEY (`id_periodo`) REFERENCES `cob_periodo` (`id_periodo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ibc_anuncio`
--
ALTER TABLE `ibc_anuncio`
  ADD CONSTRAINT `ibc_anuncio_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `ibc_usuario` (`id_usuario`),
  ADD CONSTRAINT `ibc_anuncio_ibfk_2` FOREIGN KEY (`id_componente`) REFERENCES `ibc_componente` (`id_componente`),
  ADD CONSTRAINT `ibc_anuncio_ibfk_3` FOREIGN KEY (`id_grupo`) REFERENCES `ibc_grupo` (`id_grupo`);

--
-- Filtros para la tabla `ibc_anuncio_comentario`
--
ALTER TABLE `ibc_anuncio_comentario`
  ADD CONSTRAINT `ibc_anuncio_comentario_ibfk_3` FOREIGN KEY (`id_anuncio`) REFERENCES `ibc_anuncio` (`id_anuncio`) ON DELETE CASCADE,
  ADD CONSTRAINT `ibc_anuncio_comentario_ibfk_4` FOREIGN KEY (`id_usuario`) REFERENCES `ibc_usuario` (`id_usuario`);

--
-- Filtros para la tabla `ibc_grupo`
--
ALTER TABLE `ibc_grupo`
  ADD CONSTRAINT `ibc_grupo_ibfk_1` FOREIGN KEY (`id_grupotipo`) REFERENCES `ibc_grupotipo` (`id_grupotipo`);

--
-- Filtros para la tabla `ibc_manual`
--
ALTER TABLE `ibc_manual`
  ADD CONSTRAINT `ibc_manual_ibfk_2` FOREIGN KEY (`id_manual_categoria`) REFERENCES `ibc_manual_categoria` (`id_manual_categoria`),
  ADD CONSTRAINT `ibc_manual_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `ibc_usuario` (`id_usuario`);

--
-- Filtros para la tabla `ibc_manual_categoria`
--
ALTER TABLE `ibc_manual_categoria`
  ADD CONSTRAINT `ibc_manual_categoria_ibfk_1` FOREIGN KEY (`id_componente`) REFERENCES `ibc_componente` (`id_componente`),
  ADD CONSTRAINT `ibc_manual_categoria_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `ibc_grupo` (`id_grupo`);

--
-- Filtros para la tabla `ibc_usuario_grupo`
--
ALTER TABLE `ibc_usuario_grupo`
  ADD CONSTRAINT `ibc_usuario_grupo_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `ibc_usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `ibc_usuario_grupo_ibfk_4` FOREIGN KEY (`id_grupo`) REFERENCES `ibc_grupo` (`id_grupo`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
