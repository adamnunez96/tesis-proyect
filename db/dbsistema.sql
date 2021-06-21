-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-06-2021 a las 20:19:01
-- Versión del servidor: 10.4.6-MariaDB
-- Versión de PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbsistema`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajustes`
--

CREATE TABLE `ajustes` (
  `idajuste` int(10) UNSIGNED NOT NULL,
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `idpersonal` int(10) UNSIGNED NOT NULL,
  `iddeposito` int(10) UNSIGNED NOT NULL,
  `fecha` date DEFAULT NULL,
  `obs` varchar(100) DEFAULT NULL,
  `tipoajuste` char(2) DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajustes_detalle`
--

CREATE TABLE `ajustes_detalle` (
  `idajustedet` int(10) UNSIGNED NOT NULL,
  `idmercaderias` int(10) UNSIGNED NOT NULL,
  `idajuste` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL,
  `precio` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apertura_cierre_caja`
--

CREATE TABLE `apertura_cierre_caja` (
  `idaperturacierre` int(10) UNSIGNED NOT NULL,
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `idpersonal` int(10) UNSIGNED NOT NULL,
  `idcaja` int(10) UNSIGNED NOT NULL,
  `fechaapertura` datetime DEFAULT NULL,
  `montoapertura` decimal(12,2) DEFAULT NULL,
  `fechacierre` datetime DEFAULT NULL,
  `montocierre` decimal(12,2) DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arqueo`
--

CREATE TABLE `arqueo` (
  `idarqueo` int(10) UNSIGNED NOT NULL,
  `idaperturacierre` int(10) UNSIGNED NOT NULL,
  `idformacobro` int(10) UNSIGNED NOT NULL,
  `monto` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE `cajas` (
  `idcaja` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cajas`
--

INSERT INTO `cajas` (`idcaja`, `descripcion`) VALUES
(1, 'CAJA 1'),
(3, 'CAJA 2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudades`
--

CREATE TABLE `ciudades` (
  `idciudad` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ciudades`
--

INSERT INTO `ciudades` (`idciudad`, `descripcion`) VALUES
(1, 'ASUNCION'),
(2, 'PUERTO ELSA'),
(3, 'MARIANO ROQUE ALONSO'),
(7, 'FALCON'),
(8, 'CHACOI');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `idcliente` int(10) UNSIGNED NOT NULL,
  `idciudad` int(10) UNSIGNED NOT NULL,
  `ci` int(10) UNSIGNED DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`idcliente`, `idciudad`, `ci`, `nombre`, `apellido`, `direccion`, `telefono`, `correo`, `estado`) VALUES
(1, 2, 1, 'CLIENTE CASUAL', '-', '-', '1', 'sincorreo@gmail.com', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cobros`
--

CREATE TABLE `cobros` (
  `idcobro` int(10) UNSIGNED NOT NULL,
  `idaperturacierre` int(10) UNSIGNED NOT NULL,
  `idformacobro` int(10) UNSIGNED NOT NULL,
  `fecha` date DEFAULT NULL,
  `monto` decimal(12,2) DEFAULT NULL,
  `montoefectivo` decimal(12,2) DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cobros_cheque`
--

CREATE TABLE `cobros_cheque` (
  `idcobrocheque` int(10) UNSIGNED NOT NULL,
  `identidademisora` int(10) UNSIGNED NOT NULL,
  `idcobro` int(10) UNSIGNED NOT NULL,
  `nrocheque` int(10) UNSIGNED DEFAULT NULL,
  `monto` int(10) UNSIGNED DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `titular` varchar(40) DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cobros_detalle`
--

CREATE TABLE `cobros_detalle` (
  `idcobrodetalle` int(10) UNSIGNED NOT NULL,
  `idcuentacobrar` int(10) UNSIGNED NOT NULL,
  `idcobro` int(10) UNSIGNED NOT NULL,
  `monto` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cobros_tarjeta`
--

CREATE TABLE `cobros_tarjeta` (
  `idcobrotarjeta` int(10) UNSIGNED NOT NULL,
  `idtarjeta` int(10) UNSIGNED NOT NULL,
  `idcobro` int(10) UNSIGNED NOT NULL,
  `nrotarjeta` int(10) UNSIGNED DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `monto` decimal(12,2) DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `idcompra` int(10) UNSIGNED NOT NULL,
  `idordencompra` int(10) UNSIGNED DEFAULT NULL,
  `nrofactura` varchar(25) NOT NULL,
  `idproveedor` int(10) UNSIGNED NOT NULL,
  `idpersonal` int(10) UNSIGNED NOT NULL,
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `idformapago` int(10) UNSIGNED NOT NULL,
  `idtipodocumento` int(10) UNSIGNED NOT NULL,
  `iddeposito` int(10) UNSIGNED NOT NULL,
  `fecha` datetime NOT NULL,
  `obs` varchar(100) DEFAULT NULL,
  `montoex` decimal(12,2) DEFAULT NULL,
  `grav5` decimal(12,2) DEFAULT NULL,
  `grav10` decimal(12,2) DEFAULT NULL,
  `iva5` decimal(12,2) DEFAULT NULL,
  `iva10` decimal(12,2) DEFAULT NULL,
  `monto` decimal(12,2) NOT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`idcompra`, `idordencompra`, `nrofactura`, `idproveedor`, `idpersonal`, `idsucursal`, `idformapago`, `idtipodocumento`, `iddeposito`, `fecha`, `obs`, `montoex`, `grav5`, `grav10`, `iva5`, `iva10`, `monto`, `estado`) VALUES
(1, 0, '11-11-0000', 1, 1, 1, 1, 1, 1, '2021-06-18 00:00:00', 'prueba', NULL, NULL, NULL, NULL, NULL, '55000.00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras_detalle`
--

CREATE TABLE `compras_detalle` (
  `idcompradet` int(10) UNSIGNED NOT NULL,
  `idcompra` int(10) UNSIGNED NOT NULL,
  `idmercaderia` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL,
  `precio` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credi_debi_venta_detalle`
--

CREATE TABLE `credi_debi_venta_detalle` (
  `idcredidebiventadetalle` int(10) UNSIGNED NOT NULL,
  `mercaderias_idmercaderias` int(10) UNSIGNED NOT NULL,
  `idnotacredidebiventa` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL,
  `precio` decimal(12,2) DEFAULT NULL,
  `exenta` decimal(12,2) DEFAULT NULL,
  `iva5` decimal(12,2) DEFAULT NULL,
  `iva10` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_a_cobrar`
--

CREATE TABLE `cuentas_a_cobrar` (
  `idcuentacobrar` int(10) UNSIGNED NOT NULL,
  `idfacturaventa` int(10) UNSIGNED NOT NULL,
  `monto` decimal(12,2) DEFAULT NULL,
  `nrocuota` int(10) UNSIGNED DEFAULT NULL,
  `cantcuotas` decimal(12,2) DEFAULT NULL,
  `nrofactura` int(10) UNSIGNED DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `vencimiento` datetime DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_a_pagar`
--

CREATE TABLE `cuentas_a_pagar` (
  `idcuentapagar` int(10) UNSIGNED NOT NULL,
  `idcompra` int(10) UNSIGNED NOT NULL,
  `idproveedor` int(10) UNSIGNED NOT NULL,
  `nrofactura` varchar(25) NOT NULL,
  `idnotacredidebi` int(10) UNSIGNED NOT NULL,
  `totalcuota` int(10) UNSIGNED DEFAULT NULL,
  `nrocuota` int(10) UNSIGNED DEFAULT NULL,
  `montocuota` decimal(12,2) DEFAULT NULL,
  `fechavto` datetime DEFAULT NULL,
  `obs` varchar(100) DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `depositos`
--

CREATE TABLE `depositos` (
  `iddeposito` int(10) UNSIGNED NOT NULL,
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `depositos`
--

INSERT INTO `depositos` (`iddeposito`, `idsucursal`, `descripcion`) VALUES
(1, 1, 'DEPOSITO 1'),
(2, 2, 'DEPOSITO 2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diagnosticos`
--

CREATE TABLE `diagnosticos` (
  `iddiagnostico` int(10) UNSIGNED NOT NULL,
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `idcliente` int(10) UNSIGNED NOT NULL,
  `idrecepcion` int(10) UNSIGNED NOT NULL,
  `idpersonal` int(10) UNSIGNED NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL,
  `observacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diagnostico_detalle`
--

CREATE TABLE `diagnostico_detalle` (
  `iddiagnosticodetalle` int(10) UNSIGNED NOT NULL,
  `idvehiculo` int(10) UNSIGNED NOT NULL,
  `iddiagnostico` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entidad_emisora`
--

CREATE TABLE `entidad_emisora` (
  `identidademisora` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(35) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `entidad_emisora`
--

INSERT INTO `entidad_emisora` (`identidademisora`, `descripcion`, `telefono`, `direccion`) VALUES
(1, 'ITAU', '021-000000', ''),
(3, 'VISION BANCO', '', ''),
(4, 'BANCO GNB', '', ''),
(5, 'BANCO CONTINENTAL', '', ''),
(6, 'COOPERATIVA MEDALLA MILAGROSA', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_venta`
--

CREATE TABLE `factura_venta` (
  `idfacturaventa` int(10) UNSIGNED NOT NULL,
  `idtimbrado` int(10) UNSIGNED NOT NULL,
  `idservicio` int(10) UNSIGNED NOT NULL,
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `idcliente` int(10) UNSIGNED NOT NULL,
  `idpersonal` int(10) UNSIGNED NOT NULL,
  `idaperturacierre` int(10) UNSIGNED NOT NULL,
  `idtipodocumento` int(10) UNSIGNED NOT NULL,
  `nrofactura` varchar(25) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `obs` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_venta_detalle`
--

CREATE TABLE `factura_venta_detalle` (
  `idfacturavetnadet` int(10) UNSIGNED NOT NULL,
  `idmercaderias` int(10) UNSIGNED NOT NULL,
  `idfacturaventa` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL,
  `precio` decimal(12,2) DEFAULT NULL,
  `monto` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formas_cobros`
--

CREATE TABLE `formas_cobros` (
  `idformacobro` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formas_pago`
--

CREATE TABLE `formas_pago` (
  `idformapago` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `cuota` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `formas_pago`
--

INSERT INTO `formas_pago` (`idformapago`, `descripcion`, `cuota`) VALUES
(1, 'CONTADO', 1),
(2, 'CREDITO', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_compras`
--

CREATE TABLE `libro_compras` (
  `idlibrocompra` int(10) UNSIGNED NOT NULL,
  `idcompra` int(10) UNSIGNED NOT NULL,
  `idproveedor` int(10) UNSIGNED NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `montoexenta` decimal(12,2) DEFAULT NULL,
  `montoiva5` decimal(12,2) DEFAULT NULL,
  `montoiva10` decimal(12,2) DEFAULT NULL,
  `grabiva5` decimal(12,2) DEFAULT NULL,
  `grabiva10` decimal(12,2) DEFAULT NULL,
  `montopagado` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_ventas`
--

CREATE TABLE `libro_ventas` (
  `idlibro_ventas` int(10) UNSIGNED NOT NULL,
  `idcliente` int(10) UNSIGNED NOT NULL,
  `idfacturaventa` int(10) UNSIGNED NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `montopagado` decimal(12,2) DEFAULT NULL,
  `montoiva5` decimal(12,2) DEFAULT NULL,
  `montoiva10` decimal(12,2) DEFAULT NULL,
  `grabiva5` decimal(12,2) DEFAULT NULL,
  `grabiva10` decimal(12,2) DEFAULT NULL,
  `montoexenta` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `idmarca` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`idmarca`, `descripcion`) VALUES
(2, 'KENDA'),
(3, 'FIRELLI'),
(4, 'KENTON'),
(5, 'STAR'),
(6, 'TAIGA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas_tarjetas`
--

CREATE TABLE `marcas_tarjetas` (
  `idmarcatarjeta` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `marcas_tarjetas`
--

INSERT INTO `marcas_tarjetas` (`idmarcatarjeta`, `descripcion`) VALUES
(1, 'VISA'),
(2, 'MARTERCARD');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mercaderias`
--

CREATE TABLE `mercaderias` (
  `idmercaderia` int(10) UNSIGNED NOT NULL,
  `idtipoimpuesto` int(10) UNSIGNED NOT NULL,
  `idmarca` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `preciocompra` decimal(12,2) DEFAULT NULL,
  `precioventa` decimal(12,2) DEFAULT NULL,
  `imagen` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `mercaderias`
--

INSERT INTO `mercaderias` (`idmercaderia`, `idtipoimpuesto`, `idmarca`, `descripcion`, `preciocompra`, `precioventa`, `imagen`, `estado`) VALUES
(1, 1, 2, 'FARO LED - CHINO', '55000.00', '70000.00', '1620670089.jpg', 1),
(2, 1, 2, 'CUBIERTA MOTO ARO 17 - CHINO', '185000.00', '210000.00', '1620670177.jpg', 1),
(3, 2, 3, 'PRODUCTO EJEMPLO', '20000.00', '30000.00', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota_credito_debito_compra`
--

CREATE TABLE `nota_credito_debito_compra` (
  `idnotacredidebi` int(10) UNSIGNED NOT NULL,
  `compras_idcompra` int(10) UNSIGNED NOT NULL,
  `iddeposito` int(10) UNSIGNED NOT NULL,
  `idpersonal` int(10) UNSIGNED NOT NULL,
  `idproveedor` int(10) UNSIGNED NOT NULL,
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `idtipodocumento` int(10) UNSIGNED NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `obs` varchar(100) DEFAULT NULL,
  `grav5` decimal(12,2) DEFAULT NULL,
  `grav10` decimal(12,2) DEFAULT NULL,
  `monto` decimal(12,2) DEFAULT NULL,
  `montoex` int(10) UNSIGNED DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL,
  `nrotimbrado` varchar(30) DEFAULT NULL,
  `nro_nota_credito_debito` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota_credi_debi_detalle`
--

CREATE TABLE `nota_credi_debi_detalle` (
  `idnotacredidebidet` int(10) UNSIGNED NOT NULL,
  `idmercaderias` int(10) UNSIGNED NOT NULL,
  `idnotacredidebi` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL,
  `precio` decimal(12,2) DEFAULT NULL,
  `exenta` decimal(12,2) DEFAULT NULL,
  `iva5` decimal(12,2) DEFAULT NULL,
  `iva10` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota_credi_debi_venta`
--

CREATE TABLE `nota_credi_debi_venta` (
  `idnotacredidebiventa` int(10) UNSIGNED NOT NULL,
  `dtimbrado` int(10) UNSIGNED NOT NULL,
  `dfacturaventa` int(10) UNSIGNED NOT NULL,
  `idtipodocumento` int(10) UNSIGNED NOT NULL,
  `idcliente` int(10) UNSIGNED NOT NULL,
  `iddeposito` int(10) UNSIGNED NOT NULL,
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `idpersonal` int(10) UNSIGNED NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `obs` varchar(100) DEFAULT NULL,
  `montoex` decimal(12,2) DEFAULT NULL,
  `grav5` decimal(12,2) DEFAULT NULL,
  `grav10` decimal(12,2) DEFAULT NULL,
  `monto` decimal(12,2) DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL,
  `nro_nota_credito_debito` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota_remision`
--

CREATE TABLE `nota_remision` (
  `idnotaremision` int(10) UNSIGNED NOT NULL,
  `iddeposito` int(10) UNSIGNED NOT NULL,
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `idpersonalenvia` int(10) UNSIGNED NOT NULL,
  `idpersonalrecibe` int(10) UNSIGNED NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota_remision_detalle`
--

CREATE TABLE `nota_remision_detalle` (
  `idnotaremidet` int(10) UNSIGNED NOT NULL,
  `idmercaderias` int(10) UNSIGNED NOT NULL,
  `idnotaremision` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oden_trabajo_detalle`
--

CREATE TABLE `oden_trabajo_detalle` (
  `ordentrabajodetalle` int(10) UNSIGNED NOT NULL,
  `idvehiculo` int(10) UNSIGNED NOT NULL,
  `idmercaderias` int(10) UNSIGNED NOT NULL,
  `idordentrabajo` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_compras`
--

CREATE TABLE `orden_compras` (
  `idordencompra` int(10) UNSIGNED NOT NULL,
  `idpedido` int(10) UNSIGNED DEFAULT NULL,
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `idpersonal` int(10) UNSIGNED NOT NULL,
  `idproveedor` int(10) UNSIGNED NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `obs` varchar(100) DEFAULT NULL,
  `monto` decimal(12,2) NOT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `orden_compras`
--

INSERT INTO `orden_compras` (`idordencompra`, `idpedido`, `idsucursal`, `idpersonal`, `idproveedor`, `fecha`, `obs`, `monto`, `estado`) VALUES
(1, 0, 1, 1, 1, '2021-05-26 20:41:10', 'insercion de orden de compra sin pedido previo', '240000.00', 1),
(2, 1, 1, 1, 3, '2021-05-28 11:58:26', 'orden de compra traido de un pedido de compra', '240000.00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_compras_detalle`
--

CREATE TABLE `orden_compras_detalle` (
  `idordendet` int(10) UNSIGNED NOT NULL,
  `idordencompra` int(10) UNSIGNED NOT NULL,
  `idmercaderia` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL,
  `precio` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `orden_compras_detalle`
--

INSERT INTO `orden_compras_detalle` (`idordendet`, `idordencompra`, `idmercaderia`, `descripcion`, `cantidad`, `precio`) VALUES
(1, 1, 1, NULL, 1, '55000.00'),
(2, 1, 2, NULL, 1, '185000.00'),
(3, 2, 1, NULL, 1, '55000.00'),
(4, 2, 2, NULL, 1, '185000.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_trabajos`
--

CREATE TABLE `orden_trabajos` (
  `idordentrabajo` int(10) UNSIGNED NOT NULL,
  `idpresupuestoservicio` int(10) UNSIGNED NOT NULL,
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `idpersonal` int(10) UNSIGNED NOT NULL,
  `idcliente` int(10) UNSIGNED NOT NULL,
  `fechainicio` datetime DEFAULT NULL,
  `fechasalida` datetime DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_compra`
--

CREATE TABLE `pedido_compra` (
  `idpedido` int(10) UNSIGNED NOT NULL,
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `idpersonal` int(10) UNSIGNED NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `obs` varchar(100) DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pedido_compra`
--

INSERT INTO `pedido_compra` (`idpedido`, `idsucursal`, `idpersonal`, `fecha`, `obs`, `estado`) VALUES
(1, 1, 1, '2021-05-22 20:20:09', 'prueba', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalle`
--

CREATE TABLE `pedido_detalle` (
  `idpedidodet` int(10) UNSIGNED NOT NULL,
  `idpedido` int(10) UNSIGNED NOT NULL,
  `idmercaderia` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pedido_detalle`
--

INSERT INTO `pedido_detalle` (`idpedidodet`, `idpedido`, `idmercaderia`, `descripcion`, `cantidad`) VALUES
(1, 1, 1, NULL, 1),
(2, 1, 2, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `idpermiso` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`idpermiso`, `descripcion`) VALUES
(1, 'REFERENCIALES'),
(2, 'COMPRAS'),
(3, 'VENTAS'),
(4, 'SERVICIOS'),
(5, 'REPORTES'),
(6, 'ESCRITORIO'),
(7, 'ADMINISTRADOR');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personales`
--

CREATE TABLE `personales` (
  `idpersonal` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED NOT NULL,
  `idciudad` int(10) UNSIGNED NOT NULL,
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `documento` varchar(50) DEFAULT NULL,
  `direccion` varchar(70) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `cargo` varchar(30) DEFAULT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `imagen` varchar(50) DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `personales`
--

INSERT INTO `personales` (`idpersonal`, `idusuario`, `idciudad`, `idsucursal`, `nombre`, `apellido`, `documento`, `direccion`, `telefono`, `cargo`, `correo`, `imagen`, `estado`) VALUES
(1, 1, 3, 1, 'ADAM', 'NUNEZ', '-', 'CENTRO', '021000000', 'ADMINISTRADOR', 'sincorreo@gmail.com', '1621309012.png', 1),
(2, 2, 1, 1, 'PRUEBA', 'PRUEBA', '1', 'PRUEBA', '1', 'COMPRAS', '', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuestos_servicios`
--

CREATE TABLE `presupuestos_servicios` (
  `idpresupuestoservicio` int(10) UNSIGNED NOT NULL,
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `idcliente` int(10) UNSIGNED NOT NULL,
  `iddiagnostico` int(10) UNSIGNED NOT NULL,
  `idpersonal` int(10) UNSIGNED NOT NULL,
  `fecha` date DEFAULT NULL,
  `obs` varchar(100) DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuesto_servicio_detalle`
--

CREATE TABLE `presupuesto_servicio_detalle` (
  `idpresupuestoservdet` int(10) UNSIGNED NOT NULL,
  `idvehiculo` int(10) UNSIGNED NOT NULL,
  `idpresupuestoservicio` int(10) UNSIGNED NOT NULL,
  `idmercaderias` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL,
  `precio` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `idproveedor` int(10) UNSIGNED NOT NULL,
  `idciudad` int(10) UNSIGNED NOT NULL,
  `razonsocial` varchar(50) NOT NULL,
  `ruc` varchar(30) NOT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`idproveedor`, `idciudad`, `razonsocial`, `ruc`, `direccion`, `telefono`, `correo`, `estado`) VALUES
(1, 1, 'CHACOMER SAECA', '8000000-1', 'CASA CENTRAL', '021-000000', 'chacomersaeca@gmail.com', 1),
(3, 3, 'PROVEEDOR DE PRUEBA', '8000001-1', 'RUTA TRANSCHACO', '021-000000', 'sincorreo@gmail.com', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recaudacion_a_depositar`
--

CREATE TABLE `recaudacion_a_depositar` (
  `idrecaudacion` int(10) UNSIGNED NOT NULL,
  `idarqueo` int(10) UNSIGNED NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `monto` decimal(12,2) DEFAULT NULL,
  `nrofactura` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recepciones`
--

CREATE TABLE `recepciones` (
  `idrecepcion` int(10) UNSIGNED NOT NULL,
  `idvehiculo` int(10) UNSIGNED NOT NULL,
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `idpersonal` int(10) UNSIGNED NOT NULL,
  `idcliente` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL,
  `motivo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `idservicio` int(10) UNSIGNED NOT NULL,
  `idpersonal` int(10) UNSIGNED NOT NULL,
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `idcliente` int(10) UNSIGNED NOT NULL,
  `idordentrabajo` int(10) UNSIGNED NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_detalle`
--

CREATE TABLE `servicios_detalle` (
  `idserviciodetalle` int(10) UNSIGNED NOT NULL,
  `idvehiculo` int(10) UNSIGNED NOT NULL,
  `idmercaderias` int(10) UNSIGNED NOT NULL,
  `idservicio` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `idstock` int(10) UNSIGNED NOT NULL,
  `idmercaderia` int(10) UNSIGNED NOT NULL,
  `iddepositos` int(10) UNSIGNED NOT NULL,
  `cantidad` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursales`
--

CREATE TABLE `sucursales` (
  `idsucursal` int(10) UNSIGNED NOT NULL,
  `idciudad` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sucursales`
--

INSERT INTO `sucursales` (`idsucursal`, `idciudad`, `descripcion`, `direccion`, `telefono`) VALUES
(1, 2, 'MATRIZ', 'CALLE GASPAR RODRIGUEZ DE FRANCIA', '021000000'),
(2, 2, 'SUCURSAL 1', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarjetas`
--

CREATE TABLE `tarjetas` (
  `idtarjeta` int(10) UNSIGNED NOT NULL,
  `idmarcatarjeta` int(10) UNSIGNED NOT NULL,
  `identidademisora` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tarjetas`
--

INSERT INTO `tarjetas` (`idtarjeta`, `idmarcatarjeta`, `identidademisora`, `descripcion`) VALUES
(1, 1, 1, 'CLASICA'),
(2, 2, 4, 'BLACK');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `timbrados`
--

CREATE TABLE `timbrados` (
  `idtimbrado` int(10) UNSIGNED NOT NULL,
  `idtipodocumento` int(10) UNSIGNED NOT NULL,
  `nrodesde` int(10) UNSIGNED DEFAULT NULL,
  `nrohasta` int(10) UNSIGNED DEFAULT NULL,
  `fechainicio` date DEFAULT NULL,
  `nrotimbrado` int(10) UNSIGNED DEFAULT NULL,
  `fechafin` date DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documentos`
--

CREATE TABLE `tipo_documentos` (
  `idtipodocumento` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_documentos`
--

INSERT INTO `tipo_documentos` (`idtipodocumento`, `descripcion`) VALUES
(1, 'FACTURA COMPRA'),
(2, 'FACTURA VENTA'),
(4, 'NOTA CREDITO'),
(5, 'NOTA DEBITO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_impuestos`
--

CREATE TABLE `tipo_impuestos` (
  `idtipoimpuesto` int(10) UNSIGNED NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `tipo` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_impuestos`
--

INSERT INTO `tipo_impuestos` (`idtipoimpuesto`, `descripcion`, `tipo`) VALUES
(1, '10%', 10),
(2, '5%', 5),
(5, 'EXENTA', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuariopermiso`
--

CREATE TABLE `usuariopermiso` (
  `idusuariopermiso` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED NOT NULL,
  `idpermiso` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuariopermiso`
--

INSERT INTO `usuariopermiso` (`idusuariopermiso`, `idusuario`, `idpermiso`) VALUES
(77, 1, 1),
(78, 1, 2),
(79, 1, 3),
(80, 1, 4),
(81, 1, 5),
(82, 1, 6),
(83, 1, 7),
(89, 2, 2),
(90, 2, 5),
(91, 2, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuario` int(10) UNSIGNED NOT NULL,
  `usuario` varchar(25) DEFAULT NULL,
  `clave` varchar(64) DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuario`, `usuario`, `clave`, `estado`) VALUES
(1, 'admin', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 1),
(2, 'compras', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `idvehiculo` int(10) UNSIGNED NOT NULL,
  `idmarca` int(10) UNSIGNED NOT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `chapa` varchar(50) DEFAULT NULL,
  `observacion` varchar(100) DEFAULT NULL,
  `estado` int(10) UNSIGNED DEFAULT NULL,
  `anho` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`idvehiculo`, `idmarca`, `modelo`, `chapa`, `observacion`, `estado`, `anho`) VALUES
(1, 6, 'SCOOTER 110', 'PRUEBA-123', 'SIN OBSERVACION', 1, 2020);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  ADD PRIMARY KEY (`idajuste`),
  ADD KEY `ajustes_FKIndex1` (`iddeposito`),
  ADD KEY `ajustes_FKIndex2` (`idpersonal`),
  ADD KEY `ajustes_FKIndex3` (`idsucursal`);

--
-- Indices de la tabla `ajustes_detalle`
--
ALTER TABLE `ajustes_detalle`
  ADD PRIMARY KEY (`idajustedet`),
  ADD KEY `ajustes_detalle_FKIndex1` (`idajuste`),
  ADD KEY `ajustes_detalle_FKIndex2` (`idmercaderias`);

--
-- Indices de la tabla `apertura_cierre_caja`
--
ALTER TABLE `apertura_cierre_caja`
  ADD PRIMARY KEY (`idaperturacierre`),
  ADD KEY `apertura_cierre_caja_FKIndex1` (`idcaja`),
  ADD KEY `apertura_cierre_caja_FKIndex2` (`idpersonal`),
  ADD KEY `apertura_cierre_caja_FKIndex3` (`idsucursal`);

--
-- Indices de la tabla `arqueo`
--
ALTER TABLE `arqueo`
  ADD PRIMARY KEY (`idarqueo`),
  ADD KEY `arqueo_FKIndex1` (`idformacobro`),
  ADD KEY `arqueo_FKIndex2` (`idaperturacierre`);

--
-- Indices de la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD PRIMARY KEY (`idcaja`);

--
-- Indices de la tabla `ciudades`
--
ALTER TABLE `ciudades`
  ADD PRIMARY KEY (`idciudad`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idcliente`),
  ADD KEY `clientes_FKIndex1` (`idciudad`);

--
-- Indices de la tabla `cobros`
--
ALTER TABLE `cobros`
  ADD PRIMARY KEY (`idcobro`),
  ADD KEY `formas_cobros_detalle_FKIndex1` (`idformacobro`),
  ADD KEY `cobros_FKIndex2` (`idaperturacierre`);

--
-- Indices de la tabla `cobros_cheque`
--
ALTER TABLE `cobros_cheque`
  ADD PRIMARY KEY (`idcobrocheque`),
  ADD KEY `cobros_cheque_FKIndex2` (`idcobro`),
  ADD KEY `cobros_cheque_FKIndex1` (`identidademisora`);

--
-- Indices de la tabla `cobros_detalle`
--
ALTER TABLE `cobros_detalle`
  ADD PRIMARY KEY (`idcobrodetalle`),
  ADD KEY `cobros_detalle_FKIndex1` (`idcobro`),
  ADD KEY `cobros_detalle_FKIndex2` (`idcuentacobrar`);

--
-- Indices de la tabla `cobros_tarjeta`
--
ALTER TABLE `cobros_tarjeta`
  ADD PRIMARY KEY (`idcobrotarjeta`),
  ADD KEY `cobros_tarjeta_FKIndex1` (`idcobro`),
  ADD KEY `cobros_tarjeta_FKIndex2` (`idtarjeta`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`idcompra`),
  ADD KEY `compras_FKIndex1` (`idproveedor`),
  ADD KEY `compras_FKIndex3` (`iddeposito`),
  ADD KEY `compras_FKIndex4` (`idtipodocumento`),
  ADD KEY `compras_FKIndex5` (`idpersonal`),
  ADD KEY `compras_FKIndex6` (`idformapago`),
  ADD KEY `compras_FKIndex2` (`idordencompra`),
  ADD KEY `compras_FKIndex7` (`idsucursal`);

--
-- Indices de la tabla `compras_detalle`
--
ALTER TABLE `compras_detalle`
  ADD PRIMARY KEY (`idcompradet`),
  ADD KEY `compras_detalle_FKIndex1` (`idmercaderia`),
  ADD KEY `compras_detalle_FKIndex2` (`idcompra`);

--
-- Indices de la tabla `credi_debi_venta_detalle`
--
ALTER TABLE `credi_debi_venta_detalle`
  ADD PRIMARY KEY (`idcredidebiventadetalle`),
  ADD KEY `credi_debi_venta_detalle_FKIndex1` (`idnotacredidebiventa`),
  ADD KEY `credi_debi_venta_detalle_FKIndex2` (`mercaderias_idmercaderias`);

--
-- Indices de la tabla `cuentas_a_cobrar`
--
ALTER TABLE `cuentas_a_cobrar`
  ADD PRIMARY KEY (`idcuentacobrar`),
  ADD KEY `cuentas_a_cobrar_FKIndex1` (`idfacturaventa`);

--
-- Indices de la tabla `cuentas_a_pagar`
--
ALTER TABLE `cuentas_a_pagar`
  ADD PRIMARY KEY (`idcuentapagar`),
  ADD KEY `cuentas_a_pagar_FKIndex1` (`idcompra`),
  ADD KEY `cuentas_a_pagar_FKIndex2` (`idnotacredidebi`),
  ADD KEY `fkproveedor` (`idproveedor`);

--
-- Indices de la tabla `depositos`
--
ALTER TABLE `depositos`
  ADD PRIMARY KEY (`iddeposito`),
  ADD KEY `depositos_FKIndex1` (`idsucursal`);

--
-- Indices de la tabla `diagnosticos`
--
ALTER TABLE `diagnosticos`
  ADD PRIMARY KEY (`iddiagnostico`),
  ADD KEY `diagnostico_FKIndex1` (`idrecepcion`),
  ADD KEY `diagnostico_FKIndex2` (`idcliente`),
  ADD KEY `diagnostico_FKIndex3` (`idpersonal`),
  ADD KEY `diagnosticos_FKIndex4` (`idsucursal`);

--
-- Indices de la tabla `diagnostico_detalle`
--
ALTER TABLE `diagnostico_detalle`
  ADD PRIMARY KEY (`iddiagnosticodetalle`),
  ADD KEY `diagnostico_detalle_FKIndex1` (`iddiagnostico`),
  ADD KEY `diagnostico_detalle_FKIndex2` (`idvehiculo`);

--
-- Indices de la tabla `entidad_emisora`
--
ALTER TABLE `entidad_emisora`
  ADD PRIMARY KEY (`identidademisora`);

--
-- Indices de la tabla `factura_venta`
--
ALTER TABLE `factura_venta`
  ADD PRIMARY KEY (`idfacturaventa`),
  ADD KEY `ventas_FKIndex1` (`idcliente`),
  ADD KEY `ventas_FKIndex2` (`idpersonal`),
  ADD KEY `ventas_FKIndex3` (`idtipodocumento`),
  ADD KEY `ventas_FKIndex5` (`idaperturacierre`),
  ADD KEY `factura_venta_FKIndex4` (`idsucursal`),
  ADD KEY `factura_venta_FKIndex6` (`idservicio`),
  ADD KEY `factura_venta_FKIndex7` (`idtimbrado`);

--
-- Indices de la tabla `factura_venta_detalle`
--
ALTER TABLE `factura_venta_detalle`
  ADD PRIMARY KEY (`idfacturavetnadet`),
  ADD KEY `facturacion_detalle_FKIndex1` (`idfacturaventa`),
  ADD KEY `factura_venta_detalle_FKIndex2` (`idmercaderias`);

--
-- Indices de la tabla `formas_cobros`
--
ALTER TABLE `formas_cobros`
  ADD PRIMARY KEY (`idformacobro`);

--
-- Indices de la tabla `formas_pago`
--
ALTER TABLE `formas_pago`
  ADD PRIMARY KEY (`idformapago`);

--
-- Indices de la tabla `libro_compras`
--
ALTER TABLE `libro_compras`
  ADD PRIMARY KEY (`idlibrocompra`),
  ADD KEY `libro_compras_FKIndex1` (`idproveedor`),
  ADD KEY `libro_compras_FKIndex2` (`idcompra`);

--
-- Indices de la tabla `libro_ventas`
--
ALTER TABLE `libro_ventas`
  ADD PRIMARY KEY (`idlibro_ventas`),
  ADD KEY `libro_ventas_FKIndex1` (`idfacturaventa`),
  ADD KEY `libro_ventas_FKIndex2` (`idcliente`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`idmarca`);

--
-- Indices de la tabla `marcas_tarjetas`
--
ALTER TABLE `marcas_tarjetas`
  ADD PRIMARY KEY (`idmarcatarjeta`);

--
-- Indices de la tabla `mercaderias`
--
ALTER TABLE `mercaderias`
  ADD PRIMARY KEY (`idmercaderia`),
  ADD KEY `mercaderias_FKIndex1` (`idmarca`),
  ADD KEY `productos_FKIndex3` (`idtipoimpuesto`);

--
-- Indices de la tabla `nota_credito_debito_compra`
--
ALTER TABLE `nota_credito_debito_compra`
  ADD PRIMARY KEY (`idnotacredidebi`),
  ADD KEY `nota_credito_debito_FKIndex1` (`idtipodocumento`),
  ADD KEY `nota_credito_debito_compra_FKIndex2` (`idsucursal`),
  ADD KEY `nota_credito_debito_compra_FKIndex3` (`compras_idcompra`),
  ADD KEY `nota_credito_debito_compra_FKIndex4` (`idproveedor`),
  ADD KEY `nota_credito_debito_compra_FKIndex5` (`idpersonal`),
  ADD KEY `nota_credito_debito_compra_FKIndex6` (`iddeposito`);

--
-- Indices de la tabla `nota_credi_debi_detalle`
--
ALTER TABLE `nota_credi_debi_detalle`
  ADD PRIMARY KEY (`idnotacredidebidet`),
  ADD KEY `nota_credi_debi_detalle_FKIndex1` (`idnotacredidebi`),
  ADD KEY `nota_credi_debi_detalle_FKIndex2` (`idmercaderias`);

--
-- Indices de la tabla `nota_credi_debi_venta`
--
ALTER TABLE `nota_credi_debi_venta`
  ADD PRIMARY KEY (`idnotacredidebiventa`),
  ADD KEY `nota_credi_debi_venta_FKIndex1` (`idpersonal`),
  ADD KEY `nota_credi_debi_venta_FKIndex2` (`idsucursal`),
  ADD KEY `nota_credi_debi_venta_FKIndex3` (`iddeposito`),
  ADD KEY `nota_credi_debi_venta_FKIndex4` (`idcliente`),
  ADD KEY `nota_credi_debi_venta_FKIndex5` (`idtipodocumento`),
  ADD KEY `nota_credi_debi_venta_FKIndex6` (`dfacturaventa`),
  ADD KEY `nota_credi_debi_venta_FKIndex7` (`dtimbrado`);

--
-- Indices de la tabla `nota_remision`
--
ALTER TABLE `nota_remision`
  ADD PRIMARY KEY (`idnotaremision`),
  ADD KEY `nota_remision_FKIndex1` (`idpersonalrecibe`),
  ADD KEY `nota_remision_FKIndex2` (`idpersonalenvia`),
  ADD KEY `nota_remision_FKIndex3` (`idsucursal`),
  ADD KEY `nota_remision_FKIndex4` (`iddeposito`);

--
-- Indices de la tabla `nota_remision_detalle`
--
ALTER TABLE `nota_remision_detalle`
  ADD PRIMARY KEY (`idnotaremidet`),
  ADD KEY `nota_remision_detalle_FKIndex1` (`idnotaremision`),
  ADD KEY `nota_remision_detalle_FKIndex2` (`idmercaderias`);

--
-- Indices de la tabla `oden_trabajo_detalle`
--
ALTER TABLE `oden_trabajo_detalle`
  ADD PRIMARY KEY (`ordentrabajodetalle`),
  ADD KEY `detalle_orden_compra_FKIndex1` (`idordentrabajo`),
  ADD KEY `oden_trabajo_detalle_FKIndex2` (`idmercaderias`),
  ADD KEY `oden_trabajo_detalle_FKIndex3` (`idvehiculo`);

--
-- Indices de la tabla `orden_compras`
--
ALTER TABLE `orden_compras`
  ADD PRIMARY KEY (`idordencompra`),
  ADD KEY `orden_compras_FKIndex1` (`idproveedor`),
  ADD KEY `orden_compras_FKIndex3` (`idpersonal`),
  ADD KEY `orden_compras_FKIndex4` (`idsucursal`),
  ADD KEY `orden_compras_FKIndex5` (`idpedido`);

--
-- Indices de la tabla `orden_compras_detalle`
--
ALTER TABLE `orden_compras_detalle`
  ADD PRIMARY KEY (`idordendet`),
  ADD KEY `orden_compras_detalle_FKIndex1` (`idordencompra`),
  ADD KEY `orden_compras_detalle_FKIndex2` (`idmercaderia`);

--
-- Indices de la tabla `orden_trabajos`
--
ALTER TABLE `orden_trabajos`
  ADD PRIMARY KEY (`idordentrabajo`),
  ADD KEY `orden_trabajo_FKIndex1` (`idpersonal`),
  ADD KEY `orden_trabajo_FKIndex2` (`idcliente`),
  ADD KEY `orden_trabajos_FKIndex3` (`idsucursal`),
  ADD KEY `orden_trabajos_FKIndex4` (`idpresupuestoservicio`);

--
-- Indices de la tabla `pedido_compra`
--
ALTER TABLE `pedido_compra`
  ADD PRIMARY KEY (`idpedido`),
  ADD KEY `pedidos_FKIndex1` (`idpersonal`),
  ADD KEY `pedido_compra_FKIndex4` (`idsucursal`);

--
-- Indices de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD PRIMARY KEY (`idpedidodet`),
  ADD KEY `pedido_detalle_FKIndex1` (`idpedido`),
  ADD KEY `pedido_detalle_FKIndex2` (`idmercaderia`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`idpermiso`);

--
-- Indices de la tabla `personales`
--
ALTER TABLE `personales`
  ADD PRIMARY KEY (`idpersonal`),
  ADD KEY `personales_FKIndex1` (`idciudad`),
  ADD KEY `personales_FKIndex2` (`idusuario`),
  ADD KEY `idsucursal` (`idsucursal`);

--
-- Indices de la tabla `presupuestos_servicios`
--
ALTER TABLE `presupuestos_servicios`
  ADD PRIMARY KEY (`idpresupuestoservicio`),
  ADD KEY `presupuestos_FKIndex2` (`idpersonal`),
  ADD KEY `presupuestos_FKIndex3` (`iddiagnostico`),
  ADD KEY `presupuestos_ventas_FKIndex1` (`idcliente`),
  ADD KEY `presupuestos_servicios_FKIndex4` (`idsucursal`);

--
-- Indices de la tabla `presupuesto_servicio_detalle`
--
ALTER TABLE `presupuesto_servicio_detalle`
  ADD PRIMARY KEY (`idpresupuestoservdet`),
  ADD KEY `presupuesto_servicio_detalle_FKIndex1` (`idmercaderias`),
  ADD KEY `presupuesto_servicio_detalle_FKIndex2` (`idpresupuestoservicio`),
  ADD KEY `presupuesto_servicio_detalle_FKIndex3` (`idvehiculo`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`idproveedor`),
  ADD KEY `proveedores_FKIndex1` (`idciudad`);

--
-- Indices de la tabla `recaudacion_a_depositar`
--
ALTER TABLE `recaudacion_a_depositar`
  ADD PRIMARY KEY (`idrecaudacion`),
  ADD KEY `recaudacion_a_depositar_FKIndex1` (`idarqueo`);

--
-- Indices de la tabla `recepciones`
--
ALTER TABLE `recepciones`
  ADD PRIMARY KEY (`idrecepcion`),
  ADD KEY `recepcion_FKIndex1` (`idpersonal`),
  ADD KEY `recepcion_FKIndex2` (`idcliente`),
  ADD KEY `recepciones_FKIndex3` (`idsucursal`),
  ADD KEY `recepciones_FKIndex4` (`idvehiculo`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`idservicio`),
  ADD KEY `Servicios_FKIndex1` (`idordentrabajo`),
  ADD KEY `Servicios_FKIndex2` (`idcliente`),
  ADD KEY `Servicios_FKIndex3` (`idsucursal`),
  ADD KEY `Servicios_FKIndex4` (`idpersonal`);

--
-- Indices de la tabla `servicios_detalle`
--
ALTER TABLE `servicios_detalle`
  ADD PRIMARY KEY (`idserviciodetalle`),
  ADD KEY `servicios_detalle_FKIndex1` (`idservicio`),
  ADD KEY `servicios_detalle_FKIndex2` (`idmercaderias`),
  ADD KEY `servicios_detalle_FKIndex3` (`idvehiculo`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`idstock`),
  ADD KEY `stock_FKIndex` (`iddepositos`),
  ADD KEY `stock_FKIndex2` (`idmercaderia`);

--
-- Indices de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  ADD PRIMARY KEY (`idsucursal`),
  ADD KEY `sucursales_FKIndex1` (`idciudad`);

--
-- Indices de la tabla `tarjetas`
--
ALTER TABLE `tarjetas`
  ADD PRIMARY KEY (`idtarjeta`),
  ADD KEY `tarjetas_FKIndex1` (`idmarcatarjeta`),
  ADD KEY `tarjetas_FKIndex2` (`identidademisora`);

--
-- Indices de la tabla `timbrados`
--
ALTER TABLE `timbrados`
  ADD PRIMARY KEY (`idtimbrado`),
  ADD KEY `timbrados_FKIndex1` (`idtipodocumento`);

--
-- Indices de la tabla `tipo_documentos`
--
ALTER TABLE `tipo_documentos`
  ADD PRIMARY KEY (`idtipodocumento`);

--
-- Indices de la tabla `tipo_impuestos`
--
ALTER TABLE `tipo_impuestos`
  ADD PRIMARY KEY (`idtipoimpuesto`);

--
-- Indices de la tabla `usuariopermiso`
--
ALTER TABLE `usuariopermiso`
  ADD PRIMARY KEY (`idusuariopermiso`),
  ADD KEY `usuario_permiso_FKIndex1` (`idusuario`),
  ADD KEY `usuario_permiso_FKIndex2` (`idpermiso`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuario`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`idvehiculo`),
  ADD KEY `vehiculos_FKIndex1` (`idmarca`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  MODIFY `idajuste` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ajustes_detalle`
--
ALTER TABLE `ajustes_detalle`
  MODIFY `idajustedet` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `apertura_cierre_caja`
--
ALTER TABLE `apertura_cierre_caja`
  MODIFY `idaperturacierre` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `arqueo`
--
ALTER TABLE `arqueo`
  MODIFY `idarqueo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cajas`
--
ALTER TABLE `cajas`
  MODIFY `idcaja` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ciudades`
--
ALTER TABLE `ciudades`
  MODIFY `idciudad` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idcliente` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cobros`
--
ALTER TABLE `cobros`
  MODIFY `idcobro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cobros_cheque`
--
ALTER TABLE `cobros_cheque`
  MODIFY `idcobrocheque` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cobros_detalle`
--
ALTER TABLE `cobros_detalle`
  MODIFY `idcobrodetalle` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cobros_tarjeta`
--
ALTER TABLE `cobros_tarjeta`
  MODIFY `idcobrotarjeta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `idcompra` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `compras_detalle`
--
ALTER TABLE `compras_detalle`
  MODIFY `idcompradet` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `credi_debi_venta_detalle`
--
ALTER TABLE `credi_debi_venta_detalle`
  MODIFY `idcredidebiventadetalle` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuentas_a_cobrar`
--
ALTER TABLE `cuentas_a_cobrar`
  MODIFY `idcuentacobrar` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuentas_a_pagar`
--
ALTER TABLE `cuentas_a_pagar`
  MODIFY `idcuentapagar` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `depositos`
--
ALTER TABLE `depositos`
  MODIFY `iddeposito` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `diagnosticos`
--
ALTER TABLE `diagnosticos`
  MODIFY `iddiagnostico` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `diagnostico_detalle`
--
ALTER TABLE `diagnostico_detalle`
  MODIFY `iddiagnosticodetalle` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entidad_emisora`
--
ALTER TABLE `entidad_emisora`
  MODIFY `identidademisora` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `factura_venta`
--
ALTER TABLE `factura_venta`
  MODIFY `idfacturaventa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `factura_venta_detalle`
--
ALTER TABLE `factura_venta_detalle`
  MODIFY `idfacturavetnadet` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `formas_cobros`
--
ALTER TABLE `formas_cobros`
  MODIFY `idformacobro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `formas_pago`
--
ALTER TABLE `formas_pago`
  MODIFY `idformapago` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `libro_compras`
--
ALTER TABLE `libro_compras`
  MODIFY `idlibrocompra` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `libro_ventas`
--
ALTER TABLE `libro_ventas`
  MODIFY `idlibro_ventas` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `idmarca` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `marcas_tarjetas`
--
ALTER TABLE `marcas_tarjetas`
  MODIFY `idmarcatarjeta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `mercaderias`
--
ALTER TABLE `mercaderias`
  MODIFY `idmercaderia` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `nota_credito_debito_compra`
--
ALTER TABLE `nota_credito_debito_compra`
  MODIFY `idnotacredidebi` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nota_credi_debi_detalle`
--
ALTER TABLE `nota_credi_debi_detalle`
  MODIFY `idnotacredidebidet` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nota_credi_debi_venta`
--
ALTER TABLE `nota_credi_debi_venta`
  MODIFY `idnotacredidebiventa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nota_remision`
--
ALTER TABLE `nota_remision`
  MODIFY `idnotaremision` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nota_remision_detalle`
--
ALTER TABLE `nota_remision_detalle`
  MODIFY `idnotaremidet` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `oden_trabajo_detalle`
--
ALTER TABLE `oden_trabajo_detalle`
  MODIFY `ordentrabajodetalle` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_compras`
--
ALTER TABLE `orden_compras`
  MODIFY `idordencompra` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `orden_compras_detalle`
--
ALTER TABLE `orden_compras_detalle`
  MODIFY `idordendet` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `orden_trabajos`
--
ALTER TABLE `orden_trabajos`
  MODIFY `idordentrabajo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido_compra`
--
ALTER TABLE `pedido_compra`
  MODIFY `idpedido` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  MODIFY `idpedidodet` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `idpermiso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `personales`
--
ALTER TABLE `personales`
  MODIFY `idpersonal` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `presupuestos_servicios`
--
ALTER TABLE `presupuestos_servicios`
  MODIFY `idpresupuestoservicio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `presupuesto_servicio_detalle`
--
ALTER TABLE `presupuesto_servicio_detalle`
  MODIFY `idpresupuestoservdet` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `idproveedor` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `recaudacion_a_depositar`
--
ALTER TABLE `recaudacion_a_depositar`
  MODIFY `idrecaudacion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recepciones`
--
ALTER TABLE `recepciones`
  MODIFY `idrecepcion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `idservicio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios_detalle`
--
ALTER TABLE `servicios_detalle`
  MODIFY `idserviciodetalle` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  MODIFY `idsucursal` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tarjetas`
--
ALTER TABLE `tarjetas`
  MODIFY `idtarjeta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `timbrados`
--
ALTER TABLE `timbrados`
  MODIFY `idtimbrado` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_documentos`
--
ALTER TABLE `tipo_documentos`
  MODIFY `idtipodocumento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipo_impuestos`
--
ALTER TABLE `tipo_impuestos`
  MODIFY `idtipoimpuesto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuariopermiso`
--
ALTER TABLE `usuariopermiso`
  MODIFY `idusuariopermiso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `idvehiculo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuentas_a_pagar`
--
ALTER TABLE `cuentas_a_pagar`
  ADD CONSTRAINT `fkproveedor` FOREIGN KEY (`idproveedor`) REFERENCES `proveedores` (`idproveedor`);

--
-- Filtros para la tabla `personales`
--
ALTER TABLE `personales`
  ADD CONSTRAINT `FK_personalSucursal` FOREIGN KEY (`idsucursal`) REFERENCES `sucursales` (`idsucursal`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
