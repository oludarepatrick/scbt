-- MySQL dump 10.13  Distrib 8.0.23, for Linux (x86_64)
--
-- Host: localhost    Database: quizapp
-- ------------------------------------------------------
-- Server version	8.0.23-0ubuntu0.20.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `answers`
--

DROP TABLE IF EXISTS `answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `answers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answers`
--

LOCK TABLES `answers` WRITE;
/*!40000 ALTER TABLE `answers` DISABLE KEYS */;
INSERT INTO `answers` VALUES (9,11,'No',0,'2022-01-27 10:29:43','2022-01-27 10:29:43'),(10,11,'Not sure',0,'2022-01-27 10:29:43','2022-01-27 10:29:43'),(11,11,'True',1,'2022-01-27 10:29:43','2022-01-27 10:29:43'),(12,11,'Not likely',0,'2022-01-27 10:29:43','2022-01-27 10:29:43'),(13,12,'True',1,'2022-01-27 10:30:28','2022-01-27 10:30:28'),(14,12,'False',0,'2022-01-27 10:30:29','2022-01-27 10:30:29'),(15,12,'Maybe',0,'2022-01-27 10:30:29','2022-01-27 10:30:29'),(16,12,'Not really',0,'2022-01-27 10:30:29','2022-01-27 10:30:29'),(25,13,'False',0,'2022-01-27 11:42:22','2022-01-27 11:42:22'),(26,13,'True',1,'2022-01-27 11:42:22','2022-01-27 11:42:22'),(27,13,'Not sure',0,'2022-01-27 11:42:22','2022-01-27 11:42:22'),(28,13,'Probably',0,'2022-01-27 11:42:22','2022-01-27 11:42:22'),(29,14,'Visual studio',0,'2022-01-27 12:48:06','2022-01-27 12:48:06'),(30,14,'Net Beans',1,'2022-01-27 12:48:06','2022-01-27 12:48:06'),(31,14,'Net Rice',0,'2022-01-27 12:48:06','2022-01-27 12:48:06'),(32,14,'Net Food',0,'2022-01-27 12:48:07','2022-01-27 12:48:07'),(33,15,'<p></p>',1,'2022-02-16 06:36:17','2022-02-16 06:36:17'),(34,15,'<></p>',0,'2022-02-16 06:36:18','2022-02-16 06:36:18'),(35,15,'<p>',0,'2022-02-16 06:36:18','2022-02-16 06:36:18'),(36,15,'<pa></p>',0,'2022-02-16 06:36:18','2022-02-16 06:36:18'),(37,16,'<a href=\"new.html\">new page</a>',1,'2022-02-16 06:41:58','2022-02-16 06:41:58'),(38,16,'<href=\"new.html\">Old school</href>',0,'2022-02-16 06:41:59','2022-02-16 06:41:59'),(39,16,'href=\"page.htm>',0,'2022-02-16 06:41:59','2022-02-16 06:41:59'),(40,16,'None of the above',0,'2022-02-16 06:41:59','2022-02-16 06:41:59'),(41,17,'Hyper Test Markup Language',0,'2022-02-16 06:46:12','2022-02-16 06:46:12'),(42,17,'Hyper Text Markup Language',1,'2022-02-16 06:46:13','2022-02-16 06:46:13'),(43,17,'Hypo Text Mark Language',0,'2022-02-16 06:46:13','2022-02-16 06:46:13'),(44,17,'Hyper Marking Language',0,'2022-02-16 06:46:13','2022-02-16 06:46:13'),(45,18,'Visual Studio',0,'2022-02-16 09:04:54','2022-02-16 09:04:54'),(46,18,'Jet Brain',0,'2022-02-16 09:04:54','2022-02-16 09:04:54'),(47,18,'Net Beans',1,'2022-02-16 09:04:55','2022-02-16 09:04:55'),(48,18,'Jet Pilot',0,'2022-02-16 09:04:55','2022-02-16 09:04:55'),(49,19,'False',0,'2022-02-16 09:06:33','2022-02-16 09:06:33'),(50,19,'Maybe',0,'2022-02-16 09:06:33','2022-02-16 09:06:33'),(51,19,'It is a language',0,'2022-02-16 09:06:33','2022-02-16 09:06:33'),(52,19,'True',1,'2022-02-16 09:06:33','2022-02-16 09:06:33'),(53,20,'No',0,'2022-02-16 09:07:27','2022-02-16 09:07:27'),(54,20,'True',1,'2022-02-16 09:07:27','2022-02-16 09:07:27'),(55,20,'Is a type script',0,'2022-02-16 09:07:27','2022-02-16 09:07:27'),(56,20,'Maybe',0,'2022-02-16 09:07:27','2022-02-16 09:07:27'),(57,21,'Microsoft',0,'2022-02-16 09:08:18','2022-02-16 09:08:18'),(58,21,'Oracle',0,'2022-02-16 09:08:18','2022-02-16 09:08:18'),(59,21,'Ifa',0,'2022-02-16 09:08:18','2022-02-16 09:08:18'),(60,21,'Sun microsystem',1,'2022-02-16 09:08:18','2022-02-16 09:08:18'),(61,22,'Bamboo tree in Ghana',0,'2022-02-16 09:09:41','2022-02-16 09:09:41'),(62,22,'Opeke tree in Nigeria',0,'2022-02-16 09:09:41','2022-02-16 09:09:41'),(63,22,'Oak tree in Indonesia',1,'2022-02-16 09:09:41','2022-02-16 09:09:41'),(64,22,'Apple tree in the US',0,'2022-02-16 09:09:41','2022-02-16 09:09:41'),(65,23,'Bamboo tree in Ghana',0,'2022-02-16 09:09:41','2022-02-16 09:09:41'),(66,23,'Opeke tree in Nigeria',0,'2022-02-16 09:09:41','2022-02-16 09:09:41'),(67,23,'Oak tree in Indonesia',1,'2022-02-16 09:09:42','2022-02-16 09:09:42'),(68,23,'Apple tree in the US',0,'2022-02-16 09:09:42','2022-02-16 09:09:42'),(69,24,'Name of a dog',0,'2022-02-17 20:46:08','2022-02-17 20:46:08'),(70,24,'Name of animals, place or things',1,'2022-02-17 20:46:08','2022-02-17 20:46:08'),(71,24,'I dont know',0,'2022-02-17 20:46:08','2022-02-17 20:46:08'),(72,24,'Not sure',0,'2022-02-17 20:46:08','2022-02-17 20:46:08'),(73,25,'Break',1,'2022-02-17 20:47:00','2022-02-17 20:47:00'),(74,25,'Noise',0,'2022-02-17 20:47:01','2022-02-17 20:47:01'),(75,25,'Fresh air',0,'2022-02-17 20:47:01','2022-02-17 20:47:01'),(76,25,'I dont know',0,'2022-02-17 20:47:01','2022-02-17 20:47:01'),(77,26,'an action word',1,'2022-02-17 20:47:52','2022-02-17 20:47:52'),(78,26,'a sleeping word',0,'2022-02-17 20:47:52','2022-02-17 20:47:52'),(79,26,'fighting with people',0,'2022-02-17 20:47:52','2022-02-17 20:47:52'),(80,26,'a dancing word',0,'2022-02-17 20:47:52','2022-02-17 20:47:52'),(81,27,'an action word',1,'2022-02-17 20:47:52','2022-02-17 20:47:52'),(82,27,'a sleeping word',0,'2022-02-17 20:47:52','2022-02-17 20:47:52'),(83,27,'fighting with people',0,'2022-02-17 20:47:53','2022-02-17 20:47:53'),(84,27,'a dancing word',0,'2022-02-17 20:47:53','2022-02-17 20:47:53'),(85,28,'12',0,'2022-02-17 20:48:34','2022-02-17 20:48:34'),(86,28,'212',0,'2022-02-17 20:48:34','2022-02-17 20:48:34'),(87,28,'2',0,'2022-02-17 20:48:35','2022-02-17 20:48:35'),(88,28,'21',1,'2022-02-17 20:48:35','2022-02-17 20:48:35'),(89,29,'proxy',0,'2022-02-17 20:49:19','2022-02-17 20:49:19'),(90,29,'pronoun',1,'2022-02-17 20:49:19','2022-02-17 20:49:19'),(91,29,'proverb',0,'2022-02-17 20:49:19','2022-02-17 20:49:19'),(92,29,'prone',0,'2022-02-17 20:49:19','2022-02-17 20:49:19');
/*!40000 ALTER TABLE `answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (13,'2014_10_12_000000_create_users_table',1),(14,'2014_10_12_100000_create_password_resets_table',1),(15,'2019_08_19_000000_create_failed_jobs_table',1),(16,'2019_12_14_000001_create_personal_access_tokens_table',1),(17,'2022_01_25_122653_create_quizzes_table',1),(18,'2022_01_25_122720_create_questions_table',1),(19,'2022_01_25_122734_create_answers_table',1),(20,'2022_01_25_122902_create_quiz_user_table',1),(21,'2022_01_25_123823_create_results_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `questions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quiz_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (11,'Can you build web application with this framework?',4,'2022-01-27 10:29:43','2022-01-27 10:29:43'),(12,'HTML is the backbone of the web',5,'2022-01-27 10:30:28','2022-01-27 10:30:28'),(13,'Java is an OOP?',3,'2022-01-27 10:31:13','2022-01-27 11:42:22'),(14,'The most popular java IDE is what?',3,'2022-01-27 12:48:06','2022-01-27 12:48:06'),(15,'Which of this Tag is use for paragraph',5,'2022-02-16 06:36:17','2022-02-16 06:36:17'),(16,'Which of this tag is use for linking to another page?',5,'2022-02-16 06:41:58','2022-02-16 06:41:58'),(17,'What is the full meaning of HTML?',5,'2022-02-16 06:46:12','2022-02-16 06:46:12'),(18,'The best IDE for java is?',3,'2022-02-16 09:04:54','2022-02-16 09:04:54'),(19,'Java can be used to build stand alone application',3,'2022-02-16 09:06:32','2022-02-16 09:06:32'),(20,'Java follows the principle of Object Oriented Programming',3,'2022-02-16 09:07:27','2022-02-16 09:07:27'),(21,'Java is own by which of these company?',3,'2022-02-16 09:08:17','2022-02-16 09:08:17'),(22,'Java is from which of these word?',3,'2022-02-16 09:09:41','2022-02-16 09:09:41'),(23,'Java is from which of these word?',3,'2022-02-16 09:09:41','2022-02-16 09:09:41'),(24,'What is a noun?',7,'2022-02-17 20:46:08','2022-02-17 20:46:08'),(25,'Silence is given your ear a ____',7,'2022-02-17 20:47:00','2022-02-17 20:47:00'),(26,'What is a verb',7,'2022-02-17 20:47:52','2022-02-17 20:47:52'),(27,'What is a verb',7,'2022-02-17 20:47:52','2022-02-17 20:47:52'),(28,'How many consonant letters do we have?',7,'2022-02-17 20:48:34','2022-02-17 20:48:34'),(29,'____is used instead of a noun',7,'2022-02-17 20:49:19','2022-02-17 20:49:19');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quiz_user`
--

DROP TABLE IF EXISTS `quiz_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quiz_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiz_user`
--

LOCK TABLES `quiz_user` WRITE;
/*!40000 ALTER TABLE `quiz_user` DISABLE KEYS */;
INSERT INTO `quiz_user` VALUES (2,5,1,NULL,NULL),(4,5,5,NULL,NULL),(5,3,8,NULL,NULL),(6,5,8,NULL,NULL),(7,7,9,NULL,NULL);
/*!40000 ALTER TABLE `quiz_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quizzes`
--

DROP TABLE IF EXISTS `quizzes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quizzes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `minutes` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quizzes`
--

LOCK TABLES `quizzes` WRITE;
/*!40000 ALTER TABLE `quizzes` DISABLE KEYS */;
INSERT INTO `quizzes` VALUES (3,'Java','This is a programing Language',1,'2022-01-27 10:24:21','2022-01-27 10:24:21'),(4,'Laravel','This is a PHP framework',7,'2022-01-27 10:24:45','2022-01-27 10:24:45'),(5,'HTML','This is a markup Language',10,'2022-01-27 10:25:18','2022-01-27 10:26:07'),(7,'English','English Language composition',5,'2022-02-17 20:44:15','2022-02-17 20:44:15');
/*!40000 ALTER TABLE `quizzes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `results` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `question_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `answer_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `results`
--

LOCK TABLES `results` WRITE;
/*!40000 ALTER TABLE `results` DISABLE KEYS */;
INSERT INTO `results` VALUES (13,8,13,3,26,'2022-02-16 20:36:16','2022-02-16 20:36:16'),(14,8,14,3,30,'2022-02-16 20:36:22','2022-02-16 20:36:22'),(15,8,18,3,46,'2022-02-16 20:36:29','2022-02-16 20:36:29'),(16,8,19,3,52,'2022-02-16 20:36:35','2022-02-16 20:36:35'),(17,8,20,3,56,'2022-02-16 20:36:41','2022-02-16 20:36:41'),(18,9,24,7,70,'2022-02-17 20:51:40','2022-02-17 20:51:40'),(19,9,25,7,73,'2022-02-17 20:51:55','2022-02-17 20:51:55'),(20,9,26,7,79,'2022-02-17 20:52:01','2022-02-17 20:52:01'),(21,9,28,7,88,'2022-02-17 20:52:13','2022-02-17 20:52:13'),(22,9,29,7,90,'2022-02-17 20:52:22','2022-02-17 20:52:22');
/*!40000 ALTER TABLE `results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visible_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` int NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Patrick Claudius','oludarepatrick@gmail.com',NULL,'$2y$10$TZ2sWiixgzyCU2jjZ9Xg.u/aRxmKGh7wIueCOVCuaAhUws3JHH/Xa','ireoluwa7','Lawyer','Ogba, Lagos','08076743972',0,NULL,'2022-02-01 16:55:08','2022-02-01 17:27:39'),(3,'Ireoluwa Seven','ireoluwa56@gmail.com',NULL,'$2y$10$etFMQXtkyw3YCp5juCkDsORcsg883fsy1AU4Ryqnp3NqM0mMfUBg.','Jane@123','Baker','Okota, Lagos','07053796686',0,NULL,'2022-02-01 16:56:40','2022-02-01 17:28:27'),(4,'admin','admin123@gmail.com','2022-02-01 18:46:30','$2y$10$veW9xUZNv56zyqWvtguZw./LpGWqr/jvhNzI6LGBS.mpnYWg4GJTq','password','CEO','Australia','07063415220',1,'gJb3tdFxXZ3w523bJw2sEPWmipIaEGxLtTfFgXz2ErZKyOkFOsNUY2UWSlM8','2022-02-01 18:46:30','2022-02-01 18:46:30'),(5,'funmi','funmi@gmail.com',NULL,'$2y$10$VV7eeUp3Cdp7Dq7W.31gIOGJ7sHsR5hXyR9kqZLUvWd6uKsDY0owG','funmi111','HR','Ogba, Lagos','08076743972',0,NULL,'2022-02-01 21:57:28','2022-02-01 21:57:28'),(6,'Biliki','biliki@gmail.com',NULL,'$2y$10$yVYQQpvOb5M9vPcCcJqkn.Uz6A0b1BfD7qxQFaVW8gEiTI30Dl2jC','biliki111','Seller','Lagos','0709000000',0,NULL,'2022-02-01 21:58:09','2022-02-01 21:58:09'),(7,'Biliki','bilik22i@gmail.com',NULL,'$2y$10$NYIxhUV5Xp.OPH7xga.2g.2QnuYSRdQWOZOXmSl7W7Zec.XdS3x1C','blessing','Seller','Lagos','0709000000',0,NULL,'2022-02-01 21:58:39','2022-02-01 21:58:39'),(8,'Steve','steve@gmail.com',NULL,'$2y$10$XliB8yNt16XmCDxprc6Cc.m0DZiY4wK6036ETO91bxwlmtQ6OavZi','steve','Dg','Lagos','07067356916',0,NULL,'2022-02-16 09:10:46','2022-02-16 09:10:46'),(9,'Dunni Omo','dunni@gmail.com',NULL,'$2y$10$YygAdFT/PmA1ZV0j2bodVeYV/NhmLiJIuujEZYNMayKR3jJuawaYS','dunni','Student','Olaniyi','07067356916',0,NULL,'2022-02-17 20:50:15','2022-02-17 20:50:15');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'quizapp'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-02-20 16:27:06
