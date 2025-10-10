-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 10, 2025 at 06:13 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hydrautechnik`
--

-- --------------------------------------------------------

--
-- Table structure for table `product_subcategories`
--

CREATE TABLE `product_subcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_subcategories`
--

INSERT INTO `product_subcategories` (`id`, `name`, `category_id`, `description`, `image`, `created_at`, `updated_at`) VALUES
(2, 'HYDAC Fluid Conditioning Systems', 2, 'HYDAC is a globally recognized company specializing in hydraulic systems, filtration, and fluid conditioning technologies. HYDAC filtration systems are widely used across industrial, mobile, and process applications to remove contaminants from hydraulic fluids, lubricating oils, fuels, and process fluids, ensuring reliability and efficiency in fluid power systems.', 'storage/subcategories/1759894293_award2.jpg', '2025-10-08 03:31:33', '2025-10-08 03:31:33'),
(4, 'HYDAC Process Filter', 2, 'Many industrial companies require different types of process and service water for their production and manufacturing processes. These include, for example, water for cooling purposes, washing and flushing water, seal water for rotary seals, service water to prepare chemical solutions etc.', 'storage/subcategories/1759978814_PROCESS FILTER 13.jpg', '2025-10-09 03:00:14', '2025-10-09 03:00:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product_subcategories`
--
ALTER TABLE `product_subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_subcategories_category_id_foreign` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product_subcategories`
--
ALTER TABLE `product_subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_subcategories`
--
ALTER TABLE `product_subcategories`
  ADD CONSTRAINT `product_subcategories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
