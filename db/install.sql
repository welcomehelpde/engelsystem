-- MySQL dump 10.13  Distrib 5.5.44, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: engelsysdb
-- ------------------------------------------------------
-- Server version	5.5.44-0ubuntu0.14.04.1

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
-- Table structure for table `AngelTypes`
--

DROP TABLE IF EXISTS `AngelTypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AngelTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `restricted` int(1) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AngelTypes`
--

LOCK TABLES `AngelTypes` WRITE;
/*!40000 ALTER TABLE `AngelTypes` DISABLE KEYS */;
INSERT INTO `AngelTypes` VALUES (3,'12332',0,'1e2e21e1');
/*!40000 ALTER TABLE `AngelTypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `GroupPrivileges`
--

DROP TABLE IF EXISTS `GroupPrivileges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GroupPrivileges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `privilege_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`,`privilege_id`),
  KEY `privilege_id` (`privilege_id`),
  CONSTRAINT `groupprivileges_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `Groups` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `groupprivileges_ibfk_2` FOREIGN KEY (`privilege_id`) REFERENCES `Privileges` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=263 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `GroupPrivileges`
--

LOCK TABLES `GroupPrivileges` WRITE;
/*!40000 ALTER TABLE `GroupPrivileges` DISABLE KEYS */;
INSERT INTO `GroupPrivileges` VALUES (85,-7,10),(87,-7,18),(86,-7,21),(216,-6,5),(212,-6,6),(207,-6,7),(211,-6,12),(208,-6,13),(210,-6,14),(214,-6,16),(209,-6,21),(213,-6,28),(206,-6,31),(215,-6,33),(257,-6,38),(219,-5,14),(221,-5,25),(220,-5,33),(241,-4,5),(238,-4,14),(240,-4,16),(237,-4,19),(242,-4,25),(235,-4,27),(239,-4,28),(236,-4,32),(258,-3,31),(247,-2,3),(246,-2,4),(255,-2,8),(252,-2,9),(254,-2,11),(248,-2,15),(251,-2,17),(256,-2,24),(253,-2,26),(245,-2,30),(244,-2,34),(249,-2,35),(243,-2,36),(250,-2,37),(88,-1,1),(23,-1,2),(24,-1,5),(260,-1,39),(261,-1,40),(262,-1,41);
/*!40000 ALTER TABLE `GroupPrivileges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Groups`
--

DROP TABLE IF EXISTS `Groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Groups` (
  `Name` varchar(35) NOT NULL,
  `UID` int(11) NOT NULL,
  PRIMARY KEY (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Groups`
--

LOCK TABLES `Groups` WRITE;
/*!40000 ALTER TABLE `Groups` DISABLE KEYS */;
INSERT INTO `Groups` VALUES ('6-Developer und Admins',-7),('5-Leitung',-6),('4-Teamleitung',-5),('3-Schichtleitung',-4),('-USER GESPERRT-',-3),('2-Helfer',-2),('1-Gast',-1);
/*!40000 ALTER TABLE `Groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LogEntries`
--

DROP TABLE IF EXISTS `LogEntries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LogEntries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL,
  `nick` text NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LogEntries`
--

LOCK TABLES `LogEntries` WRITE;
/*!40000 ALTER TABLE `LogEntries` DISABLE KEYS */;
INSERT INTO `LogEntries` VALUES (1,1445514185,'<a class=\"\" href=\"?p=users&amp;action=view&amp;user_id=1\"><span class=\"icon-icon_angel\"></span> admin</a>','Updated user: admin, XL, available: 1, active: 1, tshirt: 1'),(2,1445518391,'<a class=\"\" href=\"?p=users&amp;action=view&amp;user_id=1\"><span class=\"icon-icon_angel\"></span> admin</a>','Location created: hier, pentabarf import: , public: Y, number: 123'),(3,1445518391,'<a class=\"\" href=\"?p=users&amp;action=view&amp;user_id=1\"><span class=\"icon-icon_angel\"></span> admin</a>','Set needed angeltypes of location hier to: '),(4,1445518415,'<a class=\"\" href=\"?p=users&amp;action=view&amp;user_id=1\"><span class=\"icon-icon_angel\"></span> admin</a>','Shift created: Schichttyp1 with title  from 2015-10-22 00:00 to 2015-11-22 00:00'),(5,1445518415,'<a class=\"\" href=\"?p=users&amp;action=view&amp;user_id=1\"><span class=\"icon-icon_angel\"></span> admin</a>','Shift needs following angel types: '),(6,1445518499,'<a class=\"\" href=\"?p=users&amp;action=view&amp;user_id=1\"><span class=\"icon-icon_angel\"></span> admin</a>','Created angeltype: 12332, restricted: 0');
/*!40000 ALTER TABLE `LogEntries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Messages`
--

DROP TABLE IF EXISTS `Messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Datum` int(11) NOT NULL,
  `SUID` int(11) NOT NULL DEFAULT '0',
  `RUID` int(11) NOT NULL DEFAULT '0',
  `isRead` char(1) NOT NULL DEFAULT 'N',
  `Text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Datum` (`Datum`),
  KEY `SUID` (`SUID`),
  KEY `RUID` (`RUID`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`SUID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`RUID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Fuers interen Communikationssystem';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Messages`
--

LOCK TABLES `Messages` WRITE;
/*!40000 ALTER TABLE `Messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `Messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `NeededAngelTypes`
--

DROP TABLE IF EXISTS `NeededAngelTypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NeededAngelTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `angel_type_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`,`angel_type_id`),
  KEY `shift_id` (`shift_id`),
  KEY `angel_type_id` (`angel_type_id`),
  CONSTRAINT `neededangeltypes_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `Room` (`RID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `neededangeltypes_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `Shifts` (`SID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `neededangeltypes_ibfk_3` FOREIGN KEY (`angel_type_id`) REFERENCES `AngelTypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `NeededAngelTypes`
--

LOCK TABLES `NeededAngelTypes` WRITE;
/*!40000 ALTER TABLE `NeededAngelTypes` DISABLE KEYS */;
/*!40000 ALTER TABLE `NeededAngelTypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `News`
--

DROP TABLE IF EXISTS `News`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `News` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Datum` int(11) NOT NULL,
  `Betreff` varchar(150) NOT NULL DEFAULT '',
  `Text` text NOT NULL,
  `UID` int(11) NOT NULL DEFAULT '0',
  `Treffen` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `UID` (`UID`),
  CONSTRAINT `news_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `News`
--

LOCK TABLES `News` WRITE;
/*!40000 ALTER TABLE `News` DISABLE KEYS */;
/*!40000 ALTER TABLE `News` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `NewsComments`
--

DROP TABLE IF EXISTS `NewsComments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NewsComments` (
  `ID` bigint(11) NOT NULL AUTO_INCREMENT,
  `Refid` int(11) NOT NULL DEFAULT '0',
  `Datum` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Text` text NOT NULL,
  `UID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `Refid` (`Refid`),
  KEY `UID` (`UID`),
  CONSTRAINT `newscomments_ibfk_1` FOREIGN KEY (`Refid`) REFERENCES `News` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `newscomments_ibfk_2` FOREIGN KEY (`UID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `NewsComments`
--

LOCK TABLES `NewsComments` WRITE;
/*!40000 ALTER TABLE `NewsComments` DISABLE KEYS */;
/*!40000 ALTER TABLE `NewsComments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Privileges`
--

DROP TABLE IF EXISTS `Privileges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Privileges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `desc` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Privileges`
--

LOCK TABLES `Privileges` WRITE;
/*!40000 ALTER TABLE `Privileges` DISABLE KEYS */;
INSERT INTO `Privileges` VALUES (1,'start','Startseite für Gäste/Nicht eingeloggte User'),(2,'login','Logindialog'),(3,'news','Anzeigen der News-Seite'),(4,'logout','User darf sich ausloggen'),(5,'register','Einen neuen Engel registerieren'),(6,'admin_rooms','Orte administrieren'),(7,'admin_angel_types','Engel Typen administrieren'),(8,'user_settings','User profile settings'),(9,'user_messages','Writing and reading messages from user to user'),(10,'admin_groups','Manage usergroups and their rights'),(11,'user_questions','Let users ask questions'),(12,'admin_questions','Answer user\'s questions'),(13,'admin_faq','Edit FAQs'),(14,'admin_news','Administrate the news section'),(15,'news_comments','User can comment news'),(16,'admin_user','Administrate the angels'),(17,'user_meetings','Lists meetings (news)'),(18,'admin_language','Translate the system'),(19,'admin_log','Display recent changes'),(20,'user_wakeup','User wakeup-service organization'),(21,'admin_import','Import locations and shifts from pentabarf'),(22,'credits','View credits'),(23,'faq','View FAQ'),(24,'user_shifts','Signup for shifts'),(25,'user_shifts_admin','Signup other angels for shifts.'),(26,'user_myshifts','Allow angels to view their own shifts and cancel them.'),(27,'admin_arrive','Mark angels when they are available.'),(28,'admin_shifts','Create shifts'),(30,'ical','iCal shift export'),(31,'admin_active','Mark angels as active and if they got a t-shirt.'),(32,'admin_free','Show a list of free/unemployed angels.'),(33,'admin_user_angeltypes','Confirm restricted angel types'),(34,'atom',' Atom news export'),(35,'shifts_json_export','Export shifts in JSON format'),(36,'angeltypes','View angeltypes'),(37,'user_angeltypes','Join angeltypes.'),(38,'shifttypes','Administrate shift types'),(39,'faq2','View FAQ'),(40,'imprint','View imprint'),(41,'privacy','View privacy statement');
/*!40000 ALTER TABLE `Privileges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Questions`
--

DROP TABLE IF EXISTS `Questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Questions` (
  `QID` bigint(20) NOT NULL AUTO_INCREMENT,
  `UID` int(11) NOT NULL DEFAULT '0',
  `Question` text NOT NULL,
  `AID` int(11) DEFAULT NULL,
  `Answer` text,
  PRIMARY KEY (`QID`),
  KEY `UID` (`UID`),
  KEY `AID` (`AID`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`AID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Fragen und Antworten';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Questions`
--

LOCK TABLES `Questions` WRITE;
/*!40000 ALTER TABLE `Questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `Questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Room`
--

DROP TABLE IF EXISTS `Room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Room` (
  `RID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(35) NOT NULL DEFAULT '',
  `Location` varchar(50) NOT NULL DEFAULT '',
  `Lat` varchar(50) NOT NULL DEFAULT '',
  `Long` varchar(50) NOT NULL DEFAULT '',
  `FromPentabarf` char(1) NOT NULL DEFAULT 'N',
  `show` char(1) NOT NULL DEFAULT 'Y',
  `Number` int(11) DEFAULT NULL,
  PRIMARY KEY (`RID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Room`
--

LOCK TABLES `Room` WRITE;
/*!40000 ALTER TABLE `Room` DISABLE KEYS */;
INSERT INTO `Room` VALUES (6,'hier','dort','','','N','Y',0);
/*!40000 ALTER TABLE `Room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ShiftEntry`
--

DROP TABLE IF EXISTS `ShiftEntry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ShiftEntry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SID` int(11) NOT NULL DEFAULT '0',
  `TID` int(11) NOT NULL DEFAULT '0',
  `UID` int(11) NOT NULL DEFAULT '0',
  `Comment` text,
  `freeload_comment` text,
  `freeloaded` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `TID` (`TID`),
  KEY `UID` (`UID`),
  KEY `SID` (`SID`,`TID`),
  KEY `freeloaded` (`freeloaded`),
  CONSTRAINT `shiftentry_ibfk_1` FOREIGN KEY (`SID`) REFERENCES `Shifts` (`SID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shiftentry_ibfk_2` FOREIGN KEY (`UID`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shiftentry_ibfk_3` FOREIGN KEY (`TID`) REFERENCES `AngelTypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ShiftEntry`
--

LOCK TABLES `ShiftEntry` WRITE;
/*!40000 ALTER TABLE `ShiftEntry` DISABLE KEYS */;
/*!40000 ALTER TABLE `ShiftEntry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ShiftTypes`
--

DROP TABLE IF EXISTS `ShiftTypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ShiftTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `angeltype_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `angeltype_id` (`angeltype_id`),
  CONSTRAINT `shifttypes_ibfk_1` FOREIGN KEY (`angeltype_id`) REFERENCES `AngelTypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ShiftTypes`
--

LOCK TABLES `ShiftTypes` WRITE;
/*!40000 ALTER TABLE `ShiftTypes` DISABLE KEYS */;
INSERT INTO `ShiftTypes` VALUES (4,'Schichttyp1',NULL,'');
/*!40000 ALTER TABLE `ShiftTypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Shifts`
--

DROP TABLE IF EXISTS `Shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Shifts` (
  `SID` int(11) NOT NULL AUTO_INCREMENT,
  `title` text,
  `shifttype_id` int(11) NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `RID` int(11) NOT NULL DEFAULT '0',
  `URL` text,
  `PSID` int(11) DEFAULT NULL,
  `created_by_user_id` int(11) DEFAULT NULL,
  `created_at_timestamp` int(11) NOT NULL,
  `edited_by_user_id` int(11) DEFAULT NULL,
  `edited_at_timestamp` int(11) NOT NULL,
  PRIMARY KEY (`SID`),
  UNIQUE KEY `PSID` (`PSID`),
  KEY `RID` (`RID`),
  KEY `shifttype_id` (`shifttype_id`),
  KEY `created_by_user_id` (`created_by_user_id`),
  KEY `edited_by_user_id` (`edited_by_user_id`),
  CONSTRAINT `shifts_ibfk_1` FOREIGN KEY (`RID`) REFERENCES `Room` (`RID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shifts_ibfk_2` FOREIGN KEY (`shifttype_id`) REFERENCES `ShiftTypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shifts_ibfk_3` FOREIGN KEY (`created_by_user_id`) REFERENCES `User` (`UID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `shifts_ibfk_4` FOREIGN KEY (`edited_by_user_id`) REFERENCES `User` (`UID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Shifts`
--

LOCK TABLES `Shifts` WRITE;
/*!40000 ALTER TABLE `Shifts` DISABLE KEYS */;
INSERT INTO `Shifts` VALUES (133,NULL,4,1445464800,1448146800,6,NULL,NULL,1,1445518415,NULL,0);
/*!40000 ALTER TABLE `Shifts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `UID` int(11) NOT NULL AUTO_INCREMENT,
  `Nick` varchar(23) NOT NULL DEFAULT '',
  `Name` varchar(23) DEFAULT NULL,
  `Vorname` varchar(23) DEFAULT NULL,
  `Alter` int(4) DEFAULT NULL,
  `gender` enum('male','female','none') DEFAULT 'none',
  `Telefon` varchar(40) DEFAULT NULL,
  `DECT` varchar(5) DEFAULT NULL,
  `Handy` varchar(40) DEFAULT NULL,
  `email` varchar(123) DEFAULT NULL,
  `email_shiftinfo` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'User wants to be informed by mail about changes in his shifts',
  `jabber` varchar(200) DEFAULT NULL,
  `Size` varchar(4) DEFAULT NULL,
  `Passwort` varchar(128) DEFAULT NULL,
  `password_recovery_token` varchar(32) DEFAULT NULL,
  `Gekommen` tinyint(4) NOT NULL DEFAULT '0',
  `Aktiv` tinyint(4) NOT NULL DEFAULT '0',
  `force_active` tinyint(1) NOT NULL,
  `Tshirt` tinyint(4) DEFAULT '0',
  `color` tinyint(4) DEFAULT '10',
  `Sprache` char(64) NOT NULL,
  `Menu` char(1) NOT NULL DEFAULT 'L',
  `lastLogIn` int(11) NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Art` varchar(30) DEFAULT NULL,
  `kommentar` text,
  `Hometown` varchar(255) NOT NULL DEFAULT '',
  `api_key` varchar(32) NOT NULL,
  `got_voucher` int(11) NOT NULL,
  `arrival_date` int(11) DEFAULT NULL,
  `planned_arrival_date` int(11) NOT NULL,
  `planned_departure_date` int(11) DEFAULT NULL,
  `mailaddress_verification_token` varchar(32) DEFAULT NULL,
  `user_account_approved` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`UID`),
  UNIQUE KEY `Nick` (`Nick`),
  KEY `api_key` (`api_key`),
  KEY `password_recovery_token` (`password_recovery_token`),
  KEY `force_active` (`force_active`),
  KEY `arrival_date` (`arrival_date`,`planned_arrival_date`),
  KEY `planned_departure_date` (`planned_departure_date`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (1,'admin','Gates','Bill',42,'none','','-','','admin@example.com',1,'','XL','$6$rounds=5000$hjXbIhoRTH3vKiRa$Wl2P2iI5T9iRR.HHu/YFHswBW0WVn0yxCfCiX0Keco9OdIoDK6bIAADswP6KvMCJSwTGdV8PgA8g8Xfw5l8BD1',NULL,1,1,0,1,2,'de_DE.UTF-8','L',1445518535,'0000-00-00 00:00:00','','','','038850abdd1feb264406be3ffa746235',3,1439490478,1436964455,1440161255,'',1);
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `UserAngelTypes`
--

DROP TABLE IF EXISTS `UserAngelTypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UserAngelTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `angeltype_id` int(11) NOT NULL,
  `confirm_user_id` int(11) DEFAULT NULL,
  `coordinator` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`angeltype_id`,`confirm_user_id`),
  KEY `angeltype_id` (`angeltype_id`),
  KEY `confirm_user_id` (`confirm_user_id`),
  KEY `coordinator` (`coordinator`),
  CONSTRAINT `userangeltypes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `userangeltypes_ibfk_2` FOREIGN KEY (`angeltype_id`) REFERENCES `AngelTypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `userangeltypes_ibfk_3` FOREIGN KEY (`confirm_user_id`) REFERENCES `User` (`UID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UserAngelTypes`
--

LOCK TABLES `UserAngelTypes` WRITE;
/*!40000 ALTER TABLE `UserAngelTypes` DISABLE KEYS */;
/*!40000 ALTER TABLE `UserAngelTypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `UserGroups`
--

DROP TABLE IF EXISTS `UserGroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UserGroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`group_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `usergroups_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `Groups` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `usergroups_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `User` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UserGroups`
--

LOCK TABLES `UserGroups` WRITE;
/*!40000 ALTER TABLE `UserGroups` DISABLE KEYS */;
INSERT INTO `UserGroups` VALUES (3,1,-7),(4,1,-6),(12,1,-5),(2,1,-4),(1,1,-2);
/*!40000 ALTER TABLE `UserGroups` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-10-22 15:34:56
