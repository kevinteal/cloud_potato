-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 14, 2014 at 09:34 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cloud_potato`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache_shows`
--

CREATE TABLE IF NOT EXISTS `cache_shows` (
  `show_id` int(11) NOT NULL,
  `showname` varchar(100) NOT NULL,
  `ep_num` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `airdate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cache_shows`
--

INSERT INTO `cache_shows` (`show_id`, `showname`, `ep_num`, `title`, `airdate`) VALUES
(8511, 'The Big Bang Theory', 's08e05', 'The Focus Attenuation', 20141013),
(35048, 'The Blacklist', 's02e04', 'Dr. Linus Creel', 20141013);

-- --------------------------------------------------------

--
-- Table structure for table `shows`
--

CREATE TABLE IF NOT EXISTS `shows` (
  `showid` int(11) NOT NULL AUTO_INCREMENT,
  `showname` varchar(40) NOT NULL,
  `list_link` text NOT NULL,
  `tvrageapi_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `current_season` int(11) NOT NULL,
  `scheduled` varchar(20) NOT NULL,
  PRIMARY KEY (`showid`),
  UNIQUE KEY `tvrageapi_id` (`tvrageapi_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=97 ;

--
-- Dumping data for table `shows`
--

INSERT INTO `shows` (`showid`, `showname`, `list_link`, `tvrageapi_id`, `status`, `current_season`, `scheduled`) VALUES
(3, 'The Simpsons', 'http://en.wikipedia.org/wiki/List_of_The_Simpsons_episodes', 6190, 1, 26, 'Sunday'),
(4, 'Family Guy', 'http://en.wikipedia.org/wiki/List_of_Family_Guy_episodes', 3506, 1, 13, 'Sunday'),
(5, 'American Dad', 'http://en.wikipedia.org/wiki/List_of_American_Dad!_episodes', 2594, 1, 10, 'Sunday'),
(10, 'The Walking Dead', 'http://en.wikipedia.org/wiki/List_of_The_Walking_Dead_episodes', 25056, 1, 5, 'Sunday'),
(17, 'How I Met Your Mother', 'http://en.wikipedia.org/wiki/List_of_How_I_Met_Your_Mother_episodes', 3918, 0, 9, 'ended'),
(25, 'Modern Family', 'http://en.wikipedia.org/wiki/List_of_Modern_Family_episodes', 22622, 1, 6, 'Wednesday'),
(30, 'South Park', 'http://en.wikipedia.org/wiki/List_of_South_Park_episodes', 5266, 1, 18, 'Wednesday'),
(31, 'Mythbusters', 'http://en.wikipedia.org/wiki/List_of_MythBusters_episodes', 4605, 1, 15, 'Thursday'),
(32, 'The Big Bang Theory', 'http://en.wikipedia.org/wiki/List_of_The_Big_Bang_Theory_episodes', 8511, 1, 8, 'Monday'),
(33, 'Community', 'http://en.wikipedia.org/wiki/List_of_Community_episodes', 22589, 1, 5, 'Thursday'),
(38, 'Gold Rush', 'http://en.wikipedia.org/wiki/List_of_Gold_Rush_episodes', 26961, 1, 5, 'Friday'),
(43, 'Bobs Burgers', 'http://en.wikipedia.org/wiki/List_of_Bob''s_Burgers_episodes', 24607, 1, 5, 'Sunday'),
(51, 'The Blacklist', 'http://en.wikipedia.org/wiki/List_of_The_Blacklist_episodes', 35048, 1, 2, 'Monday'),
(52, 'The Goldbergs', 'http://en.wikipedia.org/wiki/The_Goldbergs_(TV_series)', 35814, 1, 2, 'Wednesday'),
(54, 'Brooklyn Nine-Nine', 'http://en.wikipedia.org/wiki/Brooklyn_Nine-Nine', 35774, 1, 2, 'Sunday'),
(56, 'Elementary ', 'http://en.wikipedia.org/wiki/List_of_Elementary_episodes', 30750, 1, 3, 'Thursday'),
(61, 'The Americans', 'http://en.wikipedia.org/wiki/List_of_The_Americans_episodes', 30449, 1, 3, 'Wednesday'),
(62, 'Louie', 'http://en.wikipedia.org/wiki/List_of_Louie_episodes', 24504, 1, 4, 'Monday'),
(63, 'Wilfred', 'http://en.wikipedia.org/wiki/List_of_Wilfred_(U.S._TV_series)_episodes', 25709, 0, 4, 'ended'),
(64, 'Legit', 'http://en.wikipedia.org/wiki/List_of_Legit_episodes', 31934, 0, 2, 'ended'),
(65, 'Archer', 'http://en.wikipedia.org/wiki/List_of_Archer_episodes', 23354, 1, 5, 'Monday'),
(66, 'American Horror Story', 'http://en.wikipedia.org/wiki/List_of_American_Horror_Story_episodes', 28776, 1, 4, 'Wednesday'),
(67, 'Resurrection', 'http://en.wikipedia.org/wiki/Resurrection_(U.S._TV_series)', 34321, 1, 2, 'Sunday'),
(68, 'The Mentalist', 'http://en.wikipedia.org/wiki/List_of_The_Mentalist_episodes', 18967, 1, 7, 'Sunday'),
(71, 'The Following', 'http://en.wikipedia.org/wiki/List_of_The_Following_episodes', 31672, 1, 3, 'Monday'),
(72, 'Rectify', 'http://en.wikipedia.org/wiki/List_of_Rectify_episodes', 30069, 1, 2, 'Thursday'),
(74, 'True Detective', 'http://en.wikipedia.org/wiki/True_Detective_(TV_series)', 31369, 1, 0, 'Sunday'),
(75, 'The Leftovers', 'http://en.wikipedia.org/wiki/The_Leftovers_(TV_series)', 34506, 1, 0, 'Sunday'),
(76, 'Silicon Valley', 'http://en.wikipedia.org/wiki/Silicon_Valley_(TV_series)', 33759, 1, 0, 'Sunday'),
(77, 'Hannibal', 'http://en.wikipedia.org/wiki/List_of_Hannibal_episodes', 30909, 1, 2, 'Friday'),
(78, 'The Shield', 'http://en.wikipedia.org/wiki/List_of_The_Shield_episodes', 6185, 0, 8, 'ended'),
(79, 'Psych ', 'http://en.wikipedia.org/wiki/List_of_Psych_episodes', 8322, 0, 8, 'ended'),
(80, 'The Wire', 'http://en.wikipedia.org/wiki/List_of_The_Wire_episodes', 6296, 0, 5, 'ended'),
(87, 'Homeland', 'http://en.wikipedia.org/wiki/List_of_Homeland_episodes', 27811, 1, 4, 'Sunday'),
(91, 'Under the Dome', 'http://en.wikipedia.org/wiki/List_of_Under_the_Dome_episodes', 25988, 1, 0, 'Monday'),
(92, 'The Sopranos', 'http://en.wikipedia.org/wiki/List_of_The_Sopranos_episodes', 6206, 1, 0, 'Sunday');

-- --------------------------------------------------------

--
-- Table structure for table `time_stamp`
--

CREATE TABLE IF NOT EXISTS `time_stamp` (
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `time_stamp`
--

INSERT INTO `time_stamp` (`time`) VALUES
(20141014);
