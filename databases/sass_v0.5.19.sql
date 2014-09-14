<<<<<<< HEAD
-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2014 at 08:00 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

=======

-- MySQL dump 10.13  Distrib 5.6.17, for Win32 (x86)
--
-- Host: localhost    Database: sass-ms_db
-- ------------------------------------------------------
-- Server version	5.6.17
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
<<<<<<< HEAD

--
-- Database: `sass-ms_db`
--

-- --------------------------------------------------------
=======
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Table structure for table `admin`
--

<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `admin` (
  `user_id` int(11) NOT NULL,
  `user_user_types_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`user_user_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
=======
DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `user_id` int(11) NOT NULL,
  `user_user_types_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`user_user_types_id`),
  CONSTRAINT `fk_admin_user1` FOREIGN KEY (`user_id`, `user_user_types_id`) REFERENCES `user` (`id`, `user_types_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Table structure for table `course`
--

<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `course` (
=======
DROP TABLE IF EXISTS `course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course` (
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `name` varchar(125) CHARACTER SET latin1 NOT NULL,
  `level` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `code_UNIQUE` (`code`)
<<<<<<< HEAD
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;
=======
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Dumping data for table `course`
--

<<<<<<< HEAD
INSERT INTO `course` (`id`, `code`, `name`, `level`) VALUES
(21, '4', 'COURSE2', NULL),
(22, '1', 'na', NULL);

-- --------------------------------------------------------
=======
LOCK TABLES `course` WRITE;
/*!40000 ALTER TABLE `course` DISABLE KEYS */;
INSERT INTO `course` VALUES (21,'4','COURSE2',NULL),(22,'1','na',NULL);
/*!40000 ALTER TABLE `course` ENABLE KEYS */;
UNLOCK TABLES;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Table structure for table `instructor`
--

<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `instructor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `f_name` varchar(45) NOT NULL,
  `l_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
=======
DROP TABLE IF EXISTS `instructor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `instructor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `f_name` varchar(45) NOT NULL,
  `l_name` varchar(45) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Dumping data for table `instructor`
--

<<<<<<< HEAD
INSERT INTO `instructor` (`id`, `f_name`, `l_name`) VALUES
(2, 'geor', 'skarl');

-- --------------------------------------------------------
=======
LOCK TABLES `instructor` WRITE;
/*!40000 ALTER TABLE `instructor` DISABLE KEYS */;
INSERT INTO `instructor` VALUES (1,'first','lat','emai@email.com2');
/*!40000 ALTER TABLE `instructor` ENABLE KEYS */;
UNLOCK TABLES;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Table structure for table `major`
--

<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `major` (
=======
DROP TABLE IF EXISTS `major`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `major` (
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET latin1 NOT NULL,
  `code` varchar(6) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `code_UNIQUE` (`code`)
<<<<<<< HEAD
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;
=======
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Dumping data for table `major`
--

<<<<<<< HEAD
INSERT INTO `major` (`id`, `name`, `code`) VALUES
(6, 'it', 'co'),
(8, 'info', 'ITC'),
(9, 'IN', 'IT');

-- --------------------------------------------------------
=======
LOCK TABLES `major` WRITE;
/*!40000 ALTER TABLE `major` DISABLE KEYS */;
INSERT INTO `major` VALUES (6,'it','co'),(8,'info','ITC'),(9,'IN','IT');
/*!40000 ALTER TABLE `major` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `major_has_courses`
--

DROP TABLE IF EXISTS `major_has_courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `major_has_courses` (
  `major_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  PRIMARY KEY (`major_id`,`course_id`),
  KEY `fk_major_has_courses_course1_idx` (`course_id`),
  CONSTRAINT `fk_major_has_courses_course1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_major_has_courses_major1` FOREIGN KEY (`major_id`) REFERENCES `major` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `major_has_courses`
--

LOCK TABLES `major_has_courses` WRITE;
/*!40000 ALTER TABLE `major_has_courses` DISABLE KEYS */;
/*!40000 ALTER TABLE `major_has_courses` ENABLE KEYS */;
UNLOCK TABLES;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Table structure for table `secretary`
--

<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `secretary` (
  `user_id` int(11) NOT NULL,
  `user_user_types_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`user_user_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
=======
DROP TABLE IF EXISTS `secretary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `secretary` (
  `user_id` int(11) NOT NULL,
  `user_user_types_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`user_user_types_id`),
  CONSTRAINT `fk_secretary_user1` FOREIGN KEY (`user_id`, `user_user_types_id`) REFERENCES `user` (`id`, `user_types_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `secretary`
--

LOCK TABLES `secretary` WRITE;
/*!40000 ALTER TABLE `secretary` DISABLE KEYS */;
/*!40000 ALTER TABLE `secretary` ENABLE KEYS */;
UNLOCK TABLES;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Table structure for table `student`
--

<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `student` (
=======
DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studentId` int(7) NOT NULL,
  `email` varchar(125) NOT NULL,
  `f_name` varchar(45) NOT NULL,
  `l_name` varchar(45) NOT NULL,
  `mobile` varchar(10) DEFAULT NULL,
  `ci` double DEFAULT NULL,
  `credits` int(11) DEFAULT NULL,
  `major_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `studentId_UNIQUE` (`studentId`),
  UNIQUE KEY `mobile_UNIQUE` (`mobile`),
<<<<<<< HEAD
  KEY `fk_student_major1_idx` (`major_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
=======
  KEY `fk_student_major1_idx` (`major_id`),
  CONSTRAINT `fk_student_major1` FOREIGN KEY (`major_id`) REFERENCES `major` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Dumping data for table `student`
--

<<<<<<< HEAD
INSERT INTO `student` (`id`, `studentId`, `email`, `f_name`, `l_name`, `mobile`, `ci`, `credits`, `major_id`) VALUES
(1, 123456, 'emai@email.com3', 'first', 'last', '6983827751', 3.5, 50, 9);

-- --------------------------------------------------------
=======
LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (1,123456,'emai@email.com3','first','last','6983827751',3.5,50,9);
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Table structure for table `student_has_instructor`
--

<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `student_has_instructor` (
=======
DROP TABLE IF EXISTS `student_has_instructor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_has_instructor` (
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f
  `student_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  PRIMARY KEY (`student_id`,`instructor_id`),
  KEY `fk_student_has_instructor_instructor1_idx` (`instructor_id`),
<<<<<<< HEAD
  KEY `fk_student_has_instructor_student1_idx` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
=======
  KEY `fk_student_has_instructor_student1_idx` (`student_id`),
  CONSTRAINT `fk_student_has_instructor_instructor1` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_student_has_instructor_student1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_has_instructor`
--

LOCK TABLES `student_has_instructor` WRITE;
/*!40000 ALTER TABLE `student_has_instructor` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_has_instructor` ENABLE KEYS */;
UNLOCK TABLES;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Table structure for table `tutor`
--

<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `tutor` (
=======
DROP TABLE IF EXISTS `tutor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tutor` (
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f
  `user_id` int(11) NOT NULL,
  `major_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_tutor_user1_idx` (`user_id`),
<<<<<<< HEAD
  KEY `fk_tutor_major1_idx` (`major_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
=======
  KEY `fk_tutor_major1_idx` (`major_id`),
  CONSTRAINT `fk_tutor_major1` FOREIGN KEY (`major_id`) REFERENCES `major` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_tutor_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Dumping data for table `tutor`
--

<<<<<<< HEAD
INSERT INTO `tutor` (`user_id`, `major_id`) VALUES
(26, 6),
(33, 6),
(34, 6),
(35, 6),
(36, 6),
(37, 6),
(38, 6),
(39, 6),
(40, 6),
(41, 6),
(42, 6),
(43, 6),
(44, 6);

-- --------------------------------------------------------
=======
LOCK TABLES `tutor` WRITE;
/*!40000 ALTER TABLE `tutor` DISABLE KEYS */;
INSERT INTO `tutor` VALUES (46,6),(47,6),(48,6),(49,6),(50,6),(51,6),(52,6);
/*!40000 ALTER TABLE `tutor` ENABLE KEYS */;
UNLOCK TABLES;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Table structure for table `tutor_has_course`
--

<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `tutor_has_course` (
=======
DROP TABLE IF EXISTS `tutor_has_course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tutor_has_course` (
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f
  `tutor_user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `fk_tutor_has_course_course1_idx` (`course_id`),
<<<<<<< HEAD
  KEY `fk_tutor_has_course_tutor1_idx` (`tutor_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;
=======
  KEY `fk_tutor_has_course_tutor1_idx` (`tutor_user_id`),
  CONSTRAINT `fk_tutor_has_course_course1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tutor_has_course_tutor1` FOREIGN KEY (`tutor_user_id`) REFERENCES `tutor` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Dumping data for table `tutor_has_course`
--

<<<<<<< HEAD
INSERT INTO `tutor_has_course` (`tutor_user_id`, `course_id`, `id`) VALUES
(26, 21, 12),
(33, 21, 13),
(43, 21, 16),
(44, 21, 19);

-- --------------------------------------------------------
=======
LOCK TABLES `tutor_has_course` WRITE;
/*!40000 ALTER TABLE `tutor_has_course` DISABLE KEYS */;
/*!40000 ALTER TABLE `tutor_has_course` ENABLE KEYS */;
UNLOCK TABLES;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Table structure for table `user`
--

<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `user` (
=======
DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(125) NOT NULL,
  `f_name` varchar(35) NOT NULL,
  `l_name` varchar(35) NOT NULL,
  `password` varchar(512) DEFAULT NULL,
  `img_loc` varchar(125) NOT NULL DEFAULT 'assets/img/avatars/default_avatar.jpg',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'date of account creation',
  `user_types_id` int(11) NOT NULL,
  `mobile` varchar(10) DEFAULT NULL,
  `profile_description` varchar(512) DEFAULT NULL,
  `gen_string` varchar(45) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`,`user_types_id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `mobile_UNIQUE` (`mobile`),
<<<<<<< HEAD
  KEY `fk_user_user_types_idx` (`user_types_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;
=======
  KEY `fk_user_user_types_idx` (`user_types_id`),
  CONSTRAINT `fk_user_user_types` FOREIGN KEY (`user_types_id`) REFERENCES `user_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Dumping data for table `user`
--

<<<<<<< HEAD
INSERT INTO `user` (`id`, `email`, `f_name`, `l_name`, `password`, `img_loc`, `date`, `user_types_id`, `mobile`, `profile_description`, `gen_string`, `active`) VALUES
(21, 'admin@acg.edu', 'admin', 'last', '$2y$10$70Lj.pqr4AY2W0ewds0pRerHA9zOPJPQHiID9Xa9qiYV5P8UXiHEa', 'app/assets/img/avatars/default_avatar.jpg', '2014-09-14 08:23:15', 16, NULL, NULL, '', 1),
(26, 'emai@email.com2', 'tutor', 'last', NULL, 'app/assets/img/avatars/default_avatar.jpg', '2014-09-14 01:35:22', 18, NULL, NULL, NULL, 1),
(33, 'emai@email.com233', 'tutorrr', 'lat', NULL, 'app/assets/img/avatars/default_avatar.jpg', '2014-09-14 02:54:48', 18, NULL, NULL, NULL, 1),
(34, 'emai@email.com', 'tutor', 'lastt', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-14 08:12:39', 18, NULL, NULL, NULL, 1),
(35, 'emai@email.com34', 'user', 'last', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-14 08:01:08', 18, NULL, NULL, NULL, 1),
(36, 'emai@email.com35', 'user', 'last', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-14 08:02:17', 18, NULL, NULL, NULL, 1),
(37, 'emai@email.com36', 'user', 'last', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-14 08:02:30', 18, NULL, NULL, NULL, 1),
(38, 'emai@email.com37', 'user', 'last', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-14 08:03:40', 18, NULL, NULL, NULL, 1),
(39, 'emai@email.com38', 'user', 'last', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-14 08:04:22', 18, NULL, NULL, NULL, 1),
(40, 'emai@email.com39', 'user', 'last', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-14 08:04:33', 18, NULL, NULL, NULL, 1),
(41, 'emai@email.com40', 'user', 'last', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-14 08:05:21', 18, NULL, NULL, NULL, 1),
(42, 'emai@email.com41', 'user', 'last', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-14 08:05:34', 18, NULL, NULL, NULL, 1),
(43, 'emai@email.com42', 'user', 'last', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-14 08:07:01', 18, NULL, NULL, NULL, 1),
(44, 'emai@email.com43', 'user', 'last', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-14 08:07:40', 18, NULL, NULL, NULL, 1);

-- --------------------------------------------------------
=======
LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (21,'admin@acg.edu','admin','last','$2y$10$70Lj.pqr4AY2W0ewds0pRerHA9zOPJPQHiID9Xa9qiYV5P8UXiHEa','assets/img/avatars/avatar_img_21.png','2014-09-14 12:04:15',16,NULL,NULL,'5415843f5bb3f7.83018049HWCRJBODZK',1),(46,'emai@email.com2e','first','last',NULL,'assets/img/avatars/default_avatar.jpg','2014-09-14 14:26:41',18,NULL,NULL,NULL,1),(47,'emai@email.com2','first','last',NULL,'assets/img/avatars/default_avatar.jpg','2014-09-14 14:27:47',18,NULL,NULL,NULL,1),(48,'emai@email.com3','first','last',NULL,'assets/img/avatars/default_avatar.jpg','2014-09-14 14:28:27',18,NULL,NULL,NULL,1),(49,'emai@email.com4','first','last','$2y$10$6KFx.Vk3NJXoMxogfUfZuO9zhgaHi6moyQGdcx.z4WfR03MX2zvC.','assets/img/avatars/default_avatar.jpg','2014-09-14 14:51:59',18,NULL,NULL,'',1),(50,'emai@email.com','FIRST','last','$2y$10$5yBdWCFiHYjbBT6q/MqHSOnzeRYPLXgxv5AwhATOpVnIEfHgbxn.C','assets/img/avatars/default_avatar.jpg','2014-09-14 15:03:49',18,NULL,NULL,'',1),(51,'emai@email.com233','Firs','last',NULL,'assets/img/avatars/default_avatar.jpg','2014-09-14 15:12:44',18,NULL,NULL,'5415b06cea7e60.05145761HTPVAFDCOW',1),(52,'emai@email.com234','Firs','last','$2y$10$9swHOPs/D4COGj2xHsHlNOtwAXCJ.RNiSCRg1auQMmKaveLbnTMYq','assets/img/avatars/default_avatar.jpg','2014-09-14 15:14:32',18,NULL,NULL,'',1),(53,'emai@email.com235','secretary','last','$2y$10$cAGAthyJLapnM1vtdz8zEuab7JRohyoeN80NAST07wy7X83xBqwPy','assets/img/avatars/default_avatar.jpg','2014-09-14 15:16:53',17,NULL,NULL,'',1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Table structure for table `user_types`
--

<<<<<<< HEAD
CREATE TABLE IF NOT EXISTS `user_types` (
=======
DROP TABLE IF EXISTS `user_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_types` (
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `description_UNIQUE` (`type`)
<<<<<<< HEAD
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;
=======
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f

--
-- Dumping data for table `user_types`
--

<<<<<<< HEAD
INSERT INTO `user_types` (`id`, `type`) VALUES
(16, 'admin'),
(17, 'secreatary'),
(18, 'tutor');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `fk_admin_user1` FOREIGN KEY (`user_id`, `user_user_types_id`) REFERENCES `user` (`id`, `user_types_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `secretary`
--
ALTER TABLE `secretary`
  ADD CONSTRAINT `fk_secretary_user1` FOREIGN KEY (`user_id`, `user_user_types_id`) REFERENCES `user` (`id`, `user_types_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_student_major1` FOREIGN KEY (`major_id`) REFERENCES `major` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `student_has_instructor`
--
ALTER TABLE `student_has_instructor`
  ADD CONSTRAINT `fk_student_has_instructor_instructor1` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_student_has_instructor_student1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tutor`
--
ALTER TABLE `tutor`
  ADD CONSTRAINT `fk_tutor_major1` FOREIGN KEY (`major_id`) REFERENCES `major` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tutor_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tutor_has_course`
--
ALTER TABLE `tutor_has_course`
  ADD CONSTRAINT `fk_tutor_has_course_course1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tutor_has_course_tutor1` FOREIGN KEY (`tutor_user_id`) REFERENCES `tutor` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_user_types` FOREIGN KEY (`user_types_id`) REFERENCES `user_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
=======
LOCK TABLES `user_types` WRITE;
/*!40000 ALTER TABLE `user_types` DISABLE KEYS */;
INSERT INTO `user_types` VALUES (16,'admin'),(17,'secretary'),(18,'tutor');
/*!40000 ALTER TABLE `user_types` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-09-14 18:22:35
>>>>>>> 711899bb9b38e1713bebd8efbfe8a51dbcb8885f
