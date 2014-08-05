-- Adminer 4.0.3 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '+02:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `AnswerGroup`;
CREATE TABLE `AnswerGroup` (
  `AnswerID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`AnswerID`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

INSERT INTO `AnswerGroup` (`AnswerID`) VALUES
(15),
(16),
(17),
(18),
(19);

DROP TABLE IF EXISTS `Answers`;
CREATE TABLE `Answers` (
  `AnswerID` int(11) NOT NULL,
  `QuestionID` int(11) NOT NULL,
  `ModuleID` varchar(10) NOT NULL,
  `StaffID` varchar(6) NOT NULL DEFAULT '',
  `NumValue` int(11) DEFAULT NULL,
  `TextValue` text,
  PRIMARY KEY (`AnswerID`,`QuestionID`,`ModuleID`,`StaffID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `Answers` (`AnswerID`, `QuestionID`, `ModuleID`, `StaffID`, `NumValue`, `TextValue`) VALUES
(18,	1,	'CS10110',	'',	5,	NULL),
(17,	4,	'CS10110',	'',	NULL,	'huet'),
(17,	3,	'CS10110',	'',	NULL,	'yeyh5r'),
(17,	2,	'CS10110',	'mfc1',	2,	NULL),
(17,	2,	'CS10110',	'lgt',	4,	NULL),
(17,	2,	'CS10110',	'dap',	1,	NULL),
(17,	1,	'CS10110',	'',	2,	NULL),
(16,	4,	'CS10110',	'',	NULL,	'wtwe4'),
(16,	3,	'CS10110',	'',	NULL,	'wetfgw'),
(16,	2,	'CS10110',	'mfc1',	5,	NULL),
(16,	2,	'CS10110',	'lgt',	4,	NULL),
(16,	2,	'CS10110',	'dap',	4,	NULL),
(16,	1,	'CS10110',	'',	2,	NULL),
(15,	4,	'CS10110',	'',	NULL,	'wtwe4'),
(15,	3,	'CS10110',	'',	NULL,	'wetfgw'),
(15,	2,	'CS10110',	'mfc1',	5,	NULL),
(15,	2,	'CS10110',	'lgt',	4,	NULL),
(15,	2,	'CS10110',	'dap',	4,	NULL),
(15,	1,	'CS10110',	'',	2,	NULL),
(18,	2,	'CS10110',	'dap',	5,	NULL),
(18,	2,	'CS10110',	'lgt',	2,	NULL),
(18,	2,	'CS10110',	'mfc1',	5,	NULL),
(18,	3,	'CS10110',	'',	NULL,	'egfwegf'),
(18,	4,	'CS10110',	'',	NULL,	'egeg'),
(19,	1,	'CS10110',	'',	3,	NULL),
(19,	2,	'CS10110',	'dap',	4,	NULL),
(19,	2,	'CS10110',	'lgt',	3,	NULL),
(19,	2,	'CS10110',	'mfc1',	5,	NULL),
(19,	3,	'CS10110',	'',	NULL,	'wtfgwq'),
(19,	4,	'CS10110',	'',	NULL,	'wgtw4e');

DROP TABLE IF EXISTS `Modules`;
CREATE TABLE `Modules` (
  `ModuleID` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `ModuleTitle` varchar(200) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`ModuleID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `Modules` (`ModuleID`, `ModuleTitle`) VALUES
('CS10110',	'Introduction to Computer Hardware, Operating Systems and Unix Tools'),
('CS10410',	'The Mathematics Driving License for Computer Science'),
('CS12020',	'Introduction to Programming'),
('CS12510',	'Functional Programming'),
('CS15020',	'Web Development Tools'),
('CS10720',	'Problems and Solutions'),
('CS12320',	'Programming Using an Object-Oriented Language'),
('CS18010',	'Professional and Personal Development'),
('CS20410',	'The Advanced Mathematics Driving License for Computer Science '),
('CS21120',	'Program Design, Data Structures and Algorithms '),
('CS22120',	'The Software Development Life Cycle'),
('CS23710',	'C and UNIX Programming'),
('CS24110',	'Image Processing'),
('CS25010',	'Web Programming'),
('CS25110',	'Introduction to System and Network Services Administration'),
('CS25410',	'Computer Architecture and Hardware '),
('CS26110',	'The Artificial Intelligence Toolbox Part 1: how to Find Solutions '),
('CS27020',	'Modelling Persistent Data'),
('CS28310',	'Introduction to Business Processes for Web Developers'),
('CS31310',	'Agile Methodologies '),
('CS32310',	'Advanced Computer Graphics '),
('CS34110',	'Computer Vision '),
('CS35710',	'Ubiquitous Computing '),
('CS35910',	'Internet Services Administration '),
('CS36110',	'Machine Learning '),
('CS36510',	'Space Robotics '),
('CS37420',	'E-Commerce: Implementation, Management and Security'),
('CS38110',	'Open Source Development Issues '),
('CS39820',	'Business Information Technology Group Project'),
('CS22310',	'User Centred Design and Human Computer Interaction '),
('CS22510',	'C++, C and Java Programming Paradigms '),
('CS25210',	'Client-Side Graphics Programming for the Web'),
('CS25710',	'Mobile, Embedded and Wearable Technology '),
('CS26210',	'The Artificial Intelligence Toolbox - Part ii: Programming in An Uncertain World '),
('CS26410',	'Introduction to Robotics '),
('CS27510',	'Commercial Database Applications '),
('CS35810',	'Further Issues in System and Network Services Administration '),
('CS36410',	'Intelligent Robotics '),
('CS38220',	'Professional Issues in the Computing Industry '),
('CS39440',	'Major Project '),
('CS39620',	'Minor Project '),
('CS39930',	'Web-Based Major Project ');

DROP TABLE IF EXISTS `Questions`;
CREATE TABLE `Questions` (
  `QuestionID` int(11) NOT NULL,
  `Staff` bit(1) NOT NULL,
  `QuestionText` text NOT NULL,
  `Type` enum('rate','text') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `Questions` (`QuestionID`, `Staff`, `QuestionText`, `Type`) VALUES
(1,	CONV('0', 2, 10) + 0,	'I have learned a good deal from this module',	'rate'),
(2,	CONV('1', 2, 10) + 0,	'This module was well taught by %s',	'rate'),
(3,	CONV('0', 2, 10) + 0,	'What one thing would you change to improve this module, and why?',	'text'),
(4,	CONV('0', 2, 10) + 0,	'Please add any further comments on this module below',	'text');

DROP TABLE IF EXISTS `Staff`;
CREATE TABLE `Staff` (
  `UserID` varchar(6) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(30) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `Staff` (`UserID`, `name`) VALUES
('bpt',	'Bernie Tiddeman'),
('dpb',	'Dave Barnes'),
('mhl',	'Mark Lee'),
('cjp',	'Chris Price'),
('qqs',	'Qiang Shen'),
('rrz',	'Reyer Zwiggelaar'),
('geg18',	'Georgios Gkoutos'),
('jgh',	'Jun He'),
('thj10',	'Thomas Jansen'),
('ffl',	'Frederic Labrosse'),
('yyl',	'Yonghuai Liu'),
('fwl',	'Fred Long'),
('mjn',	'Mark Neal'),
('waa2',	'Wayne Aubrey'),
('afc',	'Amanda Clare'),
('hmd1',	'Hannah Dee'),
('nwh',	'Nigel Hardy'),
('rkj',	'Richard Jensen'),
('cul',	'Chuan Lu'),
('phs',	'Patricia Shaw'),
('eds',	'Edel Sherratt'),
('nns',	'Neal Snooke'),
('elt7',	'Elio Tuci'),
('mxw',	'Myra Wilson'),
('roh25',	'Robert Hoehndorf'),
('ncm',	'Neil MacParthalain'),
('cns',	'Changjing Shang'),
('job46',	'Jonathan Bell'),
('alg25',	'Alexandros Giagkos'),
('weh',	'Wenda He'),
('hem23',	'Helen Miles'),
('cos',	'Colin Sauze'),
('hgs08',	'Harry Strange'),
('lgt',	'Laurence Tyler'),
('dap',	'Dave Price'),
('cwl',	'Chris Loftus'),
('rrrp',	'Rhys Parry'),
('ais',	'Adrian Shaw'),
('rcs',	'Richard Shipman'),
('aos',	'Andy Starr'),
('nst',	'Neil Taylor'),
('ltt',	'Lynda Thomas'),
('ohe',	'John Edkins'),
('hoh',	'Horst Holstein'),
('htp',	'Heather Phillips'),
('rds',	'David Sherratt'),
('mfb',	'Frank Bott'),
('esa3',	'Eslam Al-Hersh'),
('faa3',	'Fahad Alghamdi'),
('bua',	'Bushra Alolayan'),
('yar',	'Yambu Andrik Rampun'),
('szb',	'Suzana Barreto'),
('ttb7',	'Tom Blanchard'),
('juc3',	'Juan Cao'),
('jec44',	'Jessica Charlton'),
('chc16',	'Chengyuan Chen'),
('tic4',	'Tianhua Chen'),
('mfc1',	'Michael Clarke'),
('jtg09',	'James Green'),
('chg12',	'Chen Gui'),
('shj1',	'Shangzhu Jin'),
('mjw9',	'Max Walker'),
('oal',	'Olalekan Lanihun'),
('zhl6',	'Zhenpeng Li'),
('lul1',	'Lu Lou'),
('mhm1',	'Muhanad Mohammed'),
('mum4',	'Muhammad Mohmand'),
('nkn',	'Nitin Naik'),
('apn3',	'Aparajit Narayan'),
('mro7',	'Mark Ososinski'),
('lip8',	'Lilan Pan'),
('map13',	'Matthew Pugh'),
('jjr6',	'Jonathan Roscoe'),
('pds7',	'Peter Scully'),
('als31',	'Alassane Seck'),
('lls08',	'Liang Shen'),
('jis17',	'Jingping Song'),
('pas23',	'Pan Su'),
('sfw7',	'Shisheng Wang'),
('liw19',	'Liping Wang'),
('muw1',	'Muhammad Waqar'),
('yoz1',	'Yongfeng Zhang'),
('liz5',	'Ling Zheng'),
('emp22',	'Emma Posey'),
('cls26',	'Claie Suaze'),
('niz2',	'Ni Zhu'),
('prw4',	'Phillip Wilkinson'),
('ghd',	'Huw Davies'),
('jig',	'John Gilbey'),
('spk',	'Stephen Kingston'),
('rrp',	'Rhys Parry');

DROP TABLE IF EXISTS `StaffToModules`;
CREATE TABLE `StaffToModules` (
  `ModuleID` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `UserID` varchar(6) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`ModuleID`,`UserID`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `StaffToModules` (`ModuleID`, `UserID`) VALUES
('CS10110',	'dap'),
('CS10110',	'lgt'),
('CS10110',	'mfc1'),
('CS10410',	'dap'),
('CS10410',	'nkn'),
('CS10720',	'thj10'),
('CS12020',	'aos'),
('CS12320',	'cwl'),
('CS12510',	'afc'),
('CS15010',	'hmd1'),
('CS15020',	'ais'),
('CS18010',	'fwl'),
('CS18010',	'hmd1'),
('CS20410',	'afc'),
('CS20410',	'fwl'),
('CS21120',	'ltt'),
('CS21120',	'ncm'),
('CS21120',	'rcs'),
('CS22120',	'bpt'),
('CS22120',	'cjp'),
('CS22120',	'dap'),
('CS22310',	'nwh'),
('CS22510',	'ffl'),
('CS22510',	'job46'),
('CS23710',	'dap'),
('CS23710',	'fwl'),
('CS24110',	'yyl'),
('CS25010',	'ais'),
('CS25010',	'jjr6'),
('CS25110',	'ais'),
('CS25110',	'jig'),
('CS25110',	'spk'),
('CS25210',	'hmd1'),
('CS25410',	'thj10'),
('CS25710',	'cos'),
('CS25710',	'nns'),
('CS26110',	'rkj'),
('CS26210',	'elt7'),
('CS26210',	'mxw'),
('CS26410',	'mxw'),
('CS26410',	'ttb7'),
('CS27020',	'eds'),
('CS27020',	'nkn'),
('CS27510',	'rcs'),
('CS28310',	'eds'),
('CS31310',	'eds'),
('CS32310',	'bpt'),
('CS32310',	'hoh'),
('CS34110',	'ffl'),
('CS34110',	'yyl'),
('CS35710',	'mjn'),
('CS35810',	'ais'),
('CS35810',	'jig'),
('CS35810',	'spk'),
('CS35910',	'ais'),
('CS35910',	'jig'),
('CS35910',	'spk'),
('CS36110',	'cul'),
('CS36110',	'yyl'),
('CS36410',	'mjn'),
('CS36410',	'mxw'),
('CS36510',	'dpb'),
('CS37420',	'cwl'),
('CS37420',	'nst'),
('CS38110',	'rcs'),
('CS38220',	'mfb'),
('CS38220',	'rrp'),
('CS39820',	'rrp');

DROP TABLE IF EXISTS `Students`;
CREATE TABLE `Students` (
  `UserID` varchar(6) COLLATE latin1_general_ci NOT NULL,
  `Department` enum('Art','IBERS','CompSci','Welsh') COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `Students` (`UserID`, `Department`) VALUES
('keo7',	'CompSci'),
('stm26',	'CompSci'),
('abc1',	'CompSci');

DROP TABLE IF EXISTS `StudentsToModules`;
CREATE TABLE `StudentsToModules` (
  `UserID` varchar(6) COLLATE latin1_general_ci NOT NULL,
  `ModuleID` varchar(200) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`UserID`,`ModuleID`),
  KEY `ModuleID` (`ModuleID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

INSERT INTO `StudentsToModules` (`UserID`, `ModuleID`) VALUES
('abc1',	'CS10110'),
('keo7',	'CS28310'),
('keo7',	'CS31310'),
('keo7',	'CS35910'),
('keo7',	'CS37420'),
('keo7',	'CS38110'),
('keo7',	'CS38220'),
('keo7',	'CS39440'),
('stm26',	'CS10110'),
('stm26',	'CS10410'),
('stm26',	'CS10720'),
('stm26',	'CS12020'),
('stm26',	'CS12320'),
('stm26',	'CS12510'),
('stm26',	'CS18010');

-- 2014-08-05 20:47:09
