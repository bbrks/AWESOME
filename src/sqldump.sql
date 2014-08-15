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
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

INSERT INTO `AnswerGroup` (`AnswerID`, `QuestionaireID`) VALUES
(1,	1),
(2,	1),
(21,	3),
(22,	3),
(23,	3);

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

INSERT INTO `Answers` (`AnswerID`, `QuestionID`, `ModuleID`, `StaffID`, `NumValue`, `TextValue`) VALUES
(21,	1,	'cs21120',	'',	4,	NULL),
(21,	2,	'cs21120',	'ltt',	5,	NULL),
(21,	2,	'cs21120',	'ncm',	4,	NULL),
(21,	2,	'cs21120',	'rcs',	2,	NULL),
(21,	3,	'cs21120',	'',	NULL,	'Richard Shipman\'s lectures are too slow.'),
(21,	4,	'cs21120',	'',	NULL,	'Lynda Thomas\' passion for the subject came through as she impressed,'),
(21,	1,	'cs22120',	'',	4,	NULL),
(21,	2,	'cs22120',	'bpt',	5,	NULL),
(21,	2,	'cs22120',	'cjp',	4,	NULL),
(21,	2,	'cs22120',	'dap',	5,	NULL),
(21,	3,	'cs22120',	'',	NULL,	'Talk a bit more about agile development.'),
(21,	4,	'cs22120',	'',	NULL,	'Chirs Price\'s talk on Git was laughable.'),
(21,	1,	'cs22310',	'',	2,	NULL),
(21,	2,	'cs22310',	'nwh',	5,	NULL),
(21,	3,	'cs22310',	'',	NULL,	'More up-to-date HCI methods.'),
(21,	4,	'cs22310',	'',	NULL,	'Nigel Hardy is capable.'),
(21,	1,	'cs25210',	'',	5,	NULL),
(21,	2,	'cs25210',	'hmd1',	4,	NULL),
(21,	3,	'cs25210',	'',	NULL,	'More JavaScript tutorials.'),
(21,	4,	'cs25210',	'',	NULL,	'The assignment was too close to the HCI one.'),
(21,	1,	'cs25410',	'',	1,	NULL),
(21,	2,	'cs25410',	'thj10',	1,	NULL),
(21,	3,	'cs25410',	'',	NULL,	'I didn\'t do this module.'),
(21,	4,	'cs25410',	'',	NULL,	'I didn\'t do it.'),
(21,	1,	'cs27020',	'',	5,	NULL),
(21,	2,	'cs27020',	'eds',	4,	NULL),
(21,	2,	'cs27020',	'nkn',	5,	NULL),
(21,	3,	'cs27020',	'',	NULL,	'Nitin\'s lectures need to be longer!'),
(21,	4,	'cs27020',	'',	NULL,	'Edel is capable!'),
(22,	1,	'cs21120',	'',	4,	NULL),
(22,	2,	'cs21120',	'ltt',	5,	NULL),
(22,	2,	'cs21120',	'ncm',	5,	NULL),
(22,	2,	'cs21120',	'rcs',	5,	NULL),
(22,	3,	'cs21120',	'',	NULL,	'It\'s good'),
(22,	4,	'cs21120',	'',	NULL,	'I loved it.'),
(22,	1,	'cs22120',	'',	5,	NULL),
(22,	2,	'cs22120',	'bpt',	3,	NULL),
(22,	2,	'cs22120',	'cjp',	3,	NULL),
(22,	2,	'cs22120',	'dap',	5,	NULL),
(22,	3,	'cs22120',	'',	NULL,	'Bernie talks too slowly'),
(22,	4,	'cs22120',	'',	NULL,	'N/A'),
(22,	1,	'cs22310',	'',	5,	NULL),
(22,	2,	'cs22310',	'nwh',	4,	NULL),
(22,	3,	'cs22310',	'',	NULL,	'Nigel Hardy should dance during his presentations.'),
(22,	4,	'cs22310',	'',	NULL,	'I like beans'),
(22,	1,	'cs25210',	'',	5,	NULL),
(22,	2,	'cs25210',	'hmd1',	2,	NULL),
(22,	3,	'cs25210',	'',	NULL,	'Everything should of been done in HTML 4.'),
(22,	4,	'cs25210',	'',	NULL,	'Canvases are rubbish.'),
(22,	1,	'cs27020',	'',	4,	NULL),
(22,	2,	'cs27020',	'eds',	5,	NULL),
(22,	2,	'cs27020',	'nkn',	3,	NULL),
(22,	3,	'cs27020',	'',	NULL,	'Edel should wear a necklace and a tie at the same time.'),
(22,	4,	'cs27020',	'',	NULL,	'Databases are fun.'),
(23,	1,	'cs10110',	'',	5,	NULL),
(23,	2,	'cs10110',	'dap',	5,	NULL),
(23,	2,	'cs10110',	'lgt',	3,	NULL),
(23,	2,	'cs10110',	'mfc1',	5,	NULL),
(23,	3,	'cs10110',	'',	NULL,	'It\'s good, but it\'s not good.'),
(23,	4,	'cs10110',	'',	NULL,	'N/A'),
(23,	1,	'cs10410',	'',	3,	NULL),
(23,	2,	'cs10410',	'dap',	5,	NULL),
(23,	2,	'cs10410',	'nkn',	5,	NULL),
(23,	3,	'cs10410',	'',	NULL,	'Nitin should dance.'),
(23,	4,	'cs10410',	'',	NULL,	'N/A'),
(23,	1,	'cs10720',	'',	4,	NULL),
(23,	2,	'cs10720',	'thj10',	5,	NULL),
(23,	3,	'cs10720',	'',	NULL,	'I like this module even though I never did it.'),
(23,	4,	'cs10720',	'',	NULL,	'Thomas Jansen is a nice name.'),
(23,	1,	'cs18010',	'',	5,	NULL),
(23,	2,	'cs18010',	'fwl',	5,	NULL),
(23,	2,	'cs18010',	'hmd1',	4,	NULL),
(23,	3,	'cs18010',	'',	NULL,	'I want more mudkipz'),
(23,	4,	'cs18010',	'',	NULL,	'N/A');

