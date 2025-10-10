-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 10, 2025 at 06:12 AM
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
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `parent_id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, NULL, 'HYDAC FILTRATION SYSTEM', 'HYDAC is a globally recognized company specializing in hydraulic systems, filtration, and fluid conditioning technologies. HYDAC filtration systems are widely used across industrial, mobile, and process applications to remove contaminants from hydraulic fluids, lubricating oils, fuels, and process fluids, ensuring reliability and efficiency in fluid power systems.', '2025-10-08 03:25:01', '2025-10-08 03:25:01', NULL),
(4, NULL, 'HYDAC HYDRAULIC ACCUMULATORS', 'HYDAC Technology GmbH has over 50 years\' experience in the research & development, design and production of hydraulic accumulators.\r\nThis includes all hydropneumatic accumulators, from bladder accumulators and piston accumulators to diaphragm accumulators and now also the metal bellows accumulators for further fields of application.\r\nThanks to a continuous expansion of the individual models, an optimised range of accumulators was developed over the years, supplemented by gas- and fluid-side accumulator safeguards such as temperature fuse plugs, burst discs, gas safety valves and additional accessories.', '2025-10-09 01:52:03', '2025-10-09 01:52:03', NULL),
(5, NULL, 'HYDAC VALVES AND ACCESSORIES', 'HYDAC can look back on decades of experience in valve technology. Over the years, the permanent expansion of the individual valve designs has resulted in an extremely extensive and complete product range that enables almost all possibilities for designing hydraulic systems - stationary or mobile. Whether directional spool valves, directional poppet valves, pressure valves, flow valves or check valves as screw-in valves, insert valves, stackable valves, subplate and in-line mounted valves - we have the right valve for your system.', '2025-10-09 01:52:41', '2025-10-09 01:52:41', NULL),
(6, NULL, 'HYDAC COOLING SYSTEMS', 'More safety through cool system solutions.\r\n\r\nThe heat arising from internal thermal loss in main spindles, drives, control cabinets, cooling lubricants and hydraulics can be channelled away very effectively by fluid cooling.\r\n\r\nUsing HYDAC cooling systems with air cooling, water cooling or compressor cooling allows all requirements to be fulfilled and a constant cooling temperature to be achieved.', '2025-10-09 01:53:12', '2025-10-09 01:53:12', NULL),
(7, NULL, 'MASKOTECH ENGINEER HYDRAULIC JACKS', 'MULTISTRAND Pre-stressing Jacks various types capacity - 200t, 300t, 420t, 520t, 680t and stroke 100 to 300mm\r\nWedge locking device - 705, 1205, 1206, 1905, 1905, 1906, 4206\r\nMONOSTRAND Jacks Capacity-  20t, 25t, 30t, and stroke 200, 300, 750 and 1000mm\r\nBar Jacks Capacity-  60t, 80t, 100t, 120t,150t, 200t, 300t, 400t, 500 t and stroke up to 300mm.Pile Testing Jacks - 300t, 400t, 500t, 600t, 800t, 1000t, 1500t and stroke up to 500mm', '2025-10-09 01:53:54', '2025-10-09 01:53:54', NULL),
(8, NULL, 'MASKO TECH CONSTRUCTION INFRASTRUCTURE', 'Jacks, Cylinders Stud Bolt Tentioners, Step Jacks, null splitters, presses, pumping untis, customized solution for fabrication, erection and maintenance.\r\n\r\nJacks, Power Packs, Stud Bolt Tensioning and Torque Wrenches for fabrications, erection and maintenance.', '2025-10-09 01:54:26', '2025-10-09 01:54:26', NULL),
(9, NULL, 'MASKO TECH HYDRAULIC TOOLS', 'HYDRAULIC PULLERS\r\nCapacity Max Working Pressure 700 bar \r\n\r\nFeature / Application \r\n\r\nTwo and three jaw design\r\nHigh Strength drop forged steel jaws\r\nJaws with adjustable reach and spread', '2025-10-09 01:55:23', '2025-10-09 01:55:23', NULL),
(10, NULL, 'MASKO TECH CUSTOMIZED PRODUCTS', 'Wheel Balancing, Tyre Changer, Car Lift Center Post or Two Post Car Lift \r\nTyre Changers:\r\n\r\nMax Wheel Diameter:  2300 mm - TR57\r\nMax Rim Width 1100 mm - TR 57\r\nOperating Pressure: 50-130 bars - TR57\r\nMax Wheel Diameter: 1500 mm - TR26\r\nMax Rim Width 760 mm - TR26\r\nOperating Pressure 50-130 bars - TR26\r\nCar Lifts:\r\n\r\nLifting Capacity : 4.0T - TPL400\r\nMax Lift Height: 1800mm  - TPL 400\r\nMin Lift Height: 120mm - TPL 400\r\nLifting Capacity: 4-16.0T -PCP40,80,160.', '2025-10-09 01:55:47', '2025-10-09 01:55:47', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_categories_name_unique` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
