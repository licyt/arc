-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 23, 2016 at 10:09 AM
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
-- Truncate table before insert `Task`
--

TRUNCATE TABLE `Task`;
--
-- Dumping data for table `Task`
--

INSERT INTO `Task` (`idTask`, `TaskName`, `TaskSequence`, `TaskDuration`) VALUES
(1, '1. PreQuote', '10', '5 hours'),
(2, '1.1. Get Customer Data', '10', '1 hour'),
(3, '1.2. Get Print Parameters', '20', '1 hour'),
(4, '1.3. Get 3d Data', '30', '3 hours'),
(5, '2. Quote', '20', NULL),
(6, '2.1. Check 3d Data', '10', '3 hours'),
(7, '2.2. Calculate Quote', '20', '2 hours'),
(8, '2.3. Send Quote', '30', '30 minutes'),
(9, '3. Process Order', '30', NULL),
(10, '3.1. Prepare AM Data', '10', '10 hours 30 minutes'),
(11, '3.1.1. Check File', '10', '30 minutes'),
(12, '3.1.2. Fix File', '20', '4 hours'),
(13, '3.1.3. Prepare Build', '30', '2 hours'),
(14, '3.1.4. Generate Support', '40', '2 hours'),
(15, '3.1.5. Send to Print', '50', '2 hours'),
(16, '3.2. AM Production', '20', NULL),
(17, '3.2.1. Check AM Quality', '10', '2 hours'),
(18, '3.2.2. PostProcess', '20', '1 day'),
(19, '3.2.3. Check PP Quality', '30', '1 hour'),
(20, '3.3. Billing', '30', NULL),
(21, '3.3.1. Create Invoice', '10', '30 minutes'),
(22, '3.3.2. Send Invoice', '20', '30 minutes'),
(23, '3.4. Shipping', '40', NULL),
(24, '3.4.1. Pack', '10', '30 minutes'),
(25, '3.4.2. Order Shipping', '20', '30 minutes'),
(26, '3.4.3. Dispatch', '30', '15 minutes'),
(27, '4. Follow Up', '40', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
