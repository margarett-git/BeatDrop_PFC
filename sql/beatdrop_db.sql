-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 18-03-2026 a las 15:12:25
-- Versión del servidor: 8.0.45
-- Versión de PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `beatdrop_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int NOT NULL,
  `nombre_categoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`) VALUES
(1, 'Música'),
(2, 'Merchandising');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedido`
--

CREATE TABLE `detalles_pedido` (
  `id_detalle` int NOT NULL,
  `id_pedido` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `fecha_pedido` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','simulado','enviado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int NOT NULL,
  `id_categoria` int DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text,
  `precio` decimal(10,2) NOT NULL,
  `stock` int DEFAULT '0',
  `imagen_url` varchar(255) DEFAULT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `formato` varchar(50) DEFAULT NULL,
  `talla` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `id_categoria`, `nombre`, `descripcion`, `precio`, `stock`, `imagen_url`, `genero`, `formato`, `talla`) VALUES
(1, 1, 'Future – I Never Liked You', NULL, 25.00, 10, 'img/future-album.jpg', 'Hip-Hop', 'Vinilo', NULL),
(2, 1, 'Billie Eilish – Happier Than Ever', NULL, 27.00, 5, 'img/billie-album.jpg', 'Pop', 'Cassette', NULL),
(3, 1, 'The Weeknd – Dawn FM', NULL, 28.00, 0, 'img/theweeknd-album.jpg', 'R&B', 'Vinilo', NULL),
(4, 1, 'Young Thug – Business is Business', NULL, 28.00, 15, 'img/youngthug-album.jpg', 'Hip-Hop', 'Vinilo', NULL),
(5, 1, 'Tems – For Broken Ears', NULL, 20.00, 10, 'img/tems-album.jpg', 'R&B', 'CD', NULL),
(6, 1, 'Drake – Honestly, Nevermind', NULL, 26.00, 5, 'img/drake-album.jpg', 'Hip-Hop', 'Vinilo', NULL),
(7, 1, 'Ariana Grande – Positions', NULL, 22.00, 20, 'img/ariana-album.jpg', 'Pop', 'CD', NULL),
(8, 1, 'Kendrick Lamar – Mr. Morale & The Big Steppers', NULL, 30.00, 8, 'img/kendrick-album.jpg', 'Hip-Hop', 'Vinilo', NULL),
(9, 1, 'Taylor Swift – Midnights', NULL, 24.00, 25, 'img/taylor-album.jpg', 'Pop', 'CD', NULL),
(10, 1, 'Foo Fighters – Medicine at Midnight', NULL, 29.00, 12, 'img/foofighters-album.jpg', 'Rock', 'Vinilo', NULL),
(11, 1, 'Miles Davis – Kind of Blue', NULL, 20.00, 4, 'img/miles-album.jpg', 'Jazz', 'CD', NULL),
(12, 1, 'Daft Punk – Random Access Memories', NULL, 32.00, 7, 'img/daftpunk-album.jpg', 'Electrónica', 'Vinilo', NULL),
(13, 1, 'Metallica – Metallica (The Black Album)', NULL, 25.00, 15, 'img/metallica-album.jpg', 'Metal', 'CD', NULL),
(14, 1, 'Bob Marley – Legend', NULL, 27.00, 9, 'img/bobmarley-album.jpg', 'Reggae', 'Vinilo', NULL),
(15, 1, 'B.B. King – Live at the Regal', NULL, 22.00, 6, 'img/bbking-album.jpg', 'Blues', 'CD', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('cliente','admin') DEFAULT 'cliente',
  `direccion_envio` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `email`, `password`, `rol`, `direccion_envio`) VALUES
(9, 'Admin BeatDrop', 'beatdroptfg@gmail.com', '$2y$10$Ekbka38pxBwgPUiHzqC6GuIAO3yvMFiKte/HwyFbor1G80OlLzRKS', 'admin', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  MODIFY `id_detalle` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD CONSTRAINT `detalles_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `detalles_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
