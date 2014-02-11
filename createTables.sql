-- PHP Version: 5.4.9-4ubuntu2.4
-- Creates the tables for the portal's DB.
-- Based on a dump of br6's db

-- example usage:
-- `mysql -u username -p br7portaldb < createTables.sql`
--

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `gid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(10) unsigned NOT NULL,
  `leader_pid` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `open` tinyint(3) unsigned NOT NULL,
  `notes` varchar(5000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`gid`),
  KEY `tid` (`tid`),
  KEY `leader_pid` (`leader_pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=127 ;

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE IF NOT EXISTS `matches` (
  `mid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(10) unsigned NOT NULL,
  `mid1` int(10) unsigned DEFAULT NULL,
  `mid2` int(10) unsigned DEFAULT NULL,
  `gid1` int(10) unsigned DEFAULT NULL,
  `gid2` int(10) unsigned DEFAULT NULL,
  `round` tinyint(4) NOT NULL,
  `startts_s` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `endts_s` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `startts_a` timestamp NULL DEFAULT NULL,
  `endts_a` timestamp NULL DEFAULT NULL,
  `winner` tinyint(4) DEFAULT NULL,
  `notes` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`mid`),
  KEY `tid` (`tid`),
  KEY `gid1` (`gid1`),
  KEY `gid2` (`gid2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `credits` tinyint(3) unsigned NOT NULL,
  `early` tinyint(3) unsigned NOT NULL,
  `dname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` char(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seat` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seatts` timestamp NULL DEFAULT NULL,
  `seataccess` tinyint(3) unsigned DEFAULT 0,
  `registeredts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `invitedts` timestamp NULL DEFAULT NULL,
  `firstlogints` timestamp NULL DEFAULT NULL,
  `lastlogints` timestamp NULL DEFAULT NULL,
  `attendeeno` int(10) unsigned NOT NULL,
  `lname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ticket` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `orderno` int(10) unsigned NOT NULL,
  `mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`pid`),
  UNIQUE KEY `token` (`token`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `seat` (`seat`),
  KEY `email` (`email`),
  KEY `orderno` (`orderno`),
  KEY `attendeeno` (`attendeeno`),
  KEY `early` (`early`),
  KEY `credits` (`credits`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=462 ;

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

CREATE TABLE IF NOT EXISTS `tournaments` (
  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_pid` int(10) unsigned DEFAULT NULL,
  `shortcode` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tourney_type` tinyint(3) unsigned NOT NULL, -- 0: crowdsourced, 1: minor, 2: major
  `published` tinyint(4) NOT NULL,
  `teamsize` tinyint(3) unsigned NOT NULL,
  `game` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reqs` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `desc` text COLLATE utf8_unicode_ci,
  `prizes` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` varchar(5000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tid`),
  KEY `owner_pid` (`owner_pid`),
  KEY `tourney_type` (`tourney_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=53 ;


-- Populate `tournaments` table with the major and minor tournaments
INSERT INTO `tournaments` (`tid`, `owner_pid`, `shortcode`, `name`, `tourney_type`, `published`, `teamsize`, `game`, `reqs`, `desc`, `prizes`, `notes`, `createts`) VALUES
(1, 1, 'lol', 'AMD''s League Of Legends 5v5', 2, 1, 5, 'Leauge Of Legends', NULL, 'It''ll be dope.', 'CASH MUNEY', '', '2014-01-04 18:57:14'),
(2, 1, 'csgo', 'Counter-Strike Global Offensive 5v5', 2, 1, 5, 'CS:GO', NULL, 'Coming Soon.', '<h5>1st Place Team</h5>\r\n<ul>\r\n<li>$200.00 Cash</li>\r\n</ul>\r\n<h5>2nd Place Team</h5>\r\n<ul>\r\n<li>5x $30.00 Gift Cards to Best Buy</li>\r\n</ul>', '', '2014-01-26 20:07:40'),
(3, 1, 'dota2', 'Dota2 5v5', 2, 1, 5, 'Dota2', NULL, 'Coming Soon.', '<h5>1st Place Team</h5>\n<ul>\n<li>$200.00 Cash</li>\n</ul>\n<h5>2nd Place Team</h5>\n<ul>\n<li>5x $20.00 Gift Cards to Best Buy</li>\n</ul>', '', '2014-01-26 20:16:42'),
(4, 1, 'sc2', 'Starcraft 2 1v1', 2, 1, 1, 'Starcraft 2', NULL, 'Coming Soon.', '<h5>1st Place</h5>\r\n<ul>\r\n<li>$75.00 Cash</li>\r\n</ul>\r\n<h5>2nd Place</h5>\r\n<ul>\r\n<li>$40.00 Gift Card to Best Buy</li>\r\n</ul>', '', '2014-01-26 20:19:47'),
(5, 1, 'ss', 'Super Smash Brothers Brawl 1v1', 2, 1, 1, 'Super Smash Brothers Brawl', NULL, 'Coming Soon.', '<h5>1st Place</h5>\n<ul>\n<li>$50.00 Cash</li>\n</ul>\n<h5>2nd Place</h5>\n<ul>\n<li>$20.00 Gift Card to Best Buy</li>\n</ul>', '', '2014-01-26 20:21:55'),
(6, 1, 'fifa', 'FIFA 2013 1v1', 2, 1, 1, 'FIFA 2013', NULL, 'Coming Soon.', '<h5>1st Place</h5>\n<ul>\n<li>$50.00 Cash</li>\n</ul>\n<h5>2nd Place</h5>\n<ul>\n<li>$20.00 Gift Card to Best Buy</li>\n</ul>', '', '2014-01-26 20:22:55'),
(7, 1, 'sf4', 'Street Fighter IV:AE 1v1', 2, 1, 1, 'Street Fighter IV:AE', NULL, 'Coming Soon.', '<h5>1st Place</h5>\n<ul>\n<li>$50.00 Cash</li>\n</ul>\n<h5>2nd Place</h5>\n<ul>\n<li>$20.00 Gift Card to Best Buy</li>\n</ul>', '', '2014-01-26 20:27:13'),
(8, 1, 'injustice', 'Injustice 1v1', 2, 1, 1, 'Injustice', NULL, 'Coming Soon.', '<h5>1st Place</h5>\n<ul>\n<li>$50.00 Cash</li>\n</ul>\n<h5>2nd Place</h5>\n<ul>\n<li>$20.00 Gift Card to Best Buy</li>\n</ul>', '', '2014-01-26 20:27:50'),
(9, 1, 'mariokart', 'Mario Kart Wii 1v1', 2, 1, 1, 'Mario Kart Wii', NULL, 'Coming Soon.', '<h5>1st Place</h5>\n<ul>\n<li>$50.00 Cash</li>\n</ul>\n<h5>2nd Place</h5>\n<ul>\n<li>$20.00 Gift Card to Best Buy</li>\n</ul>', '', '2014-01-26 20:28:45'),
(10, 1, NULL, 'League Of Legends 1v1 Face-Off ', 1, 1, 1, 'League Of Legends', NULL, 'Coming Soon.', 'Bragging rights.', '', '2014-01-26 20:47:28'),
(11, 1, NULL, 'Starcraft 2 2v2', 1, 1, 2, 'Starcraft 2', NULL, 'Coming Soon.', 'Bragging rights.', '', '2014-01-26 20:47:53'),
(12, 1, NULL, 'Team Fortress 2 Brawl', 1, 1, 1, 'Team Fortress 2', NULL, 'Coming Soon.', 'Bragging rights.', '', '2014-01-26 20:48:26'),
(13, 1, NULL, 'Minecraft Open Server', 1, 1, 1, 'Minecraft', NULL, 'Coming Soon.', 'Bragging rights.', '', '2014-01-26 20:49:18'),
(14, 1, NULL, 'Call of Duty (one of them)', 1, 1, 1, 'Call of Duty', NULL, 'Coming Soon.', 'Bragging rights.', '', '2014-01-26 20:49:51'),
(15, 1, NULL, 'Super Smash Brothers Melee 1v1', 1, 1, 1, 'Super Smash Brothers Melee', NULL, 'Coming Soon.', 'Bragging rights.', '', '2014-01-26 20:50:19'),
(16, 1, NULL, 'Mario Kart - Open', 1, 1, 1, 'Mario Kart', NULL, 'Pick up and Play Mario Kart', 'Bragging rights.', '', '2014-01-26 20:50:48'),
(17, 1, NULL, 'Magic: The Gathering 1v1', 1, 1, 1, 'Magic: The Gathering', NULL, 'Coming Soon.', 'Bragging rights.', '', '2014-01-26 20:51:19'),
(18, 1, NULL, 'Settlers of Catan', 1, 1, 1, 'Settlers of Catan', NULL, 'Coming Soon.', 'Bragging rights.', '', '2014-01-26 20:51:39');

-- --------------------------------------------------------

--
-- Table structure for table `tournament_players`
--

CREATE TABLE IF NOT EXISTS `tournament_players` (
  `tid` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `gid` int(10) unsigned DEFAULT NULL,
  `createdts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tid`,`pid`),
  KEY `pid` (`pid`),
  KEY `gid` (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`tid`) REFERENCES `tournaments` (`tid`),
  ADD CONSTRAINT `groups_ibfk_2` FOREIGN KEY (`leader_pid`) REFERENCES `players` (`pid`);

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`tid`) REFERENCES `tournaments` (`tid`),
  ADD CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`gid1`) REFERENCES `groups` (`gid`),
  ADD CONSTRAINT `matches_ibfk_3` FOREIGN KEY (`gid2`) REFERENCES `groups` (`gid`);

--
-- Constraints for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD CONSTRAINT `tournaments_ibfk_1` FOREIGN KEY (`owner_pid`) REFERENCES `players` (`pid`);

--
-- Constraints for table `tournament_players`
--
ALTER TABLE `tournament_players`
  ADD CONSTRAINT `tournament_players_ibfk_1` FOREIGN KEY (`tid`) REFERENCES `tournaments` (`tid`),
  ADD CONSTRAINT `tournament_players_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `players` (`pid`),
  ADD CONSTRAINT `tournament_players_ibfk_3` FOREIGN KEY (`gid`) REFERENCES `groups` (`gid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
