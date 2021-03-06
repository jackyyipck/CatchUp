-- phpMyAdmin SQL Dump
-- version 4.0.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 19, 2013 at 03:39 PM
-- Server version: 5.1.70
-- PHP Version: 5.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `seayu_catchup`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event`
--

CREATE TABLE IF NOT EXISTS `tbl_event` (
  `event_id` int(10) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `event_desc` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `event_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `event_expire_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_event`
--

INSERT INTO `tbl_event` (`event_id`, `event_name`, `event_desc`, `event_create_at`, `event_expire_at`) VALUES
(1, 'Chime Long Trip', '2D1N Grad Trip!', '2013-10-02 07:33:26', '2013-10-06 08:30:00'),
(2, 'MFIN BBQ', 'The 100 People BBQ, hunting chance for PHIL', '2013-08-14 08:27:21', '2014-02-15 04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event_option`
--

CREATE TABLE IF NOT EXISTS `tbl_event_option` (
  `event_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  PRIMARY KEY (`event_id`,`option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_event_option`
--

INSERT INTO `tbl_event_option` (`event_id`, `option_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 4),
(2, 5),
(2, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event_user`
--

CREATE TABLE IF NOT EXISTS `tbl_event_user` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`event_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_event_user`
--

INSERT INTO `tbl_event_user` (`event_id`, `user_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(2, 1),
(2, 6),
(2, 8),
(2, 10);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_option`
--

CREATE TABLE IF NOT EXISTS `tbl_option` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `option_desc` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tbl_option`
--

INSERT INTO `tbl_option` (`option_id`, `option_name`, `option_desc`) VALUES
(1, '5th Oct', NULL),
(2, '12th Oct', NULL),
(3, '19th Oct', NULL),
(4, 'North Point', '北角'),
(5, 'Tai Mei Tuk', '大美督'),
(6, 'Minden Avenue', '棉登徑');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_option_user`
--

CREATE TABLE IF NOT EXISTS `tbl_option_user` (
  `option_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`option_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_option_user`
--

INSERT INTO `tbl_option_user` (`option_id`, `user_id`, `create_at`) VALUES
(3, 1, '2013-10-09 02:32:22'),
(2, 1, '2013-10-11 02:11:10'),
(1, 1, '2013-11-09 02:32:20'),
(1, 2, '2013-10-09 02:32:22'),
(2, 2, '2013-10-09 02:32:22'),
(1, 3, '2013-10-09 02:32:22'),
(1, 4, '2013-10-09 02:32:22'),
(3, 4, '2013-10-10 02:13:02'),
(2, 5, '2013-10-09 02:32:22'),
(2, 6, '2013-10-09 02:32:22'),
(4, 10, '2013-10-09 02:32:22'),
(5, 10, '2013-10-09 02:32:22'),
(4, 6, '2013-10-09 02:42:02'),
(6, 8, '2013-10-09 02:06:26'),
(6, 10, '2013-10-09 02:32:22');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `user_avatar_filename` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_mobile` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_email` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1114 ;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `user_name`, `user_avatar_filename`, `user_mobile`, `user_email`) VALUES
(1, 'Erwin', NULL, '+85295112709', 'erwin.ch.chan@gmail.com'),
(2, 'Jacky', NULL, '+85292732022', 'jackyyipck@gmail.com'),
(3, 'Nigel', NULL, '+85296858699', 'chanigel@gmail.com'),
(4, 'Manho', NULL, NULL, NULL),
(5, 'Rebecca', NULL, NULL, NULL),
(6, 'Angela', NULL, NULL, NULL),
(7, 'Cancan', NULL, NULL, NULL),
(8, 'BS', NULL, NULL, NULL),
(9, 'Janet', NULL, NULL, NULL),
(10, 'Stranger1', NULL, NULL, NULL),
(11, 'Stranger2', NULL, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
         