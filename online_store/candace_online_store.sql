-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql311.epizy.com
-- Generation Time: Aug 23, 2021 at 02:18 PM
-- Server version: 5.6.48-88.0
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `epiz_29425362_candace_online_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `fileToUpload` varchar(100) NOT NULL,
  `cus_username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `confPass` varchar(128) NOT NULL,
  `firstname` varchar(128) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `gender` varchar(128) NOT NULL,
  `accountstatus` varchar(128) NOT NULL,
  `registrationdatetime` datetime NOT NULL,
  `dateofbirth` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `fileToUpload`, `cus_username`, `password`, `confPass`, `firstname`, `lastname`, `gender`, `accountstatus`, `registrationdatetime`, `dateofbirth`) VALUES
(1, 'picture/customer/1_1629730100.jpg', 'WinnieMoke', 'candace520A@', 'candace520A@', 'Yuyu', 'Le', 'female', 'active', '2021-07-11 18:09:00', '2001-02-06'),
(2, 'picture/customer/2_1629730065.jpg', 'XiaoFang', 'candace520A@', 'candace520A@', 'Lee', 'Le', 'female', 'active', '2021-07-12 14:46:00', '2000-02-09'),
(6, 'picture/customer/6_1629730032.jpg', 'WinnieHong', 'cutexuen99@A@', 'cutexuen99@A@', 'Lee', 'Le', 'female', 'inactive', '2021-07-15 14:52:00', '2001-02-15'),
(7, 'picture/customer/7_1629729980.jpg', 'Winnieleong', 'candace520@A@', 'candace520@A@', 'Lee', 'Le', 'female', 'inactive', '2021-07-15 14:59:00', '2001-02-15'),
(10, 'picture/customer/10_1629729831.jpg', 'XiaoBob', 'candace520A@', 'candace520A@', 'Lee', 'Le', 'female', 'inactive', '2021-07-15 15:09:00', '2002-02-06'),
(11, 'picture/customer/11_1629729789.jpg', 'XiaoEng', 'candace520@A@', 'candace520@A@', 'Lee', 'Le', 'female', 'inactive', '2021-07-15 15:15:00', '2002-02-13'),
(12, 'picture/customer/12_1629729732.jpg', 'XiaoPong', 'candace520A@', 'candace520A@', 'Lee', 'Le', 'female', 'inactive', '2021-07-15 15:17:00', '2001-02-15'),
(13, 'picture/customer/13_1629729285.jpg', 'XiaoQiang', 'Candace520@A@', 'Candace520@A@', 'Lee', 'Le', 'female', 'inactive', '2021-07-15 15:29:00', '2002-01-29'),
(14, 'picture/customer/14_1629729239.jpg', 'XiaoLing', 'Candace520@A@', 'Candace520@A@', 'Lee', 'Le', 'female', 'inactive', '2021-07-15 15:30:00', '2001-02-15'),
(15, 'picture/customer/15_1629729184.jpg', 'XiaoMok', 'candace520A@', 'candace520A@', 'Lee', 'Le', 'female', 'inactive', '2021-07-15 15:42:00', '2001-02-06'),
(16, 'picture/customer/16_1629729130.png', 'XiaoDeng', 'candace520A@', 'candace520A@', 'Lee', 'Le', 'female', 'inactive', '2021-07-15 17:07:00', '2000-02-02'),
(17, 'picture/customer/17_1629729055.jpg', 'XiaoOng', 'Candace520A@', 'Candace520A@', 'Lee', 'Le', 'female', 'inactive', '2021-07-15 18:22:00', '2001-02-06'),
(18, 'picture/customer/18_1629729018.jpg', 'XiaoKing', 'candace520@A@', 'candace520@A@', 'Lee', 'Le', 'female', 'inactive', '2021-07-15 19:04:00', '2002-01-29'),
(19, 'picture/customer/19_1629728976.jpg', 'XiaoSing', 'candace520@A', 'candace520@A', 'Lee', 'Le', 'female', 'inactive', '2021-07-15 19:05:00', '1999-12-27'),
(20, 'picture/customer/20_1629728922.png', 'XiaoKe', 'Candace520@', 'Candace520@', 'Lee', 'Le', 'female', 'inactive', '2021-07-15 19:07:00', '2000-02-08'),
(21, 'picture/customer/21_1629725774.jpg', 'XiaoHong', 'Candace520@', 'Candace520@', 'Lee', 'Le', 'female', 'inactive', '2021-07-16 13:34:00', '2000-05-16'),
(22, 'picture/customer/22_1629725724.png', 'XiaoCai', 'Candace520@', 'Candace520@', 'Lee', 'Le', 'female', 'inactive', '2021-07-16 13:44:00', '2001-02-06'),
(23, 'picture/customer/23_1629724207.jpg', 'Xiaowing', 'Candace520@', 'Candace520@', 'Lee', 'Le', 'female', 'inactive', '2021-07-30 17:09:00', '2000-02-08'),
(24, 'picture/customer/24_1629724028.jpg', 'XiaoFeng', 'Candace520@', 'Candace520@', 'Lee', 'Le', 'female', 'inactive', '2021-07-30 17:11:00', '2000-02-01'),
(25, 'picture/customer/25_1629723882.jpg', 'XiaoMeng', 'Candace520@', 'Candace520@', 'Lee', 'Le', 'female', 'active', '2021-07-30 18:30:00', '2001-02-06'),
(26, 'picture/customer/noPic.jpg', 'XiaoYong', 'Candace520@', 'Candace520@', 'Lee', 'Lew', 'female', 'inactive', '2021-08-13 19:04:00', '2000-02-08'),
(27, 'picture/customer/27_1629722891.jpg', 'Winnielee', 'Candace520@', 'Candace520@', 'Lee', 'Lew', 'female', 'active', '2021-08-13 19:36:00', '1999-02-09'),
(28, 'picture/customer/28_1629713938.jpg', 'XiaoLe520', 'Candace520@', 'Candace520@', 'Lee', 'Yuyu', 'female', 'active', '2021-08-13 20:36:00', '1999-02-09'),
(31, 'picture/customer/31_1629713850.jpg', 'XiaoYing', 'Candace520@', 'Candace520@', 'Lee', 'Lew', 'female', 'inactive', '2021-08-14 12:22:00', '2003-02-04'),
(32, 'picture/customer/32_1629713798.jpg', 'WinnieYong', 'Candace520@', 'Candace520@', 'Yuyu', 'Le', 'male', 'inactive', '2021-08-14 12:23:00', '2001-02-13'),
(35, 'picture/customer/noPic.jpg', 'WinnieYing', 'Candace520@', 'Candace520@', 'Lee', 'Yuyu', 'female', 'inactive', '2021-08-14 16:50:00', '2002-02-05'),
(38, 'picture/customer/noPic.jpg', 'Winson789', '+Ys011121', '+Ys011121', 'Chew', 'Yong Sern', 'male', 'active', '2021-08-22 20:10:00', '1998-05-18'),
(39, 'picture/customer/noPic.jpg', 'Example', 'Candace520@', 'Candace520@', 'Vfvbeb', 'Begr', 'female', 'active', '2021-08-23 23:02:00', '2001-11-11');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL,
  `cus_username` varchar(128) NOT NULL,
  `total` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `cus_username`, `total`) VALUES
