-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 23, 2016 at 11:40 AM
-- Server version: 5.7.13-0ubuntu0.16.04.2
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

CREATE TABLE `Action` (
  `idAction` int(11) NOT NULL,
  `ActionName` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `ActionSequence` int(2) DEFAULT '0',
  `ActionTable` varchar(30) COLLATE utf8_slovak_ci DEFAULT NULL,
  `ActionField` varchar(30) COLLATE utf8_slovak_ci DEFAULT NULL,
  `ActionCommand` varchar(20) COLLATE utf8_slovak_ci DEFAULT NULL,
  `ActionParam1` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Address`
--

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

CREATE TABLE `DataSet` (
  `idDataSet` int(11) NOT NULL,
  `DataSetName` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `DataSetFileName` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `DataSetDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `DeliveryNote`
--

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

CREATE TABLE `DeliveryTransport` (
  `idDeliveryTransport` int(11) NOT NULL,
  `DeliveryTransportName` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `DeliveryTransportMethod` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `DeliveryTransportOption` varchar(10) COLLATE utf8_slovak_ci DEFAULT NULL,
  `DeliveryTransportDate` date DEFAULT NULL,
  `DeliveryTransportPayment` varchar(45) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Demand`
--

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

CREATE TABLE `GUI` (
  `idGUI` int(11) NOT NULL,
  `GUIelement` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `GUIattribute` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `GUIvalue` varchar(45) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Dumping data for table `GUI`
--

INSERT INTO `GUI` (`idGUI`, `GUIelement`, `GUIattribute`, `GUIvalue`) VALUES
(1, 'tabButtonAdminDemand', 'ENG', 'Orders'),
(3, 'LabelProjectName', 'ENG', 'Name'),
(4, 'LabelProject_idStatus', 'ENG', 'Status'),
(5, 'LabelProject_idCompany', 'ENG', 'Company'),
(6, 'LabelProjectStartDate', 'ENG', 'Start Date'),
(7, 'LabelProjectPriority', 'ENG', 'Priority'),
(8, 'LabelQuote_idStatus', 'ENG', 'Status'),
(9, 'LabelQuote_idProject', 'ENG', 'Project'),
(10, 'LabelQuote_idPrintParameters', 'ENG', 'Print Parameters'),
(11, 'LabelQuoteLeadTime', 'ENG', 'Lead Time'),
(12, 'LabelQuotePrice', 'ENG', 'Price'),
(13, 'LabelQuoteDateSent', 'ENG', 'Date Sent'),
(14, 'tabButtonProjectProject', 'ENG', 'Project'),
(15, 'tabButtonCompanyProject', 'ENG', 'Projects'),
(16, 'tabButtonStatusProject', 'ENG', 'Projects'),
(17, 'tabButtonProjectQuote', 'ENG', 'Quotes'),
(18, 'tabButtonQuoteQuote', 'ENG', 'Quote'),
(21, 'tabButtonQuoteDataSet', 'ENG', 'DataSets'),
(23, 'tabButtonDataSetDataSet', 'ENG', 'DataSet'),
(24, 'tabButtonStatusDataSet', 'ENG', 'DataSets'),
(25, 'tabButtonQuoteDemand', 'ENG', 'Orders'),
(26, 'tabButtonDemandDemand', 'ENG', 'Order'),
(27, 'tabButtonStatusDemand', 'ENG', 'Orders'),
(28, 'tabButtonDemandInvoice', 'ENG', 'Invoices'),
(29, 'tabButtonInvoiceInvoice', 'ENG', 'Invoice'),
(30, 'tabButtonStatusInvoice', 'ENG', 'Invoices'),
(31, 'tabButtonBuildBuild', 'ENG', 'Build'),
(32, 'tabButtonPlatformBuild', 'ENG', 'Builds'),
(33, 'tabButtonInvoiceDeliveryNote', 'ENG', 'Delivery Notes'),
(34, 'tabButtonDeliveryNoteDeliveryNote', 'ENG', 'DeliveryNote'),
(35, 'tabButtonStatusDeliveryNote', 'ENG', 'Delivery Notes'),
(36, 'tabButtonIndustryIndustry', 'ENG', 'Industry'),
(37, 'tabButtonIndustryCompany', 'ENG', 'Companies'),
(38, 'tabButtonCompanyCompany', 'ENG', 'Company'),
(39, 'tabButtonCompanyAccount', 'ENG', 'Accounts'),
(40, 'tabButtonAccountAccount', 'ENG', 'Account'),
(41, 'tabButtonCompanyAddress', 'ENG', 'Addresses'),
(42, 'tabButtonAddressAddress', 'ENG', 'Address'),
(43, 'tabButtonCompanyPerson', 'ENG', 'Persons'),
(44, 'tabButtonPersonPerson', 'ENG', 'Person'),
(45, 'tabButtonPrintParametersPrintParameters', 'ENG', 'PrintParameters'),
(46, 'tabButtonPrintParametersPart', 'ENG', 'Parts'),
(47, 'tabButtonDataSetPart', 'ENG', 'Parts'),
(48, 'tabButtonPartPart', 'ENG', 'Part'),
(49, 'tabButtonStatusPart', 'ENG', 'Parts'),
(50, 'tabButtonPlatform_has_PartPlatform_has_Part', 'ENG', 'Platform_has_Part'),
(51, 'tabButtonPlatformPlatform', 'ENG', 'Platform'),
(52, 'tabButtonDeliveryTransportDeliveryTransport', 'ENG', 'DeliveryTransport'),
(53, 'tabButtonDeliveryNoteDeliveryTransport', 'ENG', 'DeliveryTransport'),
(54, 'tabButtonNoteNote', 'ENG', 'Note'),
(55, 'tabButtonStatusStatus', 'ENG', 'Status'),
(56, 'tabButtonStatusStatusLog', 'ENG', 'Status History'),
(57, 'tabButtonStatusLogStatusLog', 'ENG', 'StatusLog'),
(58, 'tabButtonGUIGUI', 'ENG', 'GUI'),
(59, 'LabelDemand_idStatus', 'ENG', 'Status'),
(60, 'LabelDemand_idQuote', 'ENG', 'Quote'),
(61, 'LabelDemandDateReceived', 'ENG', 'Date Received'),
(62, 'LabelDemandDateConfirmed', 'ENG', 'Date Confirmed'),
(63, 'LabelDemandPostProcessStart', 'ENG', 'PostProcess Start'),
(64, 'LabelDemandPostProcessEnd', 'ENG', 'PostProcess End'),
(65, 'LabelDemandCheckedBy', 'ENG', 'Checked By'),
(66, 'LabelDemandCheckDate', 'ENG', 'Check Date'),
(67, 'LabelCompany_idIndustry', 'ENG', 'Industry'),
(68, 'LabelCompanyName', 'ENG', 'Name'),
(69, 'LabelCompanyICO', 'ENG', 'ICO'),
(70, 'LabelCompanyDIC', 'ENG', 'DIC'),
(71, 'LabelCompanyDRC', 'ENG', 'DRC'),
(72, 'LabelCompanyWebsite', 'ENG', 'Website'),
(73, 'tabButtonStatusNote', 'ENG', 'Notes'),
(75, 'tabButtonQuoteNote', 'ENG', 'Quote Notes'),
(76, 'tabButtonDemandNote', 'ENG', 'Notes'),
(77, 'tabButtonBuildNote', 'ENG', 'Notes'),
(78, 'tabButtonInvoiceNote', 'ENG', 'Notes'),
(79, 'tabButtonIndustryNote', 'ENG', 'Industry Notes'),
(80, 'tabButtonCompanyNote', 'ENG', 'Company Notes'),
(81, 'tabButtonPersonNote', 'ENG', 'Person Notes'),
(82, 'tabButtonPrintParametersNote', 'ENG', 'Print Parameter Notes'),
(83, 'tabButtonDataSetNote', 'ENG', 'DataSet Notes'),
(84, 'tabButtonPlatform_has_PartNote', 'ENG', 'Note'),
(85, 'tabButtonPlatformNote', 'ENG', 'Notes'),
(86, 'tabButtonDeliveryTransportNote', 'ENG', 'Note'),
(87, 'tabButtonDeliveryNoteNote', 'ENG', 'Note'),
(88, 'tabButtonAccountNote', 'ENG', 'Account Notes'),
(89, 'tabButtonAddressNote', 'ENG', 'Address Notes'),
(90, 'tabButtonPartNote', 'ENG', 'Notes'),
(91, 'tabButtonStatusLogNote', 'ENG', 'Note'),
(92, 'tabButtonGUINote', 'ENG', 'Notes'),
(93, 'DemandORDERidDemand', 'ENG', '#'),
(94, 'Demand', 'ENG', 'Order'),
(95, 'DemandORDERDemandDateReceived', 'ENG', 'Date Received'),
(96, 'DemandORDERStatusName', 'ENG', 'Status'),
(97, 'DemandORDERQuoteName', 'ENG', 'Quote'),
(98, 'DemandORDERDemandDateConfirmed', 'ENG', 'Date Confirmed'),
(99, 'DemandORDERDemandPostProcessStart', 'ENG', 'Post Process Start'),
(100, 'DemandORDERDemandPostProcessEnd', 'ENG', 'Post Process End'),
(101, 'DemandORDERDemandCheckedBy', 'ENG', 'Checked By'),
(102, 'DemandORDERDemandCheckDate', 'ENG', 'Check Date'),
(103, 'Demand', 'ENG', 'Order'),
(104, 'tabButtonAdminProject', 'ENG', 'stories'),
(105, 'tabButtonAdminAccount', 'ENG', 'Accounts'),
(106, 'tabButtonAdminAddress', 'ENG', 'Addresses'),
(107, 'tabButtonAdminBuild', 'ENG', 'Builds'),
(108, 'tabButtonAdminCompany', 'ENG', 'Companies'),
(109, 'tabButtonAdminDataSet', 'ENG', 'DataSets'),
(110, 'tabButtonAdminDeliveryNote', 'ENG', 'Delivery Notes'),
(111, 'tabButtonAdminDeliveryTransport', 'ENG', 'Delivery Transports'),
(112, 'tabButtonAdminIndustry', 'ENG', 'Industries'),
(113, 'tabButtonAdminInvoice', 'ENG', 'Invoices'),
(114, 'tabButtonAdminNote', 'ENG', 'Notes'),
(115, 'tabButtonAdminPart', 'ENG', 'Parts'),
(116, 'tabButtonAdminPerson', 'ENG', 'Persons'),
(117, 'tabButtonAdminPlatform', 'ENG', 'Platforms'),
(118, 'tabButtonAdminPlatform_has_Part', 'ENG', 'Platforms have Parts'),
(119, 'tabButtonAdminPrintParameters', 'ENG', 'Print Parameters'),
(120, 'tabButtonAdminQuote', 'ENG', 'Quotes'),
(121, 'tabButtonAdminStatus', 'ENG', 'Statuses'),
(122, 'tabButtonAdminStatusLog', 'ENG', 'Status Log'),
(123, 'tabGUIGUI', 'ENG', 'GUI'),
(124, 'LabelBuild_idPlatform', 'ENG', 'Platform'),
(125, 'LabelBuildRequested', 'ENG', 'Requested'),
(126, 'AccountORDERidAccount', 'ENG', '#'),
(127, 'AccountORDERAccountIBAN', 'ENG', 'IBAN'),
(128, 'AccountORDERAccountSWIFT', 'ENG', 'SWIFT'),
(129, 'AccountORDERAccountVAT', 'ENG', 'VAT'),
(132, 'AddressORDERAddressCountry', 'ENG', 'Country'),
(133, 'AddressORDERAddressCity', 'ENG', 'City'),
(134, 'AddressORDERAddressZip', 'ENG', 'ZIP'),
(135, 'AddressORDERAddressNumber', 'ENG', 'Number'),
(136, 'AddressORDERAddressStreet', 'ENG', 'Street'),
(137, 'AddressORDERAddressName', 'ENG', 'Name'),
(138, 'AddressORDERidAddress', 'ENG', '#'),
(139, 'GUIORDERidGUI', 'ENG', '#'),
(140, 'GUIORDERGUIelement', 'ENG', 'element'),
(141, 'GUIORDERGUIattribute', 'ENG', 'attribute'),
(142, 'GUIORDERGUIvalue', 'ENG', 'value'),
(143, 'IndustryORDERidIndustry', 'ENG', '#'),
(144, 'IndustryORDERIndustryShortcut', 'ENG', 'Shortcut'),
(145, 'PersonORDERPersonSurname', 'ENG', 'Surname'),
(146, 'PersonORDERPersonName', 'ENG', 'Name'),
(147, 'PersonORDERPersonPostition', 'ENG', 'Post'),
(148, 'PersonORDERPersonPhone', 'ENG', 'Phone'),
(149, 'PersonORDERPersonEmail', 'ENG', 'Email'),
(150, 'PrintParametersORDERidPrintParameters', 'ENG', '#'),
(151, 'PrintParametersORDERPrintParametersTechnology', 'ENG', 'Technology'),
(152, 'PrintParametersORDERPrintParametersMaterial', 'ENG', 'Material'),
(153, 'PrintParametersORDERPrintParametersResolution', 'ENG', 'Resolution'),
(154, 'PrintParametersORDERPrintParametersLayer', 'ENG', 'Layer'),
(155, 'PrintParametersORDERPrintParametersFinish', 'ENG', 'Finish'),
(156, 'DataSetORDERPrintParametersTechnology', 'ENG', 'Technology'),
(157, 'DataSetORDERidDataSet', 'ENG', '#'),
(158, 'DataSetORDERDataSetDate', 'ENG', 'Date'),
(159, 'DataSetORDERDataSetName', 'ENG', 'Name'),
(160, 'LabelDataSet_idStatus', 'ENG', 'Status'),
(161, 'LabelDataSet_idPrintParameters', 'ENG', 'Print_Parameters'),
(162, 'LabelDataSet_idQuote', 'ENG', 'Id_Quote'),
(163, 'LabelDataSetName', 'ENG', 'Name'),
(164, 'LabelDataSetDate', 'ENG', 'Date'),
(165, 'LabelPrintParametersTechnology', 'ENG', 'Technology'),
(166, 'LabelPrintParametersMaterial', 'ENG', 'Material'),
(167, 'LabelPrintParametersResolution', 'ENG', 'Resolution'),
(168, 'LabelPrintParametersLayer', 'ENG', 'Layer'),
(169, 'LabelPrintParametersFinish', 'ENG', 'Finish'),
(170, 'PersonORDERidPerson', 'ENG', '#'),
(171, 'CompanyORDERidCompany', 'ENG', '#'),
(172, 'CompanyORDERCompanyName', 'ENG', 'Name'),
(173, 'CompanyORDERCompanyICO', 'ENG', 'ICO'),
(174, 'CompanyORDERCompanyDIC', 'ENG', 'DIC'),
(175, 'CompanyORDERCompanyDRC', 'ENG', 'DRC'),
(176, 'CompanyORDERCompanyWebsite', 'ENG', 'www'),
(177, 'CompanyORDERIndustryName', 'ENG', 'Industry'),
(178, 'ProjectORDERProjectStartDate', 'ENG', 'Start Date'),
(179, 'StatusLogORDERStatusLogTimestamp', 'ENG', 'Timestamp'),
(180, 'StatusLogORDERidStatusLog', 'ENG', '#'),
(184, 'tabButtonQuoteStatus', 'ENG', 'Quote History'),
(185, 'tabButtonDemandStatus', 'ENG', 'Order History'),
(186, 'tabButtonInvoiceStatus', 'ENG', 'Invoice History'),
(187, 'tabButtonDataSetStatus', 'ENG', 'DataSet History'),
(188, 'tabButtonDeliveryNoteStatus', 'ENG', 'Status'),
(189, 'tabButtonPartStatus', 'ENG', 'Part History'),
(190, 'tabButtonNoteStatus', 'ENG', 'Status'),
(191, 'tabButtonStatusStatus', 'ENG', 'Status'),
(192, 'tabButtonStatusLogStatus', 'ENG', 'Status'),
(193, 'DataSetORDERQuoteQuotePrice', 'ENG', 'QuotePrice'),
(194, 'LabelPlatform_idPlatform', 'ENG', 'Platform_id'),
(195, 'LabelInvoice_idStatus', 'ENG', 'Status'),
(196, 'LabelInvoicePaymentTerm', 'ENG', 'PaymentTerm'),
(197, 'LabelInvoice_idDemand', 'ENG', 'Order'),
(198, 'LabelInvoiceIssued', 'ENG', 'Issued'),
(199, 'ProjectORDERidProject', 'ENG', '#'),
(200, 'ProjectORDERCompanyName', 'ENG', 'Company'),
(201, 'ProjectORDERProjectName', 'ENG', 'Project'),
(202, 'ProjectORDERStatusName', 'ENG', 'Status'),
(203, 'ProjectORDERProjectPriority', 'ENG', 'Priority'),
(204, 'QuoteORDERidQuote', 'ENG', '#'),
(205, 'QuoteORDERProjectName', 'ENG', 'Project'),
(206, 'QuoteORDERStatusName', 'ENG', 'Status'),
(207, 'QuoteORDERPrintParametersTechnology', 'ENG', 'Technology'),
(208, 'QuoteORDERQuotePrice', 'ENG', 'Price'),
(209, 'QuoteORDERQuoteDateSent', 'ENG', 'Date Sent'),
(210, 'QuoteORDERQuoteLeadTime', 'ENG', 'Lead Time'),
(211, 'StatusLogORDERStatusName', 'ENG', 'Status'),
(212, 'NoteORDERidNote', 'ENG', '#'),
(213, 'NoteORDERNoteText', 'ENG', 'Note'),
(214, 'NoteORDERStatusName', 'ENG', 'Status'),
(215, 'NoteORDERNoteTime', 'ENG', 'Time'),
(216, 'DataSetORDERStatusName', 'ENG', 'Status'),
(217, 'DataSetORDERQuotePrice', 'ENG', 'Price'),
(218, 'DemandORDERQuotePrice', 'ENG', 'Price'),
(219, 'InvoiceORDERidInvoice', 'ENG', '#'),
(220, 'InvoiceORDERStatusName', 'ENG', 'Status'),
(221, 'InvoiceORDERDemandName', 'ENG', 'Order'),
(222, 'InvoiceORDERInvoiceIssued', 'ENG', 'Issued'),
(223, 'InvoiceORDERInvoicePaymentTerm', 'ENG', 'Payment Term'),
(224, 'InvoiceORDERInvoiceTotal', 'ENG', 'Total'),
(225, 'InvoiceORDERInvoiceVAT', 'ENG', 'VAT'),
(226, 'InvoiceORDERInvoiceType', 'ENG', 'Type'),
(227, 'BuildORDERidBuild', 'ENG', '#'),
(228, 'BuildORDERPlatformName', 'ENG', 'Platform'),
(229, 'BuildORDERBuildRequested', 'ENG', 'Requested'),
(230, 'BuildORDERBuildPrintJobStart', 'ENG', 'Print Job Start'),
(231, 'BuildORDERStatusName', 'ENG', 'Status'),
(232, 'DeliveryNoteORDERidDeliveryNote', 'ENG', '#'),
(233, 'DeliveryNoteORDERStatusName', 'ENG', 'Status'),
(234, 'DeliveryNoteORDERInvoiceName', 'ENG', 'Invoice'),
(235, 'DeliveryNoteORDERDeliveryNoteIssued', 'ENG', 'Issued'),
(236, 'DeliveryNoteORDERDeliveryNoteSigned', 'ENG', 'Signed'),
(237, 'DeliveryNoteORDERShippingAddress', 'ENG', 'Shipping Address'),
(238, 'AccountORDERAccountName', 'ENG', 'Name'),
(239, 'AccountORDERCompanyName', 'ENG', 'Company'),
(240, 'AccountORDERAccountBank', 'ENG', 'Bank'),
(241, 'AccountORDERAccountBankCode', 'ENG', 'Bank Code'),
(242, 'AddressORDERCompanyName', 'ENG', 'Company'),
(243, 'PersonORDERCompanyName', 'ENG', 'Company'),
(244, 'PartORDERidPart', 'ENG', '#'),
(245, 'PartORDERStatusName', 'ENG', 'Status'),
(246, 'PartORDERDataSetName', 'ENG', 'Date Set'),
(247, 'PartORDERPrintParametersTechnology', 'ENG', 'Technology'),
(248, 'PartORDERPartFileName', 'ENG', 'File Name'),
(249, 'PartORDERPartQuoteFileName', 'ENG', 'Quote File Name'),
(250, 'PartORDERPartAMFileName', 'ENG', 'AM File Name'),
(251, 'PartORDERPartFinish', 'ENG', 'Finish'),
(252, 'PartORDERPartQuantity', 'ENG', 'Quantity'),
(253, 'Platform_has_PartORDERPlatformName', 'ENG', 'Name'),
(254, 'Platform_has_PartORDERPartName', 'ENG', 'Part'),
(255, 'Platform_has_PartORDERPartQuantity', 'ENG', 'Part Quantity'),
(256, 'PlatformORDERidPlatform', 'ENG', '#'),
(257, 'PlatformORDERPlatformName', 'ENG', 'Name'),
(258, 'DeliveryTransportORDERidDeliveryTransport', 'ENG', '#'),
(259, 'DeliveryTransportORDERDeliveryNoteName', 'ENG', 'Delivery Note'),
(260, 'DeliveryTransportORDERdeliveryTransportMethod', 'ENG', 'Method'),
(261, 'DeliveryTransportORDERdeliveryTransportOption', 'ENG', 'Option'),
(262, 'DeliveryTransportORDERdeliveryTransportDate', 'ENG', 'Date'),
(263, 'DeliveryTransportORDERdeliveryTransportPayment', 'ENG', 'Payment'),
(264, 'StatusORDERidStatus', 'ENG', '#'),
(265, 'StatusORDERStatusType', 'ENG', 'Type'),
(266, 'StatusORDERStatusName', 'ENG', 'Status'),
(267, 'StatusORDERStatusColor', 'ENG', 'Color'),
(268, 'tabProjectStatus', 'ENG', 'Project History'),
(269, 'tabButtonProjectNote', 'ENG', 'Project Notes'),
(270, 'tabProjectQuote', 'ENG', 'Quotes'),
(271, 'tabProjectNote', 'ENG', 'Project Notes'),
(272, 'tabButtonProjectStatus', 'ENG', 'Project History'),
(274, 'tabAdminProject', 'ENG', 'stories'),
(275, 'tabAdminQuote', 'ENG', 'Quotes'),
(276, 'tabAdminDemand', 'ENG', 'Orders'),
(277, 'tabAdminBuild', 'ENG', 'Builds'),
(278, 'tabAdminInvoice', 'ENG', 'Invoices'),
(279, 'tabAdminIndustry', 'ENG', 'Industries'),
(280, 'tabAdminCompany', 'ENG', 'Companies'),
(281, 'tabAdminPerson', 'ENG', 'Persons'),
(282, 'tabAdminPrintParameters', 'ENG', 'Print Parameters'),
(283, 'tabAdminDataSet', 'ENG', 'DataSets'),
(284, 'tabAdminAccount', 'ENG', 'Accounts'),
(285, 'tabAdminAddress', 'ENG', 'Addresses'),
(286, 'tabAdminPart', 'ENG', 'Parts'),
(287, 'tabAdminPlatform_has_Part', 'ENG', 'Platforms have Parts'),
(288, 'tabAdminPlatform', 'ENG', 'Platforms'),
(289, 'tabAdminDeliveryTransport', 'ENG', 'Delivery Transports'),
(290, 'tabAdminDeliveryNote', 'ENG', 'Delivery Notes'),
(291, 'tabAdminNote', 'ENG', 'Notes'),
(292, 'tabAdminStatus', 'ENG', 'Statuses'),
(293, 'tabAdminStatusLog', 'ENG', 'Status Log'),
(294, 'tabAdminGUI', 'ENG', 'GUI'),
(295, 'tabQuoteDataSet', 'ENG', 'DataSets'),
(296, 'tabQuoteDemand', 'ENG', 'Orders'),
(297, 'tabQuoteStatus', 'ENG', 'Quote History'),
(298, 'tabQuoteNote', 'ENG', 'Quote Notes'),
(299, 'DemandInsert', 'ENG', '+ Order'),
(300, 'tabIndustryCompany', 'ENG', 'Companies'),
(304, 'tabIndustryNote', 'ENG', 'Industry Notes'),
(305, 'tabCompanyAccount', 'ENG', 'Accounts'),
(306, 'tabCompanyAddress', 'ENG', 'Addresses'),
(308, 'tabCompanyNote', 'ENG', 'Company Notes'),
(309, 'tabCompanyProject', 'ENG', 'Projects'),
(310, 'tabCompanyPerson', 'ENG', 'Persons'),
(311, 'tabPersonNote', 'ENG', 'Person Notes'),
(312, 'tabButtonPrintParametersDataSet', 'ENG', 'DataSets'),
(313, 'tabPrintParametersDataSet', 'ENG', 'DataSets'),
(314, 'tabPrintParametersPart', 'ENG', 'Parts'),
(315, 'tabButtonPrintParametersQuote', 'ENG', 'Quotes'),
(317, 'tabPrintParametersQuote', 'ENG', 'Quotes'),
(319, 'tabPrintParametersNote', 'ENG', 'Print Parameter Notes'),
(321, 'tabDataSetPart', 'ENG', 'Parts'),
(322, 'tabDataSetStatus', 'ENG', 'DataSet History'),
(323, 'tabDataSetNote', 'ENG', 'DataSet Notes'),
(325, 'tabAccountNote', 'ENG', 'Account Notes'),
(326, 'tabAddressNote', 'ENG', 'Address Notes'),
(327, 'tabStatusDataSet', 'ENG', 'DataSets'),
(328, 'tabStatusDeliveryNote', 'ENG', 'Delivery Notes'),
(329, 'tabStatusDemand', 'ENG', 'Orders'),
(330, 'tabStatusInvoice', 'ENG', 'Invoices'),
(331, 'tabStatusNote', 'ENG', 'Notes'),
(332, 'tabStatusPart', 'ENG', 'Parts'),
(333, 'tabStatusProject', 'ENG', 'Projects'),
(334, 'tabButtonStatusQuote', 'ENG', 'Quotes'),
(335, 'tabStatusQuote', 'ENG', 'Quotes'),
(336, 'tabStatusStatusLog', 'ENG', 'Status History'),
(337, 'tabGUINote', 'ENG', 'Notes'),
(338, 'ProjectInsert', 'ENG', '+ Project'),
(339, 'QuoteInsert', 'ENG', '+ Quote'),
(340, 'BuildInsert', 'ENG', '+ Build'),
(341, 'InvoiceInsert', 'ENG', '+ Invoice'),
(342, 'IndustryInsert', 'ENG', '+ Industry'),
(343, 'CompanyInsert', 'ENG', '+ Company'),
(344, 'PersonInsert', 'ENG', '+ Person'),
(345, 'PrintParametersInsert', 'ENG', '+ Print Parameter'),
(346, 'DataSetInsert', 'ENG', '+ DataSet'),
(347, 'PlatformInsert', 'ENG', '+ Platform'),
(348, 'DeliveryTransportInsert', 'ENG', '+ Delivery Transport'),
(349, 'DeliveryNoteInsert', 'ENG', '+ Delivery Note'),
(350, 'AccountInsert', 'ENG', '+ Account'),
(351, 'AddressInsert', 'ENG', '+ Address'),
(352, 'PartInsert', 'ENG', '+ Part'),
(353, 'NoteInsert', 'ENG', '+ Note'),
(354, 'StatusLogInsert', 'ENG', '+ History'),
(355, 'GUIInsert', 'ENG', '+ GUI'),
(356, 'StatusInsert', 'ENG', '+ Status'),
(357, 'tabDemandInvoice', 'ENG', 'Invoices'),
(358, 'tabDemandStatus', 'ENG', 'Order History'),
(359, 'tabDemandNote', 'ENG', 'Notes'),
(360, 'tabPlatformBuild', 'ENG', 'Builds'),
(362, 'tabPlatformNote', 'ENG', 'Notes'),
(363, 'tabPartStatus', 'ENG', 'Part History'),
(364, 'tabPartNote', 'ENG', 'Notes'),
(367, 'tabInvoiceNote', 'ENG', 'Notes'),
(368, 'tabInvoiceStatus', 'ENG', 'Invoice History'),
(369, 'tabInvoiceDeliveryNote', 'ENG', 'Delivery Notes'),
(371, 'tabBuildNote', 'ENG', 'Notes'),
(372, 'tabButtonBuildStatus', 'ENG', 'Build History'),
(373, 'tabBuildStatus', 'ENG', 'Build History'),
(375, 'tabButtonStatusBuild', 'ENG', 'Builds'),
(377, 'tabStatusBuild', 'ENG', 'Builds'),
(378, 'BuildORDERBuildPrintJobEnd', 'ENG', 'Build Print Job End'),
(379, 'IndustryORDERIndustryName', 'ENG', 'Name'),
(382, 'DataSetFileName', 'type', 'path'),
(383, 'CompanyName', 'lookupType', 'suggest'),
(384, 'PartFileName', 'type', 'path'),
(385, 'PartQuoteFileName', 'type', 'path'),
(386, 'PartAMFileName', 'type', 'path'),
(387, 'MaterialORDERidMaterial', 'ENG', '#'),
(388, 'tabMaterialNote', 'ENG', 'Material Notes'),
(389, 'tabButtonDemandNote', 'ENG', 'Order Notes'),
(390, 'tabDemandNote', 'ENG', 'Order Notes'),
(394, 'PrintParameters', 'lookupField', 'PrintParametersTechnology'),
(399, 'ActionORDERidAction', 'ENG', '#'),
(400, 'ActionORDERActionName', 'ENG', 'Name'),
(401, 'ActionORDERActionSequence', 'ENG', 'Sequence'),
(402, 'ActionORDERActionTable', 'ENG', 'Table'),
(403, 'ActionORDERActionField', 'ENG', 'Field'),
(404, 'ActionORDERActionCommand', 'ENG', 'Command'),
(405, 'ActionORDERActionParam1', 'ENG', 'Parameter 1'),
(406, 'ActionORDERActionParam2', 'ENG', 'Parameter 2'),
(407, 'InvoiceORDERInvoiceDue', 'ENG', 'Due'),
(409, 'tabAdminPayment', 'ENG', 'Payments'),
(410, 'tabButtonAdminPayment', 'ENG', 'Payments'),
(412, 'tabButtonProjectRelationLeft', 'ENG', '--->'),
(413, 'tabButtonQuoteRelationLeft', 'ENG', '--->'),
(418, 'tabProjectRelationLeft', 'ENG', '--->'),
(419, 'RelationORDERidRelation', 'ENG', '#'),
(420, 'RelationORDERRelationRightTable', 'ENG', 'Connect to Table'),
(421, 'RelationORDERRelationRightId', 'ENG', 'Connect to Record'),
(422, 'RelationORDERRelationLeftTable', 'ENG', 'Connected Table'),
(423, 'RelationORDERRelationLeftId', 'ENG', 'Connected Record'),
(424, 'tabButtonProjectRelationRight', 'ENG', '<---'),
(425, 'tabButtonQuoteRelationRight', 'ENG', '<---'),
(430, 'tabDemandRelationRight', 'ENG', '<---'),
(431, 'tabBuildRelationRight', 'ENG', '<---'),
(432, 'tabInvoiceRelationRight', 'ENG', '<---'),
(433, 'tabProjectRelationRight', 'ENG', '<---'),
(434, 'tabQuoteRelationLeft', 'ENG', '--->'),
(435, 'tabQuoteRelationRight', 'ENG', '<---'),
(436, 'tabDemandRelationLeft', 'ENG', '--->'),
(437, 'tabButtonDemandRelationRight', 'ENG', '<---'),
(438, 'tabButtonDemandRelationLeft', 'ENG', '--->'),
(439, 'tabBuildRelationLeft', 'ENG', '--->'),
(440, 'tabButtonBuildRelationRight', 'ENG', '<---'),
(441, 'tabButtonBuildRelationLeft', 'ENG', '--->'),
(443, 'ProjectName', 'lookupType', 'suggest'),
(444, 'RelationLeft', 'ENG', '--->'),
(445, 'RelationRight', 'ENG', '<---'),
(446, 'tabButtonDataSetRelationLeft', 'ENG', '--->'),
(447, 'tabButtonDataSetRelationRight', 'ENG', '<---'),
(448, 'tabInvoiceRelationLeft', 'ENG', '--->'),
(449, 'tabButtonInvoiceRelationLeft', 'ENG', '--->'),
(450, 'tabInvoiceRelationRight', 'ENG', '--->'),
(451, 'tabButtonInvoiceRelationRight', 'ENG', '<---'),
(452, 'DemandORDERDemandName', 'ENG', 'Order Name'),
(453, 'tabPaymentRelationLeft', 'ENG', '--->'),
(454, 'tabPaymentRelationRight', 'ENG', '<---'),
(455, 'tabButtonPaymentRelationRight', 'ENG', '<---'),
(456, 'tabButtonPaymentRelationLeft', 'ENG', '--->'),
(457, 'PaymentORDERidPayment', 'ENG', '#'),
(458, 'RelationORDERRelationRObject', 'ENG', 'Connected to Object Type'),
(459, 'RelationORDERRelationRId', 'ENG', 'Connected to Object Name'),
(460, 'RelationORDERRelationLObject', 'ENG', 'Connected Object Type'),
(461, 'RelationORDERRelationLId', 'ENG', 'Connected Object Name'),
(462, 'DeliveryNoteORDERDeliveryNoteName', 'ENG', 'Name'),
(463, 'tabButtonDeliveryNoteRelationLeft', 'ENG', '--->'),
(464, 'tabDeliveryNoteRelationLeft', 'ENG', '--->'),
(465, 'tabButtonDeliveryNoteRelationRight', 'ENG', '<---'),
(466, 'tabDeliveryNoteRelationRight', 'ENG', '<---'),
(467, 'tabDeliveryTransportRelationLeft', 'ENG', '--->'),
(468, 'tabDeliveryTransportRelationRight', 'ENG', '<---'),
(469, 'tabButtonDeliveryTransportRelationLeft', 'ENG', '--->'),
(470, 'tabButtonDeliveryTransportRelationRight', 'ENG', '<---'),
(471, 'TaskORDERidTask', 'ENG', '#'),
(472, 'TaskORDERTaskName', 'ENG', 'Name'),
(473, 'TaskORDERTaskSequence', 'ENG', 'Sequence'),
(474, 'TaskORDERTaskDuration', 'ENG', 'Duration'),
(475, 'TaskORDERparentTaskName', 'ENG', 'Parent Task'),
(476, 'JobORDERidJob', 'ENG', '#'),
(477, 'JobORDERJobName', 'ENG', 'Description'),
(478, 'JobORDERJobCreated', 'ENG', 'Created'),
(479, 'JobORDERJobStarted', 'ENG', 'Started'),
(480, 'JobORDERJobFinished', 'ENG', 'Finished'),
(481, 'JobORDERJobDeadline', 'ENG', 'Deadline'),
(482, 'JobORDERStatusName', 'ENG', 'Status'),
(483, 'JobORDERTaskName', 'ENG', 'Task'),
(484, 'tabButtonJobRelationLeft', 'ENG', '--->'),
(485, 'tabJobRelationLeft', 'ENG', '--->'),
(486, 'tabButtonJobRelationRight', 'ENG', '<---'),
(487, 'tabJobRelationRight', 'ENG', '<---'),
(488, 'tabButtonJobNote', 'ENG', 'Notes'),
(489, 'tabJobStatus', 'ENG', 'History'),
(490, 'tabButtonJobStatus', 'ENG', 'History'),
(491, 'tabJobNote', 'ENG', 'Notes'),
(492, 'StatusLogORDERStatusType', 'ENG', 'Status Type'),
(493, 'tabGUIRelationLeft', 'ENG', '--->'),
(494, 'tabButtonGUIRelationLeft', 'ENG', '--->'),
(495, 'tabGUIRelationRight', 'ENG', '<---'),
(496, 'tabButtonGUIRelationRight', 'ENG', '<---'),
(497, 'tabButtonAdminAction', 'ENG', 'Automation'),
(498, 'tabButtonAdminRelation', 'ENG', 'Relations'),
(499, 'tabButtonAdminJob', 'ENG', 'Jobs'),
(500, 'tabButtonAdminTask', 'ENG', 'Tasks'),
(501, 'tabButtonAdminMaterial', 'ENG', 'Materials'),
(502, 'tabButtonTaskJob', 'ENG', 'Jobs'),
(503, 'tabTaskJob', 'ENG', 'Jobs'),
(504, 'tabTaskTask', 'ENG', 'Tasks'),
(505, 'tabButtonTaskTask', 'ENG', 'Tasks'),
(506, 'tabTaskRelationLeft', 'ENG', '--->'),
(507, 'tabButtonTaskRelationLeft', 'ENG', '--->'),
(508, 'tabTaskRelationRight', 'ENG', '<---'),
(509, 'tabButtonTaskRelationRight', 'ENG', '<---'),
(510, 'tabButtonTaskNote', 'ENG', 'Notes'),
(511, 'tabTaskNote', 'ENG', 'Notes'),
(512, 'tabAdminJob', 'ENG', 'Jobs'),
(513, 'tabAdminTask', 'ENG', 'Tasks'),
(514, 'tabAdminAction', 'ENG', 'Automation'),
(515, 'tabAdminRelation', 'ENG', 'Relations'),
(516, 'JobORDERJobPriority', 'ENG', 'Priority');

-- --------------------------------------------------------

--
-- Table structure for table `History`
--

CREATE TABLE `History` (
  `idHistory` int(11) NOT NULL,
  `HistoryTable` varchar(30) COLLATE utf8_slovak_ci DEFAULT NULL,
  `HistoryRowId` int(11) DEFAULT NULL,
  `HistoryCommand` varchar(20) COLLATE utf8_slovak_ci DEFAULT NULL,
  `HistorySQL` varchar(500) COLLATE utf8_slovak_ci DEFAULT NULL,
  `HistoryTimestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Dumping data for table `History`
--

INSERT INTO `History` (`idHistory`, `HistoryTable`, `HistoryRowId`, `HistoryCommand`, `HistorySQL`, `HistoryTimestamp`) VALUES
(1, 'Status', 1, 'CREATE', 'Status|CREATE', '2016-07-19 13:51:05'),
(2, 'Status', 2, 'CREATE', 'Status|CREATE', '2016-07-19 13:51:35'),
(3, 'Status', 1, 'UPDATE', 'Status|UPDATE', '2016-07-19 13:51:54'),
(4, 'Status', 3, 'CREATE', 'Status|CREATE', '2016-07-19 13:52:14'),
(5, 'Status', 4, 'CREATE', 'Status|CREATE', '2016-07-19 13:54:43'),
(6, 'Status', 5, 'CREATE', 'Status|CREATE', '2016-07-19 13:55:58'),
(7, 'Status', 6, 'CREATE', 'Status|CREATE', '2016-07-19 13:56:49'),
(8, 'Status', 7, 'CREATE', 'Status|CREATE', '2016-07-19 13:58:11'),
(9, 'Status', 8, 'CREATE', 'Status|CREATE', '2016-07-19 13:59:48'),
(10, 'Status', 8, 'UPDATE', 'Status|UPDATE', '2016-07-19 14:01:38'),
(11, 'Status', 9, 'CREATE', 'Status|CREATE', '2016-07-19 14:02:42'),
(12, 'Status', 10, 'CREATE', 'Status|CREATE', '2016-07-19 14:03:27'),
(13, 'Status', 11, 'CREATE', 'Status|CREATE', '2016-07-19 14:05:48'),
(14, 'Status', 12, 'CREATE', 'Status|CREATE', '2016-07-19 14:06:14'),
(15, 'Status', 12, 'UPDATE', 'Status|UPDATE', '2016-07-19 14:07:05'),
(16, 'Status', 13, 'CREATE', 'Status|CREATE', '2016-07-19 14:07:37'),
(17, 'Status', 13, 'UPDATE', 'Status|UPDATE', '2016-07-19 14:08:32'),
(18, 'Status', 13, 'UPDATE', 'Status|UPDATE', '2016-07-19 14:09:18'),
(19, 'Status', 14, 'CREATE', 'Status|CREATE', '2016-07-19 14:10:28'),
(20, 'Status', 14, 'UPDATE', 'Status|UPDATE', '2016-07-19 15:03:02'),
(21, 'Status', 15, 'CREATE', 'Status|CREATE', '2016-07-19 15:05:19'),
(22, 'Status', 14, 'UPDATE', 'Status|UPDATE', '2016-07-19 15:05:51'),
(23, 'Status', 16, 'CREATE', 'Status|CREATE', '2016-07-19 15:06:49'),
(24, 'Status', 17, 'CREATE', 'Status|CREATE', '2016-07-19 15:07:13'),
(25, 'Status', 18, 'CREATE', 'Status|CREATE', '2016-07-19 15:07:53'),
(26, 'Status', 17, 'UPDATE', 'Status|UPDATE', '2016-07-19 15:08:06'),
(27, 'Status', 19, 'CREATE', 'Status|CREATE', '2016-07-19 15:08:57'),
(28, 'Status', 20, 'CREATE', 'Status|CREATE', '2016-07-19 15:09:27'),
(29, 'Status', 21, 'CREATE', 'Status|CREATE', '2016-07-19 15:09:49'),
(30, 'Status', 21, 'UPDATE', 'Status|UPDATE', '2016-07-19 15:09:59'),
(31, 'Status', 21, 'UPDATE', 'Status|UPDATE', '2016-07-19 15:10:16'),
(32, 'Status', 22, 'CREATE', 'Status|CREATE', '2016-07-19 15:11:28'),
(33, 'Status', 23, 'CREATE', 'Status|CREATE', '2016-07-19 15:15:48'),
(34, 'Status', 24, 'CREATE', 'Status|CREATE', '2016-07-19 15:17:20'),
(35, 'Status', 25, 'CREATE', 'Status|CREATE', '2016-07-19 15:17:55'),
(36, 'Status', 26, 'CREATE', 'Status|CREATE', '2016-07-19 15:20:41'),
(37, 'Status', 27, 'CREATE', 'Status|CREATE', '2016-07-19 15:21:03'),
(38, 'Status', 28, 'CREATE', 'Status|CREATE', '2016-07-19 15:21:36'),
(39, 'Status', 27, 'UPDATE', 'Status|UPDATE', '2016-07-19 15:21:47'),
(40, 'Status', 29, 'CREATE', 'Status|CREATE', '2016-07-19 15:22:21'),
(41, 'Status', 28, 'UPDATE', 'Status|UPDATE', '2016-07-19 15:23:04'),
(42, 'Status', 27, 'UPDATE', 'Status|UPDATE', '2016-07-19 15:23:28'),
(43, 'Status', 26, 'UPDATE', 'Status|UPDATE', '2016-07-19 15:23:43'),
(44, 'Status', 29, 'UPDATE', 'Status|UPDATE', '2016-07-19 15:24:58'),
(45, 'Status', 30, 'CREATE', 'Status|CREATE', '2016-07-19 15:26:34'),
(46, 'Status', 31, 'CREATE', 'Status|CREATE', '2016-07-19 15:27:25'),
(47, 'Status', 32, 'CREATE', 'Status|CREATE', '2016-07-19 15:31:09'),
(48, 'Status', 33, 'CREATE', 'Status|CREATE', '2016-07-19 15:31:32'),
(49, 'Status', 34, 'CREATE', 'Status|CREATE', '2016-07-19 15:31:57'),
(50, 'Status', 33, 'UPDATE', 'Status|UPDATE', '2016-07-19 15:32:06'),
(51, 'Status', 32, 'UPDATE', 'Status|UPDATE', '2016-07-19 15:32:15'),
(52, 'Status', 13, 'UPDATE', 'Status|UPDATE', '2016-07-19 17:05:40'),
(53, 'Status', 30, 'UPDATE', 'Status|UPDATE', '2016-07-20 06:03:43'),
(54, 'Status', 26, 'UPDATE', 'Status|UPDATE', '2016-07-20 06:35:17'),
(55, 'Status', 27, 'UPDATE', 'Status|UPDATE', '2016-07-20 06:35:31'),
(56, 'Status', 27, 'UPDATE', 'Status|UPDATE', '2016-07-20 06:35:46'),
(57, 'Status', 13, 'UPDATE', 'Status|UPDATE', '2016-08-04 15:37:56'),
(58, 'Status', 35, 'CREATE', 'Status|CREATE', '2016-08-23 08:31:46'),
(59, 'Status', 36, 'CREATE', 'Status|CREATE', '2016-08-23 08:32:20'),
(60, 'Status', 37, 'CREATE', 'Status|CREATE', '2016-08-23 08:33:06'),
(61, 'Status', 38, 'CREATE', 'Status|CREATE', '2016-08-23 08:33:47'),
(62, 'Status', 39, 'CREATE', 'Status|CREATE', '2016-08-23 08:34:21'),
(63, 'Status', 40, 'CREATE', 'Status|CREATE', '2016-08-23 08:34:44'),
(64, 'Status', 41, 'CREATE', 'Status|CREATE', '2016-08-23 08:35:03'),
(65, 'Status', 42, 'CREATE', 'Status|CREATE', '2016-08-23 08:35:31'),
(66, 'Status', 10, 'UPDATE', 'Status|UPDATE', '2016-08-23 09:20:48'),
(67, 'Status', 42, 'UPDATE', 'Status|UPDATE', '2016-08-23 10:06:43'),
(68, 'Status', 41, 'UPDATE', 'Status|UPDATE', '2016-08-23 10:06:54'),
(69, 'Status', 40, 'UPDATE', 'Status|UPDATE', '2016-08-23 10:07:01'),
(70, 'Relation', 27, 'CREATE', 'Relation|CREATE', '2016-08-23 10:16:13'),
(71, 'Relation', 28, 'CREATE', 'Relation|CREATE', '2016-08-23 10:16:29'),
(72, 'Relation', 29, 'CREATE', 'Relation|CREATE', '2016-08-23 10:17:21'),
(73, 'Task', 2, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:19:59'),
(74, 'Task', 3, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:20:08'),
(75, 'Task', 4, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:20:16'),
(76, 'Task', 6, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:20:22'),
(77, 'Task', 7, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:20:28'),
(78, 'Task', 8, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:20:37'),
(79, 'Task', 8, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:20:44'),
(80, 'Task', 10, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:20:54'),
(81, 'Task', 11, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:21:02'),
(82, 'Task', 12, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:21:10'),
(83, 'Task', 13, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:21:33'),
(84, 'Task', 14, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:21:41'),
(85, 'Task', 15, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:21:50'),
(86, 'Task', 16, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:21:59'),
(87, 'Task', 17, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:22:06'),
(88, 'Task', 18, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:22:14'),
(89, 'Task', 19, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:22:49'),
(90, 'Task', 20, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:22:56'),
(91, 'Task', 21, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:23:09'),
(92, 'Task', 22, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:23:19'),
(93, 'Task', 23, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:23:32'),
(94, 'Task', 24, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:23:40'),
(95, 'Task', 25, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:23:50'),
(96, 'Task', 26, 'UPDATE', 'Task|UPDATE', '2016-08-23 10:24:01'),
(97, 'Relation', 20, 'UPDATE', 'Relation|UPDATE', '2016-08-23 10:31:53'),
(98, 'Relation', 20, 'UPDATE', 'Relation|UPDATE', '2016-08-23 10:32:13'),
(99, 'Relation', 20, 'UPDATE', 'Relation|UPDATE', '2016-08-23 10:32:25'),
(100, 'Relation', 20, 'UPDATE', 'Relation|UPDATE', '2016-08-23 10:34:42'),
(101, 'Relation', 20, 'UPDATE', 'Relation|UPDATE', '2016-08-23 11:07:06'),
(102, 'Relation', 53, 'CREATE', 'Relation|CREATE', '2016-08-23 11:07:53'),
(103, 'Relation', 54, 'CREATE', 'Relation|CREATE', '2016-08-23 11:08:18'),
(104, 'Industry', 1, 'CREATE', 'Industry|CREATE', '2016-08-23 11:26:26'),
(105, 'Industry', 2, 'CREATE', 'Industry|CREATE', '2016-08-23 11:26:37'),
(106, 'Industry', 3, 'CREATE', 'Industry|CREATE', '2016-08-23 11:26:47'),
(107, 'Industry', 4, 'CREATE', 'Industry|CREATE', '2016-08-23 11:26:58'),
(108, 'Industry', 5, 'CREATE', 'Industry|CREATE', '2016-08-23 11:27:08'),
(109, 'Industry', 6, 'CREATE', 'Industry|CREATE', '2016-08-23 11:27:18');

-- --------------------------------------------------------

--
-- Table structure for table `Industry`
--

CREATE TABLE `Industry` (
  `idIndustry` int(11) NOT NULL,
  `IndustryName` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `IndustryShortcut` varchar(5) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Dumping data for table `Industry`
--

INSERT INTO `Industry` (`idIndustry`, `IndustryName`, `IndustryShortcut`) VALUES
(1, 'Architecture', 'ARC'),
(2, 'Design', 'DSG'),
(3, 'Jewelry', 'JWL'),
(4, 'Artistic', 'ART'),
(5, 'Automobile', 'AUT'),
(6, '3D Print', '3DP');

-- --------------------------------------------------------

--
-- Table structure for table `Invoice`
--

CREATE TABLE `Invoice` (
  `idInvoice` int(11) NOT NULL,
  `InvoiceName` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
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
-- Table structure for table `Job`
--

CREATE TABLE `Job` (
  `idJob` int(11) NOT NULL,
  `JobName` varchar(45) DEFAULT NULL,
  `JobCreated` datetime DEFAULT CURRENT_TIMESTAMP,
  `JobStarted` datetime DEFAULT NULL,
  `JobFinished` datetime DEFAULT NULL,
  `JobDeadline` datetime DEFAULT NULL,
  `JobPriority` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Material`
--

CREATE TABLE `Material` (
  `idMaterial` int(11) NOT NULL,
  `MaterialName` varchar(45) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `Note`
--

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

CREATE TABLE `Payment` (
  `idPayment` int(11) NOT NULL,
  `PaymentName` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
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

CREATE TABLE `Platform` (
  `idPlatform` int(11) NOT NULL,
  `PlatformName` varchar(45) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PrintParameters`
--

CREATE TABLE `PrintParameters` (
  `idPrintParameters` int(11) NOT NULL,
  `PrintParametersName` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `PrintParametersTechnology` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `PrintParametersResolution` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `PrintParametersLayer` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `PrintParametersFinish` varchar(40) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Dumping data for table `PrintParameters`
--

INSERT INTO `PrintParameters` (`idPrintParameters`, `PrintParametersName`, `PrintParametersTechnology`, `PrintParametersResolution`, `PrintParametersLayer`, `PrintParametersFinish`) VALUES
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

-- --------------------------------------------------------

--
-- Table structure for table `Project`
--

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

INSERT INTO `Relation` (`idRelation`, `RelationType`, `RelationLObject`, `RelationLId`, `RelationRObject`, `RelationRId`) VALUES
(1, 'TTCP', 'Invoice', 0, 'Demand', 0),
(2, 'TTCP', 'Project', 0, 'Status', 0),
(3, 'TTCP', 'Quote', 0, 'Status', 0),
(4, 'TTCP', 'Demand', 0, 'Status', 0),
(5, 'TTCP', 'Invoice', 0, 'Status', 0),
(6, 'TTCP', 'DeliveryNote', 0, 'Status', 0),
(7, 'TTCP', 'Build', 0, 'Status', 0),
(8, 'TTCP', 'Part', 0, 'Status', 0),
(9, 'TTCP', 'DataSet', 0, 'Status', 0),
(10, 'TTCP', 'Build', 0, 'Platform', 0),
(11, 'TTCP', 'DeliveryTransport', 0, 'DeliveryNote', 0),
(12, 'TTCP', 'DeliveryNote', 0, 'Invoice', 0),
(13, 'TTCP', 'Payment', 0, 'Invoice', 0),
(14, 'TTCP', 'Quote', 0, 'Project', 0),
(15, 'TTCP', 'Demand', 0, 'Quote', 0),
(16, 'TTCP', 'Part', 0, 'PrintParameters', 0),
(17, 'TTCP', 'Part', 0, 'DataSet', 0),
(18, 'TTCP', 'DataSet', 0, 'PrintParameters', 0),
(19, 'TTCP', 'DataSet', 0, 'Quote', 0),
(20, 'TTCP', 'Quote', 0, 'Material', 0),
(21, 'TTCP', 'Quote', 0, 'PrintParameters', 0),
(22, 'TTCP', 'Account', 0, 'Company', 0),
(23, 'TTCP', 'Address', 0, 'Company', 0),
(24, 'TTCP', 'Person', 0, 'Company', 0),
(25, 'TTCP', 'Company', 0, 'Industry', 0),
(26, 'TTCP', 'Project', 0, 'Company', 0),
(27, 'RRCP', 'Job', 0, 'Status', 0),
(28, 'RRCP', 'Job', 0, 'Task', 0),
(29, 'RRCP', 'Task', 0, 'Task', 0),
(30, 'RRCP', 'Task', 2, 'Task', 1),
(31, 'RRCP', 'Task', 3, 'Task', 1),
(32, 'RRCP', 'Task', 4, 'Task', 1),
(33, 'RRCP', 'Task', 6, 'Task', 5),
(34, 'RRCP', 'Task', 7, 'Task', 5),
(35, 'RRCP', 'Task', 8, 'Task', 5),
(36, 'RRCP', 'Task', 10, 'Task', 9),
(37, 'RRCP', 'Task', 11, 'Task', 10),
(38, 'RRCP', 'Task', 12, 'Task', 10),
(39, 'RRCP', 'Task', 13, 'Task', 10),
(40, 'RRCP', 'Task', 14, 'Task', 10),
(41, 'RRCP', 'Task', 15, 'Task', 10),
(42, 'RRCP', 'Task', 16, 'Task', 9),
(43, 'RRCP', 'Task', 17, 'Task', 16),
(44, 'RRCP', 'Task', 18, 'Task', 16),
(45, 'RRCP', 'Task', 19, 'Task', 17),
(46, 'RRCP', 'Task', 20, 'Task', 9),
(47, 'RRCP', 'Task', 21, 'Task', 20),
(48, 'RRCP', 'Task', 22, 'Task', 20),
(49, 'RRCP', 'Task', 23, 'Task', 9),
(50, 'RRCP', 'Task', 24, 'Task', 23),
(51, 'RRCP', 'Task', 25, 'Task', 23),
(52, 'RRCP', 'Task', 26, 'Task', 23),
(53, 'TTCP', 'DataSet', 0, 'Material', 0),
(54, 'TTCP', 'Part', 0, 'Material', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Status`
--

CREATE TABLE `Status` (
  `idStatus` int(11) NOT NULL,
  `StatusType` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `StatusName` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `StatusColor` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `StatusFlags` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Dumping data for table `Status`
--

INSERT INTO `Status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`, `StatusFlags`) VALUES
(1, 'Project', 'PreQuoting', '2C9DAD', NULL),
(2, 'Project', 'Quoting', '39CADE', NULL),
(3, 'Project', 'Order Processing', '41E7FF', NULL),
(4, 'Quote', 'Preparing', 'BC6C25', NULL),
(5, 'Quote', '3D Data OK', 'DB7225', NULL),
(6, 'Quote', 'Ready', 'EA8724', NULL),
(7, 'Quote', 'Sent', 'F38C25', NULL),
(8, 'Quote', 'Rejected', 'F39A17', NULL),
(9, 'Quote', 'Partial', 'F6AC14', NULL),
(10, 'Quote', 'Accepted', 'FFD530', NULL),
(11, 'Demand', 'AM Data Preparation', 'C02C50', NULL),
(12, 'Demand', 'AM Production', 'DD374B', NULL),
(13, 'Demand', 'AM Quality Check', 'EE5058', NULL),
(14, 'Demand', 'PostProcess', 'FA6565', NULL),
(15, 'Demand', 'Shipping', 'FA896D', NULL),
(16, 'DataSet', 'Build Preparation', '7A7A7A', NULL),
(17, 'DataSet', 'Support Generation', '898989', NULL),
(18, 'DataSet', 'Sent to Print Queue', 'A9A9A9', NULL),
(19, 'DataSet', 'Printing', 'B4B4B4', NULL),
(20, 'DataSet', 'Printed Ok', 'C1C1C1', NULL),
(21, 'DataSet', 'Printed with Error(s) ', 'A4A4A4', NULL),
(22, 'DataSet', 'AM Quatlity Check Ok', 'CACACA', NULL),
(23, 'DataSet', 'AM Quatlity Check Failed', 'A3A3A3', NULL),
(24, 'DataSet', 'PP Quatlity Check Ok', 'CCCCCC', NULL),
(25, 'DataSet', 'PP Quatlity Check Failed', 'A4A4A4', NULL),
(26, 'DeliveryNote', 'Preparing', '663A90', NULL),
(27, 'DeliveryNote', 'Shipping Ordered', '8149B5', NULL),
(28, 'DeliveryNote', 'Dispatched', 'A25CE4', NULL),
(29, 'DeliveryNote', 'Delivered', 'A489FC', NULL),
(30, 'Invoice', 'Preparing', '498C4A', NULL),
(31, 'Invoice', 'Ready', '5CB05D', NULL),
(32, 'Invoice', 'Partially Paid', '6BCD6D', NULL),
(33, 'Invoice', 'Paid', '77E479', NULL),
(34, 'Invoice', 'Cash', '77E479', NULL),
(35, 'Job', 'New', 'AAAF4C', NULL),
(36, 'Job', 'Assigned', 'B4B950', NULL),
(37, 'Job', 'Accepted', 'BFC555', NULL),
(38, 'Job', 'In Progress', 'CED45C', NULL),
(39, 'Job', 'Paused', 'C1C756', NULL),
(40, 'Job', 'Canceled', 'A2A648', 1),
(41, 'Job', 'Failed', '9A9D44', 1),
(42, 'Job', 'Done', 'ECF369', 3);

-- --------------------------------------------------------

--
-- Table structure for table `StatusLog`
--

CREATE TABLE `StatusLog` (
  `idStatusLog` int(11) NOT NULL,
  `StatusLogRowId` int(11) NOT NULL,
  `StatusLog_idStatus` int(11) NOT NULL,
  `StatusLogTimestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Task`
--

CREATE TABLE `Task` (
  `idTask` int(11) NOT NULL,
  `TaskName` varchar(45) DEFAULT NULL,
  `TaskSequence` decimal(10,0) DEFAULT NULL,
  `TaskDuration` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Indexes for table `Job`
--
ALTER TABLE `Job`
  ADD PRIMARY KEY (`idJob`);

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
-- Indexes for table `Task`
--
ALTER TABLE `Task`
  ADD PRIMARY KEY (`idTask`);

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
  MODIFY `idGUI` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=517;
--
-- AUTO_INCREMENT for table `History`
--
ALTER TABLE `History`
  MODIFY `idHistory` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;
--
-- AUTO_INCREMENT for table `Industry`
--
ALTER TABLE `Industry`
  MODIFY `idIndustry` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `Invoice`
--
ALTER TABLE `Invoice`
  MODIFY `idInvoice` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Job`
--
ALTER TABLE `Job`
  MODIFY `idJob` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Material`
--
ALTER TABLE `Material`
  MODIFY `idMaterial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
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
  MODIFY `idPrintParameters` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
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
  MODIFY `idRelation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT for table `Status`
--
ALTER TABLE `Status`
  MODIFY `idStatus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `StatusLog`
--
ALTER TABLE `StatusLog`
  MODIFY `idStatusLog` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Task`
--
ALTER TABLE `Task`
  MODIFY `idTask` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
