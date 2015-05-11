# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.6.23)
# Database: awesome
# Generation Time: 2015-05-08 10:41:04 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table Answers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Answers`;

CREATE TABLE `Answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` varchar(20) NOT NULL DEFAULT '',
  `answer` text,
  `survey_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Modules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Modules`;

CREATE TABLE `Modules` (
  `module_code` char(7) NOT NULL DEFAULT '',
  `title` text,
  `survey_id` int(11) NOT NULL,
  PRIMARY KEY (`module_code`,`survey_id`),
  KEY `survey_id_idxfk_3` (`survey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Questionnaires
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Questionnaires`;

CREATE TABLE `Questionnaires` (
  `token` char(16) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `completed` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`token`),
  UNIQUE KEY `token` (`token`),
  KEY `survey_id_idxfk` (`survey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Questions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Questions`;

CREATE TABLE `Questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) DEFAULT NULL,
  `module` varchar(7) DEFAULT NULL,
  `text_en` text,
  `type` varchar(11) DEFAULT NULL,
  `text_cy` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Staff
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Staff`;

CREATE TABLE `Staff` (
  `aber_id` varchar(8) NOT NULL DEFAULT '',
  `name` varchar(255) DEFAULT NULL,
  `survey_id` int(11) NOT NULL,
  PRIMARY KEY (`aber_id`,`survey_id`),
  KEY `survey_id_idxfk_4` (`survey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table StaffModules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `StaffModules`;

CREATE TABLE `StaffModules` (
  `module_code` char(7) DEFAULT NULL,
  `aber_id` varchar(8) DEFAULT NULL,
  `survey_id` int(11) DEFAULT NULL,
  KEY `survey_id_idxfk_6` (`survey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table StudentModules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `StudentModules`;

CREATE TABLE `StudentModules` (
  `aber_id` varchar(8) DEFAULT NULL,
  `module_code` char(7) NOT NULL DEFAULT '',
  `survey_id` int(11) DEFAULT NULL,
  `token` char(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`module_code`,`token`),
  KEY `survey_id_idxfk_5` (`survey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Students
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Students`;

CREATE TABLE `Students` (
  `token` char(16) NOT NULL DEFAULT '',
  `aber_id` varchar(8) NOT NULL DEFAULT '',
  `survey_id` int(11) NOT NULL,
  PRIMARY KEY (`token`,`aber_id`,`survey_id`),
  UNIQUE KEY `token` (`token`),
  KEY `survey_id_idxfk_1` (`survey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table Surveys
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Surveys`;

CREATE TABLE `Surveys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_en` text NOT NULL,
  `subtitle_en` text,
  `title_cy` text,
  `subtitle_cy` text,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `locked` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
