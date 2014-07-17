-- phpMyAdmin SQL Dump
-- version 4.0.7
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 產生日期: 2014 年 07 月 14 日 20:01
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9982 ;

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
(9923, 999010, '2014-06-28 10:09:06', 'hihi'),
(9919, 999010, '2014-06-28 09:11:55', 'test'),
(9902, 999001, '2012-03-03 21:06:12', 'Comment message 2'),
(48, 1, '2014-06-01 20:52:17', '89290'),
(9920, 999010, '2014-06-28 09:29:00', 'hi'),
(9905, 999001, '2012-03-03 21:06:15', 'Comment message 9905'),
(9904, 999002, '2012-03-03 21:06:13', 'Comment message 9904'),
(55, 1, '2014-06-01 20:58:16', 'congrats!'),
(56, 1, '2014-06-01 20:58:36', 'congrats!2'),
(9901, 999001, '2012-03-03 21:06:11', 'Comment message'),
(9903, 999001, '2012-03-03 21:06:12', 'Comment message 9903'),
(124, 1, '2014-06-05 13:23:11', '你'),
(123, 17, '2014-06-05 10:47:39', 'hi'),
(9922, 999010, '2014-06-28 10:08:28', 'Gigi'),
(122, 1, '2014-06-05 10:47:29', 'hi'),
(121, 1, '2014-06-05 10:36:49', 'haha'),
(72, 1, '2014-06-02 09:43:41', '2'),
(73, 1, '2014-06-02 09:50:54', '4'),
(120, 17, '2014-06-05 10:36:30', 'I am at home'),
(119, 1, '2014-06-05 10:36:20', 'where r u'),
(116, 1, '2014-06-03 14:40:24', 'hi'),
(117, 1, '2014-06-03 16:58:04', 'where am I '),
(118, 1, '2014-06-05 09:21:48', 'this is a really really really really long long msg'),
(115, 1, '2014-06-03 14:34:37', 'hi'),
(9921, 999010, '2014-06-28 10:05:41', 'qq'),
(9908, 999010, '2014-06-22 10:36:56', 'hi'),
(9909, 999010, '2014-06-22 10:38:32', 'hi'),
(9910, 999010, '2014-06-22 10:44:12', 'hi'),
(9911, 1, '2014-06-22 10:44:51', 'hihi'),
(9912, 999010, '2014-06-22 10:57:48', 'hi'),
(9913, 1, '2014-06-22 11:02:22', 'hi'),
(9914, 999010, '2014-06-22 11:04:24', 'hi'),
(9915, 1, '2014-06-22 11:10:43', '123'),
(9916, 999010, '2014-06-22 12:19:22', 'hi'),
(9917, 999010, '2014-06-22 12:23:04', 'test'),
(9918, 999010, '2014-06-22 12:25:18', 'higigi'),
(9924, 999010, '2014-06-28 10:14:06', 'yea'),
(9925, 1, '2014-06-28 10:18:05', 'hi'),
(9926, 1, '2014-06-28 10:18:18', 'hihi'),
(9927, 1, '2014-06-28 10:19:00', 'hihi'),
(9928, 1, '2014-06-28 10:27:47', 'rrrr'),
(9929, 1, '2014-06-28 10:29:41', '123'),
(9930, 1, '2014-06-28 10:29:46', '123'),
(9931, 1, '2014-06-28 10:46:55', '12'),
(9932, 999001, '2014-06-30 12:30:15', 'Comment message 14041314150'),
(9933, 999001, '2014-06-30 12:30:15', 'Comment message 14041314151'),
(9934, 999001, '2014-06-30 12:30:15', 'Comment message 14041314152'),
(9935, 999002, '2014-06-30 12:30:15', 'Comment message 14041314153'),
(9936, 999001, '2014-06-30 12:30:15', 'Comment message 14041314154'),
(9937, 999003, '2014-06-30 12:30:15', 'Comment message 14041314155'),
(9938, 999002, '2014-06-30 12:30:15', 'Comment message 14041314156'),
(9939, 999001, '2014-06-30 12:31:13', 'Comment message 14041314730'),
(9940, 999001, '2014-06-30 12:31:13', 'Comment message 14041314731'),
(9941, 999001, '2014-06-30 12:31:13', 'Comment message 14041314732'),
(9942, 999002, '2014-06-30 12:31:13', 'Comment message 14041314733'),
(9943, 999001, '2014-06-30 12:31:13', 'Comment message 14041314734'),
(9944, 999003, '2014-06-30 12:31:13', 'Comment message 14041314735'),
(9945, 999002, '2014-06-30 12:31:13', 'Comment message 14041314736'),
(9946, 999001, '2014-06-30 12:35:01', 'Comment message 14041317010'),
(9947, 999001, '2014-06-30 12:35:01', 'Comment message 14041317011'),
(9948, 999001, '2014-06-30 12:35:01', 'Comment message 14041317012'),
(9949, 999002, '2014-06-30 12:35:02', 'Comment message 14041317023'),
(9950, 999001, '2014-06-30 12:35:02', 'Comment message 14041317024'),
(9951, 999003, '2014-06-30 12:35:02', 'Comment message 14041317025'),
(9952, 999002, '2014-06-30 12:35:02', 'Comment message 14041317026'),
(9979, 999002, '2014-07-06 07:08:13', 'Comment message 14046304936'),
(9978, 999003, '2014-07-06 07:08:13', 'Comment message 14046304935'),
(9977, 999001, '2014-07-06 07:08:13', 'Comment message 14046304934'),
(9976, 999002, '2014-07-06 07:08:13', 'Comment message 14046304933'),
(9975, 999001, '2014-07-06 07:08:13', 'Comment message 14046304932'),
(9974, 999001, '2014-07-06 07:08:13', 'Comment message 14046304931'),
(9973, 999001, '2014-07-06 07:08:13', 'Comment message 14046304930'),
(9960, 3, '2014-06-30 18:13:37', 'testing'),
(9968, 1, '2014-07-06 04:50:52', 'hi'),
(9969, 1, '2014-07-06 04:50:58', 'hj'),
(9970, 1, '2014-07-06 05:01:02', 'hi'),
(9971, 1, '2014-07-06 05:01:18', 'hihi'),
(9972, 1, '2014-07-06 05:02:08', 'hihi'),
(9980, 1, '2014-07-12 16:49:31', 'yes'),
(9981, 1, '2014-07-12 16:49:53', 'no');

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
(3, 9908),
(3, 9909),
(3, 9910),
(3, 9911),
(3, 9912),
(3, 9913),
(3, 9914),
(3, 9915),
(3, 9916),
(3, 9918),
(3, 9970),
(23, 116),
(23, 119),
(23, 120),
(23, 121),
(23, 122),
(23, 123),
(23, 124),
(23, 125),
(23, 9971),
(23, 9980),
(23, 9981),
(24, 9917),
(24, 9919),
(24, 9920),
(24, 9921),
(24, 9922),
(24, 9923),
(24, 9924),
(99981, 9973),
(99981, 9974),
(99982, 9975),
(99982, 9976),
(99982, 9977),
(99983, 9978),
(99983, 9979),
(99988, 9925),
(99988, 9926),
(99988, 9927),
(99988, 9928),
(99988, 9929),
(99988, 9930),
(99988, 9931),
(99988, 9960),
(99988, 9968),
(99988, 9969),
(99988, 9972);

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
  `event_profile_filename` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=100028 ;

