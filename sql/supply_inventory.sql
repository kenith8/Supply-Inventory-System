-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2020 at 12:29 PM
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
-- Table structure for table `add_stock`
--

CREATE TABLE `add_stock` (
  `userID` varchar(20) NOT NULL,
  `itemID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `add_stock`
--

INSERT INTO `add_stock` (`userID`, `itemID`) VALUES
('04231591', 9),
('2015-21819', 1),
('', 10),
('21819', 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `catID` int(11) NOT NULL,
  `supplycat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`catID`, `supplycat`) VALUES
(1, 'IT Supplies'),
(2, 'Office Supplies'),
(8, 'Other Supplies'),
(9, 'other sup 1');

-- --------------------------------------------------------

--
-- Table structure for table `get_item`
--

CREATE TABLE `get_item` (
  `userID` varchar(20) NOT NULL,
  `stockID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `get_item`
--

INSERT INTO `get_item` (`userID`, `stockID`, `itemID`) VALUES
('07091429', 3, 2),
('07091429', 13, 1),
('2015-2016', 2, 1),
('2015-99999', 27, 1),
('04231591', 27, 1);

-- --------------------------------------------------------

--
-- Table structure for table `in_inventory`
--

CREATE TABLE `in_inventory` (
  `risNO` varchar(100) NOT NULL,
  `userID` varchar(20) NOT NULL,
  `itemID` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `date_in` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `in_inventory`
--

INSERT INTO `in_inventory` (`risNO`, `userID`, `itemID`, `qty`, `date_in`) VALUES
('0000-01', '2015-2016', 1, 100, '2019-07-12'),
('0000-02', '2015-21819', 45, 20, '2019-07-12'),
('0000-03', '2015-21819', 100, 10, '2019-07-12'),
('0000-04', '2015-2016', 10, 10, '2019-07-13'),
('0000-05', '2015-2016', 65, 5, '2019-07-10'),
('0000-06', '2015-2016', 11, 5, '2019-07-12'),
('0000-07', '2015-21819', 5, 1, '2019-09-03'),
('0000-08', '2015-21819', 2, 100, '2019-09-02'),
('0000-09', '2015-21819', 12, 20, '2019-09-04'),
('0000-09', '2015-21819', 20, 20, '2019-09-04'),
('0000-10', '2015-21819', 22, 11, '2019-09-04'),
('0000-11', '2015-21819', 1, 14, '2019-09-04'),
('0000-12', '2015-21819', 6, 5, '2019-09-05'),
('0000-13', '2015-21819', 117, 100, '2019-09-13'),
('1234567', '2015-21819', 1, 100, '2019-09-17'),
('1234567', '2015-21819', 5, 4, '2019-09-17'),
('1234567', '2015-21819', 7, 10, '2019-09-17'),
('spam lng n', '2015-21819', 23, 10, '2019-10-04'),
('0000-15', '2015-21819', 56, 50, '2019-10-08'),
('a4 add', '2015-21819', 115, 1, '2019-10-16'),
('Sample123', '2015-2016', 1, 100, '2019-12-09'),
('Sample123', '2015-2016', 2, 100, '2019-12-09'),
('jas', '2015-2016', 2, 100, '2019-12-09'),
('jas', '2015-2016', 8, 1, '2019-12-09'),
('asfdv', '2015-21819', 1, 100, '2019-12-09'),
('ggtgsdf', '2015-21819', 1, 100, '2019-12-09'),
('ssasdrr', '2015-21819', 1, 100, '2019-12-09'),
('sddfg', '2015-21819', 1, 100, '2019-12-09'),
('12-11-2019', '04231591', 1, 100, '2019-12-11'),
('12-11-2019', '04231591', 277, 10, '2019-12-11'),
('12-11-2019', '04231591', 277, 10, '2019-12-11'),
('12-16-2019', '04231591', 2, 100, '2019-12-16'),
('ICS/RIS-0 for april 1', '21819', 1, 10, '2020-04-01'),
('ICS/RIS-1 for april 1', '21819', 9, 40, '2020-04-01'),
('ICS/RIS-1 for april 1', '21819', 10, 50, '2020-04-01'),
('ICS/RIS-1 for april 1', '21819', 11, 50, '2020-04-01'),
('ICS/RIS-0 for april 2', '2016', 13, 50, '2020-04-02'),
('ICS/RIS-0 for april 2', '2016', 2, 50, '2020-04-02'),
('ICS/RIS-0 for april 2', '2016', 5, 20, '2020-04-02'),
('ICS/RIS-0 for april 2', '2016', 115, 60, '2020-04-02'),
('ICS/RIS-0 for april 2', '2016', 116, 50, '2020-04-02'),
('ICS/RIS-0 for april 2', '2016', 117, 70, '2020-04-02'),
('ICS/RIS-0 for april 2', '2016', 118, 20, '2020-04-02'),
('ICS/RIS-2 for april 2', '2016', 115, 10, '2020-04-02'),
('ICS/RIS-2 for april 2', '2016', 116, 20, '2020-04-02'),
('ICS/RIS-2 for april 2', '2016', 117, 30, '2020-04-02'),
('ICS/RIS-2 for april 2', '2016', 118, 40, '2020-04-02'),
('ICS/RIS-2 for april 2', '2016', 119, 50, '2020-04-02'),
('ICS/RIS-2 for april 2', '2016', 120, 60, '2020-04-02'),
('ICS/RIS-2 for april 2', '2016', 121, 70, '2020-04-02'),
('ICS/RIS-2 for april 2', '2016', 122, 80, '2020-04-02'),
('ICS/RIS-1 for april 2', '2016', 14, 50, '2020-04-02'),
('ICS/RIS-1 for april 2', '2016', 123, 20, '2020-04-02');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `itemID` int(11) NOT NULL,
  `catID` int(11) NOT NULL,
  `subcatID` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `img_no` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`itemID`, `catID`, `subcatID`, `item_name`, `img_no`, `qty`) VALUES
(1, 1, 54, '1 KVA', 1, 113),
(2, 1, 54, '1.5 KVA', 2, 49),
(3, 1, 13, '10 oz/283g SRAY', 3, 0),
(4, 1, 18, '16x speed, 4.7GB capacity, 120 minutes recording time, individual casing', 4, 0),
(5, 1, 35, '24 Ports, Loaded', 5, 20),
(6, 1, 19, '4x speed, 4.5GB min. capacity, 120mins. recording time individual plastic case', 6, 0),
(7, 1, 54, '650VA', 7, 0),
(8, 1, 7, '700MB/80min. running time', 8, 0),
(9, 1, 10, 'Adhesive cleaning roller', 9, 40),
(10, 1, 55, 'Cable', 10, 50),
(11, 1, 3, 'Cable Management', 11, 49),
(12, 1, 5, 'Cable Tray', 12, 0),
(13, 1, 34, 'Cat. 6, 3 ft., Factory Crimped', 13, 49),
(14, 1, 34, 'Cat. 6, 7 ft., Factory Crimped', 14, 49),
(15, 1, 34, 'Cat. 6a, 3 ft., Factory Crimped', 15, 0),
(16, 1, 34, 'Cat. 6a, 7 ft., Factory Crimped', 16, 0),
(17, 1, 34, 'Cat. 7, 3 ft., Factory Crimped', 17, 0),
(18, 1, 34, 'Cat. 7, 7 ft., Factory Crimped', 18, 0),
(19, 1, 26, 'Computer Keyboard', 19, 0),
(20, 1, 25, 'Crimping tool (tool only)', 20, 0),
(21, 1, 40, 'Dot Matrix, 80 columns', 21, 0),
(22, 1, 22, 'External Portable, High Capacity', 22, 0),
(23, 1, 20, 'External, 52x / 52x / 52', 23, 0),
(24, 1, 34, 'Fiber Optic Patch Cord', 24, 0),
(25, 1, 6, 'Flask Media Card Reader', 25, 0),
(26, 1, 31, 'Flat, 1 inch"', 26, 0),
(27, 1, 31, 'Flat, 2 inches', 27, 0),
(28, 1, 31, 'Flat, 3 inches', 28, 0),
(29, 1, 46, 'Flatbed', 29, 0),
(30, 1, 16, 'For CD Drives', 30, 0),
(31, 1, 28, 'For DNP CX330 ID Card Printer, CY-R10FC-60, 600 images per roll', 31, 0),
(32, 1, 16, 'For DVD Drives', 32, 0),
(33, 1, 37, 'For Epson EPL 6200 L, Brand Name: 20K51099', 33, 0),
(34, 1, 48, 'for EPSON TM-T82ii', 34, 0),
(35, 1, 9, 'for Gestetner Copy Printer MP2000LE, Aficio MP 2000L', 35, 0),
(36, 1, 28, 'For ID Card Printer', 36, 0),
(37, 1, 42, 'For ID Card Printer,CY3RA-100DN, 1000 cards', 37, 0),
(38, 1, 10, 'For ID Printer', 38, 0),
(39, 1, 39, 'For Pitney Bowes', 39, 0),
(40, 1, 17, 'for Xerox Phases Printer', 40, 0),
(41, 1, 25, 'Gun Tacker for UTP Cable', 41, 0),
(42, 1, 22, 'HDD Multi Docking Station', 42, 0),
(43, 1, 2, 'HDMI, 10 meters', 43, 0),
(44, 1, 2, 'HDMI, 20 meters', 44, 0),
(45, 1, 2, 'HDMI, 30 meters', 45, 0),
(46, 1, 2, 'HDMI, 5 meters', 46, 0),
(47, 1, 57, 'HDMI, splitter, 4 ports', 47, 0),
(48, 1, 23, 'Headset', 48, 0),
(49, 1, 21, 'High Capacity Storage', 49, 0),
(50, 1, 8, 'High speed, 700MB/80 minutes capacity, compatible with 4x-12x CD drivers, slim case', 50, 0),
(51, 1, 25, 'Infrared Soldering Station', 51, 0),
(52, 1, 24, 'Ink Cartridge, Black (250 ml)', 52, 0),
(53, 1, 24, 'Ink Cartridge, Colored, Set (Cyan,Magenta and Yellow)', 53, 0),
(54, 1, 24, 'Ink, Black (250 ml)', 54, 0),
(55, 1, 24, 'Ink, Colored Set (Cyan,Magenta and Yellow)', 55, 0),
(56, 1, 40, 'InkJet, Standalone', 56, 0),
(57, 1, 22, 'Internal 3.5 IDE', 57, 0),
(58, 1, 52, 'KRONE INSERTION TOOL', 58, 0),
(59, 1, 40, 'Labeler, Thermal', 59, 0),
(60, 1, 25, 'LAN Tester for UTP Cable', 60, 0),
(61, 1, 21, 'Low Capacity Storage', 61, 0),
(62, 1, 14, 'LTO Cleaning Cartirdge', 62, 0),
(63, 1, 15, 'LTO7 Tape Cartridge', 63, 0),
(64, 1, 30, 'Maintenance Kit, Generic', 64, 0),
(65, 1, 33, 'Mouse Pad', 65, 0),
(66, 1, 50, 'Network Printer Drum for Xerox 4600', 66, 0),
(67, 1, 22, 'New: External Portable, High Capacity', 67, 0),
(68, 1, 46, 'New: Flatbed', 68, 0),
(69, 1, 21, 'New: High Capacity Storage', 69, 0),
(70, 1, 40, 'New: InkJet, Standalone', 70, 0),
(71, 1, 40, 'New: Labeler, Thermal', 71, 0),
(72, 1, 40, 'New: Laser, Standalone, Black', 72, 0),
(73, 1, 21, 'New: Low Capacity Storage', 73, 0),
(74, 1, 53, 'New: Uninterruptible Power Supply, 650 VA', 74, 0),
(75, 1, 47, 'Office Productivity Software (Single license)', 75, 0),
(76, 1, 32, 'Optical, USB connection type with scroll wheel and left and right click button', 76, 0),
(77, 1, 36, 'Photo Drum, generic', 77, 0),
(78, 1, 51, 'Plastic', 78, 0),
(79, 1, 38, 'Pocket WIFI Broadband, Open-Line, LT', 79, 0),
(80, 1, 10, 'Premier cleaning kit, (50 cleaning cards and 25 cleaning swabs)', 80, 0),
(81, 1, 41, 'Presentation Recmote clicker, Wireless, USB', 81, 0),
(82, 1, 2, 'Printer Cable 5 meters', 82, 0),
(83, 1, 25, 'Professional PC Tool Kit with Precision Screws', 83, 0),
(84, 1, 47, 'Realtime Screen Monitoring Software', 84, 0),
(85, 1, 55, 'Repeater"', 85, 0),
(86, 1, 29, 'RF Wireless Laser Pointer (Silver) with Page Up/down Presentation Function - 256 MB USB Flash Drive', 86, 0),
(87, 1, 2, 'RGB, for Video Display to PC/Laptop, 3 meters', 87, 0),
(88, 1, 43, 'Ribbon for Printer Dot Matrix, 132 Columns', 88, 0),
(89, 1, 43, 'Ribbon For Printer Dot Matrix, 80 Columns', 89, 0),
(90, 1, 44, 'RJ 11, For handset', 90, 0),
(91, 1, 44, 'RJ 11, For modular box', 91, 0),
(92, 1, 44, 'RJ 45', 92, 0),
(93, 1, 28, 'SIZE 65mm x 95mm', 93, 0),
(94, 1, 4, 'Tie Wrap, 10 inches', 94, 0),
(95, 1, 4, 'Tie Wrap, 3 inches', 95, 0),
(96, 1, 4, 'Tie Wrap, 5 inches', 96, 0),
(97, 1, 49, 'Toner Cartridge, Colored, Generic', 97, 0),
(98, 1, 49, 'Toner Cartridge, Monochrome, Generic', 98, 0),
(99, 1, 49, 'Toner Cartridge, Monochrome, High Yield', 99, 0),
(100, 1, 53, 'Uninterruptible Power Supply, 650 VA', 100, 0),
(101, 1, 2, 'USB, parallel', 101, 0),
(102, 1, 2, 'UTP Cable CAT7', 102, 0),
(103, 1, 2, 'UTP CABLE, at least CAT 6 or latest', 103, 0),
(104, 1, 2, 'UTP CABLE, at least CAT 7', 104, 0),
(105, 1, 12, 'UY Connector, Butt Splice, 2 wire Connector, Gel filled', 105, 0),
(106, 1, 1, 'VGA Adaptor (HDMI,Firewire, Serial, etc.)', 106, 0),
(107, 1, 1, 'VGA Adaptor ordinary', 107, 0),
(108, 1, 1, 'VGA, Splitter, 4 ports', 108, 0),
(109, 1, 2, 'Video Graphics Array (VGA) Cable, at least 5 meters long', 109, 0),
(110, 1, 56, 'Web Camera', 110, 0),
(111, 1, 56, 'Web Camera (For ID Badge Printer with Laminator)', 111, 0),
(112, 1, 27, 'White, up to 1/2 inches', 112, 0),
(113, 1, 11, 'Wipe Out', 113, 0),
(114, 1, 27, 'Yellow, up to 1/2 inches', 114, 0),
(115, 2, 58, 'A4 Paper', 115, 65),
(116, 2, 58, 'Alcohol, 500ml', 116, 68),
(117, 2, 58, 'Ballpen (Black)', 117, 99),
(118, 2, 58, 'Ballpen (Blue)', 118, 59),
(119, 2, 58, 'Battery AA (2pcs/Pack)', 119, 49),
(120, 2, 58, 'Battery AAA (2pcs/Pack)', 120, 60),
(121, 2, 58, 'DVD-RW', 121, 69),
(122, 2, 58, 'Elmers Glue-All 473ml', 122, 79),
(123, 2, 58, 'File Holder Box (Size)', 123, 19),
(124, 2, 58, 'Folio Paper (8.5x13)', 124, 0),
(125, 2, 58, 'Glue, with applicator', 125, 0),
(126, 2, 58, 'HP 680 Cartridge (Black)', 126, 0),
(127, 2, 58, 'HP 680 Cartridge (Colored)', 127, 0),
(128, 2, 58, 'Laminating Film (A4)', 128, 0),
(129, 2, 58, 'Masking Tape 2 inches', 129, 0),
(130, 2, 58, 'Paper Clips (32mm)', 130, 0),
(131, 2, 58, 'Paper Clips (50mm)', 131, 0),
(132, 2, 58, 'Paper Fasteners (Colored)', 132, 0),
(133, 2, 58, 'Paper Fasteners (Metal)', 133, 0),
(134, 2, 58, 'Permanent Marker (Black)', 134, 0),
(135, 2, 58, 'Permanent Marker (Red)', 135, 0),
(136, 2, 58, 'Photo Paper A4', 136, 0),
(137, 2, 58, 'Plastic Cup', 137, 0),
(138, 2, 58, 'Post-it Flag, standard', 138, 0),
(139, 2, 58, 'Push Pins', 139, 0),
(140, 2, 58, 'Rubber Band, Size 18', 140, 0),
(141, 2, 58, 'Ruler', 141, 0),
(142, 2, 58, 'Scissors 8 inches', 142, 0),
(143, 2, 58, 'Scissors 8.5 inces', 143, 0),
(144, 2, 58, 'Sign Pen (Blue)', 144, 0),
(145, 2, 58, 'Staple Wire (No. 35)', 145, 0),
(146, 2, 58, 'Stationary Tape (1 inches)', 146, 0),
(147, 2, 58, 'Stick-on (Size 3x2 inches)', 147, 0),
(148, 2, 58, 'Tape Dispenser', 148, 0),
(149, 2, 58, 'Tape Double-sided w/ foam 2 inches', 149, 0),
(150, 2, 58, 'Web camera', 150, 0),
(151, 2, 58, 'White Board Marker (Black)', 151, 0),
(240, 1, 1, 'HDMI TO VGA', 240, 0),
(241, 1, 1, 'kva', 241, 0),
(242, 2, 58, 'chalk', 242, 0),
(243, 2, 58, 'knife', 243, 0),
(247, 1, 16, 'chicken', 247, 0),
(263, 2, 58, 'soy sauce', 263, 0),
(264, 1, 17, 'wings', 264, 0),
(265, 2, 58, 'feet', 265, 0),
(266, 2, 58, 'pencil', 266, 0),
(267, 1, 1, '123123', 267, 0),
(272, 1, 10, 'Trapo', 272, 0),
(274, 6, 81, 'item 1', 274, 0),
(276, 1, 55, 'san disk', 276, 0),
(277, 8, 82, 'monoblock', 277, 29);

-- --------------------------------------------------------

--
-- Stand-in structure for view `item_logs`
-- (See below for the actual view)
--
CREATE TABLE `item_logs` (
`icsNO` varchar(100)
,`userID` varchar(20)
,`itemID` int(11)
,`qty` int(11)
,`dis_to` varchar(30)
,`date_time` datetime
);

-- --------------------------------------------------------

--
-- Table structure for table `item_stock`
--

CREATE TABLE `item_stock` (
  `stockID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `risNO` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `date_in` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_stock`
--

INSERT INTO `item_stock` (`stockID`, `itemID`, `risNO`, `qty`, `date_in`) VALUES
(13, 1, '0000-11', 12, '2019-09-04'),
(30, 1, 'sddfg', 97, '2019-12-09'),
(32, 1, 'ICS/RIS-0 for april 1', 4, '2020-04-01'),
(42, 9, 'ICS/RIS-1 for april 1', 40, '2020-04-01'),
(43, 10, 'ICS/RIS-1 for april 1', 50, '2020-04-01'),
(44, 11, 'ICS/RIS-1 for april 1', 49, '2020-04-01'),
(45, 13, 'ICS/RIS-0 for april 2', 49, '2020-04-02'),
(46, 2, 'ICS/RIS-0 for april 2', 49, '2020-04-02'),
(47, 5, 'ICS/RIS-0 for april 2', 20, '2020-04-02'),
(48, 115, 'ICS/RIS-0 for april 2', 57, '2020-04-02'),
(49, 116, 'ICS/RIS-0 for april 2', 49, '2020-04-02'),
(50, 117, 'ICS/RIS-0 for april 2', 69, '2020-04-02'),
(51, 118, 'ICS/RIS-0 for april 2', 20, '2020-04-02'),
(52, 115, 'ICS/RIS-2 for april 2', 8, '2020-04-02'),
(53, 116, 'ICS/RIS-2 for april 2', 19, '2020-04-02'),
(54, 117, 'ICS/RIS-2 for april 2', 30, '2020-04-02'),
(55, 118, 'ICS/RIS-2 for april 2', 39, '2020-04-02'),
(56, 119, 'ICS/RIS-2 for april 2', 49, '2020-04-02'),
(57, 120, 'ICS/RIS-2 for april 2', 60, '2020-04-02'),
(58, 121, 'ICS/RIS-2 for april 2', 69, '2020-04-02'),
(59, 122, 'ICS/RIS-2 for april 2', 79, '2020-04-02'),
(60, 14, 'ICS/RIS-1 for april 2', 49, '2020-04-02'),
(61, 123, 'ICS/RIS-1 for april 2', 19, '2020-04-02');

-- --------------------------------------------------------

--
-- Table structure for table `out_inventory`
--

CREATE TABLE `out_inventory` (
  `icsNO` varchar(100) NOT NULL,
  `userID` varchar(20) NOT NULL,
  `itemID` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `dis_to` varchar(30) NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `out_inventory`
--

INSERT INTO `out_inventory` (`icsNO`, `userID`, `itemID`, `qty`, `dis_to`, `date_time`) VALUES
('2020/03/27-21819-0', '21819', 1, 1, 'wan', '2020-03-27 06:14:28'),
('2020/03/27-21819-0', '21819', 1, 2, 'to', '2020-03-27 06:14:28'),
('2020/03/27-21819-0', '21819', 1, 3, 'tre', '2020-03-27 06:14:28'),
('2020/03/27-21819-1', '21819', 1, 1, 'wan1', '2020-03-27 06:15:24'),
('2020/03/27-21819-1', '21819', 1, 2, 'tu2', '2020-03-27 06:15:24'),
('2020/03/27-21819-1', '21819', 1, 3, 'tre3', '2020-03-27 06:15:24'),
('2020/03/29-21819-2', '21819', 1, 3, 'three', '2020-03-29 10:06:09'),
('2020/03/29-21819-2', '21819', 1, 2, 'two', '2020-03-29 10:06:09'),
('2020/03/29-21819-2', '21819', 1, 1, 'one', '2020-03-29 10:06:09'),
('2020/03/30-2016-0', '2016', 1, 1, '1st', '2020-03-30 11:37:54'),
('2020/04/03-2016-1', '2016', 1, 2, 'sample1', '2020-04-03 07:51:35'),
('2020/04/03-2016-1', '2016', 115, 1, 'sample1', '2020-04-03 07:51:35'),
('2020/04/03-2016-1', '2016', 116, 1, 'sample1', '2020-04-03 07:51:35'),
('2020/04/03-2016-1', '2016', 116, 1, 'sample1', '2020-04-03 07:51:35'),
('2020/04/03-2016-1', '2016', 115, 1, 'sample1', '2020-04-03 07:51:35'),
('2020/04/03-2016-1', '2016', 122, 1, 'sample1', '2020-04-03 07:51:35'),
('2020/04/03-2016-2', '2016', 115, 1, 'sample2', '2020-04-03 07:52:21'),
('2020/04/03-2016-3', '2016', 1, 420, 'sample3', '2020-04-03 07:53:06'),
('2020/04/03-2016-3', '2016', 1, 1, 'sample3', '2020-04-03 07:53:06'),
('2020/04/03-2016-4', '2016', 3, 1, 'samole', '2020-04-03 07:53:36'),
('2020/04/03-2016-5', '2016', 6, 5, 'sample', '2020-04-03 07:54:18'),
('2020/04/03-2016-5', '2016', 2, 37, 'sample', '2020-04-03 07:54:18'),
('2020/04/03-2016-6', '2016', 2, 22, 'sample', '2020-04-03 07:55:05'),
('2020/04/03-2016-6', '2016', 2, 69, 'sample', '2020-04-03 07:55:05'),
('2020/04/03-2016-6', '2016', 2, 1, 'sample', '2020-04-03 07:55:05'),
('2020/04/03-2016-7', '2016', 3, 87, 'sample', '2020-04-03 07:55:44'),
('2020/04/03-2016-7', '2016', 4, 31, 'sample', '2020-04-03 07:55:44'),
('2020/04/03-2016-8', '2016', 5, 1, 'sample', '2020-04-03 07:56:51'),
('2020/04/03-2016-8', '2016', 5, 1, 'sample', '2020-04-03 07:56:51'),
('2020/04/03-2016-9', '2016', 5, 62, 'sample', '2020-04-03 07:57:13'),
('2020/04/03-2016-10', '2016', 20, 20, 'sample', '2020-04-03 07:57:42'),
('2020/04/03-2016-10', '2016', 22, 11, 'sample', '2020-04-03 07:58:06'),
('2020/04/03-2016-10', '2016', 23, 9, 'sample', '2020-04-03 07:58:32'),
('2020/04/03-2016-10', '2016', 7, 10, 'sample', '2020-04-03 08:14:23'),
('2020/04/03-2016-10', '2016', 8, 1, 'sample', '2020-04-03 08:15:28'),
('2020/04/03-2016-11', '2016', 56, 50, 'sample', '2020-04-03 08:41:05'),
('2020/04/03-2016-12', '2016', 1, 3, 'sample', '2020-04-03 08:42:12'),
('2020/04/03-21819-99', '21819', 1, 1, 'sample1', '2020-04-03 09:12:53'),
('2020/04/03-21819-100', '21819', 12, 19, 'sample', '2020-04-03 09:14:26');

-- --------------------------------------------------------

--
-- Table structure for table `subcat`
--

CREATE TABLE `subcat` (
  `subcatID` int(11) NOT NULL,
  `catID` int(11) NOT NULL,
  `subcatName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subcat`
--

INSERT INTO `subcat` (`subcatID`, `catID`, `subcatName`) VALUES
(1, 1, 'ADAPTOR'),
(2, 1, 'CABLE'),
(3, 1, 'CABLE MANAGEMENT'),
(4, 1, 'CABLE TIE'),
(5, 1, 'CABLE TRAY'),
(6, 1, 'CARD READER'),
(7, 1, 'CD RECORDABLE'),
(8, 1, 'CD REWRITABLE'),
(9, 1, 'CLEANING BLADE ASSEMBLY'),
(10, 1, 'CLEANING KIT'),
(11, 1, 'COMPUTER CLEANER'),
(12, 1, 'CONNECTOR'),
(13, 1, 'CONTACT CLEANER'),
(14, 1, 'DATA TAPE CARTRIDGE'),
(15, 1, 'DATA TAPE CLEANER'),
(16, 1, 'DISK DRIVE CLEANER'),
(17, 1, 'DRUM'),
(18, 1, 'DVD RECORDABLE'),
(19, 1, 'DVD REWRITABLE'),
(20, 1, 'DVD/CD RW'),
(21, 1, 'FLASH/THUMB DRIVE'),
(22, 1, 'HDD'),
(23, 1, 'HEADSET'),
(24, 1, 'INK CARTRIDGE'),
(25, 1, 'IT TOOLS'),
(26, 1, 'KEYBOARD'),
(27, 1, 'LABELER CARTRIDGE'),
(28, 1, 'LAMINATING PATCH'),
(29, 1, 'LASER POINTER'),
(30, 1, 'MAINTENANCE KIT'),
(31, 1, 'MOULDING'),
(32, 1, 'MOUSE'),
(33, 1, 'MOUSE PAD'),
(34, 1, 'PATCH CORD'),
(35, 1, 'PATCH PANEL'),
(36, 1, 'PHOTO DRUM'),
(37, 1, 'PHOTOCONDUCTOR KIT'),
(38, 1, 'PORTABLE WIFI'),
(39, 1, 'PRINT HEAD'),
(40, 1, 'PRINTER'),
(41, 1, 'REMOTE CLICKER'),
(42, 1, 'RE-TRANSFER FILM'),
(43, 1, 'RIBBON'),
(44, 1, 'RJ CONNECTORS'),
(45, 1, 'ROLLER'),
(46, 1, 'SCANNER'),
(47, 1, 'SOFTWARE'),
(48, 1, 'THERMAL PAPER'),
(49, 1, 'TONER CARTRIDGE'),
(50, 1, 'TONER DRUM'),
(51, 1, 'TONER WASTE BOX'),
(52, 1, 'TOOLS, IT'),
(53, 1, 'UPS'),
(54, 1, 'UPS BATTERY'),
(55, 1, 'USB'),
(56, 1, 'WEB CAMERA'),
(57, 1, 'OUTPUT SPLITTER'),
(58, 2, 'None'),
(82, 8, 'chair'),
(83, 1, 'mini projector'),
(84, 9, 'sub  1'),
(85, 8, 'sup 1'),
(86, 8, 'sup 2'),
(89, 9, 'sub 2');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(11) NOT NULL,
  `accountType` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `name`, `password`, `accountType`) VALUES
(2016, 'Sir Ian', '123', 'admin'),
(12345, 'Sir Ino', '123', 'user'),
(21723, 'Sir Dongkoy', '12345', 'user'),
(21819, 'Mrs Sharon Palac', '123', 'admin'),
(22222, 'Maam Mynell', '123', 'user'),
(99999, 'Boss Romel', 'pips', 'user'),
(4231591, 'Arvin', '1234', 'admin'),
(7091429, 'Ian Ng-Ee', 'xxx1234567', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user_out_inventory`
--

CREATE TABLE `user_out_inventory` (
  `icsNO` varchar(100) NOT NULL,
  `userID` varchar(20) NOT NULL,
  `itemID` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `dis_to` varchar(30) DEFAULT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_out_inventory`
--

INSERT INTO `user_out_inventory` (`icsNO`, `userID`, `itemID`, `qty`, `dis_to`, `date_time`) VALUES
('2020/04/02-12345-0', '12345', 1, 1, 'NONE', '2020-04-02 11:11:17'),
('2020/04/03-12345-0', '12345', 1, 1, 'NONE', '2020-04-03 08:43:34'),
('2020/04/03-12345-0', '12345', 1, 1, 'NONE', '2020-04-03 08:43:34'),
('2020/04/03-12345-1', '12345', 1, 1, 'NONE', '2020-04-03 08:54:14'),
('2020/04/03-12345-2', '12345', 13, 1, 'NONE', '2020-04-03 08:54:28'),
('2020/04/03-12345-3', '12345', 117, 1, 'NONE', '2020-04-03 08:54:53'),
('2020/04/03-12345-4', '12345', 11, 1, 'NONE', '2020-04-03 08:55:06'),
('2020/04/03-12345-5', '12345', 115, 1, 'NONE', '2020-04-03 08:55:30'),
('2020/04/03-12345-5', '12345', 115, 1, 'NONE', '2020-04-03 08:55:30'),
('2020/04/03-12345-6', '12345', 118, 1, 'NONE', '2020-04-03 08:55:51'),
('2020/04/03-12345-7', '12345', 121, 1, 'NONE', '2020-04-03 08:56:16'),
('2020/04/03-12345-8', '12345', 2, 1, 'NONE', '2020-04-03 08:56:34'),
('2020/04/03-12345-8', '12345', 1, 1, 'NONE', '2020-04-03 08:56:34'),
('2020/04/03-12345-9', '12345', 5, 4, 'NONE', '2020-04-03 08:57:01'),
('2020/04/03-12345-10', '12345', 14, 1, 'NONE', '2020-04-03 08:57:20'),
('2020/04/03-12345-11', '12345', 1, 1, 'NONE', '2020-04-03 08:57:37'),
('2020/04/03-12345-99', '12345', 119, 1, 'NONE', '2020-04-03 08:59:53'),
('2020/04/03-12345-100', '12345', 115, 1, 'NONE', '2020-04-03 09:09:15'),
('2020/04/03-12345-101', '12345', 123, 1, 'NONE', '2020-04-03 09:09:47');

-- --------------------------------------------------------

--
-- Structure for view `item_logs`
--
DROP TABLE IF EXISTS `item_logs`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `item_logs`  AS  select `out_inventory`.`icsNO` AS `icsNO`,`out_inventory`.`userID` AS `userID`,`out_inventory`.`itemID` AS `itemID`,`out_inventory`.`qty` AS `qty`,`out_inventory`.`dis_to` AS `dis_to`,`out_inventory`.`date_time` AS `date_time` from `out_inventory` union all select `user_out_inventory`.`icsNO` AS `icsNO`,`user_out_inventory`.`userID` AS `userID`,`user_out_inventory`.`itemID` AS `itemID`,`user_out_inventory`.`qty` AS `qty`,`user_out_inventory`.`dis_to` AS `dis_to`,`user_out_inventory`.`date_time` AS `date_time` from `user_out_inventory` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`catID`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`itemID`);

--
-- Indexes for table `item_stock`
--
ALTER TABLE `item_stock`
  ADD PRIMARY KEY (`stockID`);

--
-- Indexes for table `subcat`
--
ALTER TABLE `subcat`
  ADD PRIMARY KEY (`subcatID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `catID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `itemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=278;
--
-- AUTO_INCREMENT for table `item_stock`
--
ALTER TABLE `item_stock`
  MODIFY `stockID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT for table `subcat`
--
ALTER TABLE `subcat`
  MODIFY `subcatID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
