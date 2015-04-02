-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 02 apr 2015 kl 11:55
-- Serverversion: 5.6.15-log
-- PHP-version: 5.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databas: `login`
--
CREATE DATABASE IF NOT EXISTS `login` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `login`;

-- --------------------------------------------------------

--
-- Tabellstruktur `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `role` int(1) NOT NULL DEFAULT '3',
  `failattempt` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumpning av Data i tabell `login`
--

INSERT INTO `login` (`id`, `username`, `password`, `role`, `failattempt`) VALUES
(13, 'Emil', '6ca91bd06b075f8b3d80ef205ffdffdc', 3, 0),
(12, 'Mikael', '6ca91bd06b075f8b3d80ef205ffdffdc', 3, 0),
(11, 'Admin2', '6ca91bd06b075f8b3d80ef205ffdffdc', 1, 0),
(10, 'Admin1', '6ca91bd06b075f8b3d80ef205ffdffdc', 1, 0);

-- --------------------------------------------------------

--
-- Tabellstruktur `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `LogID` int(4) NOT NULL AUTO_INCREMENT,
  `User` varchar(40) NOT NULL,
  `Action` varchar(400) NOT NULL DEFAULT 'Loggningen misslyckades',
  `ipAdress` varchar(20) NOT NULL,
  `Timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`LogID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Tabellstruktur `topiccomments`
--

CREATE TABLE IF NOT EXISTS `topiccomments` (
  `commentID` int(4) NOT NULL AUTO_INCREMENT,
  `topicId` int(4) NOT NULL,
  `comment` varchar(300) NOT NULL,
  `commentPoster` varchar(40) NOT NULL,
  PRIMARY KEY (`commentID`),
  KEY `topicId` (`topicId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

--
-- Dumpning av Data i tabell `topiccomments`
--

INSERT INTO `topiccomments` (`commentID`, `topicId`, `comment`, `commentPoster`) VALUES
(42, 40, 'Hello', 'Admin1'),
(43, 40, 'Hello Admin1', 'Mikael'),
(44, 41, 'Hello', 'Mikael');

-- --------------------------------------------------------

--
-- Tabellstruktur `topics`
--

CREATE TABLE IF NOT EXISTS `topics` (
  `topicID` int(4) NOT NULL AUTO_INCREMENT,
  `topicName` varchar(50) NOT NULL,
  `topicText` varchar(300) NOT NULL,
  `topicOwnerID` varchar(40) NOT NULL,
  PRIMARY KEY (`topicID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Dumpning av Data i tabell `topics`
--

INSERT INTO `topics` (`topicID`, `topicName`, `topicText`, `topicOwnerID`) VALUES
(40, 'Example topic 1', 'This is an example descriptio', 'Admin1'),
(41, 'This is my thread', 'This is just an example.', 'Mikael');

--
-- Restriktioner för dumpade tabeller
--

--
-- Restriktioner för tabell `topiccomments`
--
ALTER TABLE `topiccomments`
  ADD CONSTRAINT `topiccomments_ibfk_1` FOREIGN KEY (`topicId`) REFERENCES `topics` (`topicID`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
