-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 19, 2016 at 03:33 PM
-- Server version: 5.7.12-0ubuntu1.1
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hmat_arc`
--

--
-- Dumping data for table `Status`
--

INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(1, 'Project', 'PreQuoting', '2C9DAD');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(2, 'Project', 'Quoting', '39CADE');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(3, 'Project', 'Order Processing', '41E7FF');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(4, 'Quote', 'Preparing', 'BC6C25');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(5, 'Quote', '3D Data OK', 'DB7225');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(6, 'Quote', 'Ready', 'EA8724');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(7, 'Quote', 'Sent', 'F38C25');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(8, 'Quote', 'Rejected', 'F39A17');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(9, 'Quote', 'Partial', 'F6AC14');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(10, 'Quote', 'Accepted', 'F6BF0A');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(11, 'Demand', 'AM Data Preparation', 'C02C50');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(12, 'Demand', 'AM Production', 'DD374B');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(13, 'Demand', 'AM Quatlity Check', 'FA2525');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(14, 'Demand', 'PostProcess', 'FA6565');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(15, 'Demand', 'Shipping', 'FA896D');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(16, 'DataSet', 'Build Preparation', '7A7A7A');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(17, 'DataSet', 'Support Generation', '898989');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(18, 'DataSet', 'Sent to Print Queue', 'A9A9A9');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(19, 'DataSet', 'Printing', 'B4B4B4');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(20, 'DataSet', 'Printed Ok', 'C1C1C1');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(21, 'DataSet', 'Printed with Error(s) ', 'A4A4A4');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(22, 'DataSet', 'AM Quatlity Check Ok', 'CACACA');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(23, 'DataSet', 'AM Quatlity Check Failed', 'A3A3A3');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(24, 'DataSet', 'PP Quatlity Check Ok', 'CCCCCC');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(25, 'DataSet', 'PP Quatlity Check Failed', 'A4A4A4');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(26, 'DeliveryNote', 'Packed', '663A90');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(27, 'DeliveryNote', 'Shipping Ordered', '8149B5');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(28, 'DeliveryNote', 'Dispatched', 'A25CE4');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(29, 'DeliveryNote', 'Delivered', 'A489FC');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(30, 'Invoice', 'Requested', '498C4A');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(31, 'Invoice', 'Ready', '5CB05D');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(32, 'Invoice', 'Partially Paid', '6BCD6D');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(33, 'Invoice', 'Paid', '77E479');
INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`) VALUES(34, 'Invoice', 'Cash', '77E479');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
