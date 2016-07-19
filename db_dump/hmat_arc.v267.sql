-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 19, 2016 at 12:27 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `Account`
--

DROP TABLE IF EXISTS `Account`;
CREATE TABLE `Account` (
  `idAccount` int(11) NOT NULL,
  `AccountName` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `AccountBank` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `AccountBankCode` int(11) DEFAULT NULL,
  `AccountVAT` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `AccountSWIFT` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `AccountIBAN` varchar(30) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Action`
--

DROP TABLE IF EXISTS `Action`;
CREATE TABLE `Action` (
  `idAction` int(11) NOT NULL,
  `ActionName` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `ActionSequence` int(2) DEFAULT '0',
  `ActionTable` varchar(30) COLLATE utf8_slovak_ci DEFAULT NULL,
  `ActionField` varchar(30) COLLATE utf8_slovak_ci DEFAULT NULL,
  `ActionCommand` varchar(20) COLLATE utf8_slovak_ci DEFAULT NULL,
  `ActionParam1` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `ActionParam2` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `ActionEvent` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Address`
--

DROP TABLE IF EXISTS `Address`;
CREATE TABLE `Address` (
  `idAddress` int(11) NOT NULL,
  `AddressName` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `AddressStreet` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `AddressNumber` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `AddressZip` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `AddressCity` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `AddressCountry` varchar(30) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Build`
--

DROP TABLE IF EXISTS `Build`;
CREATE TABLE `Build` (
  `idBuild` int(11) NOT NULL,
  `BuildName` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `BuildRequested` datetime DEFAULT NULL,
  `BuildPrintJobStart` datetime DEFAULT NULL,
  `BuildPrintJobEnd` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Company`
--

DROP TABLE IF EXISTS `Company`;
CREATE TABLE `Company` (
  `idCompany` int(11) NOT NULL,
  `CompanyName` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `CompanyICO` varchar(25) CHARACTER SET utf8 DEFAULT NULL,
  `CompanyDIC` varchar(25) CHARACTER SET utf8 DEFAULT NULL,
  `CompanyDRC` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `CompanyWebsite` varchar(45) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `DataSet`
--

DROP TABLE IF EXISTS `DataSet`;
CREATE TABLE `DataSet` (
  `idDataSet` int(11) NOT NULL,
  `DataSetFileName` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `DataSetDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `DeliveryNote`
--

DROP TABLE IF EXISTS `DeliveryNote`;
CREATE TABLE `DeliveryNote` (
  `idDeliveryNote` int(11) NOT NULL,
  `DeliveryNoteName` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `DeliveryNoteIssued` date DEFAULT NULL,
  `DeliveryNoteSigned` date DEFAULT NULL,
  `ShippingAddress` varchar(45) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `DeliveryTransport`
--

DROP TABLE IF EXISTS `DeliveryTransport`;
CREATE TABLE `DeliveryTransport` (
  `idDeliveryTransport` int(11) NOT NULL,
  `deliveryTransportMethod` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `deliveryTransportOption` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `deliveryTransportDate` date DEFAULT NULL,
  `deliveryTransportPayment` varchar(45) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Demand`
--

DROP TABLE IF EXISTS `Demand`;
CREATE TABLE `Demand` (
  `idDemand` int(11) NOT NULL,
  `DemandName` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `DemandDateReceived` date DEFAULT NULL,
  `DemandDateConfirmed` date DEFAULT NULL,
  `DemandPostProcessStart` date DEFAULT NULL,
  `DemandPostProcessEnd` date DEFAULT NULL,
  `DemandCheckedBy` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `DemandCheckDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `GUI`
--

DROP TABLE IF EXISTS `GUI`;
CREATE TABLE `GUI` (
  `idGUI` int(11) NOT NULL,
  `GUIelement` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `GUIattribute` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `GUIvalue` varchar(45) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Dumping data for table `GUI`
--

INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(1, 'tabButtonAdminDemand', 'ENG', 'Orders');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(3, 'LabelProjectName', 'ENG', 'Name');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(4, 'LabelProject_idStatus', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(5, 'LabelProject_idCompany', 'ENG', 'Company');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(6, 'LabelProjectStartDate', 'ENG', 'Start Date');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(7, 'LabelProjectPriority', 'ENG', 'Priority');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(8, 'LabelQuote_idStatus', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(9, 'LabelQuote_idProject', 'ENG', 'Project');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(10, 'LabelQuote_idPrintParameters', 'ENG', 'Print Parameters');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(11, 'LabelQuoteLeadTime', 'ENG', 'Lead Time');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(12, 'LabelQuotePrice', 'ENG', 'Price');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(13, 'LabelQuoteDateSent', 'ENG', 'Date Sent');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(14, 'tabButtonProjectProject', 'ENG', 'Project');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(15, 'tabButtonCompanyProject', 'ENG', 'Projects');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(16, 'tabButtonStatusProject', 'ENG', 'Projects');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(17, 'tabButtonProjectQuote', 'ENG', 'Quotes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(18, 'tabButtonQuoteQuote', 'ENG', 'Quote');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(21, 'tabButtonQuoteDataSet', 'ENG', 'DataSets');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(23, 'tabButtonDataSetDataSet', 'ENG', 'DataSet');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(24, 'tabButtonStatusDataSet', 'ENG', 'DataSets');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(25, 'tabButtonQuoteDemand', 'ENG', 'Orders');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(26, 'tabButtonDemandDemand', 'ENG', 'Order');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(27, 'tabButtonStatusDemand', 'ENG', 'Orders');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(28, 'tabButtonDemandInvoice', 'ENG', 'Invoices');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(29, 'tabButtonInvoiceInvoice', 'ENG', 'Invoice');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(30, 'tabButtonStatusInvoice', 'ENG', 'Invoices');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(31, 'tabButtonBuildBuild', 'ENG', 'Build');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(32, 'tabButtonPlatformBuild', 'ENG', 'Builds');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(33, 'tabButtonInvoiceDeliveryNote', 'ENG', 'Delivery Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(34, 'tabButtonDeliveryNoteDeliveryNote', 'ENG', 'DeliveryNote');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(35, 'tabButtonStatusDeliveryNote', 'ENG', 'Delivery Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(36, 'tabButtonIndustryIndustry', 'ENG', 'Industry');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(37, 'tabButtonIndustryCompany', 'ENG', 'Companies');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(38, 'tabButtonCompanyCompany', 'ENG', 'Company');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(39, 'tabButtonCompanyAccount', 'ENG', 'Accounts');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(40, 'tabButtonAccountAccount', 'ENG', 'Account');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(41, 'tabButtonCompanyAddress', 'ENG', 'Addresses');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(42, 'tabButtonAddressAddress', 'ENG', 'Address');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(43, 'tabButtonCompanyPerson', 'ENG', 'Persons');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(44, 'tabButtonPersonPerson', 'ENG', 'Person');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(45, 'tabButtonPrintParametersPrintParameters', 'ENG', 'PrintParameters');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(46, 'tabButtonPrintParametersPart', 'ENG', 'Parts');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(47, 'tabButtonDataSetPart', 'ENG', 'Parts');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(48, 'tabButtonPartPart', 'ENG', 'Part');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(49, 'tabButtonStatusPart', 'ENG', 'Parts');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(50, 'tabButtonPlatform_has_PartPlatform_has_Part', 'ENG', 'Platform_has_Part');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(51, 'tabButtonPlatformPlatform', 'ENG', 'Platform');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(52, 'tabButtonDeliveryTransportDeliveryTransport', 'ENG', 'DeliveryTransport');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(53, 'tabButtonDeliveryNoteDeliveryTransport', 'ENG', 'DeliveryTransport');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(54, 'tabButtonNoteNote', 'ENG', 'Note');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(55, 'tabButtonStatusStatus', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(56, 'tabButtonStatusStatusLog', 'ENG', 'Status History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(57, 'tabButtonStatusLogStatusLog', 'ENG', 'StatusLog');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(58, 'tabButtonGUIGUI', 'ENG', 'GUI');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(59, 'LabelDemand_idStatus', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(60, 'LabelDemand_idQuote', 'ENG', 'Quote');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(61, 'LabelDemandDateReceived', 'ENG', 'Date Received');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(62, 'LabelDemandDateConfirmed', 'ENG', 'Date Confirmed');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(63, 'LabelDemandPostProcessStart', 'ENG', 'PostProcess Start');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(64, 'LabelDemandPostProcessEnd', 'ENG', 'PostProcess End');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(65, 'LabelDemandCheckedBy', 'ENG', 'Checked By');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(66, 'LabelDemandCheckDate', 'ENG', 'Check Date');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(67, 'LabelCompany_idIndustry', 'ENG', 'Industry');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(68, 'LabelCompanyName', 'ENG', 'Name');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(69, 'LabelCompanyICO', 'ENG', 'ICO');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(70, 'LabelCompanyDIC', 'ENG', 'DIC');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(71, 'LabelCompanyDRC', 'ENG', 'DRC');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(72, 'LabelCompanyWebsite', 'ENG', 'Website');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(73, 'tabButtonStatusNote', 'ENG', 'Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(75, 'tabButtonQuoteNote', 'ENG', 'Quote Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(76, 'tabButtonDemandNote', 'ENG', 'Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(77, 'tabButtonBuildNote', 'ENG', 'Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(78, 'tabButtonInvoiceNote', 'ENG', 'Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(79, 'tabButtonIndustryNote', 'ENG', 'Industry Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(80, 'tabButtonCompanyNote', 'ENG', 'Company Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(81, 'tabButtonPersonNote', 'ENG', 'Person Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(82, 'tabButtonPrintParametersNote', 'ENG', 'Print Parameter Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(83, 'tabButtonDataSetNote', 'ENG', 'DataSet Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(84, 'tabButtonPlatform_has_PartNote', 'ENG', 'Note');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(85, 'tabButtonPlatformNote', 'ENG', 'Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(86, 'tabButtonDeliveryTransportNote', 'ENG', 'Note');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(87, 'tabButtonDeliveryNoteNote', 'ENG', 'Note');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(88, 'tabButtonAccountNote', 'ENG', 'Account Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(89, 'tabButtonAddressNote', 'ENG', 'Address Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(90, 'tabButtonPartNote', 'ENG', 'Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(91, 'tabButtonStatusLogNote', 'ENG', 'Note');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(92, 'tabButtonGUINote', 'ENG', 'Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(93, 'DemandORDERidDemand', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(94, 'Demand', 'ENG', 'Order');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(95, 'DemandORDERDemandDateReceived', 'ENG', 'Date Received');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(96, 'DemandORDERStatusName', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(97, 'DemandORDERQuoteName', 'ENG', 'Quote');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(98, 'DemandORDERDemandDateConfirmed', 'ENG', 'Date Confirmed');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(99, 'DemandORDERDemandPostProcessStart', 'ENG', 'Post Process Start');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(100, 'DemandORDERDemandPostProcessEnd', 'ENG', 'Post Process End');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(101, 'DemandORDERDemandCheckedBy', 'ENG', 'Checked By');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(102, 'DemandORDERDemandCheckDate', 'ENG', 'Check Date');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(103, 'Demand', 'ENG', 'Order');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(104, 'tabButtonAdminProject', 'ENG', 'stories');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(105, 'tabButtonAdminAccount', 'ENG', 'Accounts');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(106, 'tabButtonAdminAddress', 'ENG', 'Addresses');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(107, 'tabButtonAdminBuild', 'ENG', 'Builds');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(108, 'tabButtonAdminCompany', 'ENG', 'Companies');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(109, 'tabButtonAdminDataSet', 'ENG', 'DataSets');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(110, 'tabButtonAdminDeliveryNote', 'ENG', 'Delivery Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(111, 'tabButtonAdminDeliveryTransport', 'ENG', 'Delivery Transports');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(112, 'tabButtonAdminIndustry', 'ENG', 'Industries');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(113, 'tabButtonAdminInvoice', 'ENG', 'Invoices');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(114, 'tabButtonAdminNote', 'ENG', 'Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(115, 'tabButtonAdminPart', 'ENG', 'Parts');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(116, 'tabButtonAdminPerson', 'ENG', 'Persons');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(117, 'tabButtonAdminPlatform', 'ENG', 'Platforms');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(118, 'tabButtonAdminPlatform_has_Part', 'ENG', 'Platforms have Parts');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(119, 'tabButtonAdminPrintParameters', 'ENG', 'Print Parameters');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(120, 'tabButtonAdminQuote', 'ENG', 'Quotes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(121, 'tabButtonAdminStatus', 'ENG', 'Statuses');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(122, 'tabButtonAdminStatusLog', 'ENG', 'Status Log');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(123, 'tabGUIGUI', 'ENG', 'GUI');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(124, 'LabelBuild_idPlatform', 'ENG', 'Platform');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(125, 'LabelBuildRequested', 'ENG', 'Requested');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(126, 'AccountORDERidAccount', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(127, 'AccountORDERAccountIBAN', 'ENG', 'IBAN');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(128, 'AccountORDERAccountSWIFT', 'ENG', 'SWIFT');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(129, 'AccountORDERAccountVAT', 'ENG', 'VAT');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(132, 'AddressORDERAddressCountry', 'ENG', 'Country');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(133, 'AddressORDERAddressCity', 'ENG', 'City');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(134, 'AddressORDERAddressZip', 'ENG', 'ZIP');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(135, 'AddressORDERAddressNumber', 'ENG', 'Number');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(136, 'AddressORDERAddressStreet', 'ENG', 'Street');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(137, 'AddressORDERAddressName', 'ENG', 'Name');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(138, 'AddressORDERidAddress', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(139, 'GUIORDERidGUI', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(140, 'GUIORDERGUIelement', 'ENG', 'element');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(141, 'GUIORDERGUIattribute', 'ENG', 'attribute');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(142, 'GUIORDERGUIvalue', 'ENG', 'value');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(143, 'IndustryORDERidIndustry', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(144, 'IndustryORDERIndustryShortcut', 'ENG', 'Shortcut');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(145, 'PersonORDERPersonSurname', 'ENG', 'Surname');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(146, 'PersonORDERPersonName', 'ENG', 'Name');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(147, 'PersonORDERPersonPostition', 'ENG', 'Post');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(148, 'PersonORDERPersonPhone', 'ENG', 'Phone');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(149, 'PersonORDERPersonEmail', 'ENG', 'Email');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(150, 'PrintParametersORDERidPrintParameters', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(151, 'PrintParametersORDERPrintParametersTechnology', 'ENG', 'Technology');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(152, 'PrintParametersORDERPrintParametersMaterial', 'ENG', 'Material');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(153, 'PrintParametersORDERPrintParametersResolution', 'ENG', 'Resolution');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(154, 'PrintParametersORDERPrintParametersLayer', 'ENG', 'Layer');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(155, 'PrintParametersORDERPrintParametersFinish', 'ENG', 'Finish');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(156, 'DataSetORDERPrintParametersTechnology', 'ENG', 'Technology');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(157, 'DataSetORDERidDataSet', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(158, 'DataSetORDERDataSetDate', 'ENG', 'Date');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(159, 'DataSetORDERDataSetName', 'ENG', 'Name');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(160, 'LabelDataSet_idStatus', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(161, 'LabelDataSet_idPrintParameters', 'ENG', 'Print_Parameters');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(162, 'LabelDataSet_idQuote', 'ENG', 'Id_Quote');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(163, 'LabelDataSetName', 'ENG', 'Name');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(164, 'LabelDataSetDate', 'ENG', 'Date');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(165, 'LabelPrintParametersTechnology', 'ENG', 'Technology');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(166, 'LabelPrintParametersMaterial', 'ENG', 'Material');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(167, 'LabelPrintParametersResolution', 'ENG', 'Resolution');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(168, 'LabelPrintParametersLayer', 'ENG', 'Layer');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(169, 'LabelPrintParametersFinish', 'ENG', 'Finish');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(170, 'PersonORDERidPerson', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(171, 'CompanyORDERidCompany', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(172, 'CompanyORDERCompanyName', 'ENG', 'Name');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(173, 'CompanyORDERCompanyICO', 'ENG', 'ICO');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(174, 'CompanyORDERCompanyDIC', 'ENG', 'DIC');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(175, 'CompanyORDERCompanyDRC', 'ENG', 'DRC');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(176, 'CompanyORDERCompanyWebsite', 'ENG', 'www');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(177, 'CompanyORDERIndustryName', 'ENG', 'Industry');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(178, 'ProjectORDERProjectStartDate', 'ENG', 'Start Date');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(179, 'StatusLogORDERStatusLogTimestamp', 'ENG', 'Timestamp');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(180, 'StatusLogORDERidStatusLog', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(184, 'tabButtonQuoteStatus', 'ENG', 'Quote History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(185, 'tabButtonDemandStatus', 'ENG', 'Order History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(186, 'tabButtonInvoiceStatus', 'ENG', 'Invoice History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(187, 'tabButtonDataSetStatus', 'ENG', 'DataSet History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(188, 'tabButtonDeliveryNoteStatus', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(189, 'tabButtonPartStatus', 'ENG', 'Part History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(190, 'tabButtonNoteStatus', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(191, 'tabButtonStatusStatus', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(192, 'tabButtonStatusLogStatus', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(193, 'DataSetORDERQuoteQuotePrice', 'ENG', 'QuotePrice');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(194, 'LabelPlatform_idPlatform', 'ENG', 'Platform_id');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(195, 'LabelInvoice_idStatus', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(196, 'LabelInvoicePaymentTerm', 'ENG', 'PaymentTerm');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(197, 'LabelInvoice_idDemand', 'ENG', 'Order');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(198, 'LabelInvoiceIssued', 'ENG', 'Issued');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(199, 'ProjectORDERidProject', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(200, 'ProjectORDERCompanyName', 'ENG', 'Company');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(201, 'ProjectORDERProjectName', 'ENG', 'Project');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(202, 'ProjectORDERStatusName', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(203, 'ProjectORDERProjectPriority', 'ENG', 'Priority');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(204, 'QuoteORDERidQuote', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(205, 'QuoteORDERProjectName', 'ENG', 'Project');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(206, 'QuoteORDERStatusName', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(207, 'QuoteORDERPrintParametersTechnology', 'ENG', 'Technology');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(208, 'QuoteORDERQuotePrice', 'ENG', 'Price');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(209, 'QuoteORDERQuoteDateSent', 'ENG', 'Date Sent');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(210, 'QuoteORDERQuoteLeadTime', 'ENG', 'Lead Time');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(211, 'StatusLogORDERStatusName', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(212, 'NoteORDERidNote', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(213, 'NoteORDERNoteText', 'ENG', 'Note');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(214, 'NoteORDERStatusName', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(215, 'NoteORDERNoteTime', 'ENG', 'Time');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(216, 'DataSetORDERStatusName', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(217, 'DataSetORDERQuotePrice', 'ENG', 'Price');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(218, 'DemandORDERQuotePrice', 'ENG', 'Price');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(219, 'InvoiceORDERidInvoice', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(220, 'InvoiceORDERStatusName', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(221, 'InvoiceORDERDemandName', 'ENG', 'Order');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(222, 'InvoiceORDERInvoiceIssued', 'ENG', 'Issued');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(223, 'InvoiceORDERInvoicePaymentTerm', 'ENG', 'Payment Term');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(224, 'InvoiceORDERInvoiceTotal', 'ENG', 'Total');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(225, 'InvoiceORDERInvoiceVAT', 'ENG', 'VAT');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(226, 'InvoiceORDERInvoiceType', 'ENG', 'Type');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(227, 'BuildORDERidBuild', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(228, 'BuildORDERPlatformName', 'ENG', 'Platform');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(229, 'BuildORDERBuildRequested', 'ENG', 'Requested');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(230, 'BuildORDERBuildPrintJobStart', 'ENG', 'Print Job Start');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(231, 'BuildORDERStatusName', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(232, 'DeliveryNoteORDERidDeliveryNote', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(233, 'DeliveryNoteORDERStatusName', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(234, 'DeliveryNoteORDERInvoiceName', 'ENG', 'Invoice');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(235, 'DeliveryNoteORDERDeliveryNoteIssued', 'ENG', 'Issued');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(236, 'DeliveryNoteORDERDeliveryNoteSigned', 'ENG', 'Signed');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(237, 'DeliveryNoteORDERShippingAddress', 'ENG', 'Shipping Address');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(238, 'AccountORDERAccountName', 'ENG', 'Name');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(239, 'AccountORDERCompanyName', 'ENG', 'Company');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(240, 'AccountORDERAccountBank', 'ENG', 'Bank');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(241, 'AccountORDERAccountBankCode', 'ENG', 'Bank Code');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(242, 'AddressORDERCompanyName', 'ENG', 'Company');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(243, 'PersonORDERCompanyName', 'ENG', 'Company');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(244, 'PartORDERidPart', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(245, 'PartORDERStatusName', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(246, 'PartORDERDataSetName', 'ENG', 'Date Set');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(247, 'PartORDERPrintParametersTechnology', 'ENG', 'Technology');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(248, 'PartORDERPartFileName', 'ENG', 'File Name');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(249, 'PartORDERPartQuoteFileName', 'ENG', 'Quote File Name');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(250, 'PartORDERPartAMFileName', 'ENG', 'AM File Name');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(251, 'PartORDERPartFinish', 'ENG', 'Finish');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(252, 'PartORDERPartQuantity', 'ENG', 'Quantity');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(253, 'Platform_has_PartORDERPlatformName', 'ENG', 'Name');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(254, 'Platform_has_PartORDERPartName', 'ENG', 'Part');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(255, 'Platform_has_PartORDERPartQuantity', 'ENG', 'Part Quantity');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(256, 'PlatformORDERidPlatform', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(257, 'PlatformORDERPlatformName', 'ENG', 'Name');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(258, 'DeliveryTransportORDERidDeliveryTransport', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(259, 'DeliveryTransportORDERDeliveryNoteName', 'ENG', 'Delivery Note');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(260, 'DeliveryTransportORDERdeliveryTransportMethod', 'ENG', 'Method');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(261, 'DeliveryTransportORDERdeliveryTransportOption', 'ENG', 'Option');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(262, 'DeliveryTransportORDERdeliveryTransportDate', 'ENG', 'Date');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(263, 'DeliveryTransportORDERdeliveryTransportPayment', 'ENG', 'Payment');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(264, 'StatusORDERidStatus', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(265, 'StatusORDERStatusType', 'ENG', 'Type');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(266, 'StatusORDERStatusName', 'ENG', 'Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(267, 'StatusORDERStatusColor', 'ENG', 'Color');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(268, 'tabProjectStatus', 'ENG', 'Project History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(269, 'tabButtonProjectNote', 'ENG', 'Project Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(270, 'tabProjectQuote', 'ENG', 'Quotes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(271, 'tabProjectNote', 'ENG', 'Project Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(272, 'tabButtonProjectStatus', 'ENG', 'Project History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(274, 'tabAdminProject', 'ENG', 'stories');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(275, 'tabAdminQuote', 'ENG', 'Quotes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(276, 'tabAdminDemand', 'ENG', 'Orders');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(277, 'tabAdminBuild', 'ENG', 'Builds');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(278, 'tabAdminInvoice', 'ENG', 'Invoices');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(279, 'tabAdminIndustry', 'ENG', 'Industries');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(280, 'tabAdminCompany', 'ENG', 'Companies');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(281, 'tabAdminPerson', 'ENG', 'Persons');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(282, 'tabAdminPrintParameters', 'ENG', 'Print Parameters');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(283, 'tabAdminDataSet', 'ENG', 'DataSets');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(284, 'tabAdminAccount', 'ENG', 'Accounts');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(285, 'tabAdminAddress', 'ENG', 'Addresses');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(286, 'tabAdminPart', 'ENG', 'Parts');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(287, 'tabAdminPlatform_has_Part', 'ENG', 'Platforms have Parts');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(288, 'tabAdminPlatform', 'ENG', 'Platforms');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(289, 'tabAdminDeliveryTransport', 'ENG', 'Delivery Transports');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(290, 'tabAdminDeliveryNote', 'ENG', 'Delivery Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(291, 'tabAdminNote', 'ENG', 'Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(292, 'tabAdminStatus', 'ENG', 'Statuses');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(293, 'tabAdminStatusLog', 'ENG', 'Status Log');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(294, 'tabAdminGUI', 'ENG', 'GUI');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(295, 'tabQuoteDataSet', 'ENG', 'DataSets');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(296, 'tabQuoteDemand', 'ENG', 'Orders');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(297, 'tabQuoteStatus', 'ENG', 'Quote History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(298, 'tabQuoteNote', 'ENG', 'Quote Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(299, 'DemandInsert', 'ENG', '+ Order');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(300, 'tabIndustryCompany', 'ENG', 'Companies');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(304, 'tabIndustryNote', 'ENG', 'Industry Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(305, 'tabCompanyAccount', 'ENG', 'Accounts');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(306, 'tabCompanyAddress', 'ENG', 'Addresses');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(308, 'tabCompanyNote', 'ENG', 'Company Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(309, 'tabCompanyProject', 'ENG', 'Projects');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(310, 'tabCompanyPerson', 'ENG', 'Persons');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(311, 'tabPersonNote', 'ENG', 'Person Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(312, 'tabButtonPrintParametersDataSet', 'ENG', 'DataSets');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(313, 'tabPrintParametersDataSet', 'ENG', 'DataSets');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(314, 'tabPrintParametersPart', 'ENG', 'Parts');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(315, 'tabButtonPrintParametersQuote', 'ENG', 'Quotes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(317, 'tabPrintParametersQuote', 'ENG', 'Quotes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(319, 'tabPrintParametersNote', 'ENG', 'Print Parameter Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(321, 'tabDataSetPart', 'ENG', 'Parts');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(322, 'tabDataSetStatus', 'ENG', 'DataSet History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(323, 'tabDataSetNote', 'ENG', 'DataSet Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(325, 'tabAccountNote', 'ENG', 'Account Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(326, 'tabAddressNote', 'ENG', 'Address Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(327, 'tabStatusDataSet', 'ENG', 'DataSets');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(328, 'tabStatusDeliveryNote', 'ENG', 'Delivery Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(329, 'tabStatusDemand', 'ENG', 'Orders');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(330, 'tabStatusInvoice', 'ENG', 'Invoices');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(331, 'tabStatusNote', 'ENG', 'Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(332, 'tabStatusPart', 'ENG', 'Parts');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(333, 'tabStatusProject', 'ENG', 'Projects');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(334, 'tabButtonStatusQuote', 'ENG', 'Quotes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(335, 'tabStatusQuote', 'ENG', 'Quotes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(336, 'tabStatusStatusLog', 'ENG', 'Status History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(337, 'tabGUINote', 'ENG', 'Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(338, 'ProjectInsert', 'ENG', '+ Project');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(339, 'QuoteInsert', 'ENG', '+ Quote');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(340, 'BuildInsert', 'ENG', '+ Build');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(341, 'InvoiceInsert', 'ENG', '+ Invoice');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(342, 'IndustryInsert', 'ENG', '+ Industry');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(343, 'CompanyInsert', 'ENG', '+ Company');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(344, 'PersonInsert', 'ENG', '+ Person');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(345, 'PrintParametersInsert', 'ENG', '+ Print Parameter');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(346, 'DataSetInsert', 'ENG', '+ DataSet');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(347, 'PlatformInsert', 'ENG', '+ Platform');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(348, 'DeliveryTransportInsert', 'ENG', '+ Delivery Transport');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(349, 'DeliveryNoteInsert', 'ENG', '+ Delivery Note');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(350, 'AccountInsert', 'ENG', '+ Account');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(351, 'AddressInsert', 'ENG', '+ Address');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(352, 'PartInsert', 'ENG', '+ Part');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(353, 'NoteInsert', 'ENG', '+ Note');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(354, 'StatusLogInsert', 'ENG', '+ History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(355, 'GUIInsert', 'ENG', '+ GUI');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(356, 'StatusInsert', 'ENG', '+ Status');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(357, 'tabDemandInvoice', 'ENG', 'Invoices');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(358, 'tabDemandStatus', 'ENG', 'Order History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(359, 'tabDemandNote', 'ENG', 'Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(360, 'tabPlatformBuild', 'ENG', 'Builds');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(362, 'tabPlatformNote', 'ENG', 'Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(363, 'tabPartStatus', 'ENG', 'Part History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(364, 'tabPartNote', 'ENG', 'Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(367, 'tabInvoiceNote', 'ENG', 'Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(368, 'tabInvoiceStatus', 'ENG', 'Invoice History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(369, 'tabInvoiceDeliveryNote', 'ENG', 'Delivery Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(371, 'tabBuildNote', 'ENG', 'Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(372, 'tabButtonBuildStatus', 'ENG', 'Build History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(373, 'tabBuildStatus', 'ENG', 'Build History');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(374, 'DeliveryTransport', 'lookupField', 'idDeliveryTransport');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(375, 'tabButtonStatusBuild', 'ENG', 'Builds');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(377, 'tabStatusBuild', 'ENG', 'Builds');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(378, 'BuildORDERBuildPrintJobEnd', 'ENG', 'Build Print Job End');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(379, 'IndustryORDERIndustryName', 'ENG', 'Name');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(382, 'DataSetFileName', 'type', 'path');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(383, 'CompanyName', 'lookupType', 'suggest');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(384, 'PartFileName', 'type', 'path');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(385, 'PartQuoteFileName', 'type', 'path');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(386, 'PartAMFileName', 'type', 'path');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(387, 'MaterialORDERidMaterial', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(388, 'tabMaterialNote', 'ENG', 'Material Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(389, 'tabButtonDemandNote', 'ENG', 'Order Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(390, 'tabDemandNote', 'ENG', 'Order Notes');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(391, 'Invoice', 'lookupField', 'InvoiceIdentifier');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(392, 'DeliveryNote', 'lookupField', 'idDeliveryNote');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(394, 'PrintParameters', 'lookupField', 'PrintParametersTechnology');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(399, 'ActionORDERidAction', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(400, 'ActionORDERActionName', 'ENG', 'Name');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(401, 'ActionORDERActionSequence', 'ENG', 'Sequence');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(402, 'ActionORDERActionTable', 'ENG', 'Table');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(403, 'ActionORDERActionField', 'ENG', 'Field');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(404, 'ActionORDERActionCommand', 'ENG', 'Command');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(405, 'ActionORDERActionParam1', 'ENG', 'Parameter 1');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(406, 'ActionORDERActionParam2', 'ENG', 'Parameter 2');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(407, 'InvoiceORDERInvoiceDue', 'ENG', 'Due');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(408, 'Payment', 'lookupField', 'PaymentIdentifier');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(409, 'tabAdminPayment', 'ENG', 'Payments');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(410, 'tabButtonAdminPayment', 'ENG', 'Payments');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(411, 'Action', 'lookupField', 'Action_idAction');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(412, 'tabButtonProjectRelationLeft', 'ENG', '--->');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(413, 'tabButtonQuoteRelationLeft', 'ENG', '--->');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(418, 'tabProjectRelationLeft', 'ENG', '--->');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(419, 'RelationORDERidRelation', 'ENG', '#');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(420, 'RelationORDERRelationRightTable', 'ENG', 'Connect to Table');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(421, 'RelationORDERRelationRightId', 'ENG', 'Connect to Record');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(422, 'RelationORDERRelationLeftTable', 'ENG', 'Connected Table');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(423, 'RelationORDERRelationLeftId', 'ENG', 'Connected Record');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(424, 'tabButtonProjectRelationRight', 'ENG', '<---');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(425, 'tabButtonQuoteRelationRight', 'ENG', '<---');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(430, 'tabDemandRelationRight', 'ENG', '<---');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(431, 'tabBuildRelationRight', 'ENG', '<---');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(432, 'tabInvoiceRelationRight', 'ENG', '<---');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(433, 'tabProjectRelationRight', 'ENG', '<---');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(434, 'tabQuoteRelationLeft', 'ENG', '--->');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(435, 'tabQuoteRelationRight', 'ENG', '<---');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(436, 'tabDemandRelationLeft', 'ENG', '--->');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(437, 'tabButtonDemandRelationRight', 'ENG', '<---');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(438, 'tabButtonDemandRelationLeft', 'ENG', '--->');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(439, 'tabBuildRelationLeft', 'ENG', '--->');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(440, 'tabButtonBuildRelationRight', 'ENG', '<---');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(441, 'tabButtonBuildRelationLeft', 'ENG', '--->');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(442, 'Payment', 'lookupField', 'PaymentAmunt');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(443, 'ProjectName', 'lookupType', 'suggest');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(444, 'RelationLeft', 'ENG', '--->');
INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES(445, 'RelationRight', 'ENG', '<---');

-- --------------------------------------------------------

--
-- Table structure for table `History`
--

DROP TABLE IF EXISTS `History`;
CREATE TABLE `History` (
  `idHistory` int(11) NOT NULL,
  `HistoryTable` varchar(30) COLLATE utf8_slovak_ci DEFAULT NULL,
  `HistoryRowId` int(11) DEFAULT NULL,
  `HistoryCommand` varchar(20) COLLATE utf8_slovak_ci DEFAULT NULL,
  `HistorySQL` varchar(500) COLLATE utf8_slovak_ci DEFAULT NULL,
  `HistoryTimestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Industry`
--

DROP TABLE IF EXISTS `Industry`;
CREATE TABLE `Industry` (
  `idIndustry` int(11) NOT NULL,
  `IndustryName` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `IndustryShortcut` varchar(5) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Invoice`
--

DROP TABLE IF EXISTS `Invoice`;
CREATE TABLE `Invoice` (
  `idInvoice` int(11) NOT NULL,
  `InvoiceIssued` date DEFAULT NULL,
  `InvoicePaymentTerm` int(11) DEFAULT NULL,
  `InvoiceTotal` float DEFAULT '0',
  `InvoiceVAT` float DEFAULT '0',
  `InvoiceType` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `InvoiceDue` date DEFAULT NULL,
  `InvoiceIdentifier` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Material`
--

DROP TABLE IF EXISTS `Material`;
CREATE TABLE `Material` (
  `idMaterial` int(11) NOT NULL,
  `MaterialName` varchar(45) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Note`
--

DROP TABLE IF EXISTS `Note`;
CREATE TABLE `Note` (
  `idNote` int(11) NOT NULL,
  `NoteText` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `NoteTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `NoteTable` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `NoteRowId` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Part`
--

DROP TABLE IF EXISTS `Part`;
CREATE TABLE `Part` (
  `idPart` int(11) NOT NULL,
  `PartName` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `PartFileName` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `PartQuoteFileName` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `PartAMFileName` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `PartFinish` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `PartQuantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Payment`
--

DROP TABLE IF EXISTS `Payment`;
CREATE TABLE `Payment` (
  `idPayment` int(11) NOT NULL,
  `PaymentAmount` float DEFAULT NULL,
  `PaymentIdentifier` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `PaymentFlow` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `PaymentType` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `PaymentDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Person`
--

DROP TABLE IF EXISTS `Person`;
CREATE TABLE `Person` (
  `idPerson` int(11) NOT NULL,
  `PersonName` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `PersonSurname` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `PersonTitle` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `PersonAppend` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `PersonPostition` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `PersonPhone` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `PersonEmail` varchar(45) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Platform`
--

DROP TABLE IF EXISTS `Platform`;
CREATE TABLE `Platform` (
  `idPlatform` int(11) NOT NULL,
  `PlatformName` varchar(45) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PrintParameters`
--

DROP TABLE IF EXISTS `PrintParameters`;
CREATE TABLE `PrintParameters` (
  `idPrintParameters` int(11) NOT NULL,
  `PrintParametersName` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `PrintParametersTechnology` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `PrintParametersResolution` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `PrintParametersLayer` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `PrintParametersFinish` varchar(40) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Project`
--

DROP TABLE IF EXISTS `Project`;
CREATE TABLE `Project` (
  `idProject` int(11) NOT NULL,
  `ProjectName` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `ProjectStartDate` date DEFAULT NULL,
  `ProjectDeadline` date DEFAULT NULL,
  `ProjectPriority` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Quote`
--

DROP TABLE IF EXISTS `Quote`;
CREATE TABLE `Quote` (
  `idQuote` int(11) NOT NULL,
  `QuoteName` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `QuoteLeadTime` time DEFAULT NULL,
  `QuoteCost` decimal(10,2) DEFAULT '0.00',
  `QuotePrice` decimal(10,2) DEFAULT '0.00',
  `QuoteDateSent` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Relation`
--

DROP TABLE IF EXISTS `Relation`;
CREATE TABLE `Relation` (
  `idRelation` int(11) NOT NULL,
  `RelationType` varchar(20) COLLATE utf8_slovak_ci DEFAULT NULL,
  `RelationLObject` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `RelationLId` int(11) DEFAULT NULL,
  `RelationRObject` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `RelationRId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Dumping data for table `Relation`
--

INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(1, 'TTCP', 'Invoice', 0, 'Demand', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(2, 'TTCP', 'Project', 0, 'Status', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(3, 'TTCP', 'Quote', 0, 'Status', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(4, 'TTCP', 'Demand', 0, 'Status', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(5, 'TTCP', 'Invoice', 0, 'Status', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(6, 'TTCP', 'DeliveryNote', 0, 'Status', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(7, 'TTCP', 'Build', 0, 'Status', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(8, 'TTCP', 'Part', 0, 'Status', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(9, 'TTCP', 'DataSet', 0, 'Status', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(10, 'TTCP', 'Build', 0, 'Platform', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(11, 'TTCP', 'DeliveryTransport', 0, 'DeliveryNote', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(12, 'TTCP', 'DeliveryNote', 0, 'Invoice', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(13, 'TTCP', 'Payment', 0, 'Invoice', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(14, 'TTCP', 'Quote', 0, 'Project', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(15, 'TTCP', 'Demand', 0, 'Quote', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(16, 'TTCP', 'Part', 0, 'PrintParameters', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(17, 'TTCP', 'Part', 0, 'DataSet', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(18, 'TTCP', 'DataSet', 0, 'PrintParameters', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(19, 'TTCP', 'DataSet', 0, 'Quote', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(20, 'TTCP', 'PrintParameters', 0, 'Material', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(21, 'TTCP', 'Quote', 0, 'PrintParameters', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(22, 'TTCP', 'Account', 0, 'Company', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(23, 'TTCP', 'Address', 0, 'Company', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(24, 'TTCP', 'Person', 0, 'Company', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(25, 'TTCP', 'Company', 0, 'Industry', 0);
INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES(26, 'TTCP', 'Project', 0, 'Company', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Status`
--

DROP TABLE IF EXISTS `Status`;
CREATE TABLE `Status` (
  `idStatus` int(11) NOT NULL,
  `StatusType` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `StatusName` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `StatusColor` varchar(20) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `StatusLog`
--

DROP TABLE IF EXISTS `StatusLog`;
CREATE TABLE `StatusLog` (
  `idStatusLog` int(11) NOT NULL,
  `StatusLogRowId` int(11) NOT NULL,
  `StatusLog_idStatus` int(11) NOT NULL,
  `StatusLogTimestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Account`
--
ALTER TABLE `Account`
  ADD PRIMARY KEY (`idAccount`);

--
-- Indexes for table `Action`
--
ALTER TABLE `Action`
  ADD PRIMARY KEY (`idAction`);

--
-- Indexes for table `Address`
--
ALTER TABLE `Address`
  ADD PRIMARY KEY (`idAddress`);

--
-- Indexes for table `Build`
--
ALTER TABLE `Build`
  ADD PRIMARY KEY (`idBuild`);

--
-- Indexes for table `Company`
--
ALTER TABLE `Company`
  ADD PRIMARY KEY (`idCompany`),
  ADD UNIQUE KEY `CompanyName_UNIQUE` (`CompanyName`);

--
-- Indexes for table `DataSet`
--
ALTER TABLE `DataSet`
  ADD PRIMARY KEY (`idDataSet`);

--
-- Indexes for table `DeliveryNote`
--
ALTER TABLE `DeliveryNote`
  ADD PRIMARY KEY (`idDeliveryNote`);

--
-- Indexes for table `DeliveryTransport`
--
ALTER TABLE `DeliveryTransport`
  ADD PRIMARY KEY (`idDeliveryTransport`);

--
-- Indexes for table `Demand`
--
ALTER TABLE `Demand`
  ADD PRIMARY KEY (`idDemand`);

--
-- Indexes for table `GUI`
--
ALTER TABLE `GUI`
  ADD PRIMARY KEY (`idGUI`);

--
-- Indexes for table `History`
--
ALTER TABLE `History`
  ADD PRIMARY KEY (`idHistory`);

--
-- Indexes for table `Industry`
--
ALTER TABLE `Industry`
  ADD PRIMARY KEY (`idIndustry`),
  ADD UNIQUE KEY `IndustryShortcut_UNIQUE` (`IndustryShortcut`),
  ADD UNIQUE KEY `IndustryName_UNIQUE` (`IndustryName`);

--
-- Indexes for table `Invoice`
--
ALTER TABLE `Invoice`
  ADD PRIMARY KEY (`idInvoice`);

--
-- Indexes for table `Material`
--
ALTER TABLE `Material`
  ADD PRIMARY KEY (`idMaterial`);

--
-- Indexes for table `Note`
--
ALTER TABLE `Note`
  ADD PRIMARY KEY (`idNote`);

--
-- Indexes for table `Part`
--
ALTER TABLE `Part`
  ADD PRIMARY KEY (`idPart`);

--
-- Indexes for table `Payment`
--
ALTER TABLE `Payment`
  ADD PRIMARY KEY (`idPayment`);

--
-- Indexes for table `Person`
--
ALTER TABLE `Person`
  ADD PRIMARY KEY (`idPerson`);

--
-- Indexes for table `Platform`
--
ALTER TABLE `Platform`
  ADD PRIMARY KEY (`idPlatform`);

--
-- Indexes for table `PrintParameters`
--
ALTER TABLE `PrintParameters`
  ADD PRIMARY KEY (`idPrintParameters`);

--
-- Indexes for table `Project`
--
ALTER TABLE `Project`
  ADD PRIMARY KEY (`idProject`),
  ADD UNIQUE KEY `ProjectName_UNIQUE` (`ProjectName`);

--
-- Indexes for table `Quote`
--
ALTER TABLE `Quote`
  ADD PRIMARY KEY (`idQuote`);

--
-- Indexes for table `Relation`
--
ALTER TABLE `Relation`
  ADD PRIMARY KEY (`idRelation`),
  ADD UNIQUE KEY `index2` (`RelationLObject`,`RelationLId`,`RelationRObject`,`RelationRId`);

--
-- Indexes for table `Status`
--
ALTER TABLE `Status`
  ADD PRIMARY KEY (`idStatus`);

--
-- Indexes for table `StatusLog`
--
ALTER TABLE `StatusLog`
  ADD PRIMARY KEY (`idStatusLog`,`StatusLogRowId`,`StatusLog_idStatus`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Account`
--
ALTER TABLE `Account`
  MODIFY `idAccount` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Action`
--
ALTER TABLE `Action`
  MODIFY `idAction` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Address`
--
ALTER TABLE `Address`
  MODIFY `idAddress` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Build`
--
ALTER TABLE `Build`
  MODIFY `idBuild` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Company`
--
ALTER TABLE `Company`
  MODIFY `idCompany` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `DataSet`
--
ALTER TABLE `DataSet`
  MODIFY `idDataSet` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `DeliveryNote`
--
ALTER TABLE `DeliveryNote`
  MODIFY `idDeliveryNote` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `DeliveryTransport`
--
ALTER TABLE `DeliveryTransport`
  MODIFY `idDeliveryTransport` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Demand`
--
ALTER TABLE `Demand`
  MODIFY `idDemand` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `GUI`
--
ALTER TABLE `GUI`
  MODIFY `idGUI` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=446;
--
-- AUTO_INCREMENT for table `History`
--
ALTER TABLE `History`
  MODIFY `idHistory` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Industry`
--
ALTER TABLE `Industry`
  MODIFY `idIndustry` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Invoice`
--
ALTER TABLE `Invoice`
  MODIFY `idInvoice` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Material`
--
ALTER TABLE `Material`
  MODIFY `idMaterial` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Note`
--
ALTER TABLE `Note`
  MODIFY `idNote` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Part`
--
ALTER TABLE `Part`
  MODIFY `idPart` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Payment`
--
ALTER TABLE `Payment`
  MODIFY `idPayment` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Person`
--
ALTER TABLE `Person`
  MODIFY `idPerson` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Platform`
--
ALTER TABLE `Platform`
  MODIFY `idPlatform` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PrintParameters`
--
ALTER TABLE `PrintParameters`
  MODIFY `idPrintParameters` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Project`
--
ALTER TABLE `Project`
  MODIFY `idProject` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Quote`
--
ALTER TABLE `Quote`
  MODIFY `idQuote` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Relation`
--
ALTER TABLE `Relation`
  MODIFY `idRelation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `Status`
--
ALTER TABLE `Status`
  MODIFY `idStatus` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `StatusLog`
--
ALTER TABLE `StatusLog`
  MODIFY `idStatusLog` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
