SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `action` (
  `idAction` int(11) NOT NULL,
  `ActionName` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL,
  `ActionSequence` int(2) DEFAULT '0',
  `ActionTable` varchar(30) COLLATE utf8_slovak_ci DEFAULT NULL,
  `ActionField` varchar(30) COLLATE utf8_slovak_ci DEFAULT NULL,
  `ActionCommand` varchar(20) COLLATE utf8_slovak_ci DEFAULT NULL,
  `ActionParam1` varchar(45) COLLATE utf8_slovak_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

CREATE TABLE `gui` (
  `idGUI` int(11) NOT NULL,
  `GUIelement` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `GUIattribute` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `GUIvalue` varchar(500) COLLATE utf8_slovak_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

CREATE TABLE `history` (
  `idHistory` int(11) NOT NULL,
  `HistoryTable` varchar(30) COLLATE utf8_slovak_ci DEFAULT NULL,
  `HistoryRowId` int(11) DEFAULT NULL,
  `HistoryCommand` varchar(20) COLLATE utf8_slovak_ci DEFAULT NULL,
  `HistorySQL` varchar(500) COLLATE utf8_slovak_ci DEFAULT NULL,
  `HistoryTimestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

CREATE TABLE `job` (
  `idJob` int(11) NOT NULL,
  `JobName` varchar(45) DEFAULT NULL,
  `JobCreated` datetime DEFAULT CURRENT_TIMESTAMP,
  `JobStarted` datetime DEFAULT NULL,
  `JobFinished` datetime DEFAULT NULL,
  `JobDeadline` datetime DEFAULT NULL,
  `JobPriority` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `note` (
  `idNote` int(11) NOT NULL,
  `NoteText` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `NoteTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `NoteTable` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `NoteRowId` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

CREATE TABLE `relation` (
  `idRelation` int(11) NOT NULL,
  `RelationType` varchar(20) COLLATE utf8_slovak_ci DEFAULT NULL,
  `RelationLObject` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `RelationLId` int(11) DEFAULT NULL,
  `RelationRObject` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `RelationRId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

CREATE TABLE `status` (
  `idStatus` int(11) NOT NULL,
  `StatusType` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `StatusName` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `StatusColor` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `StatusFlags` int(11) DEFAULT NULL,
  `StatusSequence` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

CREATE TABLE `statuslog` (
  `idStatusLog` int(11) NOT NULL,
  `StatusLogRowId` int(11) NOT NULL,
  `StatusLog_idStatus` int(11) NOT NULL,
  `StatusLogTimestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

CREATE TABLE `task` (
  `idTask` int(11) NOT NULL,
  `TaskName` varchar(45) DEFAULT NULL,
  `TaskSequence` decimal(10,0) DEFAULT NULL,
  `TaskDuration` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `action`
  ADD PRIMARY KEY (`idAction`);

ALTER TABLE `gui`
  ADD PRIMARY KEY (`idGUI`);

ALTER TABLE `history`
  ADD PRIMARY KEY (`idHistory`);

ALTER TABLE `job`
  ADD PRIMARY KEY (`idJob`);

ALTER TABLE `note`
  ADD PRIMARY KEY (`idNote`);

ALTER TABLE `relation`
  ADD PRIMARY KEY (`idRelation`),
  ADD UNIQUE KEY `index2` (`RelationLObject`,`RelationLId`,`RelationRObject`,`RelationRId`);

ALTER TABLE `status`
  ADD PRIMARY KEY (`idStatus`);

ALTER TABLE `statuslog`
  ADD PRIMARY KEY (`idStatusLog`,`StatusLogRowId`,`StatusLog_idStatus`);

ALTER TABLE `task`
  ADD PRIMARY KEY (`idTask`);

ALTER TABLE `action`
  MODIFY `idAction` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `gui`
  MODIFY `idGUI` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `history`
  MODIFY `idHistory` int(11) NOT NULL AUTO_INCREMENT;
 
ALTER TABLE `job`
  MODIFY `idJob` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `note`
  MODIFY `idNote` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `relation`
  MODIFY `idRelation` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `status`
  MODIFY `idStatus` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `statuslog`
  MODIFY `idStatusLog` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `task`
  MODIFY `idTask` int(11) NOT NULL AUTO_INCREMENT;
