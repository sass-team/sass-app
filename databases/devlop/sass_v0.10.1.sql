
-- MySQL dump 10.13  Distrib 5.6.17, for Win32 (x86)
--
-- Host: localhost    Database: sass-ms_db
-- ------------------------------------------------------
-- Server version	5.6.17

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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `user_id` int(11) NOT NULL,
  `user_types_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_admin_user_types1_idx` (`user_types_id`),
  CONSTRAINT `fk_admin_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_admin_user_types1` FOREIGN KEY (`user_types_id`) REFERENCES `user_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `appointment`
--

DROP TABLE IF EXISTS `appointment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appointment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `course_id` int(11) NOT NULL,
  `tutor_user_id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_appointment_course1_idx` (`course_id`),
  KEY `fk_appointment_tutor1_idx` (`tutor_user_id`),
  KEY `fk_appointment_schedule1_idx` (`term_id`),
  CONSTRAINT `fk_appointment_course1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_appointment_schedule1` FOREIGN KEY (`term_id`) REFERENCES `term` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_appointment_tutor1` FOREIGN KEY (`tutor_user_id`) REFERENCES `tutor` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointment`
--

LOCK TABLES `appointment` WRITE;
/*!40000 ALTER TABLE `appointment` DISABLE KEYS */;
/*!40000 ALTER TABLE `appointment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `appointment_has_student`
--

DROP TABLE IF EXISTS `appointment_has_student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appointment_has_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appointment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `instructor_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `report_id_UNIQUE` (`report_id`),
  KEY `fk_appointment_has_student_student2_idx` (`student_id`),
  KEY `fk_appointment_has_student_appointment2_idx` (`appointment_id`),
  KEY `fk_appointment_has_student_report1_idx` (`report_id`),
  KEY `fk_appointment_has_student_instructor1_idx` (`instructor_id`),
  CONSTRAINT `fk_appointment_has_student_appointment2` FOREIGN KEY (`appointment_id`) REFERENCES `appointment` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_appointment_has_student_instructor1` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_appointment_has_student_report1` FOREIGN KEY (`report_id`) REFERENCES `report` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_appointment_has_student_student2` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointment_has_student`
--

LOCK TABLES `appointment_has_student` WRITE;
/*!40000 ALTER TABLE `appointment_has_student` DISABLE KEYS */;
/*!40000 ALTER TABLE `appointment_has_student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `name` varchar(125) CHARACTER SET latin1 NOT NULL,
  `level` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course`
--

LOCK TABLES `course` WRITE;
/*!40000 ALTER TABLE `course` DISABLE KEYS */;
INSERT INTO `course` VALUES (6,'CS1070','Introduction to Computers',NULL),(7,'EN1212','Intro to English',NULL);
/*!40000 ALTER TABLE `course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instructor`
--

DROP TABLE IF EXISTS `instructor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `instructor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `f_name` varchar(45) NOT NULL,
  `l_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructor`
--

LOCK TABLES `instructor` WRITE;
/*!40000 ALTER TABLE `instructor` DISABLE KEYS */;
/*!40000 ALTER TABLE `instructor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `major`
--

DROP TABLE IF EXISTS `major`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `major` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `code` varchar(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `major`
--

LOCK TABLES `major` WRITE;
/*!40000 ALTER TABLE `major` DISABLE KEYS */;
INSERT INTO `major` VALUES (2,'Undecided','UNN');
/*!40000 ALTER TABLE `major` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `outcome_of_session`
--

DROP TABLE IF EXISTS `outcome_of_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `outcome_of_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(45) NOT NULL,
  `report_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_outcome_of_session_report1_idx` (`report_id`),
  CONSTRAINT `fk_outcome_of_session_report1` FOREIGN KEY (`report_id`) REFERENCES `report` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `outcome_of_session`
--

LOCK TABLES `outcome_of_session` WRITE;
/*!40000 ALTER TABLE `outcome_of_session` DISABLE KEYS */;
/*!40000 ALTER TABLE `outcome_of_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `primary_focus_of_conference`
--

DROP TABLE IF EXISTS `primary_focus_of_conference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `primary_focus_of_conference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(45) NOT NULL,
  `report_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_primary_focus_of_conference_report1_idx` (`report_id`),
  CONSTRAINT `fk_primary_focus_of_conference_report1` FOREIGN KEY (`report_id`) REFERENCES `report` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `primary_focus_of_conference`
--

LOCK TABLES `primary_focus_of_conference` WRITE;
/*!40000 ALTER TABLE `primary_focus_of_conference` DISABLE KEYS */;
/*!40000 ALTER TABLE `primary_focus_of_conference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report`
--

DROP TABLE IF EXISTS `report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `students_concerns` text,
  `relevant_feedback_or_guidelines` text,
  `additional_comments` text,
  PRIMARY KEY (`id`),
  KEY `fk_appointment_has_student_student1_idx` (`student_id`),
  KEY `fk_appointment_has_student_has_report_has_instructor_instru_idx` (`instructor_id`),
  CONSTRAINT `fk_appointment_has_student_has_report_has_instructor_instruct1` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_appointment_has_student_student1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=170 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report`
--

LOCK TABLES `report` WRITE;
/*!40000 ALTER TABLE `report` DISABLE KEYS */;
/*!40000 ALTER TABLE `report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `secretary`
--

DROP TABLE IF EXISTS `secretary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `secretary` (
  `user_id` int(11) NOT NULL,
  `user_types_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_secretary_user_types1_idx` (`user_types_id`),
  CONSTRAINT `fk_secretary_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_secretary_user_types1` FOREIGN KEY (`user_types_id`) REFERENCES `user_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `secretary`
--

LOCK TABLES `secretary` WRITE;
/*!40000 ALTER TABLE `secretary` DISABLE KEYS */;
/*!40000 ALTER TABLE `secretary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studentId` int(7) NOT NULL,
  `email` varchar(125) NOT NULL,
  `f_name` varchar(45) NOT NULL,
  `l_name` varchar(45) NOT NULL,
  `mobile` varchar(10) DEFAULT NULL,
  `ci` double DEFAULT NULL,
  `credits` int(3) DEFAULT NULL,
  `major_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `studentId_UNIQUE` (`studentId`),
  UNIQUE KEY `mobile_UNIQUE` (`mobile`),
  KEY `fk_student_major1_idx` (`major_id`),
  CONSTRAINT `fk_student_major1` FOREIGN KEY (`major_id`) REFERENCES `major` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (5,135995,'p.anastasakis@acg.edu','Ellie','Anastasakis','6982136576',NULL,NULL,2),(6,180486,'a.sourlis@acg.edu','Aristotelis','Sourlis','6988416421',3.5,NULL,2),(7,126198,'a.villiotis@acg.edu','Aggelos','Villiotis','6985734144',2.9,NULL,2);
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_brought_along`
--

DROP TABLE IF EXISTS `student_brought_along`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_brought_along` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(45) NOT NULL,
  `report_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_student_brought_along_report1_idx` (`report_id`),
  CONSTRAINT `fk_student_brought_along_report1` FOREIGN KEY (`report_id`) REFERENCES `report` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_brought_along`
--

LOCK TABLES `student_brought_along` WRITE;
/*!40000 ALTER TABLE `student_brought_along` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_brought_along` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `term`
--

DROP TABLE IF EXISTS `term`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `term` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `term`
--

LOCK TABLES `term` WRITE;
/*!40000 ALTER TABLE `term` DISABLE KEYS */;
INSERT INTO `term` VALUES (2,'Fall Semester 2014','2014-09-16 13:39:00','2014-12-15 15:39:00'),(3,'Fall Semester 2015','2014-09-30 13:40:00','2015-02-24 15:40:00');
/*!40000 ALTER TABLE `term` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tutor`
--

DROP TABLE IF EXISTS `tutor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tutor` (
  `user_id` int(11) NOT NULL,
  `major_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_tutor_user1_idx` (`user_id`),
  KEY `fk_tutor_major1_idx` (`major_id`),
  CONSTRAINT `fk_tutor_major1` FOREIGN KEY (`major_id`) REFERENCES `major` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_tutor_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tutor`
--

LOCK TABLES `tutor` WRITE;
/*!40000 ALTER TABLE `tutor` DISABLE KEYS */;
INSERT INTO `tutor` VALUES (11,2),(14,2),(15,2),(16,2);
/*!40000 ALTER TABLE `tutor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tutor_has_course_has_term`
--

DROP TABLE IF EXISTS `tutor_has_course_has_term`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tutor_has_course_has_term` (
  `tutor_user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tutor_has_course_course1_idx` (`course_id`),
  KEY `fk_tutor_has_course_tutor1_idx` (`tutor_user_id`),
  KEY `fk_tutor_has_course_has_schedule_term1_idx` (`term_id`),
  CONSTRAINT `fk_tutor_has_course_course1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_tutor_has_course_has_schedule_term1` FOREIGN KEY (`term_id`) REFERENCES `term` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_tutor_has_course_tutor1` FOREIGN KEY (`tutor_user_id`) REFERENCES `tutor` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tutor_has_course_has_term`
--

LOCK TABLES `tutor_has_course_has_term` WRITE;
/*!40000 ALTER TABLE `tutor_has_course_has_term` DISABLE KEYS */;
/*!40000 ALTER TABLE `tutor_has_course_has_term` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(125) NOT NULL,
  `f_name` varchar(35) NOT NULL,
  `l_name` varchar(35) NOT NULL,
  `password` varchar(512) DEFAULT NULL,
  `img_loc` varchar(125) NOT NULL DEFAULT 'assets/img/avatars/default_avatar.jpg',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'date of account creation',
  `user_types_id` int(11) NOT NULL,
  `mobile` varchar(10) DEFAULT NULL,
  `profile_description` varchar(512) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `gen_string` varchar(45) DEFAULT NULL,
  `gen_string_update_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `mobile_UNIQUE` (`mobile`),
  KEY `fk_user_user_types_idx` (`user_types_id`),
  CONSTRAINT `fk_user_user_types` FOREIGN KEY (`user_types_id`) REFERENCES `user_types` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (2,'r.dokollari@acg.edu','Rizart','Dokollari','$2y$10$7Ck6i8Ly4wF0x5UnArGNAex47mAI/E8pvh6LfgzUhmG00R6OJYEYy','assets/img/avatars/avatar_img_2.png','2014-09-16 13:26:42',4,'6983827751','Lover, hacker, vault dweller.',1,'','2014-09-16 13:26:54'),(6,'G.Skarlatos@acg.edu','George','Skarlatos','$2y$10$VNYezLQEWkfiVWhkWv6/NO.sWjwP6WVI/axwkABBs/WNB2UM0uk62','assets/img/avatars/default_avatar.jpg','2014-09-17 03:10:49',4,NULL,NULL,1,'','2014-09-19 09:06:49'),(8,'p.anastasakis@acg.edu','Penelope','Anastasakis','$2y$10$nOrgUzz8887gK3wLRldctOPY67ptWH.LX8cP/bgKa/G4MgcwX.4vi','assets/img/avatars/default_avatar.jpg','2014-09-17 09:16:31',4,NULL,NULL,1,'','2014-09-17 12:17:28'),(9,'a.darivaki@acg.edu','Aimilia','Darivaki','$2y$10$75qWQlw9u8BJ4n0ldQa/s.X4BvaeqcqUJ7hVVbdn3OIyXZNPeTW7e','assets/img/avatars/default_avatar.jpg','2014-09-17 09:46:11',4,'6973361028',NULL,1,'5419895e73c185.96454964WMCARUJGXV','2014-09-17 13:15:10'),(10,'dale.pappas59@gmail.com','Dale','Pappas','$2y$10$lnrRm1FRJuQeNJK5hAg06ufT3C/KX2OyaU6q7FLXbDUeDeyOHgHFK','assets/img/avatars/default_avatar.jpg','2014-09-17 09:48:59',4,NULL,NULL,1,'','2014-09-17 12:49:56'),(11,'t.vasilopoulou@acg.edu','Fani','Vassilopoulou',NULL,'assets/img/avatars/default_avatar.jpg','2014-09-17 10:44:41',6,NULL,NULL,1,'54199082bf32d9.88627267PWSCZKUTHJ','2014-09-17 13:45:38'),(12,'sourlakos17@hotmail.com','Aristotelis','Sourlis','$2y$10$GlFxnpwMGAfrmcN7GRtpseatFzPG9pdhV7QaeOZJkMG2Jxk5.h23y','assets/img/avatars/default_avatar.jpg','2014-09-17 11:14:13',4,NULL,NULL,1,'','2014-09-17 14:15:10'),(13,'a.villiotis@acg.edu','Aggelos','Villiotis','$2y$10$JReXes7IZnxwZ2xOT6CC7.kBujMYa.iLpWqzIbGpE0K6NJXH2aSZm','assets/img/avatars/default_avatar.jpg','2014-09-18 05:03:23',5,NULL,NULL,1,'','2014-09-18 08:04:21'),(14,'m.kalokairinos@acg.edu','Manos','Kalokairinos',NULL,'assets/img/avatars/default_avatar.jpg','2014-09-18 05:09:02',6,NULL,NULL,1,'541a935899b799.54395849YILRKBWTDH','2014-09-18 08:10:00'),(15,'a.diamantopoulou@acg.edu','Artemis','Diamantopoulou',NULL,'assets/img/avatars/default_avatar.jpg','2014-09-18 05:10:44',6,NULL,NULL,1,'541a93becfe278.73822969BRYFLTXEPJ','2014-09-18 08:11:42'),(16,'t.sotiriou@acg.edu','Vladimir','Putin','$2y$10$UngKhrAHXa9/NV6mq9hn1ug62YUzdpAt2RcfjByKgPpHX4KNqPXBm','assets/img/avatars/default_avatar.jpg','2014-09-18 05:25:07',6,NULL,NULL,1,'','2014-09-18 08:26:05');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_types`
--

DROP TABLE IF EXISTS `user_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `description_UNIQUE` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_types`
--

LOCK TABLES `user_types` WRITE;
/*!40000 ALTER TABLE `user_types` DISABLE KEYS */;
INSERT INTO `user_types` VALUES (4,'admin'),(5,'secretary'),(6,'tutor');
/*!40000 ALTER TABLE `user_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_week_hours`
--

DROP TABLE IF EXISTS `work_week_hours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_week_hours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start` timestamp NULL DEFAULT NULL,
  `end` timestamp NULL DEFAULT NULL,
  `term_id` int(11) NOT NULL,
  `tutor_user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_work_day_schedule1_idx` (`term_id`),
  KEY `fk_work_week_hours_tutor1_idx` (`tutor_user_id`),
  CONSTRAINT `fk_work_day_schedule1` FOREIGN KEY (`term_id`) REFERENCES `term` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_work_week_hours_tutor1` FOREIGN KEY (`tutor_user_id`) REFERENCES `tutor` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_week_hours`
--

LOCK TABLES `work_week_hours` WRITE;
/*!40000 ALTER TABLE `work_week_hours` DISABLE KEYS */;
/*!40000 ALTER TABLE `work_week_hours` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-09-19 15:16:42
