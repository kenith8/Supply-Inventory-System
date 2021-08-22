-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2020 at 01:46 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `supply_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_out_inventory`
--

CREATE TABLE `user_out_inventory` (
  `icsNO` varchar(100) NOT NULL,
  `userID` varchar(20) NOT NULL,
  `itemID` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `dis_to` varchar(20) DEFAULT NULL,
  `date_out` date NOT NULL,
  `time_out` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_out_inventory`
--

INSERT INTO `user_out_inventory` (`icsNO`, `userID`, `itemID`, `qty`, `dis_to`, `date_out`, `time_out`) VALUES
('2019/12/09-07091429-0', '07091429', 3, 1, 'NONE', '2019-12-09', '14:23:31'),
('2019/12/09-07091429-0', '07091429', 3, 1, 'NONE', '2019-12-09', '14:23:53'),
('2019/12/09-07091429-1', '07091429', 3, 1, 'NONE', '2019-12-09', '14:24:23'),
('2019/12/09-07091429-2', '07091429', 3, 1, 'NONE', '2019-12-09', '14:24:38'),
('2019/12/09-07091429-3', '07091429', 1, 1, 'NONE', '2019-12-09', '14:25:01'),
('2019/12/09-07091429-3', '07091429', 3, 1, 'NONE', '2019-12-09', '14:25:01'),
('2019/12/09-07091429-3', '07091429', 2, 1, 'NONE', '2019-12-09', '14:25:01'),
('2019/12/09-07091429-4', '07091429', 3, 1, 'NONE', '2019-12-09', '14:25:21');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
