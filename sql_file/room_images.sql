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
-- Table structure for table `room_images`
--

CREATE TABLE `room_images` (
  `id` int(11) NOT NULL,
  `room_category_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_images`
--

INSERT INTO `room_images` (`id`, `room_category_id`, `image`, `created_at`) VALUES
(1, 1, 'http://localhost/jiya_project/uploads/1771483538_offers.jpg', '2026-02-19 06:45:38'),
(6, 1, 'https://localhost/jiya_project/uploads/1771914242_9918_hotel12.jpg', '2026-02-24 06:24:02'),
(7, 1, 'https://localhost/jiya_project/rooms/1771915317_1700_hotel12.jpg', '2026-02-24 06:41:57'),
(8, 1, 'https://localhost/jiya_project/rooms/1771917969_hotel12.jpg', '2026-02-24 07:26:09'),
(9, 1, 'https://localhost/jiya_project/rooms/1771918254_hotel12.jpg', '2026-02-24 07:30:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `room_images`
--
ALTER TABLE `room_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_room_images_category` (`room_category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `room_images`
--
ALTER TABLE `room_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `room_images`
--
ALTER TABLE `room_images`
  ADD CONSTRAINT `fk_room_images_category` FOREIGN KEY (`room_category_id`) REFERENCES `room_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
