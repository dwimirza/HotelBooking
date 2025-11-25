-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 08:33 AM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_user_count` ()   BEGIN
  SELECT COUNT(*) AS users FROM users;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `hotel_id` int(10) UNSIGNED NOT NULL,
  `booking_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `total_amount` decimal(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `hotel_id`, `booking_date`, `status`, `total_amount`) VALUES
(1, NULL, 2, '2025-10-18 21:44:27', 'pending', 1450000.00),
(2, NULL, 1, '2025-10-19 02:23:11', 'pending', 2350000.00),
(3, NULL, 1, '2025-10-19 02:35:57', 'confirmed', 12150000.00),
(4, 5, 1, '2025-10-19 14:05:07', 'confirmed', 2350000.00),
(5, 5, 1, '2025-10-19 14:47:54', 'confirmed', 4650000.00),
(6, 5, 1, '2025-10-19 14:53:15', 'confirmed', 4650000.00),
(7, NULL, 1, '2025-10-19 22:56:30', 'confirmed', 2350000.00),
(8, 6, 1, '2025-10-19 23:07:32', 'confirmed', 2350000.00),
(9, 5, 1, '2025-10-20 00:27:15', 'pending', 2350000.00),
(10, 5, 1, '2025-10-20 00:28:11', 'pending', 2350000.00),
(11, 5, 1, '2025-10-20 00:28:35', 'pending', 2350000.00),
(12, 11, 1, '2025-10-20 14:30:55', 'confirmed', 2350000.00),
(13, 11, 1, '2025-10-20 14:32:02', 'pending', 2350000.00),
(14, 11, 2, '2025-10-20 15:25:50', 'confirmed', 1450000.00),
(15, 11, 1, '2025-10-20 15:37:04', 'confirmed', 2350000.00);

-- --------------------------------------------------------

--
-- Table structure for table `booking_details`
--

CREATE TABLE `booking_details` (
  `detail_id` int(10) UNSIGNED NOT NULL,
  `booking_id` int(10) UNSIGNED NOT NULL,
  `room_id` int(10) UNSIGNED NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `special_request` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_details`
--

INSERT INTO `booking_details` (`detail_id`, `booking_id`, `room_id`, `price_per_night`, `check_in`, `check_out`, `special_request`) VALUES
(1, 1, 5, 1300000.00, '2025-10-18', '2025-10-19', ''),
(2, 2, 1, 2200000.00, '2025-10-19', '2025-10-20', ''),
(3, 3, 4, 12000000.00, '2025-10-19', '2025-10-20', ''),
(4, 4, 1, 2200000.00, '2025-10-19', '2025-10-20', ''),
(5, 5, 3, 4500000.00, '2025-10-19', '2025-10-20', ''),
(6, 6, 3, 4500000.00, '2025-10-19', '2025-10-20', ''),
(7, 7, 1, 2200000.00, '2025-10-19', '2025-10-20', ''),
(8, 8, 1, 2200000.00, '2025-10-19', '2025-10-20', ''),
(9, 9, 1, 2200000.00, '2025-10-20', '2025-10-21', ''),
(10, 10, 1, 2200000.00, '2025-10-20', '2025-10-21', ''),
(11, 11, 1, 2200000.00, '2025-10-20', '2025-10-21', ''),
(12, 12, 1, 2200000.00, '2025-10-20', '2025-10-21', ''),
(13, 13, 1, 2200000.00, '2025-10-20', '2025-10-21', ''),
(14, 14, 5, 1300000.00, '2025-10-20', '2025-10-21', ''),
(15, 15, 1, 2200000.00, '2025-10-20', '2025-10-21', '');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `hotel_id` int(10) UNSIGNED NOT NULL,
  `hotel_name` varchar(255) NOT NULL,
  `city` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone_no` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `star_rating` tinyint(3) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `available_room` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`hotel_id`, `hotel_name`, `city`, `address`, `phone_no`, `email`, `star_rating`, `created_at`, `updated_at`, `available_room`) VALUES
