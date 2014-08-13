
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 13, 2014 at 06:08 PM
-- Server version: 5.1.66
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `u946435197_sass`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `user_id` int(11) NOT NULL,
  `user_user_types_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`user_user_types_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `name` varchar(125) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `code`, `name`) VALUES
(1, '2186', 'Computer System Architecture'),
(2, '2234', 'Object Oriented Programming'),
(3, '3157', 'Project Management'),
(4, '2201', 'Contemporary Mass Communication'),
(5, '2206', 'Interpersonal Communication');

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE IF NOT EXISTS `instructor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `f_name` varchar(45) DEFAULT NULL,
  `l_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `instructor_has_course`
--

CREATE TABLE IF NOT EXISTS `instructor_has_course` (
  `instructor_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  PRIMARY KEY (`instructor_id`,`course_id`),
  KEY `fk_instructor_has_course_course1_idx` (`course_id`),
  KEY `fk_instructor_has_course_instructor1_idx` (`instructor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `major`
--

CREATE TABLE IF NOT EXISTS `major` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET latin1 NOT NULL,
  `extension` varchar(6) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `code_UNIQUE` (`extension`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `major`
--

INSERT INTO `major` (`id`, `name`, `extension`) VALUES
(1, 'Communication', 'CN'),
(2, 'Information Technology', 'ITC');

-- --------------------------------------------------------

--
-- Table structure for table `major_has_courses`
--

CREATE TABLE IF NOT EXISTS `major_has_courses` (
  `major_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  PRIMARY KEY (`major_id`,`course_id`),
  KEY `fk_major_has_courses_course1_idx` (`course_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `major_has_courses`
--

INSERT INTO `major_has_courses` (`major_id`, `course_id`) VALUES
(1, 4),
(1, 5),
(2, 1),
(2, 2),
(2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `secretary`
--

CREATE TABLE IF NOT EXISTS `secretary` (
  `user_id` int(11) NOT NULL,
  `user_user_types_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`user_user_types_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(125) NOT NULL,
  `f_name` varchar(45) NOT NULL,
  `l_name` varchar(45) NOT NULL,
  `mobile` int(11) NOT NULL,
  `ci` double NOT NULL,
  `credits` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `student_has_course`
--

CREATE TABLE IF NOT EXISTS `student_has_course` (
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  PRIMARY KEY (`student_id`,`course_id`),
  KEY `fk_student_has_course_course1_idx` (`course_id`),
  KEY `fk_student_has_course_student1_idx` (`student_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tutor`
--

CREATE TABLE IF NOT EXISTS `tutor` (
  `major_id` int(11) NOT NULL,
  `major_id1` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_user_types_id` int(11) NOT NULL,
  PRIMARY KEY (`major_id`,`major_id1`,`user_id`,`user_user_types_id`),
  KEY `fk_tutor_major2_idx` (`major_id1`),
  KEY `fk_tutor_user1_idx` (`user_id`,`user_user_types_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tutor_teaches_courses`
--

CREATE TABLE IF NOT EXISTS `tutor_teaches_courses` (
  `tutor_major_id` int(11) NOT NULL,
  `tutor_major_id1` int(11) NOT NULL,
  `tutor_user_id` int(11) NOT NULL,
  `tutor_user_user_types_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  PRIMARY KEY (`tutor_major_id`,`tutor_major_id1`,`tutor_user_id`,`tutor_user_user_types_id`,`course_id`),
  KEY `fk_tutor_teaches_courses_course1_idx` (`course_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(125) NOT NULL,
  `f_name` varchar(45) NOT NULL,
  `l_name` varchar(45) NOT NULL,
  `password` varchar(512) DEFAULT NULL,
  `img_loc` varchar(125) NOT NULL DEFAULT 'img/avatars/default_avatar.jpg',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'date of account creation',
  `user_types_id` int(11) NOT NULL,
  `mobile` varchar(10) DEFAULT NULL,
  `profile_description` varchar(512) DEFAULT NULL,
  `gen_string` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`,`user_types_id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `mobile_UNIQUE` (`mobile`),
  KEY `fk_user_user_types_idx` (`user_types_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `f_name`, `l_name`, `password`, `img_loc`, `date`, `user_types_id`, `mobile`, `profile_description`, `gen_string`) VALUES
(1, 'r.dokollari@acg.edu', 'Rizartas', 'Dokollsdfariafdfrfdsa', '$2y$10$nsaeOnv2ALH0o8dASQnP1.MrqWxdmund1ngwkQ0LyUynZT93H0k0W', 'app/assets/img/avatars/avatar_img_1.jpg', '2014-08-13 17:39:52', 7, '6983827759', 'Lover, Hacxgddker, Vfasdfgdzgdfgdsfgulsdfgtsdasdfsaffa Dweller2fchsdfdfdfsddsaf', '53eba2e8cca7d6.76187032RMGNCQEOWV'),
(2, 'r.dokollari@gmail.com', 'Rizart', 'Dokollari', '', 'img/avatars/default_avatar.jpg', '2014-07-22 18:06:22', 7, '6983827752', NULL, NULL),
(6, 'r.dokollari@acg.edu2', 'Riz', 'Dok', NULL, 'img/avatars/default_avatar.jpg', '2014-07-22 21:24:11', 9, NULL, NULL, NULL),
(7, 'r.dokollari@gmail.coma', 'd', 's', '$2y$10$eJlVOlBjsYrDias.Q5B3Vu8HmPelABeaoCNOvMxGpZd7MN.nomXcK', 'img/avatars/default_avatar.jpg', '2014-07-30 07:02:42', 9, NULL, NULL, '53d898920e3d31.28625952PRTUANMQSC'),
(8, 'geo-i.f@hotmail.com', 'Ger', 'Skar', NULL, 'img/avatars/default_avatar.jpg', '2014-08-13 17:37:46', 7, NULL, NULL, NULL);

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
(8, 'secretary'),
(9, 'tutor');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
