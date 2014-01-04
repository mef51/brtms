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
