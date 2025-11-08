-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: mysqldb
-- Tiempo de generación: 27-10-2025 a las 21:57:12
-- Versión del servidor: 5.7.44
-- Versión de PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `diario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autor`
--

CREATE TABLE `autor` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `rol` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `autor`
--

INSERT INTO `autor` (`id`, `nombre`, `rol`) VALUES
(1, 'Juan Garay', 'Editor'),
(2, 'Steve Gomez', 'Fotografia'),
(3, 'Luis Estevez', 'Montaje');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticia`
--

CREATE TABLE `noticia` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text,
  `img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `noticia`
--

INSERT INTO `noticia` (`id`, `titulo`, `descripcion`, `img`) VALUES
(1, 'La NASA denunció que China planea poner un supermercado en la luna.', 'Según la agencia espacial norteamericana, en las últimas semanas se interceptaron 3 cohetes chinos con mercadería y un cuarto, tripulado por personas de origen peruano entrenados para hacer reabastesimiento de mercadería en gravedad 0.', 'https://www.nasa.gov/sites/default/files/styles/full_width_feature/public/thumbnails/image/crs7-postlaunch1.jpg'),
(2, 'Crean software para detectar gente pelotudeando en el trabajo.', 'El Software, promete disparar la productividad de empresas y comercios, al eliminar tiempos muertos de empleados asignandoles diferentes roles. Varios sindicatos ya se declararon en contra del uso de esta tecnología.', 'https://1.bp.blogspot.com/-f1w3f4ePS3s/W1jVAAlT6RI/AAAAAAAAGuE/jAD5isAy2Ws5MNlrbViteOkzs3TTAr5nACLcBGAs/s640/SW_DRONE.jpg'),
(3, 'Quien es Tito Musk, el primo argentino de Elon Musk que se presentó en lo de Guido Kaczka.', 'Tito Musk vive en Bernal y desde chico se destacó por inventar pelotudeces. En 1986 intentó mandar un conejo a la Luna pero revento como el Challenger. También dice que habla seguido con su primo, Elon.', 'https://files.merca20.com/uploads/2022/05/Elon-Musk-tatuaje-e1651875137289.jpeg'),
(4, 'Starbucks se va del país y hay preocupación entre hipsters: -\'no sabemos donde iremos a mostrar que tenemos una macbook.', 'Según el comunicado oficial debido a la crisis global que vive el sector, y la cantidad de pelotudos que ocupan mesas durante 8 horas con un café y dos mediaslunas solamente para darse corte que tienen una MacBook, se hace inviables la continidad de las operaciones en Argentina.', 'https://1.bp.blogspot.com/-HiD_vBlSbg0/X2PjR8udR5I/AAAAAAAAIvw/E3EWAortz7A3ZHIdTrOGHa7YGsf17ZUiQCLcBGAsYHQ/w640-h444/starbucks.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autor`
--
ALTER TABLE `autor`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `noticia`
--
ALTER TABLE `noticia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `autor`
--
ALTER TABLE `autor`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `noticia`
--
ALTER TABLE `noticia`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
