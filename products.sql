-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 10, 2025 at 06:14 AM
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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `subcategory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `specification` text DEFAULT NULL,
  `ixu` text DEFAULT NULL,
  `olx` text DEFAULT NULL,
  `fam_atex` text DEFAULT NULL,
  `olsw` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `subcategory_id`, `category_id`, `description`, `image`, `specification`, `ixu`, `olx`, `fam_atex`, `olsw`, `created_at`, `updated_at`) VALUES
(2, 'HYDAC Dewatering and Other Fluid Conditioning', 2, 2, 'With the dewatering, degassing and other fluid servicing and care systems, HYDAC offers an extensive product range of professional solutions for oil servicing and care in bypass flow for hydraulic and lubrication media. The product programme from HYDAC encompasses both mobile and stationary servicing and care systems.', 'storage/products/1760072829_prd3.jpg', 'Dewatering through vacuum or coalescence procedures\r\nElimination of acids and oil ageing products\r\nDegassing and servicing and care of transformer oil\r\nDeoiling of water', 'The service-friendly ion exchange units of the IXU series are used for the preparation of phosphate-ester-based (HFR-R) flame-resistant hydraulic and lubrication fluids. They effectively remove the acidic decomposition products arising from hydrolysis and/or oxidation of the fluid. The units are deployed with flow rates of up to approximately 9 l/minute in bypass flow at the hydraulic and lubrication oil tanks up to approximately 20,000 litres in size. Mobile or stationary IXUs are available. HYDAC\'s own Ion eXchange Elements (IXE), filled with ion exchanger resins, are deployed in the IXU. The units of the IXU series are deployed in power plants, in the steel industry and for applications involving ester-based flame-resistant fluids. For the reduction of hydrolysis and the formation of acids in the fluid, we recommend continuous dewatering, e.g. by means of a FluidAqua Mobil – FAM.', 'HYDAC´s OXiStop is a tank solution with integrated, hydraulically driven degassing and dewatering unit. An integrated membrane prevents direct contact with the ambient air. This means that the tank can be designed for the differential operating volume actually needed, reducing its size. The pump flow rate is not important for the tank design. The fluid attains a very low gas and water content. Thanks to the membrane, which keeps the fluid \"vacuum packed\", it is also possible to install the OXiStop in extremely dusty or humid environments. HYDAC offers three standard sizes for a differential operating volume up to 70 liters. Additionally, customer-specific solutions can be realized. The OXiStop can be equipped with an optional return line filter, bypass flow filter, water / oil cooler as well as with the AquaSensor AS and the ContaminationSensor CS.', 'The dewatering units of the FluidAqua Mobil FAM ATEX series fulfil the requirements of the EU guideline 94/9/EC (ATEX = Atmosphères Explosibles - explosive atmosphere) for areas in which explosive gas atmospheres could arise (Zones 1 snd 2). The labelling of the explosion-protected FAMs in accordance with ATEX are, depending on the version  II2G IIB T4. or II2G IIB + H2 (T4) In addition to the protective electrical mechanisms, the mechanical components are also designed in accordance with the standard. Application in the chemical and petrochemical industry in which explosive gas atmospheres could arise and in all areas in which combustible substances such as gases or vapours occasionally arise.  Greater safety during operation in explosive gas atmosphere One device with three functions: - Dewatering - Filtration - Degassing', 'The OffLine Separator Water OLSW is used for separating free mineral oil from aqueous cleaning fluids in accordance with the coalescence principle. For this, the smallest oil droplets become embedded on the fibres of the OilRheo elements and run together to form larger oil droplets on the fibre nodes while rising upward on the outer side of the OilRheo elements. The separated oil is guided into an oil container from which the oil is then manually drained off. In order to ensure the optimum de-oiling performance of the OLSW, a prefilter is integrated for the separation of solid material contamination. The unit is conceived for installation in the bypass flow.  Cost-effective de-oiling of aqueous media Protection of the OilRheo elements through prefiltration Prolongation of the service life of the aqueous cleaning fluids used Compliance with environmental protection and disposal regulations', '2025-10-08 03:32:51', '2025-10-10 05:07:09'),
(4, 'HYDAC Bladder Accumulator', NULL, 4, 'Bladder Accumulator are hydropneumatic accumulators with a flexible bladder as a separation element between compressible gas cushion and operating fluid.\r\nHYDAC bladder accumulators consist of a welded or forged pressure vessel, the accumulator bladder and the fittings for the gas- and medium-side connection.\r\nIn addition to the standard design, special designs for particular applications are possible, for example for very high discharge speeds and high pressures.', 'storage/products/1760072817_prd1.jpg', 'Nominal volume: 0.5 ... 450 l,\r\nPermissible operating pressure: up to 1,000 bar,\r\nMaterials for the elastomer: NBR, ECO, IIR, FKM (FPM),\r\nAccumulator shell materials: carbon steel, stainless steel, aluminium, composite materials.', NULL, NULL, NULL, NULL, '2025-10-09 02:05:38', '2025-10-10 05:06:57'),
(6, 'HYDAC Flow Valves', NULL, 5, 'The flow valve programmed encompasses practically all of the functions for influencing the flow rate which are required in hydraulics. Flow valves influence the flow rate in a system by means of mechanical or hydraulic modification of throttle cross-sections. HYDAC offers both manually and hydraulically controlled valves in this area.', 'storage/products/1760072843_prd4.jpg', NULL, NULL, NULL, NULL, NULL, '2025-10-09 02:29:21', '2025-10-10 05:07:23'),
(7, 'HYDAC Refigerator Units', NULL, 6, 'The RFCS refrigerated fluid chiller system allows various fluids such as water, water glycol and oil to be cooled at or below ambient temperature. The chiller consists of refrigerator, pump, tank and controller and is able to set the temperature of the operating fluid to a previously configured target value independently.\r\n\r\nThe energy-efficient, patented mixer principle, combined with a sealless immersion pump, makes this system the ideal component for your machine tool.', 'storage/products/1760072857_prd6.jpg', 'Target temperature can be set at or below ambient temperature\r\nLeak-free immersion pump\r\nCompact dimensions\r\nUser-friendly controller interface\r\nCleanable air filter\r\nPlug & Play solution\r\nEasy to service and user-friendly', NULL, NULL, NULL, NULL, '2025-10-09 02:50:01', '2025-10-10 05:07:37'),
(8, 'HYDAC Fluid-Air Aooling Systems FLKS', NULL, 6, 'The FLKS is a compact fluid-air cooling system with a plastic tank, circulating pump, heat exchanger and fan for cooling circuits with water-glycol or mineral oil.', 'storage/products/1760072871_prd5.jpg', 'Cost-effective and efficient cooling system\r\nSizes FLKS-1, FLKS-2, FLKS-3, FLKS-4 and FLKS-5 with plastic tank housing with integrated fan\r\n Sizes FLKS-8 and FLKS-10 for especially high performance and high flow rates\r\nEnergy-efficient thanks to optimized and adjusted drives and the heat being released directly to the surroundings\r\nSpeed-controled systems:\r\nThe temperature of the operating fluid is controlled by adjusting the fan speed with a set difference to the ambient temperature.\r\nThe speed control is integrated as standard for sizes FLKS-8 and FLKS-10.Cost-effective and efficient cooling system\r\nSizes FLKS-1, FLKS-2, FLKS-3, FLKS-4 and FLKS-5 with plastic tank housing with integrated fan\r\n Sizes FLKS-8 and FLKS-10 for especially high performance and high flow rates\r\nEnergy-efficient thanks to optimized and adjusted drives and the heat being released directly to the surroundings\r\nSpeed-controled systems:\r\nThe temperature of the operating fluid is controlled by adjusting the fan speed with a set difference to the ambient temperature.\r\nThe speed control is integrated as standard for sizes FLKS-8 and FLKS-10.', NULL, NULL, NULL, NULL, '2025-10-09 02:51:42', '2025-10-10 05:07:51'),
(10, 'Masko Tech Flat Jacks', NULL, 7, NULL, 'storage/products/1760072882_prd1.jpg', 'Features / Application\r\nCompact and flat design ideally suited where clearance is minimum for insertion or jack\r\nMounting holes for easy fixturing \r\nHeavy equipment lifting, alignment and clamping.', NULL, NULL, NULL, NULL, '2025-10-09 02:54:44', '2025-10-10 05:08:02'),
(11, 'Masko Tech Bar Stressing Jacks  BAR STRESSING JACKS', NULL, 8, 'Masko Tech Bar Stressing Jacks\r\n\r\nBAR STRESSING JACKS', 'storage/products/1760072893_prd4.jpg', NULL, NULL, NULL, NULL, NULL, '2025-10-09 02:55:39', '2025-10-10 05:08:13'),
(12, 'Portable High Tonnage Jacks', NULL, 9, 'Capacity 30 - 200 ton / Stroke 200 - 680 mm Max Working Pressure 700 bar', 'storage/products/1760072905_1759978201_REFREGERATORS 2.jpg', 'Features / Application\r\nCompact & Portable, Steel Alloy Construction\r\nExtension pieces / Bridges provided for additional reach\r\nElectric / Pneumatic Motor (Optional)\r\nManual / Remote Pendant Control valve (Optional)', NULL, NULL, NULL, NULL, '2025-10-09 02:56:31', '2025-10-10 05:08:25'),
(13, 'Masko Tech Tyre Changers', NULL, 10, NULL, 'storage/products/1760072915_1759978648_tyre changer 2.jpg', 'Features: \r\nit is use to mount / dismount the drop centre rim / Tubeless wheel / wheel with ring of passenger car / truck / agricultural vehicle / industrial vehicle.\r\nthe tool arm can automatically move horizontally and vertically .\r\ntool can automatic rotate the spindle on the mounting head, use rolling bearing design, it can  be used for the tyre with huge resistance.\r\nthere is safe protection switch  on the lifting arm.', NULL, NULL, NULL, NULL, '2025-10-09 02:57:28', '2025-10-10 05:08:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_subcategory_id_foreign` (`subcategory_id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `product_subcategories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
