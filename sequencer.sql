-- phpMyAdmin SQL Dump
-- version 4.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 15, 2015 at 01:29 PM
-- Server version: 5.1.73-log
-- PHP Version: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sequencer`
--

-- --------------------------------------------------------

--
-- Table structure for table `sequences`
--

CREATE TABLE IF NOT EXISTS `sequences` (
`id` int(11) NOT NULL,
  `date` int(10) NOT NULL,
  `title` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT 'Untitled',
  `accesscount` int(10) NOT NULL,
  `basedon` int(11) NOT NULL,
  `data` mediumtext NOT NULL,
  `datalength` int(11) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `scale` int(2) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=69051 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sequences`
--
ALTER TABLE `sequences`
 ADD PRIMARY KEY (`id`), ADD KEY `date` (`date`), ADD KEY `accesscount` (`accesscount`), ADD KEY `datalength` (`datalength`), ADD KEY `datalength_2` (`datalength`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sequences`
--
ALTER TABLE `sequences`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=69051;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
