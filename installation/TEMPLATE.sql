-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: TESTING
-- ------------------------------------------------------
-- Server version	5.5.38-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Admin_Categories`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Admin_Categories` (
  `CATEGORY` varchar(45) NOT NULL,
  `DESCRIPTION` varchar(360) DEFAULT NULL,
  `RANKING` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`CATEGORY`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Admin_Categories`
--

LOCK TABLES `Admin_Categories` WRITE;
/*!40000 ALTER TABLE `Admin_Categories` DISABLE KEYS */;
INSERT INTO `Admin_Categories` VALUES ('Categories',NULL,2),('Email',NULL,6),('History',NULL,5),('Items',NULL,3),('Misc',NULL,7),('Tags',NULL,4),('Users',NULL,1);
/*!40000 ALTER TABLE `Admin_Categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Admin_Category_Tasks`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Admin_Category_Tasks` (
  `CATEGORY_FK` varchar(45) NOT NULL,
  `TASK` varchar(45) NOT NULL,
  `PATH` varchar(360) NOT NULL,
  `RANKING` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`CATEGORY_FK`,`TASK`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Admin_Category_Tasks`
--

LOCK TABLES `Admin_Category_Tasks` WRITE;
/*!40000 ALTER TABLE `Admin_Category_Tasks` DISABLE KEYS */;
INSERT INTO `Admin_Category_Tasks` VALUES ('Categories','Create','admin-categories-create.php',0),('Categories','Update/Delete','admin-categories-updatedelete.php',0),('Email','Configure (upcoming feature)','admin-feature-upcoming.php',0),('Email','Send (upcoming feature)','admin-feature-upcoming.php',0),('History','Export (upcoming feature)','admin-feature-upcoming.php',0),('History','View','admin-history-view.php',0),('Items','Create','admin-items-create.php',0),('Items','Update/Delete','admin-items-updatedelete.php',0),('Items','View checked out','admin-items-viewcheckedout.php',0),('Misc','Discontinue service','admin-misc-discoserv.php',3),('Misc','Options','admin-misc-options.php',1),('Misc','Update payment info','admin-feature-upcoming.php',2),('Tags','Create/Update/Delete','admin-tags-createupdatedelete.php',0),('Tags','Manage associations','admin-tags-manageassociations.php',0),('Users','Create','admin-users-create.php',0),('Users','Update/Delete','admin-users-updatedelete.php',0);
/*!40000 ALTER TABLE `Admin_Category_Tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Admin_Misc_Options`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Admin_Misc_Options` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Admin_Misc_Options`
--

LOCK TABLES `Admin_Misc_Options` WRITE;
/*!40000 ALTER TABLE `Admin_Misc_Options` DISABLE KEYS */;
INSERT INTO `Admin_Misc_Options` VALUES (301,'Email address for carbon copy','SMTP_CC','jwilmoth@kioskcheckoutsystem.com','email',0,NULL,NULL),(300,'Name for carbon copy','SMTP_CC_NAME','JWilmoth','string',0,NULL,NULL),(303,'Email to appear in FROM field','SMTP_FROM','jwilmoth@kioskcheckoutsystem.com','email',0,NULL,NULL),(302,'Name to appear in FROM field','SMTP_FROM_NAME','JWilmoth','string',0,NULL,NULL),(305,'Mail server port','SMTP_PORT','25','integer',0,NULL,NULL),(304,'Mail server address','SMTP_SERVER','mail.koiskcheckoutsystem.com','string',0,NULL,NULL);
/*!40000 ALTER TABLE `Admin_Misc_Options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Categories`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Categories` (
  `CATEGORY` varchar(45) NOT NULL,
  `DESCRIPTION` tinytext,
  `RANKING` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `EXPANDED` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`CATEGORY`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Categories`
--

LOCK TABLES `Categories` WRITE;
/*!40000 ALTER TABLE `Categories` DISABLE KEYS */;
INSERT INTO `Categories` VALUES ('Category A','Test',999,0),('Category B','Test 2',999,1),('Category C','Test3',999,0);
/*!40000 ALTER TABLE `Categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `History`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `History` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `History`
--

LOCK TABLES `History` WRITE;
/*!40000 ALTER TABLE `History` DISABLE KEYS */;
/*!40000 ALTER TABLE `History` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Items`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Items` (
  `CATEGORY_FK` varchar(45) NOT NULL,
  `ITEM` varchar(45) NOT NULL,
  `DESCRIPTION` tinytext,
  `USER_EMAIL_FK` varchar(45) DEFAULT NULL,
  `DATE_OUT` date DEFAULT NULL,
  `HOLD` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`CATEGORY_FK`,`ITEM`),
  KEY `I_USER_EMAIL_FK_idx` (`USER_EMAIL_FK`),
  CONSTRAINT `USER_EMAIL_FK` FOREIGN KEY (`USER_EMAIL_FK`) REFERENCES `Users` (`EMAIL`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Items`
--

LOCK TABLES `Items` WRITE;
/*!40000 ALTER TABLE `Items` DISABLE KEYS */;
/*!40000 ALTER TABLE `Items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Items_Tags`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Items_Tags` (
  `CATEGORY_FK` varchar(45) NOT NULL,
  `ITEM_FK` varchar(45) NOT NULL,
  `TAG_FK` varchar(35) NOT NULL,
  `TAG_OPTION` varchar(35) NOT NULL,
  PRIMARY KEY (`CATEGORY_FK`,`ITEM_FK`,`TAG_FK`),
  KEY `IT_TAGTAGOPTION_FK_idx` (`TAG_FK`,`TAG_OPTION`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Items_Tags`
--

LOCK TABLES `Items_Tags` WRITE;
/*!40000 ALTER TABLE `Items_Tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `Items_Tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Options`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Options` (
  `OPTION` varchar(45) NOT NULL,
  `PATH` varchar(360) NOT NULL,
  `IMAGE` varchar(256) NOT NULL,
  `IMAGEHOVER` varchar(256) NOT NULL,
  `IMAGE_ALT` varchar(256) NOT NULL,
  `IMAGEHOVER_ALT` varchar(256) NOT NULL,
  PRIMARY KEY (`OPTION`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Options`
--

LOCK TABLES `Options` WRITE;
/*!40000 ALTER TABLE `Options` DISABLE KEYS */;
INSERT INTO `Options` VALUES ('Check In','option-checkin.php','../images/CheckInSmHover.png','../images/CheckInSm.png','../images/CheckInSmHover.png','../images/CheckInSm.png'),('Check Out','option-checkout.php','../images/CheckOutSmHover.png','../images/CheckOutSm.png','../images/CheckOutHover.png','../images/CheckOut.png');
/*!40000 ALTER TABLE `Options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Tags`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Tags` (
  `TAG` varchar(35) NOT NULL,
  PRIMARY KEY (`TAG`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Tags`
--

LOCK TABLES `Tags` WRITE;
/*!40000 ALTER TABLE `Tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `Tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Tags_Options`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Tags_Options` (
  `TAG_FK` varchar(35) NOT NULL,
  `TAG_OPTION` varchar(35) NOT NULL,
  PRIMARY KEY (`TAG_FK`,`TAG_OPTION`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Tags_Options`
--

LOCK TABLES `Tags_Options` WRITE;
/*!40000 ALTER TABLE `Tags_Options` DISABLE KEYS */;
/*!40000 ALTER TABLE `Tags_Options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User_Options`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User_Options` (
  `EMAIL_FK` varchar(45) NOT NULL,
  `OPTION_FK` varchar(45) NOT NULL,
  `CATEGORY_FK` varchar(45) NOT NULL,
  `ITEM_FK` varchar(45) NOT NULL,
  PRIMARY KEY (`EMAIL_FK`,`OPTION_FK`,`CATEGORY_FK`,`ITEM_FK`),
  KEY `U_O_OPTION_FK_idx` (`OPTION_FK`),
  KEY `U_O_ITEM_FK_idx` (`ITEM_FK`),
  KEY `U_O_CATEGORY_FK_idx` (`CATEGORY_FK`,`ITEM_FK`),
  CONSTRAINT `UO_EMAIL_FK` FOREIGN KEY (`EMAIL_FK`) REFERENCES `Users` (`EMAIL`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User_Options`
--

LOCK TABLES `User_Options` WRITE;
/*!40000 ALTER TABLE `User_Options` DISABLE KEYS */;
INSERT INTO `User_Options` VALUES ('admin@kioskcheckoutsystem.com','Check Out','','');
/*!40000 ALTER TABLE `User_Options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `NAME` varchar(25) NOT NULL,
  `EMAIL` varchar(45) NOT NULL,
  `PIN` char(4) NOT NULL DEFAULT '1234',
  `ADMIN` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `TIMEOUT` datetime DEFAULT NULL,
  `HIDE` tinyint(1) NOT NULL DEFAULT '0',
  `RANKING` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`EMAIL`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES ('Admin','admin@kioskcheckoutsystem.com','2007',1,'2017-01-07 14:03:54',1,0);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-01-21 14:39:37
