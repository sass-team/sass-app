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


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sass-ms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `user_id` int(11) NOT NULL,
  `user_user_types_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`user_user_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `code`, `name`, `level`) VALUES
(21, '4', 'COURSE2', NULL),
(22, '1', 'na', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE IF NOT EXISTS `instructor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `f_name` varchar(45) NOT NULL,
  `l_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `instructor`
--

INSERT INTO `instructor` (`id`, `f_name`, `l_name`) VALUES
(2, 'geor', 'skarl');

-- --------------------------------------------------------

--
-- Table structure for table `major`
--

CREATE TABLE IF NOT EXISTS `major` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET latin1 NOT NULL,
  `code` varchar(6) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `major`
--

INSERT INTO `major` (`id`, `name`, `code`) VALUES
(6, 'it', 'co'),
(8, 'info', 'ITC'),
(9, 'IN', 'IT');

-- --------------------------------------------------------

--
-- Table structure for table `secretary`
--

CREATE TABLE IF NOT EXISTS `secretary` (
  `user_id` int(11) NOT NULL,
  `user_user_types_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`user_user_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `credits` int(11) DEFAULT NULL,
  `major_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `studentId_UNIQUE` (`studentId`),
  UNIQUE KEY `mobile_UNIQUE` (`mobile`),
  KEY `fk_student_major1_idx` (`major_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `studentId`, `email`, `f_name`, `l_name`, `mobile`, `ci`, `credits`, `major_id`) VALUES
(1, 123456, 'emai@email.com3', 'first', 'last', '6983827751', 3.5, 50, 9);

-- --------------------------------------------------------

--
-- Table structure for table `student_has_instructor`
--

CREATE TABLE IF NOT EXISTS `student_has_instructor` (
  `student_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  PRIMARY KEY (`student_id`,`instructor_id`),
  KEY `fk_student_has_instructor_instructor1_idx` (`instructor_id`),
  KEY `fk_student_has_instructor_student1_idx` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tutor`
--

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

--
-- Table structure for table `tutor_has_course`
--

CREATE TABLE IF NOT EXISTS `tutor_has_course` (
  `tutor_user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `fk_tutor_has_course_course1_idx` (`course_id`),
  KEY `fk_tutor_has_course_tutor1_idx` (`tutor_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `tutor_has_course`
--

INSERT INTO `tutor_has_course` (`tutor_user_id`, `course_id`, `id`) VALUES
(26, 21, 12),
(33, 21, 13),
(43, 21, 16),
(44, 21, 19);

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
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'date of account creation',
  `user_types_id` int(11) NOT NULL,
  `mobile` varchar(10) DEFAULT NULL,
  `profile_description` varchar(512) DEFAULT NULL,
  `gen_string` varchar(45) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`,`user_types_id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `mobile_UNIQUE` (`mobile`),
  KEY `fk_user_user_types_idx` (`user_types_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

--
-- Dumping data for table `user`
--

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

--
-- Table structure for table `user_types`
--

CREATE TABLE IF NOT EXISTS `user_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `description_UNIQUE` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `user_types`
--

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
