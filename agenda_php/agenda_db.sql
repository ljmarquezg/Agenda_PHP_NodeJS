-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2017 at 03:40 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agenda_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `hora_inicio` varchar(20) DEFAULT NULL,
  `fecha_finalizacion` varchar(20) DEFAULT NULL,
  `hora_finalizacion` varchar(20) DEFAULT NULL,
  `allday` tinyint(1) NOT NULL,
  `fk_usuarios` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `eventos`
--

INSERT INTO `eventos` (`id`,`titulo`, `fecha_inicio`, `hora_inicio`, `fecha_finalizacion`, `hora_finalizacion`, `allday`, `fk_usuarios`) VALUES
(1,'Primer Evento Demo', '2017-10-01', '06:00:00', '2017-10-01', '07:00:00', 0, 'demo@mail.com'),
(2,'Segundo Evento Demo', '2017-10-03', '08:30:00', '2017-10-03', '10:30:00', 0, 'demo@mail.com'),
(3,'Tercer Evento Demo - Dia entero', '2017-10-05', '', '', '', 1, 'demo@mail.com'),
(4,'Primer Evento Juan - Dia entero', '2017-10-02', ':00', '', ':00', 1, 'juan@mail.com'),
(5,'Segundo Evento Juan', '2017-10-02', '12:30:00', '2017-10-05', '17:30:00', 0, 'juan@mail.com'),
(6,'Tercer Evento Juan', '2017-10-11', '12:30:00', '2017-10-12', '17:30:00', 0, 'juan@mail.com'),
(7,'Primer Evento Carla', '2017-10-05', '05:00:00', '2017-10-07', '15:00:00', 0, 'carla@mail.com'),
(8,'Segundo Evento Carla', '2017-10-11', '16:00:00', '2017-10-11', '17:00:00', 0, 'carla@mail.com'),
(9,'Tercer Evento Carla - Dia entero', '2017-10-15', ':00', '', ':00', 1, 'carla@mail.com'),
(10,'Cuarto Evento Carla - Dia entero', '2017-10-16', '', '', '', 1, 'carla@mail.com');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `email` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_nacimiento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`email`, `nombre`, `password`, `fecha_nacimiento`) VALUES
('carla@mail.com', 'Carla Rodriguez', '$2y$10$jKtuUYl6SCCnPt1D1XVPvepTnxWQBRY6S58NGB3AhTt4F0SVZhaum', '1990-04-15'),
('demo@mail.com', 'Usuario Demo', '$2y$10$/0sQ3gkcfaIShTsthJeQPuWIGDyssIZz88be0IyLp5moRNnfOj6dq', '1998-09-08'),
('juan@mail.com', 'Juan GÃ³mez', '$2y$10$rUNipkbIkuD9i0SS1beoauf3vI.H64ifn/8wWDAHWKlqn1kdBrpLS', '1997-12-21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuarios` (`fk_usuarios`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `fk_usuarioemail_evento` FOREIGN KEY (`fk_usuarios`) REFERENCES `usuarios` (`email`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
