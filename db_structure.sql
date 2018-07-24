-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2018 at 12:20 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `1vfhawvk`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL COMMENT 'รหัส',
  `user_id` int(11) NOT NULL COMMENT 'ผู้สั่งซื้อ',
  `shipping_type` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `sender` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'ที่อยู่ผู้ส่ง',
  `sender_type` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `receiver` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'ที่อยู่ผู้รับ',
  `order_datetime` datetime NOT NULL COMMENT 'วันและเวลาที่สั่งซื้อ',
  `total` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT 'ราคารวม',
  `order_status` char(1) CHARACTER SET utf8 NOT NULL DEFAULT 'R' COMMENT 'สถานะการสั่งซื้อสินค้า',
  `tracking_no` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ship_date` datetime DEFAULT NULL,
  `ship_price` decimal(8,2) DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci,
  `remark1` int(8) NOT NULL,
  `remark2` int(8) NOT NULL,
  `remark3` text COLLATE utf8_unicode_ci NOT NULL,
  `remark4` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_id` int(11) NOT NULL COMMENT 'รหัสสั่งซื้อ',
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `quantity` int(11) NOT NULL COMMENT 'จำนวนสั่งซื้อ',
  `price` decimal(8,2) NOT NULL COMMENT 'ราคา',
  `remark1` int(8) NOT NULL,
  `remark2` int(8) NOT NULL,
  `remark3` text COLLATE utf8_unicode_ci NOT NULL,
  `remark4` text COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Triggers `order_details`
--
DELIMITER $$
CREATE TRIGGER `return_stock_after_del` AFTER DELETE ON `order_details` FOR EACH ROW UPDATE products SET quantity = quantity + old.quantity WHERE id = old.product_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL COMMENT 'รหัสชำระเงิน',
  `pay_money` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT 'จำนวนโอน',
  `pay_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `detail` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0.00' COMMENT 'รายละเอียด',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT 'รหัสใบสั่งซื้อ',
  `url_picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'ecimage.jpg',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL COMMENT 'รหัส',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อสินค้า',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT 'ราคาสินค้า',
  `agent_price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `sale_price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `ship_price` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_ship_date` date DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci COMMENT 'รายละเอียดสินค้า',
  `quantity` int(11) NOT NULL COMMENT 'จำนวนคงเหลือ',
  `weight` int(8) NOT NULL,
  `url_picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'ecimage.jpg' COMMENT 'รูปภาพหลักสินค้า',
  `parcel` decimal(8,2) DEFAULT NULL,
  `registered` decimal(8,2) DEFAULT NULL,
  `ems` decimal(8,2) DEFAULT NULL,
  `kerry` decimal(8,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT 'วันที่สร้าง',
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `remark1` int(8) NOT NULL,
  `remark2` int(8) NOT NULL,
  `remark3` text COLLATE utf8_unicode_ci NOT NULL,
  `remark4` text COLLATE utf8_unicode_ci NOT NULL,
  `flag_status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `๏ปฟLineID` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `User` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `submit_ip` varchar(255) DEFAULT NULL,
  `submit_time` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `problem` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `result` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` char(1) CHARACTER SET latin1 NOT NULL DEFAULT 'A',
  `user_id` int(11) NOT NULL COMMENT 'user แจ้งปัญหา'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL COMMENT 'รหัสสมาชิก',
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อเข้าใช้',
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'รหัสผ่าน',
  `address` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'ที่อยู่',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่สร้าง',
  `role` enum('user','admin') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user' COMMENT 'ประเภทสมาชิก'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_payments_orders` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัส', AUTO_INCREMENT=3636;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสชำระเงิน', AUTO_INCREMENT=2473;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัส', AUTO_INCREMENT=563;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสสมาชิก', AUTO_INCREMENT=72401;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
