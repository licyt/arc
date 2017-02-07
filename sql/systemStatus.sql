SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
INSERT INTO `status` (`idStatus`, `StatusType`, `StatusName`, `StatusColor`, `StatusFlags`, `StatusSequence`) VALUES
(35, 'Job', 'New', 'AAAF4C', NULL, NULL),
(36, 'Job', 'Assigned', 'B4B950', NULL, NULL),
(37, 'Job', 'Accepted', 'BFC555', NULL, NULL),
(38, 'Job', 'In Progress', 'CED45C', NULL, NULL),
(39, 'Job', 'Paused', 'C1C756', NULL, NULL),
(40, 'Job', 'Canceled', 'A2A648', 1, NULL),
(41, 'Job', 'Failed', '9A9D44', 1, NULL),
(42, 'Job', 'Done', 'ECF369', 3, NULL);
