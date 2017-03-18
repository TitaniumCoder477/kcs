-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 18, 2017 at 10:03 AM
-- Server version: 5.5.38-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `TESTING`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin_Categories`
--

CREATE TABLE IF NOT EXISTS `Admin_Categories` (
  `CATEGORY` varchar(45) NOT NULL,
  `DESCRIPTION` varchar(360) DEFAULT NULL,
  `RANKING` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`CATEGORY`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Admin_Categories`
--

INSERT INTO `Admin_Categories` (`CATEGORY`, `DESCRIPTION`, `RANKING`) VALUES
('Categories', NULL, 2),
('Email', NULL, 6),
('History', NULL, 5),
('Items', NULL, 3),
('Misc', NULL, 7),
('Tags', NULL, 4),
('Users', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Admin_Category_Tasks`
--

CREATE TABLE IF NOT EXISTS `Admin_Category_Tasks` (
  `CATEGORY_FK` varchar(45) NOT NULL,
  `TASK` varchar(45) NOT NULL,
  `PATH` varchar(360) NOT NULL,
  `RANKING` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`CATEGORY_FK`,`TASK`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Admin_Category_Tasks`
--

INSERT INTO `Admin_Category_Tasks` (`CATEGORY_FK`, `TASK`, `PATH`, `RANKING`) VALUES
('Categories', 'Create', 'admin-categories-create.php', 0),
('Categories', 'Update/Delete', 'admin-categories-updatedelete.php', 0),
('Email', 'Configure (upcoming feature)', 'admin-feature-upcoming.php', 0),
('Email', 'Send (upcoming feature)', 'admin-feature-upcoming.php', 0),
('History', 'Export (upcoming feature)', 'admin-feature-upcoming.php', 0),
('History', 'View', 'admin-history-view.php', 0),
('Items', 'Create', 'admin-items-create.php', 0),
('Items', 'Update/Delete', 'admin-items-updatedelete.php', 0),
('Items', 'View checked out', 'admin-items-viewcheckedout.php', 0),
('Misc', 'Options', 'admin-misc-options.php', 1),
('Tags', 'Create/Update/Delete', 'admin-tags-createupdatedelete.php', 0),
('Tags', 'Manage associations', 'admin-tags-manageassociations.php', 0),
('Users', 'Create', 'admin-users-create.php', 0),
('Users', 'Update/Delete', 'admin-users-updatedelete.php', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Admin_Misc_Options`
--

CREATE TABLE IF NOT EXISTS `Admin_Misc_Options` (
  `RANKING` int(11) NOT NULL DEFAULT '-1',
  `DESCRIPTION` varchar(65) NOT NULL,
  `SETTING` varchar(90) NOT NULL,
  `VALUE` varchar(512) DEFAULT NULL,
  `TYPE` varchar(16) NOT NULL DEFAULT 'string',
  `REQUIRED` tinyint(4) NOT NULL DEFAULT '0',
  `MIN_BOUND` int(11) DEFAULT NULL,
  `MAX_BOUND` int(11) DEFAULT NULL,
  PRIMARY KEY (`SETTING`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Admin_Misc_Options`
--

INSERT INTO `Admin_Misc_Options` (`RANKING`, `DESCRIPTION`, `SETTING`, `VALUE`, `TYPE`, `REQUIRED`, `MIN_BOUND`, `MAX_BOUND`) VALUES
(301, 'Email address for carbon copy', 'SMTP_CC', 'jwilmoth@kioskcheckoutsystem.com', 'email', 0, NULL, NULL),
(300, 'Name for carbon copy', 'SMTP_CC_NAME', 'JWilmoth', 'string', 0, NULL, NULL),
(303, 'Email to appear in FROM field', 'SMTP_FROM', 'jwilmoth@kioskcheckoutsystem.com', 'email', 0, NULL, NULL),
(302, 'Name to appear in FROM field', 'SMTP_FROM_NAME', 'JWilmoth', 'string', 0, NULL, NULL),
(305, 'Mail server port', 'SMTP_PORT', '25', 'integer', 0, NULL, NULL),
(304, 'Mail server address', 'SMTP_SERVER', 'mail.koiskcheckoutsystem.com', 'string', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Categories`
--

CREATE TABLE IF NOT EXISTS `Categories` (
  `CATEGORY` varchar(45) NOT NULL,
  `DESCRIPTION` tinytext,
  `RANKING` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `EXPANDED` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`CATEGORY`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Categories`
--

INSERT INTO `Categories` (`CATEGORY`, `DESCRIPTION`, `RANKING`, `EXPANDED`) VALUES
('Category A', 'Test', 999, 0),
('Category B', 'Test 2', 999, 1),
('Category C', 'Test3', 999, 0);

-- --------------------------------------------------------

--
-- Table structure for table `History`
--

CREATE TABLE IF NOT EXISTS `History` (
  `DATE` date NOT NULL,
  `TIME` time NOT NULL,
  `NAME` varchar(45) NOT NULL,
  `EMAIL` varchar(45) NOT NULL,
  `OPTION` varchar(45) NOT NULL,
  `CATEGORY` varchar(45) NOT NULL,
  `ITEM` varchar(45) NOT NULL,
  `HISTORY` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`DATE`,`TIME`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Items`
--

CREATE TABLE IF NOT EXISTS `Items` (
  `CATEGORY_FK` varchar(45) NOT NULL,
  `ITEM` varchar(45) NOT NULL,
  `DESCRIPTION` tinytext,
  `USER_EMAIL_FK` varchar(45) DEFAULT NULL,
  `DATE_OUT` date DEFAULT NULL,
  `HOLD` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`CATEGORY_FK`,`ITEM`),
  KEY `I_USER_EMAIL_FK_idx` (`USER_EMAIL_FK`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Items_Tags`
--

CREATE TABLE IF NOT EXISTS `Items_Tags` (
  `CATEGORY_FK` varchar(45) NOT NULL,
  `ITEM_FK` varchar(45) NOT NULL,
  `TAG_FK` varchar(35) NOT NULL,
  `TAG_OPTION` varchar(35) NOT NULL,
  PRIMARY KEY (`CATEGORY_FK`,`ITEM_FK`,`TAG_FK`),
  KEY `IT_TAGTAGOPTION_FK_idx` (`TAG_FK`,`TAG_OPTION`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Options`
--

CREATE TABLE IF NOT EXISTS `Options` (
  `OPTION` varchar(45) NOT NULL,
  `PATH` varchar(360) NOT NULL,
  `IMAGE` varchar(256) NOT NULL,
  `IMAGEHOVER` varchar(256) NOT NULL,
  `IMAGE_ALT` varchar(256) NOT NULL,
  `IMAGEHOVER_ALT` varchar(256) NOT NULL,
  PRIMARY KEY (`OPTION`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Options`
--

INSERT INTO `Options` (`OPTION`, `PATH`, `IMAGE`, `IMAGEHOVER`, `IMAGE_ALT`, `IMAGEHOVER_ALT`) VALUES
('Check In', 'option-checkin.php', '../images/CheckInSmHover.png', '../images/CheckInSm.png', '../images/CheckInSmHover.png', '../images/CheckInSm.png'),
('Check Out', 'option-checkout.php', '../images/CheckOutSmHover.png', '../images/CheckOutSm.png', '../images/CheckOutHover.png', '../images/CheckOut.png');

-- --------------------------------------------------------

--
-- Table structure for table `Tags`
--

CREATE TABLE IF NOT EXISTS `Tags` (
  `TAG` varchar(35) NOT NULL,
  PRIMARY KEY (`TAG`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Tags_Options`
--

CREATE TABLE IF NOT EXISTS `Tags_Options` (
  `TAG_FK` varchar(35) NOT NULL,
  `TAG_OPTION` varchar(35) NOT NULL,
  PRIMARY KEY (`TAG_FK`,`TAG_OPTION`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `NAME` varchar(25) NOT NULL,
  `EMAIL` varchar(45) NOT NULL,
  `PIN` char(4) NOT NULL DEFAULT '1234',
  `ADMIN` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `TIMEOUT` datetime DEFAULT NULL,
  `HIDE` tinyint(1) NOT NULL DEFAULT '0',
  `RANKING` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`EMAIL`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`NAME`, `EMAIL`, `PIN`, `ADMIN`, `TIMEOUT`, `HIDE`, `RANKING`) VALUES
('Admin', 'admin@kioskcheckoutsystem.com', '2007', 1, '2017-03-18 11:01:53', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `User_Options`
--

CREATE TABLE IF NOT EXISTS `User_Options` (
  `EMAIL_FK` varchar(45) NOT NULL,
  `OPTION_FK` varchar(45) NOT NULL,
  `CATEGORY_FK` varchar(45) NOT NULL,
  `ITEM_FK` varchar(45) NOT NULL,
  PRIMARY KEY (`EMAIL_FK`,`OPTION_FK`,`CATEGORY_FK`,`ITEM_FK`),
  KEY `U_O_OPTION_FK_idx` (`OPTION_FK`),
  KEY `U_O_ITEM_FK_idx` (`ITEM_FK`),
  KEY `U_O_CATEGORY_FK_idx` (`CATEGORY_FK`,`ITEM_FK`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `User_Options`
--

INSERT INTO `User_Options` (`EMAIL_FK`, `OPTION_FK`, `CATEGORY_FK`, `ITEM_FK`) VALUES
('admin@kioskcheckoutsystem.com', 'Check Out', '', '');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Items`
--
ALTER TABLE `Items`
  ADD CONSTRAINT `USER_EMAIL_FK` FOREIGN KEY (`USER_EMAIL_FK`) REFERENCES `Users` (`EMAIL`) ON UPDATE CASCADE;

--
-- Constraints for table `User_Options`
--
ALTER TABLE `User_Options`
  ADD CONSTRAINT `UO_EMAIL_FK` FOREIGN KEY (`EMAIL_FK`) REFERENCES `Users` (`EMAIL`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