(8, 'Xiao le', 418.04),
(11, 'Winnie lee', 22.7),
(13, 'Xiao ying', 55),
(14, 'Xiao ying', 75.1),
(15, 'Xiao Le', 7),
(16, 'Xiao Le', 22.7),
(17, 'Wilson', 681.93),
(18, 'Winson789', 80),
(19, 'XiaoMeng', 880);

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `orderID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`orderID`, `productID`, `quantity`, `total`) VALUES
(8, 10, 5, 275),
(11, 6, 2, 22.7),
(12, 3, 2, 3.98),
(18, 53, 1, 80),
(14, 7, 1, 7),
(15, 7, 1, 7),
(8, 7, 3, 21),
(16, 6, 2, 22.7),
(14, 6, 6, 68.1),
(17, 8, 4, 35.96),
(17, 53, 8, 640),
(17, 3, 3, 5.97),
(8, 6, 6, 68.1),
(19, 10, 16, 880),
(13, 10, 1, 55),
(8, 8, 6, 53.94),
(20, 6, 16, 181.6);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `productID` int(11) NOT NULL,
  `fileToUpload` varchar(100) NOT NULL,
  `name` varchar(128) NOT NULL,
  `nameMalay` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL,
  `promotion_price` double NOT NULL,
  `manufacture_date` date NOT NULL,
  `expired_date` date NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`productID`, `fileToUpload`, `name`, `nameMalay`, `description`, `price`, `promotion_price`, `manufacture_date`, `expired_date`, `created`) VALUES
(3, 'picture/product/3.png', 'Pillow', 'Bantal', 'To sleep well in night', 1.99, 1, '2021-08-07', '2021-09-11', '2015-08-02 12:14:29'),
(6, 'picture/product/6.png', 'Mouse', 'Tetikus', 'Very useful if you love your computer.', 11.35, 4, '2021-08-12', '2021-09-10', '2015-08-02 12:17:58'),
(7, 'picture/product/noPic.jpg', 'Earphone', 'J', 'You need this one if you love music.', 7, 6, '2021-08-07', '2021-09-11', '2015-08-02 12:18:21'),
(8, 'picture/product/8_1629709963.jpg', 'Netball', 'Bola Jaring', 'Netball sport lover', 8.99, 6, '2021-08-07', '2021-09-10', '2015-08-02 12:18:56'),
(10, 'picture/product/10.png', 'Book', 'Buku', 'To enance knowledge', 55, 22, '2021-07-14', '2021-08-07', '2021-07-14 12:28:44'),
(50, 'picture/product/noPic.jpg', 'Hand Cream', 'Krim Tangan', 'To moisture your hand', 55, 22, '2021-08-06', '2021-08-28', '2021-08-05 14:03:52'),
(52, 'picture/product/52.png', 'Mirror', 'Cermin', 'Bring us face to face with reality', 55, 22, '2021-08-20', '2021-09-04', '2021-08-05 17:42:45'),
(53, 'picture/product/53.png', 'Hair Dryer', 'Pengering Rambut', 'To dry your hair', 80, 50, '2021-08-13', '2021-09-04', '2021-08-07 11:23:48'),
(54, 'picture/product/noPic.jpg', 'Comb', 'Sikat', 'To comb hair', 66, 44, '2021-08-05', '2021-09-03', '2021-08-07 11:26:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`orderID`,`productID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`productID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `productID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
