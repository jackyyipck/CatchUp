-- phpMyAdmin SQL Dump
-- version 4.0.7
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 產生日期: 2014 年 05 月 04 日 22:17
-- 伺服器版本: 5.1.70
-- PHP 版本: 5.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 資料庫: `seayu_catchup`
--

-- --------------------------------------------------------

--
-- 表的結構 `tbl_comment`
--

CREATE TABLE IF NOT EXISTS `tbl_comment` (
  `comment_id` int(10) NOT NULL AUTO_INCREMENT,
  `create_by` int(11) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `comment` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- 轉存資料表中的資料 `tbl_comment`
--

INSERT INTO `tbl_comment` (`comment_id`, `create_by`, `create_at`, `comment`) VALUES
(1, 1, '2014-04-29 15:59:35', 'I like Starbucks'),
(2, 3, '2014-04-29 16:00:41', 'You drink coffee ga meh'),
(3, 1, '2014-04-29 16:45:53', 'Of cos but not addicted'),
(4, 1, '2014-05-02 18:03:15', 'of Cos not Addicted You Know? You Know? You KNOW???????');

-- --------------------------------------------------------

--
-- 表的結構 `tbl_comment_event`
--

CREATE TABLE IF NOT EXISTS `tbl_comment_event` (
  `event_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  PRIMARY KEY (`event_id`,`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 轉存資料表中的資料 `tbl_comment_event`
--

INSERT INTO `tbl_comment_event` (`event_id`, `comment_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4);

-- --------------------------------------------------------

--
-- 表的結構 `tbl_event`
--

CREATE TABLE IF NOT EXISTS `tbl_event` (
  `event_id` int(10) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `event_desc` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `event_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `event_start_at` timestamp NULL DEFAULT NULL,
  `event_expire_at` timestamp NULL DEFAULT NULL,
  `event_create_by` int(11) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- 轉存資料表中的資料 `tbl_event`
--

INSERT INTO `tbl_event` (`event_id`, `event_name`, `event_desc`, `event_create_at`, `event_start_at`, `event_expire_at`, `event_create_by`) VALUES
(1, 'King proj', 'King project without king of prep (9gel)\n', '0000-00-00 00:00:00', '2014-04-28 23:58:28', '2014-04-29 23:58:28', 3),
(2, 'App enhancement ', 'Why don''t we use our app for logging works for our app lol', '0000-00-00 00:00:00', '2014-04-28 08:07:07', '2014-04-29 08:07:07', 3),
(3, 'An event created', 'Waiting for 9gel to join back us!', '0000-00-00 00:00:00', '2014-05-01 06:58:56', '2014-05-03 06:58:56', 1),
(4, 'Mahjong', 'Let''s play mahjong!', '0000-00-00 00:00:00', '2014-05-02 20:00:18', '2014-05-03 08:00:18', 1),
(5, 'Eat pork son thi', 'Sik tomato brand\n\n', '0000-00-00 00:00:00', '2014-08-08 13:59:42', '2014-08-11 23:59:42', 1),
(8, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(9, 'New Event', 'New Event Desc', '0000-00-00 00:00:00', '2014-05-03 12:17:27', '2014-05-04 12:17:27', 1);

-- --------------------------------------------------------

--
-- 表的結構 `tbl_event_option`
--

CREATE TABLE IF NOT EXISTS `tbl_event_option` (
  `event_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  PRIMARY KEY (`event_id`,`option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 轉存資料表中的資料 `tbl_event_option`
--

INSERT INTO `tbl_event_option` (`event_id`, `option_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 11),
(3, 12),
(3, 13),
(3, 14),
(4, 15),
(4, 16),
(5, 17),
(5, 18),
(5, 19),
(9, 24),
(9, 25),
(9, 26);

-- --------------------------------------------------------

--
-- 表的結構 `tbl_event_user`
--

CREATE TABLE IF NOT EXISTS `tbl_event_user` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`event_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 轉存資料表中的資料 `tbl_event_user`
--

INSERT INTO `tbl_event_user` (`event_id`, `user_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(2, 3),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(5, 1),
(5, 2),
(5, 3),
(9, 1),
(9, 2),
(9, 3);

-- --------------------------------------------------------

--
-- 表的結構 `tbl_option`
--

CREATE TABLE IF NOT EXISTS `tbl_option` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `option_desc` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=27 ;

--
-- 轉存資料表中的資料 `tbl_option`
--

INSERT INTO `tbl_option` (`option_id`, `option_name`, `option_desc`) VALUES
(1, 'starbucks', ''),
(2, 'pacific coffee', ''),
(3, 'cafe habitu', ''),
(4, 'red bar', ''),
(5, 'Event profile pi', ''),
(6, 'Event is not sho', ''),
(7, 'longer option (db level change)', ''),
(11, 'longer option (db)', ''),
(12, '9gel', ''),
(13, '8gel', ''),
(14, '7gel', ''),
(15, 'jacky''s home', ''),
(16, 'janet''s home', ''),
(17, 'yuen air', ''),
(18, 'ban long', ''),
(19, 'thousand money 3esfsd fsf sdf sdf sd fs sdfsdf. sdfsdf s s fs', ''),
(24, 'Choice 1', ''),
(25, 'Choice 2', ''),
(26, 'Choice 3', '');

-- --------------------------------------------------------

--
-- 表的結構 `tbl_option_user`
--

CREATE TABLE IF NOT EXISTS `tbl_option_user` (
  `option_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`option_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 轉存資料表中的資料 `tbl_option_user`
--

INSERT INTO `tbl_option_user` (`option_id`, `user_id`, `create_at`) VALUES
(1, 1, '2014-04-29 15:59:23'),
(7, 3, '2014-04-29 16:07:18'),
(4, 3, '2014-04-29 16:02:12'),
(1, 3, '2014-04-29 16:00:53'),
(6, 1, '2014-04-29 16:18:06'),
(14, 1, '2014-05-01 06:59:36'),
(15, 1, '2014-05-01 11:23:31'),
(5, 1, '2014-05-02 13:00:45'),
(7, 1, '2014-05-02 13:00:45'),
(3, 1, '2014-05-02 13:03:03'),
(17, 1, '2014-05-02 13:04:20'),
(0, 0, '2014-05-04 11:42:02');

-- --------------------------------------------------------

--
-- 表的結構 `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `user_avatar_filename` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_mobile` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_email` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `verification_code` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `device_id` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `has_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- 轉存資料表中的資料 `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `user_name`, `user_avatar_filename`, `user_mobile`, `user_email`, `user_status`, `user_create_at`, `verification_code`, `device_id`, `has_verified`) VALUES
(1, 'Erwin', 'avatars/113992056571.jpg', '+852 9511 2709', 'seayu@outlook.com', NULL, '2014-05-04 12:14:17', '238841', '42F69791-D0C7-4774-93C6-18DD5AD6620D', 1),
(2, 'Man Ho', 'avatars/213987859142.jpg', '+852 9813 0932', 'ngmanho888@gmail.com', NULL, '2014-04-29 15:38:34', '867629', '96A34883-33C8-435C-A132-8B437EF841C2', 1),
(3, 'JKY', 'avatars/313987863183.jpg', '+852 9273 2022', 'jackyyipck@gmail.com', NULL, '2014-04-29 15:45:18', '808935', '2CAB0C97-E7BF-42AA-AD9D-626C8DB37145', 1),
(4, 'TBD', NULL, '+852 9606 3356', NULL, NULL, '2014-04-30 13:08:29', '305470', 'D99E53B5-8403-4C4B-BE93-A0D66233087E', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
         