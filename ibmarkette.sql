-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2026 at 03:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ibmarkette`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `mileage` int(11) NOT NULL,
  `fuel_type` varchar(50) NOT NULL,
  `transmission` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `description_fr` text DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `title_fr` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `brand`, `model`, `year`, `price`, `mileage`, `fuel_type`, `transmission`, `category`, `image`, `description_en`, `description_fr`, `title_en`, `title_fr`, `created_at`, `updated_at`) VALUES
(2, 'BMW', 'X5 M50i', 2021, 12750.00, 22000, 'Petrol', 'Automatic', 'SUV', 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=400&q=80', 'Powerful V8 engine with M performance and luxury interior.', 'Moteur V8 puissant avec performance M et intérieur luxueux.', 'X5 M50i', 'X5 M50i', '2026-04-04 10:52:38', '2026-04-04 10:52:38'),
(3, 'Audi', 'RS6 Avant', 2022, 17000.00, 9800, 'Petrol', 'Automatic', 'Sports', 'https://images.unsplash.com/photo-1606152421802-db97b9c7a11b?w=400&q=80', 'Supercar performance in a practical wagon body.', 'Performances de supercar dans un break pratique.', 'RS6 Avant', 'RS6 Avant', '2026-04-04 10:52:38', '2026-04-04 10:52:38'),
(4, 'suv', '2028', 2006, 65678.70, 2993, 'Petrol', 'Manual', 'SUV', '69d19a51a2a79.jpg', 'ferari', 'ferari', 'Ferari', 'Ferari', '2026-04-04 23:10:09', '2026-04-04 23:10:09');

-- --------------------------------------------------------

--
-- Table structure for table `featured_cars`
--

CREATE TABLE `featured_cars` (
  `id` int(11) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `title_fr` varchar(255) NOT NULL,
  `description_en` text DEFAULT NULL,
  `description_fr` text DEFAULT NULL,
  `image_url` varchar(500) NOT NULL,
  `price_cfa` bigint(20) NOT NULL,
  `year` int(11) NOT NULL,
  `mileage` int(11) NOT NULL,
  `hp` int(11) NOT NULL,
  `fuel_type` varchar(50) NOT NULL,
  `transmission` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_large_card` tinyint(1) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `featured_cars`
--

INSERT INTO `featured_cars` (`id`, `title_en`, `title_fr`, `description_en`, `description_fr`, `image_url`, `price_cfa`, `year`, `mileage`, `hp`, `fuel_type`, `transmission`, `category`, `sort_order`, `is_large_card`, `active`, `created_at`) VALUES
(1, 'M5 Competition', 'M5 Competition', 'The ultimate sports sedan. BMW M5 Competition with 625 HP.', 'La berline sport ultime. BMW M5 Competition de 625 ch.', 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=1200&q=85', 53700000, 2022, 12400, 625, 'Petrol', '8-Speed M Steptronic', 'Sports', 1, 1, 1, '2026-04-04 10:52:38'),
(2, 'GLE 450 AMG', 'GLE 450 AMG', 'Luxury SUV with AMG performance.', 'SUV de luxe avec performance AMG.', 'https://images.unsplash.com/photo-1617469767368-7e3c71e1e834?w=400&q=80', 43200000, 2021, 28000, 362, 'Petrol', '9G-Tronic', 'SUV', 2, 0, 1, '2026-04-04 10:52:38'),
(3, 'Cayenne Turbo S', 'Cayenne Turbo S', 'Supercar performance in an SUV.', 'Performances de supercar dans un SUV.', 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=400&q=80', 70800000, 2022, 8500, 550, 'Petrol', '8-Speed Tiptronic', 'SUV', 3, 0, 1, '2026-04-04 10:52:38'),
(4, 'Ferari', 'Ferari', 'ferari', 'ferari', '/uploads/featured/69d199fcb7a48.jpg', 200000, 1992, 200, 2000, 'Petrol', 'Manual', 'SUV', 1, 1, 1, '2026-04-04 23:08:44');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'site_title', 'IBMARKETTE'),
(2, 'logo_path', 'logo.jpg'),
(3, 'contact_phone', '+225 07 89 43 32 90'),
(4, 'contact_whatsapp', '2250789433290'),
(5, 'contact_address', '123 Auto Avenue, Abidjan, Côte d\'Ivoire'),
(6, 'contact_email', 'info@ibmarkette.com'),
(7, 'hero_title_en', 'DRIVE YOUR DREAM MACHINE'),
(8, 'hero_title_fr', 'CONDUISEZ VOTRE MACHINE DE RÊVE'),
(9, 'hero_subtitle_en', 'IBMARKETTE brings you the finest selection of premium pre-owned vehicles.'),
(10, 'hero_subtitle_fr', 'IBMARKETTE vous présente la meilleure sélection de véhicules d\'occasion premium.'),
(11, 'footer_text_en', 'Your premier destination for quality pre-owned vehicles.'),
(12, 'footer_text_fr', 'Votre destination privilégiée pour des véhicules d\'occasion de qualité.'),
(13, 'hero_slides', '[{\"image\":\"https:\\/\\/images.unsplash.com\\/photo-1492144534655-ae79c964c9d7?w=1800&q=80\",\"title_en\":\"DRIVE YOUR DREAM MACHINE\",\"title_fr\":\"CONDUISEZ VOTRE MACHINE DE R\\u00caVE\",\"subtitle_en\":\"IBMARKETTE brings you the finest selection of premium pre-owned vehicles.\",\"subtitle_fr\":\"IBMARKETTE vous pr\\u00e9sente la meilleure s\\u00e9lection de v\\u00e9hicules d\'occasion premium.\",\"btn_text_en\":\"Browse Inventory\",\"btn_text_fr\":\"Voir l\'Inventaire\",\"btn_link\":\"#inventory\"}]'),
(14, 'social_facebook', ''),
(15, 'social_instagram', ''),
(16, 'social_tiktok', ''),
(17, 'social_youtube', ''),
(18, 'social_twitter', ''),
(19, 'social_linkedin', ''),
(20, 'addresses', '[\"123 Auto Avenue, Abidjan, C\\u00f4te d\'Ivoire\"]'),
(21, 'phones', '[\"+225 07 89 43 32 90\",\"+225 05 55 66 77 88\"]'),
(22, 'whatsapp_number', '2250789433290');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$.weJKWsb.oKjSpb9CaijyufYKHbuiZXo/n/AcCl1cqXA5xNmlObSu', 'admin', '2026-04-04 10:52:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `featured_cars`
--
ALTER TABLE `featured_cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

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
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `featured_cars`
--
ALTER TABLE `featured_cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
