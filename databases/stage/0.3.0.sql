
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 21, 2014 at 12:18 PM
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `major`
--

INSERT INTO `major` (`id`, `name`, `code`) VALUES
(2, 'I do not know', 'A'),
(4, 'Undecided', 'B'),
(5, 'Acounting and Finance', 'AF'),
(6, 'Computer Information Systems', 'CIS'),
(7, 'International Business and European Affairs', 'IBEA'),
(8, 'International Tourism and Hospitality Managem', 'ITHM'),
(9, 'Management', 'MG'),
(10, 'Marketing', 'MK'),
(11, 'Communication', 'CN'),
(12, 'Economics', 'EC'),
(13, 'English', 'EN'),
(14, 'Environmental Studies', 'ES'),
(15, 'History', 'HY'),
(16, 'Information Technology', 'IT'),
(17, 'Philosophy', 'PH'),
(18, 'Psychology', 'PS'),
(19, 'Sociology', 'SO'),
(20, 'Art History', 'AT');

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
(2, 'Fall Semester 2014', '2014-09-15 13:39:00', '2014-12-15 15:39:00');

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
(26, 2),
(25, 2),
(24, 2),
(23, 2),
(22, 2),
(27, 2),
(28, 2),
(29, 2),
(30, 2),
(31, 2),
(32, 2),
(33, 2),
(34, 2),
(35, 2),
(36, 2),
(37, 2),
(38, 2),
(39, 2),
(40, 2),
(41, 2),
(42, 2),
(43, 2),
(45, 2),
(46, 2),
(49, 2),
(51, 2);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `f_name`, `l_name`, `password`, `img_loc`, `date`, `user_types_id`, `mobile`, `profile_description`, `active`, `gen_string`, `gen_string_update_at`) VALUES
(2, 'r.dokollari@acg.edu', 'Rizart', 'Dokollari', '$2y$10$VgXd4dMq.ZWDhA7qOPwV8utvA7W6gu4Sq/YZxgWcFsBFVE.Ws6OKe', 'assets/img/avatars/avatar_img_2.png', '2014-09-16 13:26:42', 4, '6983827751', 'Lover, hacker, vault dweller.', 1, '', '2014-09-20 06:35:11'),
(6, 'G.Skarlatos@acg.edu', 'George', 'Skarlatos', '$2y$10$VNYezLQEWkfiVWhkWv6/NO.sWjwP6WVI/axwkABBs/WNB2UM0uk62', 'assets/img/avatars/default_avatar.jpg', '2014-09-17 03:10:49', 4, '6986627210', NULL, 1, '', '2014-09-19 09:06:49'),
(22, 'a.boulougari@acg.edu', 'Andromachi', 'Boulougari', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 11:38:12', 6, NULL, NULL, 1, '541eb8e2ad6861.45809004AXGSDUHQIP', '2014-09-21 14:39:14'),
(9, 'a.darivaki@acg.edu', 'Aimilia', 'Darivaki', '$2y$10$75qWQlw9u8BJ4n0ldQa/s.X4BvaeqcqUJ7hVVbdn3OIyXZNPeTW7e', 'assets/img/avatars/default_avatar.jpg', '2014-09-17 09:46:11', 4, '6973361028', NULL, 1, '5419895e73c185.96454964WMCARUJGXV', '2014-09-17 13:15:10'),
(31, 'e.kalokairinos@acg.edu', 'Emmanouil', 'Kalokairinos', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 11:57:17', 6, NULL, NULL, 1, '541ebd5b34a931.04756726TXVQCAWOIZ', '2014-09-21 14:58:19'),
(30, 's.gavrilaki@acg.edu', 'Sofia', 'Gavrilaki', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 11:56:37', 6, NULL, NULL, 1, '541ebd32df2be1.22987127WZKTMUJDHR', '2014-09-21 14:57:38'),
(29, 'a.davila@acg.edu', 'Alejandra', 'Yanez Davila', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 11:55:48', 6, NULL, NULL, 1, '541ebd0290efa4.13002225OANHQVDXPU', '2014-09-21 14:56:50'),
(28, 'g.ziaidis@acg.edu', 'Georgios', 'Ziaidis', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 11:54:59', 6, NULL, NULL, 1, '541ebcd0e9a8a4.23497360KWPGMBQERL', '2014-09-21 14:56:00'),
(27, 'a.filippopoulou@acg.edu', 'Athanasia', 'Filippopoulou', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 11:51:33', 6, NULL, NULL, 1, '541ebc03bcadc4.16311416TGBNECMWLR', '2014-09-21 14:52:35'),
(26, 'g.falireas@acg.edu', 'Gregory', 'Falireas', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 11:50:47', 6, NULL, NULL, 1, '541ebbd56bdda9.92395784CEJGOBLVTN', '2014-09-21 14:51:49'),
(25, 'a.diamantopoulou@acg.edu', 'Artemis', 'Diamantopoulou', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 11:45:10', 6, NULL, NULL, 1, '541eba83d3dc21.74642647YQNCXJLOKG', '2014-09-21 14:46:11'),
(23, 'm.chioti@acg.edu', 'Maria', 'Chioti', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 11:41:24', 6, NULL, NULL, 1, '541eb9a2094060.49169986UNMGPICZTO', '2014-09-21 14:42:26'),
(24, 'm.diamantidis@acg.edu', 'Mary', 'Diamantidis', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 11:42:37', 6, NULL, NULL, 1, '541eb9eb205445.76695079WYXUIGFMAL', '2014-09-21 14:43:39'),
(32, 'k.karaiou@acg.edu', 'Kyriaki', 'Karadimou', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 11:57:52', 6, NULL, NULL, 1, '541ebd7e3006c0.15581752YXNMJKWODI', '2014-09-21 14:58:54'),
(33, 'e.kolici@acg.edu', 'Edela Emmanouela', 'Kolici', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 11:59:22', 6, NULL, NULL, 1, '541ebdd8889f91.94370677EICVLAKJDZ', '2014-09-21 15:00:24'),
(34, 'f.mouriki@acg.edu', 'Fotini', 'Mouriki', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 11:59:51', 6, NULL, NULL, 1, '541ebdf55be3b3.57298468BLCQUDXOVA', '2014-09-21 15:00:53'),
(35, 'a.mylona@acg.edu', 'Alexandra', 'Mylona', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:00:29', 6, NULL, NULL, 1, '541ebe1b1ac285.61318886QTCFRIZVNL', '2014-09-21 15:01:31'),
(36, 'a.orfanopoulou@acg.edu', 'Apollonia', 'Orfanopoulou', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:01:07', 6, NULL, NULL, 1, '541ebe410e0686.89107060ZHNCGSPTKB', '2014-09-21 15:02:09'),
(37, 'i.papadakis@acg.edu', 'Irene', 'Papadakis', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:01:42', 6, NULL, NULL, 1, '541ebe6441cde8.88492376FQABRMCKXN', '2014-09-21 15:02:44'),
(38, 'm.papaioannou@acg.edu', 'Maria Zoe', 'Papaioannou', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:02:35', 6, NULL, NULL, 1, '541ebe98c8f473.44549498IHRFLATMKU', '2014-09-21 15:03:36'),
(39, 'dale.pappas59@gmail.com', 'Dale', 'Pappas', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:03:49', 6, NULL, NULL, 1, '541ebee31bde66.07064632LCMUDEASIY', '2014-09-21 15:04:51'),
(40, 'p.paraskevopoulos@acg.edu', 'Petros Ioannis', 'Paraskevopoulos', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:04:44', 6, NULL, NULL, 1, '541ebf1a413291.83091315YOUWXHNSMZ', '2014-09-21 15:05:46'),
(41, 'n.saranti@acg.edu', 'Natalia', 'Saranti', '$2y$10$AMLBKpDdrK6xgD.L/HVyLeOGHqFaoz.5d98ePlJhs9VqFkjNp4WF.', 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:06:22', 6, NULL, NULL, 1, '', '2014-09-21 15:07:24'),
(42, 't.sotiriou@acg.edu', 'Thodoris', 'Sotiriou', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:07:01', 6, NULL, NULL, 1, '541ebfa34e9c44.94833863QCUDAPBNIX', '2014-09-21 15:08:03'),
(43, 'v.sotiropoulos@acg.edu', 'Vasilis', 'Sotiropoulos', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:07:39', 6, NULL, NULL, 1, '541ebfc96a0526.81666947JOSCDVGXQW', '2014-09-21 15:08:41'),
(44, 'a.sourlis@acg.edu', 'Aristotelis', 'Sourlis', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:08:21', 5, NULL, NULL, 1, '541ebff2d42539.80766562MXTELIDUPF', '2014-09-21 15:09:22'),
(45, 'd.spyrou@acg.edu', 'Dimitrios', 'spyrou', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:09:21', 6, NULL, NULL, 1, '541ec02f4f02c0.25890837PBHOWZAJTV', '2014-09-21 15:10:23'),
(46, 'e.tzempelikou@acg.edu', 'Eleni', 'Tzempelikou', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:10:06', 6, NULL, NULL, 1, '541ec05be37f25.76762322OZDXRICAFQ', '2014-09-21 15:11:07'),
(47, 's.vallianos@acg.edu', 'Spyros', 'Vallianos', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:11:21', 5, NULL, NULL, 1, '541ec0a7158383.23622508BQWJCTUNPG', '2014-09-21 15:12:23'),
(48, 'm.vartholomaios@acg.edu', 'Michail', 'Vartholomaios', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:12:57', 5, NULL, NULL, 1, '541ec1073dafb9.69268609AZVCLJXKEI', '2014-09-21 15:13:59'),
(49, 't.vasilipoulou@acg.edu', 'Theofano', 'Vasilipoulou', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:13:33', 6, NULL, NULL, 1, '541ec12b234901.28586907WJTHDAILER', '2014-09-21 15:14:35'),
(50, 'a.villiotis@acg.edu', 'Angelos', 'Villiotis', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:14:09', 5, NULL, NULL, 1, '541ec14f847e06.65523311PKTFMDXSLB', '2014-09-21 15:15:11'),
(51, 'a.vogiatzis@acg.edu', 'Alexandros', 'Vogiatzis', NULL, 'assets/img/avatars/default_avatar.jpg', '2014-09-21 12:16:22', 6, NULL, NULL, 1, '541ec1d47f49f2.92421565FYJALNHTQD', '2014-09-21 15:17:24');

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
