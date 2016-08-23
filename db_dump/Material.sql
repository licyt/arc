-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 23, 2016 at 11:19 AM
-- Server version: 5.7.13-0ubuntu0.16.04.2
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hmat_dev`
--

--
-- Truncate table before insert `Material`
--

TRUNCATE TABLE `Material`;
--
-- Dumping data for table `Material`
--

INSERT INTO `Material` (`idMaterial`, `MaterialName`) VALUES
(0, NULL),
(1, 'thermoplastics'),
(2, 'eutectic metals'),
(3, 'edible material'),
(4, 'rubbers'),
(5, 'modeling clay'),
(6, 'plasticine'),
(7, 'metal clay'),
(8, 'ceramic materials'),
(9, 'metal alloy'),
(10, 'cermet'),
(11, 'metal matrix composite'),
(12, 'ceramic matrix composite'),
(13, 'photopolymer'),
(14, 'powdered polymers'),
(15, 'plaster'),
(16, 'titanium alloys'),
(17, 'cobalt chrome alloys'),
(18, 'stainless steel'),
(19, 'alluminium'),
(20, 'thermoplatic powder'),
(21, 'metal powders'),
(22, 'ceramic powders'),
(23, 'paper'),
(24, 'metal foil'),
(25, 'plastic film');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
