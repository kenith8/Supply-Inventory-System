-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2020 at 12:37 PM
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
-- Structure for view `item_logs`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `item_logs`  AS  select `out_inventory`.`icsNO` AS `icsNO`,`out_inventory`.`userID` AS `userID`,`out_inventory`.`itemID` AS `itemID`,`out_inventory`.`qty` AS `qty`,`out_inventory`.`dis_to` AS `dis_to`,`out_inventory`.`date_time` AS `date_time` from `out_inventory` union all select `user_out_inventory`.`icsNO` AS `icsNO`,`user_out_inventory`.`userID` AS `userID`,`user_out_inventory`.`itemID` AS `itemID`,`user_out_inventory`.`qty` AS `qty`,`user_out_inventory`.`dis_to` AS `dis_to`,`user_out_inventory`.`date_time` AS `date_time` from `user_out_inventory` ;

--
-- VIEW  `item_logs`
-- Data: None
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