(1, 'Hotel Indonesia Kempinski Jakarta', 'Jakarta', 'Jl. MH Thamrin No.1, Central Jakarta', '+62 21 23583800', 'info@kempinski.com', 5, '2025-10-16 01:13:44', '2025-10-16 01:13:44', 30),
(2, 'Ascott Jakarta', 'Jakarta', 'Jl. Kebon Kacang Raya No.2, Central Jakarta', '+62 21 3927888', 'jakarta@ascott.com', 4, '2025-10-16 01:13:44', '2025-10-16 01:13:44', 25),
(3, 'Whiz Prime Hotel Kelapa Gading', 'Jakarta', 'Jl. Boulevard Barat Raya, North Jakarta', '+62 21 45853888', 'info@whizprime.com', 3, '2025-10-16 01:13:44', '2025-10-16 01:13:44', 40),
(4, 'The Legian Seminyak Bali', 'Bali', 'Jl. Kayu Aya, Seminyak Beach, Bali', '+62 361 730622', 'info@thelegianbali.com', 5, '2025-10-16 01:13:44', '2025-10-16 01:13:44', 20),
(5, 'Courtyard by Marriott Bali Seminyak Resort', 'Bali', 'Jl. Camplung Tanduk No.103SP, Seminyak', '+62 361 8499600', 'reservations@courtyardbali.com', 4, '2025-10-16 01:13:44', '2025-10-16 01:13:44', 35),
(6, 'Hotel Kuta Beach', 'Bali', 'Jl. Kubu Bene, Kuta, Bali', '+62 361 8465656', 'kuta@pophotels.com', 2, '2025-10-16 01:13:44', '2025-10-16 23:13:11', 50),
(7, 'Hotel Majapahit Surabaya', 'Surabaya', 'Jl. Tunjungan No.65, Surabaya', '+62 31 5454333', 'info@hotel-majapahit.com', 5, '2025-10-16 01:13:44', '2025-10-16 01:13:44', 28),
(8, 'Swiss-Belinn Tunjungan', 'Surabaya', 'Jl. Tunjungan No.101, Surabaya', '+62 31 99002999', 'info@swiss-belinn.com', 4, '2025-10-16 01:13:44', '2025-10-16 01:13:44', 33),
(9, 'Hotel Gubeng Surabaya', 'Surabaya', 'Jl. Bangka No.8-18, Surabaya', '+62 31 5018088', 'gubeng@pophotels.com', 2, '2025-10-16 01:13:44', '2025-10-17 13:31:58', 60),
(10, 'The Trans Luxury Hotel Bandung', 'Bandung', 'Jl. Gatot Subroto No.289, Bandung', '+62 22 87348888', 'info@thetranshotel.com', 5, '2025-10-16 01:13:44', '2025-10-16 01:13:44', 22),
(11, 'Hilton Bandung', 'Bandung', 'Jl. HOS Tjokroaminoto No.41-43, Bandung', '+62 22 86068888', 'info@hiltonbandung.com', 5, '2025-10-16 01:13:44', '2025-10-16 01:13:44', 27),
(12, 'Favehotel Braga', 'Bandung', 'Jl. Braga No.99-101, Bandung', '+62 22 84468888', 'braga@favehotels.com', 3, '2025-10-16 01:13:44', '2025-10-16 01:13:44', 45);

-- --------------------------------------------------------

--
-- Table structure for table `hotel_facilities`
--

