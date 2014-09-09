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
-- Table structure for table `ml_tarotspread`
--

DROP TABLE IF EXISTS `ml_tarotspread`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ml_tarotspread` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `totalcards` int(11) DEFAULT NULL,
  `summary` varchar(250) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `question` varchar(250) DEFAULT NULL,
  `description` text,
  `image` varchar(250) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `visitorcount` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ml_tarotspread`
--

LOCK TABLES `ml_tarotspread` WRITE;
/*!40000 ALTER TABLE `ml_tarotspread` DISABLE KEYS */;
INSERT INTO `ml_tarotspread` VALUES (1,'Ankh',9,'Oorzaken, achtergronden, perspectieven',4,'Wat is de oorzaak/reden van mijn problemen en wat zijn mijn vooruitzichten?','Gebaseerd op het legpatroon de \"Ankh\" uit het oude Egypte.','Ankh.gif',310,19464),(2,'Astrologische Kring',12,'Uitgebreide beschrijving van het heden en andere perspectieven',4,'Waar sta ik?','','AstrologischeKring.gif',736,19020),(3,'Beslissingsspel',7,'Kiezen uit twee mogelijkheden, hulp bij nemen van beslissingen',2,'Wat moet ik besluiten?','Verschaft duidelijkheid inzake te nemen beslissingen.','Beslissingsspel.gif',418,40942),(4,'Blinde Vlek',4,'Zelfkennis',3,'Geen vragen nodig.','Informatie over hoe onze waarneming van onszelf verschilt van de manier waarop anderen ons zien.','BlindeVlek.gif',224,46267),(5,'Blokkade',5,'Dingen die dwars zitten',2,'Wat zit mij in de weg?','','Blokkade.gif',471,39779),(6,'Dagkaart',1,'De tendens van de dag',0,'Hoe sta ik er vandaag voor?','','Dagkaart.gif',97,167964),(7,'Dagkaarten',2,'De tendens van de dag',0,'Hoe staat mijn dag ervoor?','','Dagkaarten.gif',204,38607),(8,'De Deur',11,'Beeldende beschrijving van een drempel die moet worden overschreden',4,'Wat staat mij te wachten?','','Deur.gif',485,26297),(9,'De Volgende Stap',4,'Wat vervolgens te doen',1,'Wat is mijn volgende Stap?','','Tendens&Perspectieven.gif',291,95843),(10,'De Weg',7,'De Beste handelswijze om een doel te bereiken',3,'Hoe moet ik me gedragen? Wat kan ik doen?','','Weg.gif',351,66833),(11,'Differentiatie / De Moedige Sprong',8,'De Moedige Sprong Daden',1,'Hoe kom ik verder?','','Differentiatie.gif',525,15696),(12,'Droomdoel',6,'Doelen',1,'Wat moet ik doen om mijn doel te bereiken?','','Droomdoel.gif',351,24335),(13,'Geheim van de Hogepriesteres',9,'Het verloop van een proces en de verborgen betekenis ervan',3,'Hoe ontwikkeld mijn plan zich? Hoe gaat het verder?','Gebaseerd op kaart De Hogepriesteres van de Raider-Waite. \r\nGeeft informatie over de verwachten trend/achtergrond. Kan tevens antwoord geven op vragen die met \"Waarom\" beginnen.','Hogepriesteres.gif',364,83532),(14,'Geven en Nemen',14,'Geven en Nemen in Relatie',1,'Wat heb ik nodig?','','GevenEnNemen.gif',739,32546),(15,'Gevolgen',7,'Gevolgen',3,'Wat is het gevolg van de Situatie waarin ik me op dit moment in bevind?','','Gevolgen.gif',311,21906),(16,'Graal',7,'Accepteren van gebeurtenissen',2,'Wat moet ik doen om mijn verleden te accepteren?','','Graal.gif',525,19132),(17,'Hexagramlegging',7,'Positieve en negatieve invloeden',1,'Welke positieve en negatieve invloeden hebben invloed op mij?','','Hexagramlegging.gif',311,9710),(18,'Inventaris',9,'Vragen over Verleden - Heden - Toekomst',1,'Vragen over verleden, heden en toekomst','','Inventaris.gif',351,44081),(19,'Jaarkaart',1,'Themiek van komende jaar',0,'Hoe sta ik er dit jaar voor?','','Dagkaart.gif',97,64408),(20,'Jung',7,'Openbaring van de Vader, Moeder en Kind in ons',2,'Wat willen we in dit leven bereiken?','','Jung.gif',418,6261),(21,'Keltisch Kruis / Zonnekruis',10,'Voor alle vragen geschikt',2,'Geschikt voor iedere vraagstelling. Algemeen.','Keltisch Kruis is een universeel legpatroon dat geschikt is voor elke vraagsteling.','KeltischKruis.gif',578,163235),(22,'Keuzevragen',5,'Keuze die genomen moet worden',2,'Wat zijn de positieve en negatieve punten bij mijn keuze?','','Keuzevragen.gif',291,17631),(23,'Levensboom / Kabbala',10,'Situatie vertaalt vanuit de Kabbala',3,'Geschikt voor iedere vraagstelling. Algemeen.','Dit legpatroon wordt vertaald met behulp van de leer van de Kabbala.','Levensboom.gif',311,9755),(24,'Numerologie',9,'Situatie bekeken vanuit de Numerologie',3,'Nvt','Geeft een overzicht van de Situatie bekeken vanuit de Numerologie.','Numerologie.gif',525,11043),(25,'Partnerspel',6,'Hoe partners tegenover elkaar staan',1,'Hoe staat mijn relatie ervoor?','Geeft informatie over een relatie hoe twee mensen ten opzichte van elkaar staan. Beide partners trekken tegelijkertijd elk 3 kaarten uit de Grote Arcana','Partnerspel.gif',291,58481),(26,'Pentagram',5,'Vragen over uitgangsposities',1,'Waar wil ik naartoe?','','Pentagram.gif',679,6066),(27,'Piramide',7,'Overwinnen van situaties',2,'Wat moet ik doen aan de situatie?','','Piramide.gif',418,9716),(28,'Planningsspel',5,'De beste aanpak om een doel te bereiken',1,'Hoe bereik ik mijn doel?','','Planningsspel.gif',291,12525),(29,'Pro en Contra',2,'Hulp om snel te beslissen',1,'Wat zijn de voor- en nadelen?','','Dagkaarten.gif',204,36005),(30,'Relatiespel',7,'De actuele relatie tussen twee mensen',2,'Hoe staat mijn relatie ervoor?','Geeft informatie over een relatie hoe twee mensen ten opzichte van elkaar staan.','Relatiespel.gif',311,195133),(31,'Ronde Legging',13,'Themiek van het jaar',3,'Hoe sta ik er dit jaar voor?','','RondeLegging.gif',739,26708),(32,'Simpele Trekking',3,'Vragen over Verleden - Heden - Toekomst',0,'Vragen over verleden, heden en toekomst','','SimpeleTrekking.gif',311,79411),(33,'Ster',5,'Positie ten opzichte van sterke en zwakke punten',1,'Wat zijn mijn sterke en zwakke punten?','','Ster.gif',351,7732),(34,'Tarotmagie',2,'Bewuste en onbewuste instellingen',1,'Hoe relateren mijn bewuste- en onbewuste instelling met elkaar?','','Tarotmagie.gif',204,25083),(35,'Tendens en Perspectieven / Kruis',4,'Beoordeling van een situatie, voorstel en prognose',1,'Hoe moet ik mij gedragen?','','Tendens&Perspectieven.gif',291,41279),(36,'Toppunt van Geluk',6,'Geluk en Keuzes',1,'Wordt ik gelukkig als ik deze keuze neem?','','ToppuntGeluk.gif',311,39874),(37,'Toverspreuk van de Zigeuner',7,'Levensverwachtingen',2,'Hoe staat mijn leven ervoor?','','ToverspreukZigeuner.gif',739,44056),(38,'Trend',4,'Vragen over trend',1,'Wat kan ik en wat wil ik?','','Trend.gif',418,9953),(39,'Uitgebreid Keltisch Kruis / Uitgebreid Zonnekruis',13,'Voor alle vragen geschikt',3,'Geschikt voor iedere vraagstelling. Algemeen.','Keltisch Kruis is een universeel legpatroon dat geschikt is voor elke vraagsteling.','UitgebreidKeltischKruis.gif',739,39911),(40,'Uitgebreide Dagkaart',3,'De tendens van de dag',0,'Hoe staat mijn dag ervoor?','','UitgebreideDagkaart.gif',311,118268),(41,'Vervulling van wensen',5,'Wensen',1,'Wat moet ik doen om mijn wens uit te laten komen?','','VervullingWensen.gif',525,50389),(42,'Weg van de Oplossing',9,'Oplossingen',3,'Hoe vind ik de oplossing?','','WegOplossing.gif',311,13640),(43,'Werkcyclus',9,'Werk',2,'Wat verwacht ik van mijn werk?','','Werkcyclus.gif',739,17733),(44,'Zwaard',7,'Overtuigingskracht/\r\nUitganspositie',3,'Wat is mijn uitgangspunt?','','Zwaard.gif',505,5853);
/*!40000 ALTER TABLE `ml_tarotspread` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-09-09 21:38:04