DROP TABLE IF EXISTS `Modules`;
CREATE TABLE `Modules` (
  `ModuleID` varchar(10) NOT NULL,
  `QuestionaireID` int(11) NOT NULL,
  `ModuleTitle` varchar(200) NOT NULL,
  PRIMARY KEY (`ModuleID`,`QuestionaireID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `Modules` (`ModuleID`, `QuestionaireID`, `ModuleTitle`) VALUES
('CS10110',	0,	'Introduction to Computer Hardware, Operating Systems and Unix Tools'),
('CS10410',	0,	'The Mathematics Driving License for Computer Science'),
('CS12020',	0,	'Introduction to Programming'),
('CS12510',	0,	'Functional Programming'),
('CS15020',	0,	'Web Development Tools'),
('CS10720',	0,	'Problems and Solutions'),
('CS12320',	0,	'Programming Using an Object-Oriented Language'),
('CS18010',	0,	'Professional and Personal Development'),
('CS20410',	0,	'The Advanced Mathematics Driving License for Computer Science '),
('CS21120',	0,	'Program Design, Data Structures and Algorithms '),
('CS22120',	0,	'The Software Development Life Cycle'),
('CS23710',	0,	'C and UNIX Programming'),
('CS24110',	0,	'Image Processing'),
('CS25010',	0,	'Web Programming'),
('CS25110',	0,	'Introduction to System and Network Services Administration'),
('CS25410',	0,	'Computer Architecture and Hardware '),
('CS26110',	0,	'The Artificial Intelligence Toolbox Part 1: how to Find Solutions '),
('CS27020',	0,	'Modelling Persistent Data'),
('CS28310',	0,	'Introduction to Business Processes for Web Developers'),
('CS31310',	0,	'Agile Methodologies '),
('CS32310',	0,	'Advanced Computer Graphics '),
('CS34110',	0,	'Computer Vision '),
('CS35710',	0,	'Ubiquitous Computing '),
('CS35910',	0,	'Internet Services Administration '),
('CS36110',	0,	'Machine Learning '),
('CS36510',	0,	'Space Robotics '),
('CS37420',	0,	'E-Commerce: Implementation, Management and Security'),
('CS38110',	0,	'Open Source Development Issues '),
('CS39820',	0,	'Business Information Technology Group Project'),
('CS22310',	0,	'User Centred Design and Human Computer Interaction '),
('CS22510',	0,	'C++, C and Java Programming Paradigms '),
('CS25210',	0,	'Client-Side Graphics Programming for the Web'),
('CS25710',	0,	'Mobile, Embedded and Wearable Technology '),
('CS26210',	0,	'The Artificial Intelligence Toolbox - Part ii: Programming in An Uncertain World '),
('CS26410',	0,	'Introduction to Robotics '),
('CS27510',	0,	'Commercial Database Applications '),
('CS35810',	0,	'Further Issues in System and Network Services Administration '),
('CS36410',	0,	'Intelligent Robotics '),
('CS38220',	0,	'Professional Issues in the Computing Industry '),
('CS39440',	0,	'Major Project '),
('CS39620',	0,	'Minor Project '),
('CS39930',	0,	'Web-Based Major Project '),
('cs10110',	3,	'Introduction to Computer Hardware, Operating Systems and Unix Tools'),
('cs10410',	3,	'The Mathematics Driving License for Computer Science'),
('cs12020',	3,	'Introduction to Programming'),
('cs12510',	3,	'Functional Programming'),
('cs15020',	3,	'Web Development Tools'),
('cs10720',	3,	'Problems and Solutions'),
('cs12320',	3,	'Programming Using an Object-Oriented Language'),
('cs18010',	3,	'Professional and Personal Development'),
('cs20410',	3,	'The Advanced Mathematics Driving License for Computer Science '),
('cs21120',	3,	'Program Design, Data Structures and Algorithms '),
('cs22120',	3,	'The Software Development Life Cycle'),
('cs23710',	3,	'C and UNIX Programming'),
('cs24110',	3,	'Image Processing'),
('cs25010',	3,	'Web Programming'),
('cs25110',	3,	'Introduction to System and Network Services Administration'),
('cs25410',	3,	'Computer Architecture and Hardware '),
('cs26110',	3,	'The Artificial Intelligence Toolbox Part 1: how to Find Solutions '),
('cs27020',	3,	'Modelling Persistent Data'),
('cs28310',	3,	'Introduction to Business Processes for Web Developers'),
('cs31310',	3,	'Agile Methodologies '),
('cs32310',	3,	'Advanced Computer Graphics '),
('cs34110',	3,	'Computer Vision '),
('cs35710',	3,	'Ubiquitous Computing '),
('cs35910',	3,	'Internet Services Administration '),
('cs36110',	3,	'Machine Learning '),
('cs36510',	3,	'Space Robotics '),
('cs37420',	3,	'E-Commerce: Implementation, Management and Security'),
('cs38110',	3,	'Open Source Development Issues '),
('cs39820',	3,	'Business Information Technology Group Project'),
('cs22310',	3,	'User Centred Design and Human Computer Interaction '),
('cs22510',	3,	'C++, C and Java Programming Paradigms '),
('cs25210',	3,	'Client-Side Graphics Programming for the Web'),
('cs25710',	3,	'Mobile, Embedded and Wearable Technology '),
('cs26210',	3,	'The Artificial Intelligence Toolbox - Part ii: Programming in An Uncertain World '),
('cs26410',	3,	'Introduction to Robotics '),
('cs27510',	3,	'Commercial Database Applications '),
('cs35810',	3,	'Further Issues in System and Network Services Administration '),
('cs36410',	3,	'Intelligent Robotics '),
('cs38220',	3,	'Professional Issues in the Computing Industry '),
('cs39440',	3,	'Major Project '),
('cs39620',	3,	'Minor Project '),
('cs39930',	3,	'Web-Based Major Project ');

DROP TABLE IF EXISTS `Questionaires`;
CREATE TABLE `Questionaires` (
  `QuestionaireID` int(11) NOT NULL AUTO_INCREMENT,
  `QuestionaireName` varchar(20) NOT NULL,
  `QuestionaireDepartment` enum('Art','IBERS','CompSci','Welsh') NOT NULL,
  PRIMARY KEY (`QuestionaireID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `Questionaires` (`QuestionaireID`, `QuestionaireName`, `QuestionaireDepartment`) VALUES
(1,	'ModifiedName',	'CompSci'),
(2,	'test',	'CompSci'),
(3,	'October2014',	'CompSci');

DROP TABLE IF EXISTS `Questions`;
CREATE TABLE `Questions` (
  `QuestionID` int(11) NOT NULL,
  `Staff` bit(1) NOT NULL,
  `QuestionText` text NOT NULL,
  `QuestionText_welsh` text NOT NULL,
  `Type` enum('rate','text') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `Questions` (`QuestionID`, `Staff`, `QuestionText`, `QuestionText_welsh`, `Type`) VALUES
(1,	CONV('0', 2, 10) + 0,	'I have learned a good deal from this module',	'Rydw i wedi dysgu llawer o\'r modiwl',	'rate'),
(2,	CONV('1', 2, 10) + 0,	'This module was well taught by %s',	'Mae\'r modiwl ei haddysgu yn dda %s',	'rate'),
(3,	CONV('0', 2, 10) + 0,	'What one thing would you change to improve this module, and why?',	'Gwelliannau Modiwl, a pham?',	'text'),
(4,	CONV('0', 2, 10) + 0,	'Please add any further comments on this module below',	'sylwadau pellach',	'text');

DROP TABLE IF EXISTS `Staff`;
CREATE TABLE `Staff` (
  `UserID` varchar(6) NOT NULL,
  `QuestionaireID` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL,
  PRIMARY KEY (`UserID`,`QuestionaireID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `Staff` (`UserID`, `QuestionaireID`, `Name`) VALUES
('bpt',	3,	'Bernie Tiddeman'),
('dpb',	3,	'Dave Barnes'),
('mhl',	3,	'Mark Lee'),
('cjp',	3,	'Chris Price'),
('qqs',	3,	'Qiang Shen'),
('rrz',	3,	'Reyer Zwiggelaar'),
('geg18',	3,	'Georgios Gkoutos'),
('jgh',	3,	'Jun He'),
('thj10',	3,	'Thomas Jansen'),
('ffl',	3,	'Frederic Labrosse'),
('yyl',	3,	'Yonghuai Liu'),
('fwl',	3,	'Fred Long'),
('mjn',	3,	'Mark Neal'),
('waa2',	3,	'Wayne Aubrey'),
('afc',	3,	'Amanda Clare'),
('hmd1',	3,	'Hannah Dee'),
('nwh',	3,	'Nigel Hardy'),
('rkj',	3,	'Richard Jensen'),
('cul',	3,	'Chuan Lu'),
('phs',	3,	'Patricia Shaw'),
('eds',	3,	'Edel Sherratt'),
('nns',	3,	'Neal Snooke'),
('elt7',	3,	'Elio Tuci'),
('mxw',	3,	'Myra Wilson'),
('roh25',	3,	'Robert Hoehndorf'),
('ncm',	3,	'Neil MacParthalain'),
('cns',	3,	'Changjing Shang'),
('job46',	3,	'Jonathan Bell'),
('alg25',	3,	'Alexandros Giagkos'),
('weh',	3,	'Wenda He'),
('hem23',	3,	'Helen Miles'),
('cos',	3,	'Colin Sauze'),
('hgs08',	3,	'Harry Strange'),
('lgt',	3,	'Laurence Tyler'),
('dap',	3,	'Dave Price'),
('cwl',	3,	'Chris Loftus'),
('rrrp',	3,	'Rhys Parry'),
('ais',	3,	'Adrian Shaw'),
('rcs',	3,	'Richard Shipman'),
('aos',	3,	'Andy Starr'),
('nst',	3,	'Neil Taylor'),
('ltt',	3,	'Lynda Thomas'),
('ohe',	3,	'John Edkins'),
('hoh',	3,	'Horst Holstein'),
('htp',	3,	'Heather Phillips'),
('rds',	3,	'David Sherratt'),
('mfb',	3,	'Frank Bott'),
('esa3',	3,	'Eslam Al-Hersh'),
('faa3',	3,	'Fahad Alghamdi'),
('bua',	3,	'Bushra Alolayan'),
('yar',	3,	'Yambu Andrik Rampun'),
('szb',	3,	'Suzana Barreto'),
('ttb7',	3,	'Tom Blanchard'),
('juc3',	3,	'Juan Cao'),
('jec44',	3,	'Jessica Charlton'),
('chc16',	3,	'Chengyuan Chen'),
('tic4',	3,	'Tianhua Chen'),
('mfc1',	3,	'Michael Clarke'),
('jtg09',	3,	'James Green'),
('chg12',	3,	'Chen Gui'),
('shj1',	3,	'Shangzhu Jin'),
('mjw9',	3,	'Max Walker'),
('oal',	3,	'Olalekan Lanihun'),
('zhl6',	3,	'Zhenpeng Li'),
('lul1',	3,	'Lu Lou'),
('mhm1',	3,	'Muhanad Mohammed'),
('mum4',	3,	'Muhammad Mohmand'),
('nkn',	3,	'Nitin Naik'),
('apn3',	3,	'Aparajit Narayan'),
('mro7',	3,	'Mark Ososinski'),
('lip8',	3,	'Lilan Pan'),
('map13',	3,	'Matthew Pugh'),
('jjr6',	3,	'Jonathan Roscoe'),
('pds7',	3,	'Peter Scully'),
('als31',	3,	'Alassane Seck'),
('lls08',	3,	'Liang Shen'),
('jis17',	3,	'Jingping Song'),
('pas23',	3,	'Pan Su'),
('sfw7',	3,	'Shisheng Wang'),
('liw19',	3,	'Liping Wang'),
('muw1',	3,	'Muhammad Waqar'),
('yoz1',	3,	'Yongfeng Zhang'),
('liz5',	3,	'Ling Zheng'),
('emp22',	3,	'Emma Posey'),
('cls26',	3,	'Claie Suaze'),
('niz2',	3,	'Ni Zhu'),
('prw4',	3,	'Phillip Wilkinson'),
('ghd',	3,	'Huw Davies'),
('jig',	3,	'John Gilbey'),
('spk',	3,	'Stephen Kingston');

DROP TABLE IF EXISTS `StaffToModules`;
CREATE TABLE `StaffToModules` (
  `ModuleID` varchar(200) NOT NULL,
  `UserID` varchar(6) NOT NULL,
  `QuestionaireID` int(11) NOT NULL,
  PRIMARY KEY (`ModuleID`,`UserID`,`QuestionaireID`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `StaffToModules` (`ModuleID`, `UserID`, `QuestionaireID`) VALUES
('CS10110',	'dap',	0),
('cs10110',	'dap',	3),
('CS10110',	'lgt',	0),
('cs10110',	'lgt',	3),
('CS10110',	'mfc1',	0),
('cs10110',	'mfc1',	3),
('CS10410',	'dap',	0),
('cs10410',	'dap',	3),
('CS10410',	'nkn',	0),
('cs10410',	'nkn',	3),
('CS10720',	'thj10',	0),
('cs10720',	'thj10',	3),
('CS12020',	'aos',	0),
('cs12020',	'aos',	3),
('CS12320',	'cwl',	0),
('cs12320',	'cwl',	3),
('CS12510',	'afc',	0),
('cs12510',	'afc',	3),
('CS15010',	'hmd1',	0),
('cs15010',	'hmd1',	3),
('CS15020',	'ais',	0),
('cs15020',	'ais',	3),
('CS18010',	'fwl',	0),
('cs18010',	'fwl',	3),
('CS18010',	'hmd1',	0),
('cs18010',	'hmd1',	3),
('CS20410',	'afc',	0),
('cs20410',	'afc',	3),
('CS20410',	'fwl',	0),
('cs20410',	'fwl',	3),
('CS21120',	'ltt',	0),
('cs21120',	'ltt',	3),
('CS21120',	'ncm',	0),
('cs21120',	'ncm',	3),
('CS21120',	'rcs',	0),
('cs21120',	'rcs',	3),
('CS22120',	'bpt',	0),
('cs22120',	'bpt',	3),
('CS22120',	'cjp',	0),
('cs22120',	'cjp',	3),
('CS22120',	'dap',	0),
('cs22120',	'dap',	3),
('CS22310',	'nwh',	0),
('cs22310',	'nwh',	3),
('CS22510',	'ffl',	0),
('cs22510',	'ffl',	3),
('CS22510',	'job46',	0),
('cs22510',	'job46',	3),
('CS23710',	'dap',	0),
('cs23710',	'dap',	3),
('CS23710',	'fwl',	0),
('cs23710',	'fwl',	3),
('CS24110',	'yyl',	0),
('cs24110',	'yyl',	3),
('CS25010',	'ais',	0),
('cs25010',	'ais',	3),
('CS25010',	'jjr6',	0),
('cs25010',	'jjr6',	3),
('CS25110',	'ais',	0),
('cs25110',	'ais',	3),
('CS25110',	'jig',	0),
('cs25110',	'jig',	3),
('CS25110',	'spk',	0),
('cs25110',	'spk',	3),
('CS25210',	'hmd1',	0),
('cs25210',	'hmd1',	3),
('CS25410',	'thj10',	0),
('cs25410',	'thj10',	3),
('CS25710',	'cos',	0),
('cs25710',	'cos',	3),
('CS25710',	'nns',	0),
('cs25710',	'nns',	3),
('CS26110',	'rkj',	0),
('cs26110',	'rkj',	3),
('CS26210',	'elt7',	0),
('cs26210',	'elt7',	3),
('CS26210',	'mxw',	0),
('cs26210',	'mxw',	3),
('CS26410',	'mxw',	0),
('cs26410',	'mxw',	3),
('CS26410',	'ttb7',	0),
('cs26410',	'ttb7',	3),
('CS27020',	'eds',	0),
('cs27020',	'eds',	3),
('CS27020',	'nkn',	0),
('cs27020',	'nkn',	3),
('CS27510',	'rcs',	0),
('cs27510',	'rcs',	3),
('CS28310',	'eds',	0),
('cs28310',	'eds',	3),
('CS31310',	'eds',	0),
('cs31310',	'eds',	3),
('CS32310',	'bpt',	0),
('cs32310',	'bpt',	3),
('CS32310',	'hoh',	0),
('cs32310',	'hoh',	3),
('CS34110',	'ffl',	0),
('cs34110',	'ffl',	3),
('CS34110',	'yyl',	0),
('cs34110',	'yyl',	3),
('CS35710',	'mjn',	0),
('cs35710',	'mjn',	3),
('CS35810',	'ais',	0),
('cs35810',	'ais',	3),
('CS35810',	'jig',	0),
('cs35810',	'jig',	3),
('CS35810',	'spk',	0),
('cs35810',	'spk',	3),
('CS35910',	'ais',	0),
('cs35910',	'ais',	3),
('CS35910',	'jig',	0),
('cs35910',	'jig',	3),
('CS35910',	'spk',	0),
('cs35910',	'spk',	3),
('CS36110',	'cul',	0),
('cs36110',	'cul',	3),
('CS36110',	'yyl',	0),
('cs36110',	'yyl',	3),
('CS36410',	'mjn',	0),
('cs36410',	'mjn',	3),
('CS36410',	'mxw',	0),
('cs36410',	'mxw',	3),
('CS36510',	'dpb',	0),
('cs36510',	'dpb',	3),
('CS37420',	'cwl',	0),
('cs37420',	'cwl',	3),
('CS37420',	'nst',	0),
('cs37420',	'nst',	3),
('CS38110',	'rcs',	0),
('cs38110',	'rcs',	3),
('CS38220',	'mfb',	0),
('cs38220',	'mfb',	3),
('CS38220',	'rrp',	0),
('cs38220',	'rrp',	3),
('CS39820',	'rrp',	0),
('cs39820',	'rrp',	3);

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

INSERT INTO `Students` (`UserID`, `QuestionaireID`, `Department`, `Token`, `Done`) VALUES
('keo7',	3,	'CompSci',	'82c59f91',	CONV('0', 2, 10) + 0),
('stm26',	3,	'CompSci',	'bcaa22ee',	CONV('0', 2, 10) + 0),
('nid16',	3,	'CompSci',	'86368125',	CONV('0', 2, 10) + 0),
('jmt14',	3,	'CompSci',	'4a3c6510',	CONV('0', 2, 10) + 0),
('th1',	3,	'CompSci',	'e98ab488',	CONV('0', 2, 10) + 0);

DROP TABLE IF EXISTS `StudentsToModules`;
CREATE TABLE `StudentsToModules` (
  `UserID` varchar(6) NOT NULL,
  `ModuleID` varchar(200) NOT NULL,
  `QuestionaireID` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`ModuleID`,`QuestionaireID`),
  KEY `ModuleID` (`ModuleID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `StudentsToModules` (`UserID`, `ModuleID`, `QuestionaireID`) VALUES
('jmt14',	'cs21120',	1),
('jmt14',	'cs21120',	3),
('jmt14',	'cs22120',	1),
('jmt14',	'cs22120',	3),
('jmt14',	'cs22310',	1),
('jmt14',	'cs22310',	3),
('jmt14',	'cs25210',	1),
('jmt14',	'cs25210',	3),
('jmt14',	'cs25410',	1),
('jmt14',	'cs25410',	3),
('jmt14',	'cs27020',	1),
('jmt14',	'cs27020',	3),
('keo7',	'cs21120',	1),
('keo7',	'cs21120',	3),
('keo7',	'cs22120',	1),
('keo7',	'cs22120',	3),
('keo7',	'cs22310',	1),
('keo7',	'cs22310',	3),
('keo7',	'cs25210',	1),
('keo7',	'cs25210',	3),
('keo7',	'cs25410',	1),
('keo7',	'cs25410',	3),
('keo7',	'cs27020',	1),
('keo7',	'cs27020',	3),
('nid16',	'cs21120',	1),
('nid16',	'cs21120',	3),
('nid16',	'cs22120',	1),
('nid16',	'cs22120',	3),
('nid16',	'cs22310',	1),
('nid16',	'cs22310',	3),
('nid16',	'cs25210',	1),
('nid16',	'cs25210',	3),
('nid16',	'cs27020',	1),
('nid16',	'cs27020',	3),
('nid16',	'il35010',	1),
('nid16',	'il35010',	3),
('stm26',	'cs21120',	1),
('stm26',	'cs21120',	3),
('stm26',	'cs22120',	1),
('stm26',	'cs22120',	3),
('stm26',	'cs22310',	1),
('stm26',	'cs22310',	3),
('stm26',	'cs25210',	1),
('stm26',	'cs25210',	3),
('stm26',	'cs27020',	1),
('stm26',	'cs27020',	3),
('stm26',	'cs28310',	1),
('stm26',	'cs28310',	3),
('th1',	'',	1),
('th1',	'',	3),
('th1',	'cs10110',	1),
('th1',	'cs10110',	3),
('th1',	'cs10410',	1),
('th1',	'cs10410',	3),
('th1',	'cs10720',	1),
('th1',	'cs10720',	3),
('th1',	'cs12420',	1),
('th1',	'cs12420',	3),
('th1',	'cs18010',	1),
('th1',	'cs18010',	3);

-- 2014-08-11 06:40:39
