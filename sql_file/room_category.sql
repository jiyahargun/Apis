-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2026 at 07:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `room_category`
--

CREATE TABLE `room_category` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `room_type` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_rooms` int(11) NOT NULL,
  `available_rooms` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `room_status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_category`
--

INSERT INTO `room_category` (`id`, `hotel_id`, `room_type`, `price`, `total_rooms`, `available_rooms`, `description`, `room_status`) VALUES
(1, 1, 'premium', 2500.00, 25, 21, 'the hotels_with_functions', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `room_category`
--
ALTER TABLE `room_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_room_category_hotel` (`hotel_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `room_category`
--
ALTER TABLE `room_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `room_category`
--
ALTER TABLE `room_category`
  ADD CONSTRAINT `fk_room_category_hotel` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