--
-- 轉存資料表中的資料 `tbl_event`
--

INSERT INTO `tbl_event` (`event_id`, `event_name`, `event_desc`, `event_create_at`, `event_start_at`, `event_expire_at`, `event_create_by`, `is_allday`, `event_profile_filename`) VALUES
(1, 'King proj', 'King project without king of prep (9gel)\n', '0000-00-00 00:00:00', '2014-04-28 23:58:28', '2014-04-29 23:58:28', 3, 0, ''),
(2, 'App enhancement ', 'Why don''t we use our app for logging works for our app lol', '0000-00-00 00:00:00', '2014-04-28 08:07:07', '2014-04-29 08:07:07', 3, 0, ''),
(3, 'An event created', 'Waiting for 9gel to join back us!', '0000-00-00 00:00:00', '2014-06-15 02:19:46', '2014-06-25 04:58:56', 1, 0, ''),
(24, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(99981, 'TestEvent1', 'TestEvent1 Description', '2013-10-21 16:00:00', '2013-10-29 16:00:01', '2013-10-29 16:00:02', 999001, 0, ''),
(99988, 'Not Joining Test', 'Hihi', '0000-00-00 00:00:00', '2014-06-27 23:31:54', '0000-00-00 00:00:00', 1, 0, ''),
(23, 'Chat test', 'For testing purpose', '0000-00-00 00:00:00', '2014-06-01 22:08:42', '0000-00-00 00:00:00', 1, 0, ''),
(100008, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100009, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100010, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100011, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100012, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100013, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100014, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100015, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100016, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100017, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100018, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100019, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100020, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100021, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100022, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100023, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100024, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100025, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100026, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, ''),
(100027, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, '');

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
(24, 81),
(99981, 99881),
(99981, 99882),
(99981, 99883),
(99988, 99891),
(100010, 99924),
(100011, 99925),
(100012, 99926),
(100013, 99927),
(100014, 99928),
(100015, 99929),
(100016, 99930),
(100017, 99931),
(100018, 99932),
(100019, 99933),
(100020, 99934),
(100021, 99935),
(100022, 99936),
(100023, 99937),
(100024, 99938),
(100025, 99939),
(100026, 99940),
(100027, 99941);

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
(3, 999010),
(23, 1),
(23, 17),
(99981, 999001),
(99981, 999002),
(99981, 999004),
(99982, 999001),
(99983, 999001),
(99988, 1),
(99988, 3),
(99988, 999016);

-- --------------------------------------------------------

--
-- 表的結構 `tbl_option`
--

CREATE TABLE IF NOT EXISTS `tbl_option` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `option_desc` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=99942 ;

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
(99929, 'Ss', ''),
(81, '1', ''),
(99930, 'Dd', ''),
(99883, 'Option 3', 'Option Desc 3'),
(99884, '1', ''),
(99928, 'Bb', ''),
(99882, 'Option 2', 'Option Desc 2'),
(99881, 'Option 1', 'Option Desc 1'),
(99891, 'Choices', ''),
(99924, 'Nn', ''),
(99925, 'Sdasdas', ''),
(99926, 'Gg', ''),
(99927, 'Nn', ''),
(80, 'Chat', ''),
(99941, 'Gfeg', ''),
(99940, 'Gdfgf', ''),
(99939, 'Fsadf', ''),
(99938, 'Fasdfas', ''),
(99937, 'Gg', ''),
(99936, 'Dsds', ''),
(99931, 'Sas', ''),
(99932, 'Dd', ''),
(99933, 'Yy', ''),
(99934, 'Ss', ''),
(99935, 'Hhh', '');

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
(7, 3, '2014-04-29 16:07:18'),
(4, 3, '2014-04-29 16:02:12'),
(1, 3, '2014-04-29 16:00:53'),
(6, 1, '2014-04-29 16:18:06'),
(14, 1, '2014-05-01 06:59:36'),
(80, 17, '2014-06-05 13:03:33'),
(5, 1, '2014-05-02 13:00:45'),
(7, 1, '2014-05-02 13:00:45'),
(14, 3, '2014-06-05 13:37:29'),
(0, 0, '2014-05-04 11:42:02'),
(12, 1, '2014-06-05 06:56:53'),
(80, 1, '2014-06-23 16:19:41'),
(81, 999010, '2014-06-25 15:25:10'),
(99883, 999004, '2014-07-06 07:08:13'),
(99882, 999002, '2014-07-06 07:08:13'),
(99882, 999001, '2014-07-06 07:08:12'),
(99881, 999001, '2014-07-06 07:08:11'),
(13, 1, '2014-06-15 12:42:28'),
(6, 3, '2014-06-05 13:38:59'),
(81, 1, '2014-06-22 10:29:14'),
(99892, 1, '2014-06-28 07:50:26'),
(99891, 1, '2014-06-28 07:40:15'),
(99891, 3, '2014-06-29 15:48:35'),
(99892, 3, '2014-06-30 18:13:50');

-- --------------------------------------------------------

--
-- 表的結構 `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_avatar_filename` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_mobile` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_email` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `verification_code` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `device_id` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `device_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `has_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=999018 ;

--
-- 轉存資料表中的資料 `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `user_name`, `user_avatar_filename`, `user_mobile`, `user_email`, `user_status`, `user_create_at`, `verification_code`, `device_id`, `device_token`, `has_verified`) VALUES
(1, 'Erwin', 'avatars/356a192b7913b04c54574d18c28d46e6395428ab.jpg', '+852 9511 2709', 'seayu@outlook.com', 'TESTING2', '2014-07-14 11:07:28', '793571', 'E1A00395-F84D-48C8-8663-581A6F73FDAA', '', 1),
(2, 'Man Ho', 'avatars/213987859142.jpg', '+852 9813 0932', 'ngmanho888@gmail.com', 'MH''s Status', '2014-05-10 19:11:03', '867629', '96A34883-33C8-435C-A132-8B437EF841C2', '', 1),
(3, 'JKY@iPadMini', 'avatars/314042263923.jpg', '+852 9273 2022', 'jackyyipck@gmail.com', 'JKY''s Status', '2014-07-01 14:53:12', '516991', '7ABCF36E-819C-41F5-9736-D18D8D5AE5F4', '', 1),
(999016, 'Chan', 'avatars/9990161404623922999016.jpg', '+852 2427 7096', 'mail@seayu.hk', '', '2014-07-06 05:18:42', '875604', '5FF6EAF0-5FAD-4C7B-9FA2-5D5B49DD0095', '69552929bc2467afd750342a83a4a5f0a56d75c4df09d8b423d7a28e3b70b0de', 1),
(999008, 'Tester8', 'Avatar8', '852123456008', 'Tester8@test.com', NULL, '2012-03-03 21:06:14', '', 'deviceid008', 'devicetoken008', 1),
(17, 'seayu', 'avatars/17140197344817.jpg', '+852 5904 1445', 'seayu@outlook.com', 'My status', '2014-06-15 09:45:17', '231408', '', '', 0),
(999007, 'Tester7', 'Avatar7', '852123456007', 'Tester7@test.com', NULL, '2012-03-03 21:06:13', '', 'deviceid007', 'devicetoken007', 0),
(999006, 'Tester6', 'Avatar6', '852123456006', 'Tester6@test.com', NULL, '2012-03-03 21:06:12', '', 'deviceid006', 'devicetoken006', 1),
(999010, 'Pauline', 'avatars/9990101403946699999010.jpg', '+852 9606 3356', 'paulinecwy@gmail.com', '', '2014-07-14 10:19:12', '375904', '878FFEAD-325E-4139-B084-3562A1754CB1', 'b9c3b9e86557b006f35140a2867d5e4331fb07df1e4f4b4234831101696682b2', 0),
(999005, 'Tester5', 'Avatar5', '852123456005', 'Tester5@test.com', NULL, '2012-03-03 21:06:11', '', 'deviceid005', 'devicetoken005', 0),
(999004, 'Tester4', 'Avatar4', '852123456004', 'Tester4@test.com', NULL, '2012-03-03 21:06:10', '', 'deviceid004', 'devicetoken004', 1),
(999003, 'Tester3', 'Avatar3', '852123456003', 'Tester3@test.com', NULL, '2012-03-03 21:06:09', '', 'deviceid003', 'devicetoken003', 1),
(999002, 'Tester2', 'Avatar2', '852123456002', 'Tester2@test.com', NULL, '2012-03-03 21:06:08', '', 'deviceid002', 'devicetoken002', 1),
(999001, 'Tester1', 'Avatar1', '852123456001', 'Tester1@test.com', NULL, '2012-03-03 21:06:07', '', 'deviceid001', 'devicetoken001', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
         