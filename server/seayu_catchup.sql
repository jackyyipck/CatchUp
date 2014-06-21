-- phpMyAdmin SQL Dump
-- version 4.0.7
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 產生日期: 2014 年 06 月 15 日 21:41
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=136 ;

--
-- 轉存資料表中的資料 `tbl_comment`
--

INSERT INTO `tbl_comment` (`comment_id`, `create_by`, `create_at`, `comment`) VALUES
(1, 1, '2014-04-29 15:59:35', 'I like Starbucks'),
(2, 3, '2014-04-29 16:00:41', 'You drink coffee ga meh'),
(3, 1, '2014-04-29 16:45:53', 'Of cos but not addicted'),
(4, 1, '2014-05-02 18:03:15', 'of Cos not Addicted You Know? You Know? You KNOW???????'),
(5, 1, '2014-05-29 15:39:50', 'ADD'),
(6, 1, '2014-05-29 15:52:41', 'ADD2'),
(7, 1, '2014-05-29 17:08:26', 'ADD23'),
(8, 1, '2014-05-29 17:18:10', 'ADD234'),
(9, 1, '2014-05-29 17:29:55', 'ADD2345'),
(10, 1, '2014-05-29 17:30:24', 'ADD23454'),
(11, 1, '2014-05-29 17:30:41', 'ADD234541ewdaesda'),
(12, 1, '2014-05-29 17:31:04', 'ADD234541ewdaesdafdfjhgfdghjhgfhjhg'),
(13, 1, '2014-05-29 17:31:32', 'ADD234541ewdaesdafdfjhgfdghjhgfhjhgsaaaasas'),
(14, 1, '2014-05-29 17:32:42', 'ADD234541ewdaesdafdfjhgfdghjhgfhjhgsaaaasas'),
(15, 1, '2014-05-29 17:33:04', '&#20320;&#22909;'),
(16, 1, '2014-05-29 17:33:37', '&#25105;&#24456;&#22909;&#21568;'),
(17, 1, '2014-05-29 17:39:40', '&#25105;&#24456;&#22909;&#21568;'),
(19, 1, '2014-06-01 20:21:54', 'yes'),
(20, 1, '2014-06-01 20:22:09', 'yes r'),
(134, 1, '2014-06-14 12:19:08', 'hi'),
(23, 1, '2014-06-01 20:31:00', 'rrr'),
(135, 1, '2014-06-15 13:40:30', 'test'),
(133, 3, '2014-06-05 13:38:44', 'kkjgkukhgjgchkvkhvkhvigckjvutfkhvljbkhcjgcougpugkyxficoyiyf'),
(132, 3, '2014-06-05 13:38:21', 'hhhhhi'),
(131, 3, '2014-06-05 13:38:17', 'lll'),
(130, 3, '2014-06-05 13:38:11', '中文字'),
(129, 3, '2014-06-05 13:37:52', '中文字'),
(128, 3, '2014-06-05 13:37:43', 'llll'),
(127, 3, '2014-06-05 13:37:18', 'really like whatsapp wor'),
(126, 3, '2014-06-05 13:37:03', 'yo'),
(125, 17, '2014-06-05 13:25:52', '我又怎樣'),
(48, 1, '2014-06-01 20:52:17', '89290'),
(55, 1, '2014-06-01 20:58:16', 'congrats!'),
(56, 1, '2014-06-01 20:58:36', 'congrats!2'),
(124, 1, '2014-06-05 13:23:11', '你'),
(123, 17, '2014-06-05 10:47:39', 'hi'),
(122, 1, '2014-06-05 10:47:29', 'hi'),
(121, 1, '2014-06-05 10:36:49', 'haha'),
(72, 1, '2014-06-02 09:43:41', '2'),
(73, 1, '2014-06-02 09:50:54', '4'),
(120, 17, '2014-06-05 10:36:30', 'I am at home'),
(119, 1, '2014-06-05 10:36:20', 'where r u'),
(116, 1, '2014-06-03 14:40:24', 'hi'),
(117, 1, '2014-06-03 16:58:04', 'where am I '),
(118, 1, '2014-06-05 09:21:48', 'this is a really really really really long long msg'),
(115, 1, '2014-06-03 14:34:37', 'hi');

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
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 17),
(1, 19),
(1, 20),
(1, 23),
(1, 72),
(1, 73),
(1, 118),
(1, 128),
(1, 129),
(1, 130),
(1, 131),
(1, 132),
(2, 14),
(2, 15),
(2, 16),
(2, 55),
(2, 56),
(3, 48),
(3, 115),
(3, 117),
(3, 126),
(3, 127),
(3, 133),
(3, 134),
(3, 135),
(23, 116),
(23, 119),
(23, 120),
(23, 121),
(23, 122),
(23, 123),
(23, 124),
(23, 125);

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
  `is_allday` tinyint(1) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

