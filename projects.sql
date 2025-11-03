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
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PUBLISHED',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `image`, `category`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Hydraulic Cylinder Repair and Testing', 'Hydrautechnik performed a complete overhaul of two hydraulic cylinders used in the clinker conveyor system. Both cylinders exhibited leakage, pressure loss, and rod scoring due to contamination and seal wear.', 'storage/projects/3W77azYkNg8cZuMlJd3VgSmxGtElUbb5BoTMiClu.webp', 'OTHER', 'PUBLISHED', '2025-11-03 05:44:55', '2025-11-03 07:56:26'),
(2, 'Hydraulic Power Unit Overhauling and System Restoration', 'Hydrautechnik was commissioned to perform a full overhaul and restoration of a Hydraulic Power Unit (HPU) used in a turbine lubrication and control system. The unit was experiencing severe pressure fluctuations, oil contamination, and component wear that affected production reliability.', NULL, 'COMPLETED', 'PUBLISHED', '2025-11-03 06:51:58', '2025-11-03 07:55:43'),
(3, 'Lubrication System Installation', 'Design and installation of a centralized automatic lubrication system for the billet conveyor and rolling mill bearings to replace the previous manual greasing process.', NULL, 'COMPLETED', 'PUBLISHED', '2025-11-03 07:56:44', '2025-11-03 07:56:54'),
(4, 'Hydraulic System Troubleshooting', 'Overview:\r\nA vessel’s crane system was experiencing slow actuation and erratic motion. Hydrautechnik was contracted to perform diagnostics and repairs.', NULL, 'ONGOING', 'PUBLISHED', '2025-11-03 07:57:16', '2025-11-03 07:57:24'),
(5, 'Design and Fabrication of Hydraulic Power Unit (HPU)', 'Client: Aboitiz Power\r\nLocation: Davao City\r\nDuration: 5 Weeks\r\nCategory: Design & Fabrication\r\n\r\nOverview:\r\nFabrication of a custom-built Hydraulic Power Unit designed for turbine control applications.\r\n\r\nScope of Work:\r\n\r\nEngineering design and layout of the hydraulic circuit\r\n\r\nFabrication of 500-liter capacity oil tank and frame assembly\r\n\r\nInstallation of dual pressure pumps, valves, and filters\r\n\r\nIn-house performance testing and commissioning', NULL, 'ONGOING', 'PUBLISHED', '2025-11-03 07:57:48', '2025-11-03 07:58:22'),
(6, 'Failure and Damage Analysis', 'Hydrautechnik conducted an investigation into recurring hydraulic motor failures in the bottle molding line.\r\n\r\nScope of Work:\r\n\r\nCollected and analyzed failed components\r\n\r\nPerformed oil contamination analysis\r\n\r\nIdentified high silica particle presence as root cause\r\n\r\nRecommended filtration upgrades and improved maintenance intervals', NULL, 'OTHER', 'PUBLISHED', '2025-11-03 07:58:42', '2025-11-03 07:58:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
