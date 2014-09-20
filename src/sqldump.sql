-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `AnswerGroup`;
CREATE TABLE `AnswerGroup` (
  `AnswerID` int(11) NOT NULL AUTO_INCREMENT,
  `QuestionaireID` int(11) NOT NULL,
  PRIMARY KEY (`AnswerID`,`QuestionaireID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Answers`;
CREATE TABLE `Answers` (
  `AnswerID` int(11) NOT NULL,
  `QuestionID` int(11) NOT NULL,
  `ModuleID` varchar(10) NOT NULL,
  `StaffID` varchar(6) NOT NULL DEFAULT '',
  `NumValue` int(11) DEFAULT NULL,
  `TextValue` text,
  PRIMARY KEY (`AnswerID`,`QuestionID`,`ModuleID`,`StaffID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Config`;
CREATE TABLE `Config` (
  `key` varchar(10) NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `Config` (`key`, `value`) VALUES
('version',	'5');

DROP TABLE IF EXISTS `Departments`;
CREATE TABLE `Departments` (
  `DepartmentCode` char(1) NOT NULL,
  `DepartmentName` varchar(30) NOT NULL,
  `enabled` bit(1) NOT NULL DEFAULT b'1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `Departments` (`DepartmentCode`, `DepartmentName`, `enabled`) VALUES
('F',	'ART',	CONV('1', 2, 10) + 0),
('I',	'BIOLOGICAL SCIENCES',	CONV('1', 2, 10) + 0),
('N',	'COMPUTER SCIENCE',	CONV('1', 2, 10) + 0),
('Z',	'CONTINUING EDUCATION',	CONV('1', 2, 10) + 0),
('U',	'DIS',	CONV('1', 2, 10) + 0),
('B',	'EDUCATION',	CONV('1', 2, 10) + 0),
('C',	'ENGLISH',	CONV('1', 2, 10) + 0),
('D',	'EUROPEAN LANGUAGES',	CONV('1', 2, 10) + 0),
('X',	'EXTERNAL',	CONV('1', 2, 10) + 0),
('P',	'GEOGRAPHY AND EARTH SCIENCES',	CONV('1', 2, 10) + 0),
('E',	'HISTORY AND WELSH HISTORY',	CONV('1', 2, 10) + 0),
('I',	'IBERS',	CONV('1', 2, 10) + 0),
('K',	'INTERNATIONAL POLITICS',	CONV('1', 2, 10) + 0),
('V',	'LANGUAGE AND LEARNING',	CONV('1', 2, 10) + 0),
('L',	'LAW',	CONV('1', 2, 10) + 0),
('Y',	'MANAGEMENT AND BUSINESS',	CONV('1', 2, 10) + 0),
('M',	'MATHEMATICS',	CONV('1', 2, 10) + 0),
('T',	'MATHEMATICS AND PHYSICS',	CONV('1', 2, 10) + 0),
('W',	'PSYCHOLOGY',	CONV('1', 2, 10) + 0),
('I',	'RURAL SCIENCES',	CONV('1', 2, 10) + 0),
('Q',	'SPORTS SCIENCES',	CONV('1', 2, 10) + 0),
('A',	'THEATRE, FILM AND TV',	CONV('1', 2, 10) + 0),
('G',	'WELSH',	CONV('1', 2, 10) + 0);

DROP TABLE IF EXISTS `Modules`;
CREATE TABLE `Modules` (
  `ModuleID` varchar(10) NOT NULL,
  `QuestionaireID` int(11) NOT NULL,
  `ModuleTitle` varchar(200) NOT NULL,
  `Fake` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`ModuleID`,`QuestionaireID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ModuleSemester`;
CREATE TABLE `ModuleSemester` (
  `QuestionnaireID` int(11) NOT NULL,
  `ModuleID` varchar(10) NOT NULL,
  `ModuleSemester` varchar(5) NOT NULL,
  `SemesterWithinQuestionnaire` bit(1) NOT NULL,
  PRIMARY KEY (`QuestionnaireID`,`ModuleID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP VIEW IF EXISTS `Modules_Filtered`;
CREATE TABLE `Modules_Filtered` (`QuestionaireID` int(11), `ModuleID` varchar(10), `ModuleTitle` varchar(200), `Fake` bit(1));


DROP TABLE IF EXISTS `Questionaires`;
CREATE TABLE `Questionaires` (
  `QuestionaireID` int(11) NOT NULL AUTO_INCREMENT,
  `QuestionaireName` varchar(20) NOT NULL,
  `QuestionaireDepartment` char(1) NOT NULL,
  `QuestionaireSemester` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`QuestionaireID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Questions`;
CREATE TABLE `Questions` (
  `QuestionID` int(11) NOT NULL AUTO_INCREMENT,
  `Staff` bit(1) NOT NULL,
  `QuestionText` text NOT NULL,
  `QuestionText_welsh` text NOT NULL,
  `Type` enum('rate','text') NOT NULL,
  `QuestionaireID` int(11) DEFAULT NULL,
  `ModuleID` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`QuestionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Staff`;
CREATE TABLE `Staff` (
  `UserID` varchar(6) NOT NULL,
  `QuestionaireID` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL,
  PRIMARY KEY (`UserID`,`QuestionaireID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `StaffToModules`;
CREATE TABLE `StaffToModules` (
  `ModuleID` varchar(200) NOT NULL,
  `UserID` varchar(6) NOT NULL,
  `QuestionaireID` int(11) NOT NULL,
  PRIMARY KEY (`ModuleID`,`UserID`,`QuestionaireID`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Students`;
CREATE TABLE `Students` (
  `UserID` varchar(6) NOT NULL,
  `QuestionaireID` int(11) NOT NULL,
  `Department` varchar(10) NOT NULL,
  `Token` varchar(8) NOT NULL,
  `Done` bit(1) NOT NULL,
  PRIMARY KEY (`UserID`,`QuestionaireID`),
  UNIQUE KEY `Token` (`Token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `StudentsToModules`;
CREATE TABLE `StudentsToModules` (
  `UserID` varchar(6) NOT NULL,
  `ModuleID` varchar(200) NOT NULL,
  `QuestionaireID` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`ModuleID`,`QuestionaireID`),
  KEY `ModuleID` (`ModuleID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `Modules_Filtered`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `Modules_Filtered` AS select `Modules`.`QuestionaireID` AS `QuestionaireID`,`Modules`.`ModuleID` AS `ModuleID`,`Modules`.`ModuleTitle` AS `ModuleTitle`,`Modules`.`Fake` AS `Fake` from (`Modules` left join `ModuleSemester` on(((`Modules`.`ModuleID` = convert(`ModuleSemester`.`ModuleID` using utf8)) and (`Modules`.`QuestionaireID` = `ModuleSemester`.`QuestionnaireID`)))) where ((`ModuleSemester`.`SemesterWithinQuestionnaire` = 1) or (`Modules`.`Fake` = 1));

-- 2014-09-20 01:40:03