--
-- 轉存資料表中的資料 `tbl_event`
--

INSERT INTO `tbl_event` (`event_id`, `event_name`, `event_desc`, `event_create_at`, `event_start_at`, `event_expire_at`, `event_create_by`, `is_allday`) VALUES
(1, 'King proj', 'King project without king of prep (9gel)\n', '0000-00-00 00:00:00', '2014-04-28 23:58:28', '2014-04-29 23:58:28', 3, 0),
(2, 'App enhancement ', 'Why don''t we use our app for logging works for our app lol', '0000-00-00 00:00:00', '2014-04-28 08:07:07', '2014-04-29 08:07:07', 3, 0),
(3, 'An event created', 'Waiting for 9gel to join back us!', '0000-00-00 00:00:00', '2014-06-15 02:19:46', '2014-06-25 04:58:56', 1, 0),
(24, 'Test', 'Testing', '0000-00-00 00:00:00', '2014-06-15 03:14:50', '0000-00-00 00:00:00', 1, 1),
(23, 'Chat test', 'For testing purpose', '0000-00-00 00:00:00', '2014-06-01 22:08:42', '0000-00-00 00:00:00', 1, 0);

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
(23, 80),
(24, 81);

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
(23, 1),
(23, 17),
(24, 1),
(24, 2),
(24, 3);

-- --------------------------------------------------------

--
-- 表的結構 `tbl_option`
--

CREATE TABLE IF NOT EXISTS `tbl_option` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `option_desc` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=82 ;

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
(81, '1', ''),
(80, 'Chat', '');

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
(80, 17, '2014-06-05 13:03:33'),
(5, 1, '2014-05-02 13:00:45'),
(7, 1, '2014-05-02 13:00:45'),
(3, 1, '2014-05-02 13:03:03'),
(14, 3, '2014-06-05 13:37:29'),
(0, 0, '2014-05-04 11:42:02'),
(12, 1, '2014-06-05 06:56:53'),
(13, 1, '2014-06-15 12:42:28'),
(6, 3, '2014-06-05 13:38:59');

-- --------------------------------------------------------

--
-- 表的結構 `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_avatar_filename` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_mobile` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_email` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `verification_code` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `device_id` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `has_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- 轉存資料表中的資料 `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `user_name`, `user_avatar_filename`, `user_mobile`, `user_email`, `user_status`, `user_create_at`, `verification_code`, `device_id`, `has_verified`) VALUES
(1, 'Erwin', 'avatars/114028394401.jpg', '+852 9511 2709', 'seayu@outlook.com', 'TESTING', '2014-06-15 13:37:20', '909698', '3CC86F08-113F-4E96-A18F-3A0BB7E21C91', 1),
(2, 'Man Ho', 'avatars/213987859142.jpg', '+852 9813 0932', 'ngmanho888@gmail.com', 'MH''s Status', '2014-05-10 19:11:03', '867629', '96A34883-33C8-435C-A132-8B437EF841C2', 1),
(3, 'JKY', 'avatars/314019754103.jpg', '+852 9273 2022', 'jackyyipck@gmail.com', 'JKY''s Status', '2014-06-05 13:36:50', '774485', '2CAB0C97-E7BF-42AA-AD9D-626C8DB37145', 1),
(17, 'seayu', 'avatars/17140197344817.jpg', '+852 5904 1445', 'seayu@outlook.com', 'My status', '2014-06-15 09:45:17', '231408', '', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
         