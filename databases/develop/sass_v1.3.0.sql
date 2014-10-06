CREATE DATABASE  IF NOT EXISTS `sass-ms_db` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `sass-ms_db`;
-- MySQL dump 10.13  Distrib 5.6.17, for Win64 (x86_64)
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
  `label_message` varchar(15) NOT NULL DEFAULT 'pending',
  `label_color` varchar(15) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`),
  KEY `fk_appointment_course1_idx` (`course_id`),
  KEY `fk_appointment_tutor1_idx` (`tutor_user_id`),
  KEY `fk_appointment_schedule1_idx` (`term_id`),
  CONSTRAINT `fk_appointment_course1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_appointment_schedule1` FOREIGN KEY (`term_id`) REFERENCES `term` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_appointment_tutor1` FOREIGN KEY (`tutor_user_id`) REFERENCES `tutor` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointment`
--

LOCK TABLES `appointment` WRITE;
/*!40000 ALTER TABLE `appointment` DISABLE KEYS */;
INSERT INTO `appointment` VALUES (1,'2014-10-03 09:00:00','2014-10-03 09:30:00',10,19,7,'complete','success'),(2,'2014-10-06 09:00:00','2014-10-06 09:30:00',10,19,7,'pending','default');
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
INSERT INTO `appointment_has_student` VALUES (1,1,8,52,2),(2,2,8,NULL,2);
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course`
--

LOCK TABLES `course` WRITE;
/*!40000 ALTER TABLE `course` DISABLE KEYS */;
INSERT INTO `course` VALUES (10,'MA','Maths',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instructor`
--

LOCK TABLES `instructor` WRITE;
/*!40000 ALTER TABLE `instructor` DISABLE KEYS */;
INSERT INTO `instructor` VALUES (2,'first','last');
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
INSERT INTO `major` VALUES (3,'Undecided','UN');
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
  `project_topic_other` text NOT NULL,
  `students_concerns` text,
  `relevant_feedback_or_guidelines` text,
  `additional_comments` text,
  `label_message` varchar(15) NOT NULL DEFAULT 'pending fill',
  `label_color` varchar(15) NOT NULL DEFAULT 'warning',
  `other_text_area` text,
  PRIMARY KEY (`id`),
  KEY `fk_appointment_has_student_student1_idx` (`student_id`),
  KEY `fk_appointment_has_student_has_report_has_instructor_instru_idx` (`instructor_id`),
  CONSTRAINT `fk_appointment_has_student_has_report_has_instructor_instruct1` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_appointment_has_student_student1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report`
--

LOCK TABLES `report` WRITE;
/*!40000 ALTER TABLE `report` DISABLE KEYS */;
INSERT INTO `report` VALUES (49,8,2,'',NULL,NULL,NULL,'pending fill','warning',NULL),(50,9,2,'',NULL,NULL,NULL,'pending fill','warning',NULL),(51,8,2,'',NULL,NULL,NULL,'pending fill','warning',NULL),(52,8,2,'',NULL,NULL,NULL,'pending fill','warning',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (8,1223344,'emai@email.com','first name','lAST','6983827751',NULL,NULL,3),(9,234424,'emai@email.com1','fIRST','l','6983827750',NULL,NULL,3);
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `term`
--

LOCK TABLES `term` WRITE;
/*!40000 ALTER TABLE `term` DISABLE KEYS */;
INSERT INTO `term` VALUES (7,'Fall Semester 2014','2014-10-03 05:50:00','2014-12-25 06:50:00'),(8,'Fd','2014-10-03 06:01:00','2014-12-24 07:01:00');
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
INSERT INTO `tutor` VALUES (18,3),(19,3),(20,3),(21,3);
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tutor_has_course_has_term`
--

LOCK TABLES `tutor_has_course_has_term` WRITE;
/*!40000 ALTER TABLE `tutor_has_course_has_term` DISABLE KEYS */;
INSERT INTO `tutor_has_course_has_term` VALUES (19,10,7,7);
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (17,'r.dokollari@acg.edu','Rizart','Dokollari','$2y$10$6Y76z7PxHON5N4qOVkxLfu9mmNhg6068pOWMejx/RpwfeUHi4kpN.','assets/img/avatars/default_avatar.jpg','2014-09-24 11:37:53',7,'6983827751',NULL,1,'54267455cff021.11763509HJPXTYLROA','2014-09-27 08:24:53'),(18,'emai@email.com','first','Dokollari','$2y$10$TNd6cKv9XHGjybeQjfjI6Ov4ZUqXStvWR3Bs4Jl4ZPcbyk2OgWOlS','assets/img/avatars/default_avatar.jpg','2014-09-24 12:55:12',8,NULL,NULL,1,'','2014-09-26 03:00:19'),(19,'emai@email.com1','second','last','$2y$10$9XAgZJ8gXdDri1eya72uReqmTy3yU3PE79PhY6uXxPh7.k0OvcPKW','assets/img/avatars/default_avatar.jpg','2014-09-26 07:02:59',8,NULL,NULL,1,'','2014-09-27 13:17:10'),(20,'emai@email.com2','first','last',NULL,'assets/img/avatars/default_avatar.jpg','2014-09-27 08:27:58',8,NULL,NULL,1,'5426750ed57383.68051513VTNHUFBLQE','2014-09-27 08:27:58'),(21,'emai@email.com5','first','last',NULL,'assets/img/avatars/default_avatar.jpg','2014-10-03 03:27:21',8,NULL,NULL,1,'542e179a031ff0.00479269OBYGVEKFNI','2014-10-03 03:27:22');
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_types`
--

LOCK TABLES `user_types` WRITE;
/*!40000 ALTER TABLE `user_types` DISABLE KEYS */;
INSERT INTO `user_types` VALUES (7,'admin'),(9,'secretary'),(8,'tutor');
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
  `start` time NOT NULL,
  `end` time NOT NULL,
  `term_id` int(11) NOT NULL,
  `tutor_user_id` int(11) NOT NULL,
  `monday` tinyint(1) NOT NULL DEFAULT '0',
  `tuesday` tinyint(1) NOT NULL DEFAULT '0',
  `wednesday` tinyint(1) NOT NULL DEFAULT '0',
  `thursday` tinyint(1) NOT NULL DEFAULT '0',
  `friday` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_work_day_schedule1_idx` (`term_id`),
  KEY `fk_work_week_hours_tutor1_idx` (`tutor_user_id`),
  CONSTRAINT `fk_work_day_schedule1` FOREIGN KEY (`term_id`) REFERENCES `term` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_work_week_hours_tutor1` FOREIGN KEY (`tutor_user_id`) REFERENCES `tutor` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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

-- Dump completed on 2014-10-05 17:21:57
