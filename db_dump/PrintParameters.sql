-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 23, 2016 at 11:20 AM
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
-- Truncate table before insert `PrintParameters`
--

TRUNCATE TABLE `PrintParameters`;
--
-- Dumping data for table `PrintParameters`
--

INSERT INTO `PrintParameters` (`idPrintParameters`, `PrintParametersName`, `PrintParametersTechnology`, `PrintParametersResolution`, `PrintParametersLayer`, `PrintParametersFinish`) VALUES
(0, NULL, 'undefined', '', '', ''),
(2, NULL, 'FDM - Fused Depositi', '', '', ''),
(3, NULL, 'FDM - Fused Deposition Modelling', '', '', ''),
(4, NULL, 'DIW - Direct Ink Writing', NULL, NULL, NULL),
(5, NULL, 'SLA - Stereolitography', NULL, NULL, NULL),
(6, NULL, 'DLP - Digital Light Processing', NULL, NULL, NULL),
(7, NULL, '3DP - Powdered bed and inkjet head 3D printing', NULL, NULL, NULL),
(8, NULL, 'EBM - Electron-beam Melting', NULL, NULL, NULL),
(9, NULL, 'SLM - Selective Laser Melting', NULL, NULL, NULL),
(10, NULL, 'SHS - Selective Heat Sintering', NULL, NULL, NULL),
(11, NULL, 'DMLS - Direct Metal Laser Sintering', NULL, NULL, NULL),
(12, NULL, 'LOM - Laminated Object Manufacturing', NULL, NULL, NULL),
(13, NULL, 'DED - Direct Energy Deposition', NULL, NULL, NULL),
(14, NULL, 'EBF - Electron beam freeform fabrication', NULL, NULL, NULL),
(15, NULL, 'SLS - Selective Laser Sintering', NULL, NULL, NULL),
(16, NULL, 'MC - Metal Casting', NULL, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
