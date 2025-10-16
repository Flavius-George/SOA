-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: fs177.cti.ugal.ro    Database: fs177
-- ------------------------------------------------------
-- Server version	8.0.42-0ubuntu0.20.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `persoane`
--

DROP TABLE IF EXISTS `persoane`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persoane` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nume` varchar(100) DEFAULT NULL,
  `prenume` varchar(100) DEFAULT NULL,
  `cetatenie` varchar(50) DEFAULT NULL,
  `tip_document` varchar(50) DEFAULT NULL,
  `nr_document` varchar(50) DEFAULT NULL,
  `data_trecere` date DEFAULT NULL,
  `sens` enum('Intrare','Iesire') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persoane`
--

LOCK TABLES `persoane` WRITE;
/*!40000 ALTER TABLE `persoane` DISABLE KEYS */;
INSERT INTO `persoane` VALUES (1,'Popescu','Ion','TEST','CI','AB123456','2025-05-10','Intrare'),(2,'Ionescu','Maria','RO','Pasaport','XZ987654','2025-05-12','Iesire'),(3,'Smith','John','US','Pasaport','US998877','2025-05-15','Intrare'),(4,'Müller','Anna','DE','Pasaport','DE554433','2025-05-16','Intrare'),(5,'Garcia','Carlos','ES','DNI','ES112233','2025-05-14','Iesire'),(6,'Popa','Alexandru','RO','CI','RO556677','2025-05-13','Intrare'),(7,'Dubois','Sophie','FR','Carte Identite','FR778899','2025-05-11','Iesire'),(8,'Kowalski','Jan','PL','Paszport','PL334455','2025-05-17','Intrare'),(9,'Nakamura','Yuki','JP','Pasaport','JP221144','2025-05-16','Iesire'),(10,'Ivanov','Sergey','RU','Pasport','RU998822','2025-05-15','Intrare'),(12,'Andersson','Emma','SE','Pas','SE778800','2025-05-18','Intrare'),(13,'Nguyen','Thi','VN','Passport','VN334422','2025-05-14','Iesire'),(14,'Brown','James','GB','Passport','GB556600','2025-05-13','Intrare'),(15,'Kim','Min','KR','Pasport','KR112244','2025-05-12','Iesire');
/*!40000 ALTER TABLE `persoane` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-16 18:42:40
