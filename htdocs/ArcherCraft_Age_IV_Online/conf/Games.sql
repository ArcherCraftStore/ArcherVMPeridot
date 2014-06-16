-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2014 at 09:50 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

--
-- ArcherCraft's Game Info
--
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `acoserver`
--

-- --------------------------------------------------------

--
-- Table structure for table `Games`
--

DROP TABLE IF EXISTS `Games`;
CREATE TABLE IF NOT EXISTS `games` (
  `name` text NOT NULL,
  `type` text NOT NULL,
  `MAXUSERS` int(11) NOT NULL,
  `server` text NOT NULL
) TYPE=InnoDB COMMENT='Stores Game Info Like a Train Station''s Timetable';

--
-- MIME TYPES FOR TABLE `Games`:
--   `MAXUSERS`
--       `Text_Plain`
--   `name`
--       `Text_Plain`
--   `server`
--       `Text_Plain`
--   `type`
--       `Text_Plain`
--

--
-- Truncate table before insert `Games`
--

TRUNCATE TABLE `Games`;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
