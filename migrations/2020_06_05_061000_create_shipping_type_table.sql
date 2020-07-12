-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Jun 30, 2020 at 01:59 AM
-- Server version: 8.0.20
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `playitnow`
--

-- --------------------------------------------------------

--
-- Table structure for table `shipping_type`
--

CREATE TABLE `shipping_type` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `is_ws` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `shipping_type`
--

INSERT INTO `shipping_type` (`id`, `name`, `is_ws`) VALUES
(1, 'พัสดุ', 1),
(2, 'ลงทะเบียน', 1),
(3, 'EMS', 1),
(4, 'KERRY', 0),
(5, 'FLASH', 0),
(6, 'JT', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shipping_type`
--
ALTER TABLE `shipping_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shipping_type`
--
ALTER TABLE `shipping_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
