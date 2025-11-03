-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 03, 2025 at 08:12 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

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
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(9, 'Repair of Hydraulics', 'Comprehensive repair and overhaul of hydraulic cylinders, pumps, motors, and valves. Hydrautechnik restores damaged or worn components to OEM standards using precision machining, testing, and calibration.', NULL, 'PUBLISHED', '2025-11-03 07:41:42', '2025-11-03 07:41:42'),
(10, 'Lubrication Components', 'Supply, installation, and maintenance of industrial lubrication systems and components — including pumps, filters, fittings, and distribution blocks to ensure optimal system efficiency and equipment longevity.', NULL, 'PUBLISHED', '2025-11-03 07:42:05', '2025-11-03 07:42:05'),
(11, 'Troubleshooting of Hydraulic Systems', 'On-site diagnostics and fault isolation for hydraulic equipment. Hydrautechnik’s technicians identify system leaks, pressure issues, contamination, or control failures using advanced testing instruments.', NULL, 'PUBLISHED', '2025-11-03 07:42:24', '2025-11-03 07:42:24'),
(12, 'Lubrication & Hydraulics', 'Integrated solutions for both lubrication and hydraulic systems — from design and setup to fluid conditioning, filtration, and preventive maintenance programs for industrial and mobile equipment.', NULL, 'PUBLISHED', '2025-11-03 07:42:43', '2025-11-03 07:42:43'),
(13, 'Design & Fabrication', 'Custom hydraulic and lubrication system design, including manifold fabrication, piping layout, and power unit assembly. Hydrautechnik provides tailored engineering solutions based on client specifications.', NULL, 'PUBLISHED', '2025-11-03 07:43:06', '2025-11-03 07:43:06'),
(14, 'Failure & Damage Analysis', 'Root cause analysis of hydraulic and lubrication component failures. Includes laboratory testing, contamination analysis, and technical reports to prevent recurrence and improve system reliability.', NULL, 'PUBLISHED', '2025-11-03 07:43:49', '2025-11-03 07:43:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
