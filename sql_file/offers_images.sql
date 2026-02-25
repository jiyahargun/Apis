-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2026 at 07:03 AM
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
-- Table structure for table `offers_images`
--

CREATE TABLE `offers_images` (
  `id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offers_images`
--

INSERT INTO `offers_images` (`id`, `offer_id`, `image`, `created_at`) VALUES
(3, 1, 'http://localhost/jiya_project/uploads/1771492982_offers.jpg', '2026-02-19 09:23:02'),
(4, 1, 'https://localhost/API/uploads/1771913843_splash.jpg', '2026-02-24 06:17:23'),
(5, 1, 'https://localhost/jiya_project/uploads/1771913968_splash.jpg', '2026-02-24 06:19:28'),
(6, 1, 'https://localhost/jiya_project/uploads/1771914332_hotel12.jpg', '2026-02-24 06:25:32'),
(7, 1, 'https://localhost/jiya_project/offers/1771915261_hotel12.jpg', '2026-02-24 06:41:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `offers_images`
--
ALTER TABLE `offers_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_offers_images_offer` (`offer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `offers_images`
--
ALTER TABLE `offers_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `offers_images`
--
ALTER TABLE `offers_images`
  ADD CONSTRAINT `fk_offers_images_offer` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
