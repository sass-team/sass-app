
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 12, 2014 at 01:59 PM
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
  `label_message` varchar(15) NOT NULL DEFAULT 'pending',
  `label_color` varchar(15) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`),
  KEY `fk_appointment_course1_idx` (`course_id`),
  KEY `fk_appointment_tutor1_idx` (`tutor_user_id`),
  KEY `fk_appointment_schedule1_idx` (`term_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `start_time`, `end_time`, `course_id`, `tutor_user_id`, `term_id`, `label_message`, `label_color`) VALUES
(1, '2014-10-03 09:00:00', '2014-10-03 09:30:00', 10, 19, 7, 'complete', 'success'),
(2, '2014-10-06 09:00:00', '2014-10-06 09:30:00', 10, 19, 7, 'pending', 'default'),
(3, '2014-10-03 16:30:00', '2014-10-03 17:00:00', 10, 19, 7, 'complete', 'success');

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
(1, 1, 8, 52, 2),
(2, 2, 8, NULL, 2),
(3, 3, 8, 55, 2),
(4, 3, 9, 56, 2);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `code`, `name`, `level`) VALUES
(10, 'MA', 'Maths', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE IF NOT EXISTS `instructor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `f_name` varchar(45) NOT NULL,
  `l_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `instructor`
--

INSERT INTO `instructor` (`id`, `f_name`, `l_name`) VALUES
(2, 'first', 'last');

-- --------------------------------------------------------

--
-- Table structure for table `mail`
--

CREATE TABLE IF NOT EXISTS `mail` (
  `last_sent` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mail`
--

INSERT INTO `mail` (`last_sent`) VALUES
('2014-10-11 16:14:31'),
('2014-10-11 16:15:49'),
('2014-10-11 16:15:58'),
('2014-10-11 16:15:59'),
('2014-10-11 16:16:00'),
('2014-10-11 16:16:00'),
('2014-10-11 16:16:01'),
('2014-10-11 16:16:02'),
('2014-10-11 16:16:07'),
('2014-10-11 16:16:08'),
('2014-10-11 16:16:14'),
('2014-10-11 16:18:37'),
('2014-10-11 16:18:37'),
('2014-10-11 16:18:38'),
('2014-10-11 16:18:39'),
('2014-10-11 16:18:40'),
('2014-10-11 16:18:41'),
('2014-10-11 16:18:41'),
('2014-10-11 16:18:42'),
('2014-10-11 16:18:43'),
('2014-10-11 16:18:44'),
('2014-10-11 16:18:44'),
('2014-10-11 16:18:45'),
('2014-10-11 16:18:46'),
('2014-10-11 16:18:47'),
('2014-10-11 16:18:48'),
('2014-10-11 16:18:48'),
('2014-10-11 16:18:49'),
('2014-10-11 16:18:50'),
('2014-10-11 16:18:51'),
('2014-10-11 16:19:38'),
('2014-10-11 16:19:38'),
('2014-10-11 18:04:23'),
('2014-10-11 23:54:39'),
('2014-10-12 06:33:25'),
('2014-10-12 06:34:04'),
('2014-10-12 06:35:24'),
('2014-10-12 06:35:25'),
('2014-10-12 06:35:26'),
('2014-10-12 06:35:26'),
('2014-10-12 06:35:27'),
('2014-10-12 06:35:28'),
('2014-10-12 06:35:28'),
('2014-10-12 06:36:29'),
('2014-10-12 06:57:44'),
('2014-10-12 06:57:52'),
('2014-10-12 06:58:13'),
('2014-10-12 06:58:13'),
('2014-10-12 06:58:14'),
('2014-10-12 06:58:15'),
('2014-10-12 06:58:16'),
('2014-10-12 06:58:16'),
('2014-10-12 06:58:18'),
('2014-10-12 06:58:18'),
('2014-10-12 06:58:45'),
('2014-10-12 06:58:54'),
('2014-10-12 06:59:14'),
('2014-10-12 06:59:56'),
('2014-10-12 06:59:56'),
('2014-10-12 06:59:57'),
('2014-10-12 06:59:58'),
('2014-10-12 06:59:59'),
('2014-10-12 06:59:59'),
('2014-10-12 07:00:00'),
('2014-10-12 07:00:00'),
('2014-10-12 07:00:01'),
('2014-10-12 07:00:02'),
('2014-10-12 07:00:03'),
('2014-10-12 07:00:04'),
('2014-10-12 07:00:04'),
('2014-10-12 07:00:05'),
('2014-10-12 07:00:06'),
('2014-10-12 07:00:07'),
('2014-10-12 07:00:07'),
('2014-10-12 07:00:08'),
('2014-10-12 07:00:15'),
('2014-10-12 07:00:57'),
('2014-10-12 07:01:35'),
('2014-10-12 07:01:37'),
('2014-10-12 07:01:38'),
('2014-10-12 07:01:39'),
('2014-10-12 07:01:40'),
('2014-10-12 07:03:17'),
('2014-10-12 07:03:18'),
('2014-10-12 07:03:19'),
('2014-10-12 07:03:19'),
('2014-10-12 07:03:20'),
('2014-10-12 07:03:21'),
('2014-10-12 07:03:21'),
('2014-10-12 07:03:22'),
('2014-10-12 07:03:22'),
('2014-10-12 07:03:23'),
('2014-10-12 07:03:24'),
('2014-10-12 07:03:24'),
('2014-10-12 07:03:25'),
('2014-10-12 07:03:26'),
('2014-10-12 07:03:26'),
('2014-10-12 07:03:27'),
('2014-10-12 07:03:28'),
('2014-10-12 07:03:28'),
('2014-10-12 07:03:29'),
('2014-10-12 07:04:18'),
('2014-10-12 07:04:20'),
('2014-10-12 07:19:30'),
('2014-10-12 07:19:31'),
('2014-10-12 07:19:32'),
('2014-10-12 07:19:32'),
('2014-10-12 07:19:33'),
('2014-10-12 07:19:33'),
('2014-10-12 07:19:34'),
('2014-10-12 07:19:34'),
('2014-10-12 07:19:35'),
('2014-10-12 07:19:35'),
('2014-10-12 07:19:36'),
('2014-10-12 07:19:36'),
('2014-10-12 07:19:37'),
('2014-10-12 07:19:37'),
('2014-10-12 07:19:38'),
('2014-10-12 07:19:39'),
('2014-10-12 07:19:40'),
('2014-10-12 07:19:40'),
('2014-10-12 07:19:41'),
('2014-10-12 07:20:39'),
('2014-10-12 07:27:20'),
('2014-10-12 07:27:20'),
('2014-10-12 07:27:21'),
('2014-10-12 07:27:22'),
('2014-10-12 07:27:22'),
('2014-10-12 07:27:23'),
('2014-10-12 07:27:24'),
('2014-10-12 07:27:24'),
('2014-10-12 07:27:25'),
('2014-10-12 07:27:26'),
('2014-10-12 07:27:26'),
('2014-10-12 07:27:27'),
('2014-10-12 07:27:28'),
('2014-10-12 07:27:28'),
('2014-10-12 07:27:30'),
('2014-10-12 07:27:30'),
('2014-10-12 07:27:31'),
('2014-10-12 07:27:32'),
('2014-10-12 07:27:33'),
('2014-10-12 07:28:21'),
('2014-10-12 07:28:21'),
('2014-10-12 07:28:22'),
('2014-10-12 07:28:23'),
('2014-10-12 07:28:23'),
('2014-10-12 07:28:24'),
('2014-10-12 07:28:38'),
('2014-10-12 07:29:55'),
('2014-10-12 07:29:57'),
('2014-10-12 07:29:57'),
('2014-10-12 07:29:58'),
('2014-10-12 07:29:58'),
('2014-10-12 07:29:59'),
('2014-10-12 07:29:59'),
('2014-10-12 07:30:00'),
('2014-10-12 07:30:00'),
('2014-10-12 07:30:01'),
('2014-10-12 07:30:01'),
('2014-10-12 07:30:01'),
('2014-10-12 07:30:02'),
('2014-10-12 07:30:02'),
('2014-10-12 07:30:03'),
('2014-10-12 07:30:03'),
('2014-10-12 07:30:04'),
('2014-10-12 07:30:05'),
('2014-10-12 07:30:59'),
('2014-10-12 07:36:23'),
('2014-10-12 07:36:35'),
('2014-10-12 07:37:13'),
('2014-10-12 07:37:14'),
('2014-10-12 07:37:14'),
('2014-10-12 07:37:15'),
('2014-10-12 07:37:15'),
('2014-10-12 07:39:08'),
('2014-10-12 07:39:09'),
('2014-10-12 07:40:57'),
('2014-10-12 07:40:59'),
('2014-10-12 07:41:00'),
('2014-10-12 07:41:01'),
('2014-10-12 07:41:02'),
('2014-10-12 07:41:05'),
('2014-10-12 07:41:10'),
('2014-10-12 07:41:12'),
('2014-10-12 07:43:17'),
('2014-10-12 07:51:40'),
('2014-10-12 07:53:21'),
('2014-10-12 07:54:52'),
('2014-10-12 07:57:10'),
('2014-10-12 07:57:11'),
('2014-10-12 07:57:29'),
('2014-10-12 07:57:50'),
('2014-10-12 07:59:01'),
('2014-10-12 08:00:42'),
('2014-10-12 08:02:01'),
('2014-10-12 08:04:12'),
('2014-10-12 08:05:12'),
('2014-10-12 08:07:03'),
('2014-10-12 08:11:44'),
('2014-10-12 08:11:44'),
('2014-10-12 08:37:04'),
('2014-10-12 08:38:00'),
('2014-10-12 12:00:02'),
('2014-10-12 13:00:02'),
('2014-10-12 14:00:03'),
('2014-10-12 15:00:02'),
('2014-10-12 16:00:02'),
('2014-10-12 17:00:02');

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
(3, 'Undecided', 'UN');

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
  `project_topic_other` text NOT NULL,
  `students_concerns` text,
  `relevant_feedback_or_guidelines` text,
  `additional_comments` text,
  `label_message` varchar(25) NOT NULL DEFAULT 'pending fill',
  `label_color` varchar(15) NOT NULL DEFAULT 'warning',
  `other_text_area` text,
  PRIMARY KEY (`id`),
  KEY `fk_appointment_has_student_student1_idx` (`student_id`),
  KEY `fk_appointment_has_student_has_report_has_instructor_instru_idx` (`instructor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`id`, `student_id`, `instructor_id`, `project_topic_other`, `students_concerns`, `relevant_feedback_or_guidelines`, `additional_comments`, `label_message`, `label_color`, `other_text_area`) VALUES
(49, 8, 2, '', NULL, NULL, NULL, 'pending fill', 'warning', NULL),
(50, 9, 2, '', NULL, NULL, NULL, 'pending fill', 'warning', NULL),
(51, 8, 2, '', NULL, NULL, NULL, 'pending fill', 'warning', NULL),
(52, 8, 2, 'project project', NULL, NULL, NULL, 'pending fill', 'warning', NULL),
(55, 8, 2, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'Focus of Conference\r\n', '', '', 'pending validation', 'warning', ''),
(56, 9, 2, 'cccccccccccccccccccccccccccccc', 'Focus of Conference\r\n', '', '', 'pending validat', 'warning', '');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `studentId`, `email`, `f_name`, `l_name`, `mobile`, `ci`, `credits`, `major_id`) VALUES
(8, 1223344, 'emai@email.com', 'first name', 'lAST', '6983827751', NULL, NULL, 3),
(9, 234424, 'emai@email.com1', 'fIRST', 'l', '6983827750', NULL, NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `student_brought_along`
--

CREATE TABLE IF NOT EXISTS `student_brought_along` (
  `report_id` int(11) NOT NULL,
  `assignment_graded` tinyint(1) NOT NULL DEFAULT '0',
  `draft` tinyint(1) NOT NULL DEFAULT '0',
  `instructors_feedback` tinyint(1) NOT NULL DEFAULT '0',
  `textbook` tinyint(1) NOT NULL DEFAULT '0',
  `notes` tinyint(1) NOT NULL DEFAULT '0',
  `assignment_sheet` tinyint(1) NOT NULL DEFAULT '0',
  `exercise_on` varchar(45) DEFAULT NULL,
  `other` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`report_id`),
  KEY `fk_student_brought_along_report1_idx` (`report_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student_brought_along`
--

INSERT INTO `student_brought_along` (`report_id`, `assignment_graded`, `draft`, `instructors_feedback`, `textbook`, `notes`, `assignment_sheet`, `exercise_on`, `other`) VALUES
(52, 0, 1, 0, 0, 0, 0, 'ASDFASD', 'ASFASDFASDFDSFADSFADS'),
(55, 1, 0, 1, 1, 1, 1, NULL, ''),
(56, 0, 1, 1, 1, 1, 1, 'dfgdfg', NULL);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `term`
--

INSERT INTO `term` (`id`, `name`, `start_date`, `end_date`) VALUES
(7, 'Fall Semester 2014', '2014-10-03 05:50:00', '2014-12-25 06:50:00'),
(8, 'Fd', '2014-10-03 06:01:00', '2014-12-24 07:01:00');

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
(18, 3),
(19, 3),
(20, 3),
(21, 3);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tutor_has_course_has_term`
--

INSERT INTO `tutor_has_course_has_term` (`tutor_user_id`, `course_id`, `id`, `term_id`) VALUES
(19, 10, 7, 7);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `f_name`, `l_name`, `password`, `img_loc`, `date`, `user_types_id`, `mobile`, `profile_description`, `active`, `gen_string`, `gen_string_update_at`) VALUES
(17, 'r.dokollari@acg.edu', 'Rizart', 'Dokollari', '$2y$10$6Y76z7PxHON5N4qOVkxLfu9mmNhg6068pOWMejx/RpwfeUHi4kpN.', 'assets/img/avatars/default_avatar.jpg', '2014-09-24 11:37:53', 7, '6983827751', NULL, 1, '54267455cff021.11763509HJPXTYLROA', '2014-09-27 08:24:53'),
(18, 'emai@email.com', 'first', 'Dokollari', '$2y$10$TNd6cKv9XHGjybeQjfjI6Ov4ZUqXStvWR3Bs4Jl4ZPcbyk2OgWOlS', 'assets/img/avatars/default_avatar.jpg', '2014-09-24 12:55:12', 8, NULL, NULL, 1, '', '2014-09-26 03:00:19'),
(19, 'emai@email.com1', 'tutor_first', 'tutor_last', '$2y$10$9XAgZJ8gXdDri1eya72uReqmTy3yU3PE79PhY6uXxPh7.k0OvcPKW', 'assets/img/avatars/default_avatar.jpg', '2014-09-26 07:02:59', 8, NULL, NULL, 1, '', '2014-09-27 13:17:10'),
(20, 'emai@email.com2', 'first', 'last', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-27 08:27:58', 8, NULL, NULL, 1, '5426750ed57383.68051513VTNHUFBLQE', '2014-09-27 08:27:58'),
(21, 'emai@email.com5', 'first', 'last', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-10-03 03:27:21', 8, NULL, NULL, 1, '542e179a031ff0.00479269OBYGVEKFNI', '2014-10-03 03:27:22');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE IF NOT EXISTS `user_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `description_UNIQUE` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `type`) VALUES
(7, 'admin'),
(9, 'secretary'),
(8, 'tutor');

-- --------------------------------------------------------

--
-- Table structure for table `work_week_hours`
--

CREATE TABLE IF NOT EXISTS `work_week_hours` (
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
  KEY `fk_work_week_hours_tutor1_idx` (`tutor_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
