-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 12, 2014 at 06:51 PM
-- Server version: 5.5.38
-- PHP Version: 5.3.10-1ubuntu3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `awesome`
--

-- --------------------------------------------------------

--
-- Table structure for table `AnswerGroup`
--

CREATE TABLE IF NOT EXISTS `AnswerGroup` (
  `AnswerID` int(11) NOT NULL AUTO_INCREMENT,
  `QuestionaireID` int(11) NOT NULL,
  PRIMARY KEY (`AnswerID`,`QuestionaireID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Table structure for table `Answers`
--

CREATE TABLE IF NOT EXISTS `Answers` (
  `AnswerID` int(11) NOT NULL,
  `QuestionID` int(11) NOT NULL,
  `ModuleID` varchar(10) NOT NULL,
  `StaffID` varchar(6) NOT NULL DEFAULT '',
  `NumValue` int(11) DEFAULT NULL,
  `TextValue` text,
  PRIMARY KEY (`AnswerID`,`QuestionID`,`ModuleID`,`StaffID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Config`
--

CREATE TABLE IF NOT EXISTS `Config` (
  `key` varchar(10) CHARACTER SET latin1 NOT NULL,
  `value` text CHARACTER SET latin1 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Modules`
--

CREATE TABLE IF NOT EXISTS `Modules` (
  `ModuleID` varchar(10) NOT NULL,
  `QuestionaireID` int(11) NOT NULL,
  `ModuleTitle` varchar(200) NOT NULL,
  `Fake` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`ModuleID`,`QuestionaireID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Questionaires`
--

CREATE TABLE IF NOT EXISTS `Questionaires` (
  `QuestionaireID` int(11) NOT NULL AUTO_INCREMENT,
  `QuestionaireName` varchar(20) NOT NULL,
  `QuestionaireDepartment` varchar(30) NOT NULL,
  PRIMARY KEY (`QuestionaireID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `Questions`
--

CREATE TABLE IF NOT EXISTS `Questions` (
  `QuestionID` int(11) NOT NULL AUTO_INCREMENT,
  `Staff` bit(1) NOT NULL,
  `QuestionText` text NOT NULL,
  `QuestionText_welsh` text NOT NULL,
  `Type` enum('rate','text') NOT NULL,
  `QuestionaireID` int(11) DEFAULT NULL,
  `ModuleID` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`QuestionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

-- --------------------------------------------------------

--
-- Table structure for table `Staff`
--

CREATE TABLE IF NOT EXISTS `Staff` (
  `UserID` varchar(6) NOT NULL,
  `QuestionaireID` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL,
  PRIMARY KEY (`UserID`,`QuestionaireID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `StaffToModules`
--

CREATE TABLE IF NOT EXISTS `StaffToModules` (
  `ModuleID` varchar(200) NOT NULL,
  `UserID` varchar(6) NOT NULL,
  `QuestionaireID` int(11) NOT NULL,
  PRIMARY KEY (`ModuleID`,`UserID`,`QuestionaireID`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Students`
--

CREATE TABLE IF NOT EXISTS `Students` (
  `UserID` varchar(6) NOT NULL,
  `QuestionaireID` int(11) NOT NULL,
  `Department` varchar(10) NOT NULL,
  `Token` varchar(8) NOT NULL,
  `Done` bit(1) NOT NULL,
  PRIMARY KEY (`UserID`,`QuestionaireID`),
  UNIQUE KEY `Token` (`Token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `StudentsToModules`
--

CREATE TABLE IF NOT EXISTS `StudentsToModules` (
  `UserID` varchar(6) NOT NULL,
  `ModuleID` varchar(200) NOT NULL,
  `QuestionaireID` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`ModuleID`,`QuestionaireID`),
  KEY `ModuleID` (`ModuleID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