CREATE TABLE `hotel_facilities` (
  `hotel_id` int(10) UNSIGNED NOT NULL,
  `swimming_pool` tinyint(1) DEFAULT 0,
  `gymnasium` tinyint(1) DEFAULT 0,
  `wifi` tinyint(1) DEFAULT 0,
  `room_service` tinyint(1) DEFAULT 0,
  `air_condition` tinyint(1) DEFAULT 0,
  `breakfast` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel_facilities`
--

INSERT INTO `hotel_facilities` (`hotel_id`, `swimming_pool`, `gymnasium`, `wifi`, `room_service`, `air_condition`, `breakfast`) VALUES
(1, 1, 1, 1, 1, 1, 1),
(2, 1, 1, 1, 1, 1, 1),
(3, 0, 0, 1, 1, 1, 1),
(4, 1, 1, 1, 1, 1, 1),
(5, 1, 0, 1, 1, 1, 1),
(6, 0, 0, 1, 1, 1, 0),
(7, 1, 1, 1, 1, 1, 1),
(8, 0, 1, 1, 1, 1, 1),
(9, 0, 0, 1, 1, 1, 0),
(10, 1, 1, 1, 1, 1, 1),
(11, 1, 1, 1, 1, 1, 1),
(12, 0, 0, 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(10) UNSIGNED NOT NULL,
  `booking_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(100) DEFAULT NULL,
  `status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `booking_id`, `amount`, `payment_date`, `payment_method`, `status`) VALUES
(1, 3, 12150000.00, '2025-10-19 02:42:44', 'credit_card', 'paid'),
(2, 4, 2350000.00, '2025-10-19 14:05:21', 'e_wallet', 'pending'),
(3, 5, 4650000.00, '2025-10-19 14:48:08', 'bank_transfer', 'paid'),
(4, 6, 4650000.00, '2025-10-19 14:53:41', 'bank_transfer', 'paid'),
(5, 7, 2350000.00, '2025-10-19 22:56:35', 'credit_card', 'paid'),
(6, 8, 2350000.00, '2025-10-19 23:07:37', 'credit_card', 'paid'),
(7, 12, 2350000.00, '2025-10-20 14:31:25', 'credit_card', 'paid'),
(8, 14, 1450000.00, '2025-10-20 15:26:06', 'credit_card', 'refunded'),
(9, 15, 2350000.00, '2025-10-20 15:37:06', 'credit_card', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(10) UNSIGNED NOT NULL,
  `hotel_id` int(10) UNSIGNED NOT NULL,
  `room_type` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `availability` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `hotel_id`, `room_type`, `price`, `availability`) VALUES
(1, 1, 'Deluxe', 2200000.00, 3),
(2, 1, 'Grand Deluxe', 2800000.00, 8),
(3, 1, 'Executive Suite', 4500000.00, 6),
(4, 1, 'Presidential Suite', 12000000.00, 2),
(5, 2, 'Studio', 1300000.00, 9),
(6, 2, 'One Bedroom', 1700000.00, 8),
(7, 2, 'Two Bedroom', 2400000.00, 5),
(8, 3, 'Standard', 450000.00, 18),
(9, 3, 'Superior', 550000.00, 12),
(10, 3, 'Family', 800000.00, 6),
(11, 4, 'Studio Suite', 5000000.00, 6),
(12, 4, 'One Bedroom Suite', 7200000.00, 8),
(13, 4, 'Two Bedroom Suite', 11000000.00, 4),
(14, 5, 'Deluxe', 1500000.00, 12),
(15, 5, 'Pool View', 1850000.00, 10),
(16, 5, 'Suite', 3000000.00, 6),
(17, 6, 'POP Room', 350000.00, 25),
(18, 6, 'POP Family', 500000.00, 10),
(19, 7, 'Heritage Deluxe', 1900000.00, 10),
(20, 7, 'Executive', 2600000.00, 8),
(21, 7, 'Majapahit Suite', 5200000.00, 4),
(22, 8, 'Superior', 800000.00, 14),
(23, 8, 'Deluxe', 950000.00, 12),
(24, 8, 'Executive', 1400000.00, 5),
(25, 9, 'POP Room', 300000.00, 30),
(26, 9, 'POP Corner', 380000.00, 12),
(27, 10, 'Premier', 2200000.00, 8),
(28, 10, 'Club', 3000000.00, 8),
(29, 10, 'Celebrity Suite', 9000000.00, 3),
(30, 11, 'Guest Room', 1800000.00, 10),
(31, 11, 'Executive Room', 2600000.00, 9),
(32, 11, 'King Suite', 6000000.00, 4),
(33, 12, 'Standard', 400000.00, 20),
(34, 12, 'Superior', 520000.00, 15);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password_hash`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Duffy', 'dgarden0@purevolume.com', '$2a$04$HXFxsTx3xtQ2L4/4oxI2oOdFcLnOJjIjqvrm0tDWj4PxPKUJeSEmC', 'user', '2025-10-13 14:49:52', '2025-10-13 14:49:52'),
(2, 'Benito', 'blittley1@moonfruit.com', '$2a$04$2aHwF592l0OIi2c.3AhJpuoM8CCpP1FURyDgVAPsU8V1vesrN2r1G', 'admin', '2025-10-13 14:49:52', '2025-10-13 14:49:52'),
(3, 'Luffy', 'luffyg@gmail.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin', '2025-10-14 14:33:54', '2025-10-14 14:33:54'),
(4, 'Jack', 'Jackjill@gmail.com', '12dea96fec20593566ab75692c9949596833adc9', 'user', '2025-10-14 16:57:08', '2025-10-14 16:57:08'),
(5, 'Fajar', 'test123@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'user', '2025-10-19 13:36:05', '2025-10-19 13:36:05'),
(6, 'admin', 'admin@hotel.com', 'f865b53623b121fd34ee5426c792e5c33af8c227', 'admin', '2025-10-19 15:08:16', '2025-10-19 15:08:16'),
(7, 'user', 'user@hotel.com', '95c946bf622ef93b0a211cd0fd028dfdfcf7e39e', 'user', '2025-10-20 00:12:11', '2025-10-20 00:12:11'),
(8, 'JayC', 'jayc@gmail.com', '$2y$10$LR4cOTsrUFMIWwbCvBf52ufcukfnK8n2yXruOUkTF5CSOaPIvcOLe', 'user', '2025-10-20 00:13:19', '2025-10-20 00:13:19'),
(9, 'hasryl', 'natawijaya@gmail.com', '$2y$10$RYTewK66v5ChrsOm0A2Jy.fQsDDlmWLn57h3EvnnOrSnmEl9lYbq.', 'user', '2025-10-20 12:49:28', '2025-10-20 12:49:28'),
(10, 'Nanda', 'nanda@gmail.com', '$2y$10$hlDnPx1Kc2LxEAqXBW6FuOegYyBkaTb5zb8tId6EEt6L/qJeVTweK', 'admin', '2025-10-20 13:36:53', '2025-10-20 13:41:26'),
(11, 'Nata', 'hasryl@gmail.com', '$2y$10$z66wfNFZUjhxc7S7OJ2TIuvzIylVGf8vKSWO3ll7VOA4kFjLLFNYO', 'user', '2025-10-20 13:40:34', '2025-10-20 13:40:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `idx_bookings_user` (`user_id`),
  ADD KEY `idx_bookings_hotel` (`hotel_id`);

--
-- Indexes for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `idx_details_booking` (`booking_id`),
  ADD KEY `idx_details_room` (`room_id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`hotel_id`);

--
-- Indexes for table `hotel_facilities`
--
ALTER TABLE `hotel_facilities`
  ADD PRIMARY KEY (`hotel_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `booking_id` (`booking_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `idx_rooms_hotel` (`hotel_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `booking_details`
--
ALTER TABLE `booking_details`
  MODIFY `detail_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `hotel_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_bookings_hotel` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bookings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD CONSTRAINT `fk_details_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_details_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON UPDATE CASCADE;

--
-- Constraints for table `hotel_facilities`
--
ALTER TABLE `hotel_facilities`
  ADD CONSTRAINT `hotel_facilities_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `fk_rooms_hotel` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
