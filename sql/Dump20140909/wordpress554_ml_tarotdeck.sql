CREATE DATABASE  IF NOT EXISTS `wordpress554` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `wordpress554`;
-- MySQL dump 10.13  Distrib 5.6.17, for Win32 (x86)
--
-- Host: localhost    Database: wordpress554
-- ------------------------------------------------------
-- Server version	5.1.72-community

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
-- Table structure for table `ml_tarotdeck`
--

DROP TABLE IF EXISTS `ml_tarotdeck`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ml_tarotdeck` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `imageback` varchar(255) DEFAULT 'Back of tarotcards',
  `description` text,
  `isbn` varchar(20) DEFAULT NULL,
  `imagesfolder` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ml_tarotdeck`
--

LOCK TABLES `ml_tarotdeck` WRITE;
/*!40000 ALTER TABLE `ml_tarotdeck` DISABLE KEYS */;
INSERT INTO `ml_tarotdeck` VALUES (1,'Haindl Tarot','Hermann Haindl','','','0880794658','haindl'),(2,'Celtic Dragon Tarot','D.J. Conway & Lisa Hunt','','','1567181821','celtdragon'),(3,'Crowley Tarot','Aleister Crowley & Lady Frieda Harris','crowley_back.jpg','','9073140285','crowley'),(4,'Fairy Tarots','Antonio Lupatelli','','','0738700061','fairytarots'),(5,'H.R. Giger Tarot','Akron & H.R. Giger','','','3822858501','giger'),(6,'Osho Zen Tarot','Ma Deva Padma (Susan Morgan)','','','0752216384','osho'),(7,'Rider-Waite Tarot','Arhur E. Waite & Pamela Smith','waite_back.jpg','','907314017X','waite'),(8,'Russian Tarot of St. Petersburg','Yury Shakov','','','0880791969','russian'),(9,'Sacred Circle Tarot','Anna Franklin & Paul Mason','','','156718457X','sacrcircle'),(10,'Dragon Tarot','Peter Pracownik','','','0880791829','dragon'),(11,'Adrian Tarot','Adrian B. Koehli','','','1572810564','adrian'),(12,'Gendron Tarot','Melanie Gendron','','','1572810653','gendron'),(13,'Tarot of a Moon Garden','Karen Marie Sweikhardt','','','1572810955','moongarden'),(14,'Art Nouveau Tarot','Matt Myers','','','0880793759','artnouveau'),(15,'Tarok van de Herleving','Onbekend','','','','herleving'),(16,'The Arthurian Tarot','Anna-Marie Ferguson','','','','arthurian'),(17,'The Norse Tarot','Clive Barrett','','','','norse'),(18,'The Pythagorean Tarot','John Opsopaus','','','1567184499','pyth'),(19,'Waking of the Wild Spirit Tarot','Poppy Palin','','','','wildspirit'),(20,'Fey Tarot','Riccardo Minetti en Mara Aghem','','','0738702803','fey'),(21,'The Tarot of Transformation','Willow Arlenea en Jasmin Lee Cori','','','1578632390','transformation'),(22,'Cosmic Tarot','Norbert Losche','','','0880791837','cosmic'),(23,'Herbal Tarot','Michael Tierra & Candis Cantin','herbal_back.jpg','','0880793325','herbal'),(24,'De Tarot van de Druïden','Philip en Stephanie Carr-Gomm','druiden_back.jpg','','9069636360','druiden'),(25,'Whimsical Tarot','Mary Hanson-Roberts en Dorothy Morrison','whimsical_back.jpg','','1572812532','whimsical'),(26,'The Gilded Tarot','Ciro Marchetti en Barbara Moore','gilded_back.jpg','','0738705209','gilded'),(27,'Shapeshifter Tarot','D.J.Conway en Sirona Knight','shapeshifter_back.jpg','','1567183840','shapeshifter'),(28,'Röhrig Tarot','Carl W. Rohrig','rohrig_back.jpg','','1885394','rohrig'),(29,'Tarot of Dreams','Ciro Marchetti','','','','tarotofdreams');
/*!40000 ALTER TABLE `ml_tarotdeck` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-09-09 21:38:00
