-- phpMyAdmin SQL Dump
-- version 4.0.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 13, 2014 at 04:05 PM
-- Server version: 5.1.70
-- PHP Version: 5.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: 'seayu_catchup'
--
CREATE DATABASE IF NOT EXISTS seayu_catchup DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE seayu_catchup;

-- --------------------------------------------------------

--
-- Table structure for table 'tbl_comment'
--

DROP TABLE IF EXISTS tbl_comment;
CREATE TABLE IF NOT EXISTS tbl_comment (
  comment_id int(10) NOT NULL AUTO_INCREMENT,
  create_by int(11) NOT NULL,
  create_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `comment` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (comment_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9911 ;

-- --------------------------------------------------------

--
-- Table structure for table 'tbl_comment_event'
--

DROP TABLE IF EXISTS tbl_comment_event;
CREATE TABLE IF NOT EXISTS tbl_comment_event (
  event_id int(11) NOT NULL,
  comment_id int(11) NOT NULL,
  PRIMARY KEY (event_id,comment_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'tbl_event'
--

DROP TABLE IF EXISTS tbl_event;
CREATE TABLE IF NOT EXISTS tbl_event (
  event_id int(10) NOT NULL AUTO_INCREMENT,
  event_name varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  event_desc varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  event_create_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  event_start_at timestamp NULL DEFAULT NULL,
  event_expire_at timestamp NULL DEFAULT NULL,
  event_create_by int(11) NOT NULL,
  PRIMARY KEY (event_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=100017 ;

-- --------------------------------------------------------

--
-- Table structure for table 'tbl_event_option'
--

DROP TABLE IF EXISTS tbl_event_option;
CREATE TABLE IF NOT EXISTS tbl_event_option (
  event_id int(11) NOT NULL,
  option_id int(11) NOT NULL,
  PRIMARY KEY (event_id,option_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'tbl_event_user'
--

DROP TABLE IF EXISTS tbl_event_user;
CREATE TABLE IF NOT EXISTS tbl_event_user (
  event_id int(11) NOT NULL,
  user_id int(11) NOT NULL,
  PRIMARY KEY (event_id,user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'tbl_option'
--

DROP TABLE IF EXISTS tbl_option;
CREATE TABLE IF NOT EXISTS tbl_option (
  option_id int(11) NOT NULL AUTO_INCREMENT,
  option_name varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  option_desc varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (option_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=99927 ;

-- --------------------------------------------------------

--
-- Table structure for table 'tbl_option_user'
--

DROP TABLE IF EXISTS tbl_option_user;
CREATE TABLE IF NOT EXISTS tbl_option_user (
  option_id int(11) NOT NULL,
  user_id int(11) NOT NULL,
  create_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (option_id,user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table 'tbl_user'
--

DROP TABLE IF EXISTS tbl_user;
CREATE TABLE IF NOT EXISTS tbl_user (
  user_id int(10) NOT NULL AUTO_INCREMENT,
  user_name varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  user_avatar_filename varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  user_mobile varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  user_email varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  user_create_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  verification_code varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  device_id varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  has_verified tinyint(1) NOT NULL,
  PRIMARY KEY (user_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=999048 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
         