
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 21, 2014 at 05:54 AM
-- Server version: 10.0.12-MariaDB
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `u110998101_sassd`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `user_id` int(11) NOT NULL,
  `user_types_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_admin_user_types1_idx` (`user_types_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE IF NOT EXISTS `appointment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `course_id` int(11) NOT NULL,
  `tutor_user_id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_appointment_course1_idx` (`course_id`),
  KEY `fk_appointment_tutor1_idx` (`tutor_user_id`),
  KEY `fk_appointment_schedule1_idx` (`term_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `start_time`, `end_time`, `course_id`, `tutor_user_id`, `term_id`) VALUES
(17, '2014-09-19 15:26:00', '2014-09-19 15:56:00', 8, 11, 2),
(18, '2014-09-20 12:00:00', '2014-09-20 12:30:00', 7, 16, 2);

-- --------------------------------------------------------

--
-- Table structure for table `appointment_has_student`
--

CREATE TABLE IF NOT EXISTS `appointment_has_student` (
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
  KEY `fk_appointment_has_student_instructor1_idx` (`instructor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `appointment_has_student`
--

INSERT INTO `appointment_has_student` (`id`, `appointment_id`, `student_id`, `report_id`, `instructor_id`) VALUES
(3, 17, 5, NULL, 4),
(4, 18, 6, NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `name` varchar(125) CHARACTER SET latin1 NOT NULL,
  `level` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `code`, `name`, `level`) VALUES
(6, 'CS1070', 'Introduction to Computers', NULL),
(7, 'EN1212', 'Intro to English', NULL),
(8, 'MA1001', 'Math', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE IF NOT EXISTS `instructor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `f_name` varchar(45) NOT NULL,
  `l_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `instructor`
--

INSERT INTO `instructor` (`id`, `f_name`, `l_name`) VALUES
(4, 'Maira', 'Kotsovoulou');

-- --------------------------------------------------------

--
-- Table structure for table `major`
--

CREATE TABLE IF NOT EXISTS `major` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `code` varchar(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `major`
--

INSERT INTO `major` (`id`, `name`, `code`) VALUES
(2, 'Undecided', 'UNN');

-- --------------------------------------------------------

--
-- Table structure for table `outcome_of_session`
--

CREATE TABLE IF NOT EXISTS `outcome_of_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(45) NOT NULL,
  `report_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_outcome_of_session_report1_idx` (`report_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `primary_focus_of_conference`
--

CREATE TABLE IF NOT EXISTS `primary_focus_of_conference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(45) NOT NULL,
  `report_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_primary_focus_of_conference_report1_idx` (`report_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `students_concerns` text,
  `relevant_feedback_or_guidelines` text,
  `additional_comments` text,
  PRIMARY KEY (`id`),
  KEY `fk_appointment_has_student_student1_idx` (`student_id`),
  KEY `fk_appointment_has_student_has_report_has_instructor_instru_idx` (`instructor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=170 ;

-- --------------------------------------------------------

--
-- Table structure for table `secretary`
--

CREATE TABLE IF NOT EXISTS `secretary` (
  `user_id` int(11) NOT NULL,
  `user_types_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_secretary_user_types1_idx` (`user_types_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
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
  KEY `fk_student_major1_idx` (`major_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `studentId`, `email`, `f_name`, `l_name`, `mobile`, `ci`, `credits`, `major_id`) VALUES
(5, 135995, 'p.anastasakis@acg.edu', 'Ellie', 'Anastasakis', '6982136576', NULL, NULL, 2),
(6, 180486, 'a.sourlis@acg.edu', 'Aristotelis', 'Sourlis', '6988416421', 3.5, NULL, 2),
(7, 126198, 'a.villiotis@acg.edu', 'Aggelos', 'Villiotis', '6985734144', 2.9, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `student_brought_along`
--

CREATE TABLE IF NOT EXISTS `student_brought_along` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(45) NOT NULL,
  `report_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_student_brought_along_report1_idx` (`report_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `term`
--

CREATE TABLE IF NOT EXISTS `term` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `term`
--

INSERT INTO `term` (`id`, `name`, `start_date`, `end_date`) VALUES
(2, 'Fall Semester 2014', '2014-09-16 13:39:00', '2014-12-15 15:39:00'),
(5, 'Fall Semester 2015', '2015-09-15 06:40:00', '2014-12-15 06:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `tutor`
--

CREATE TABLE IF NOT EXISTS `tutor` (
  `user_id` int(11) NOT NULL,
  `major_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_tutor_user1_idx` (`user_id`),
  KEY `fk_tutor_major1_idx` (`major_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tutor`
--

INSERT INTO `tutor` (`user_id`, `major_id`) VALUES
(11, 2),
(14, 2),
(15, 2),
(16, 2),
(20, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tutor_has_course_has_term`
--

CREATE TABLE IF NOT EXISTS `tutor_has_course_has_term` (
  `tutor_user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tutor_has_course_course1_idx` (`course_id`),
  KEY `fk_tutor_has_course_tutor1_idx` (`tutor_user_id`),
  KEY `fk_tutor_has_course_has_schedule_term1_idx` (`term_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `tutor_has_course_has_term`
--

INSERT INTO `tutor_has_course_has_term` (`tutor_user_id`, `course_id`, `id`, `term_id`) VALUES
(16, 7, 11, 2),
(20, 6, 12, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
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
  KEY `fk_user_user_types_idx` (`user_types_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `f_name`, `l_name`, `password`, `img_loc`, `date`, `user_types_id`, `mobile`, `profile_description`, `active`, `gen_string`, `gen_string_update_at`) VALUES
(2, 'r.dokollari@acg.edu', 'Rizart', 'Dokollari', '$2y$10$VgXd4dMq.ZWDhA7qOPwV8utvA7W6gu4Sq/YZxgWcFsBFVE.Ws6OKe', 'assets/img/avatars/avatar_img_2.png', '2014-09-16 13:26:42', 4, '6983827751', 'Lover, hacker, vault dweller.', 1, '', '2014-09-20 06:35:11'),
(6, 'G.Skarlatos@acg.edu', 'George', 'Skarlatos', '$2y$10$VNYezLQEWkfiVWhkWv6/NO.sWjwP6WVI/axwkABBs/WNB2UM0uk62', 'assets/img/avatars/default_avatar.jpg', '2014-09-17 03:10:49', 4, NULL, NULL, 1, '', '2014-09-19 09:06:49'),
(8, 'p.anastasakis@acg.edu', 'Penelope', 'Anastasakis', '$2y$10$nOrgUzz8887gK3wLRldctOPY67ptWH.LX8cP/bgKa/G4MgcwX.4vi', 'assets/img/avatars/default_avatar.jpg', '2014-09-17 09:16:31', 4, NULL, NULL, 1, '', '2014-09-17 12:17:28'),
(9, 'a.darivaki@acg.edu', 'Aimilia', 'Darivaki', '$2y$10$75qWQlw9u8BJ4n0ldQa/s.X4BvaeqcqUJ7hVVbdn3OIyXZNPeTW7e', 'assets/img/avatars/default_avatar.jpg', '2014-09-17 09:46:11', 4, '6973361028', NULL, 1, '5419895e73c185.96454964WMCARUJGXV', '2014-09-17 13:15:10'),
(10, 'dale.pappas59@gmail.com', 'Dale', 'Pappas', '$2y$10$lnrRm1FRJuQeNJK5hAg06ufT3C/KX2OyaU6q7FLXbDUeDeyOHgHFK', 'assets/img/avatars/default_avatar.jpg', '2014-09-17 09:48:59', 4, NULL, NULL, 1, '', '2014-09-17 12:49:56'),
(11, 't.vasilopoulou@acg.edu', 'Fani', 'Vassilopoulou', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-17 10:44:41', 6, NULL, NULL, 1, '54199082bf32d9.88627267PWSCZKUTHJ', '2014-09-17 13:45:38'),
(12, 'sourlakos17@hotmail.com', 'Aristotelis', 'Sourlis', '$2y$10$GlFxnpwMGAfrmcN7GRtpseatFzPG9pdhV7QaeOZJkMG2Jxk5.h23y', 'assets/img/avatars/default_avatar.jpg', '2014-09-17 11:14:13', 4, NULL, NULL, 1, '', '2014-09-17 14:15:10'),
(13, 'a.villiotis@acg.edu', 'Aggelos', 'Villiotis', '$2y$10$JReXes7IZnxwZ2xOT6CC7.kBujMYa.iLpWqzIbGpE0K6NJXH2aSZm', 'assets/img/avatars/default_avatar.jpg', '2014-09-18 05:03:23', 5, NULL, NULL, 1, '', '2014-09-18 08:04:21'),
(14, 'm.kalokairinos@acg.edu', 'Manos', 'Kalokairinos', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-18 05:09:02', 6, NULL, NULL, 1, '541a935899b799.54395849YILRKBWTDH', '2014-09-18 08:10:00'),
(15, 'a.diamantopoulou@acg.edu', 'Artemis', 'Diamantopoulou', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-18 05:10:44', 6, NULL, NULL, 1, '541a93becfe278.73822969BRYFLTXEPJ', '2014-09-18 08:11:42'),
(16, 't.sotiriou@acg.edu', 'Vladimir', 'Putin', '$2y$10$UngKhrAHXa9/NV6mq9hn1ug62YUzdpAt2RcfjByKgPpHX4KNqPXBm', 'assets/img/avatars/default_avatar.jpg', '2014-09-18 05:25:07', 6, NULL, NULL, 1, '', '2014-09-18 08:26:05'),
(20, 'g.ziaidis@acg.edu', 'testg', 'testg', '$2y$10$rUvwgExYdmn4URKtipH9hOluOzhaCINc90pKQcG6vW1Vlkfpw6iSG', 'assets/img/avatars/default_avatar.jpg', '2014-09-19 12:40:08', 6, NULL, NULL, 1, '', '2014-09-19 15:41:07');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE IF NOT EXISTS `user_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `description_UNIQUE` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `type`) VALUES
(4, 'admin'),
(5, 'secretary'),
(6, 'tutor');

-- --------------------------------------------------------

--
-- Table structure for table `work_week_hours`
--

CREATE TABLE IF NOT EXISTS `work_week_hours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start` timestamp NULL DEFAULT NULL,
  `end` timestamp NULL DEFAULT NULL,
  `term_id` int(11) NOT NULL,
  `tutor_user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_work_day_schedule1_idx` (`term_id`),
  KEY `fk_work_week_hours_tutor1_idx` (`tutor_user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `work_week_hours`
--

INSERT INTO `work_week_hours` (`id`, `start`, `end`, `term_id`, `tutor_user_id`) VALUES
(5, '2014-09-19 17:25:00', '2014-09-19 17:55:00', 2, 11);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
