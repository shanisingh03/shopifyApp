-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 12, 2018 at 12:15 PM
-- Server version: 10.1.33-MariaDB
-- PHP Version: 7.1.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tech_social`
--

-- --------------------------------------------------------

--
-- Table structure for table `shopify_stores`
--

CREATE TABLE `shopify_stores` (
  `id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `shop` varchar(255) DEFAULT NULL,
  `hmac` varchar(255) DEFAULT NULL,
  `timestamp` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `charge_id` bigint(20) DEFAULT NULL,
  `store_status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shopify_stores`
--

INSERT INTO `shopify_stores` (`id`, `code`, `shop`, `hmac`, `timestamp`, `token`, `charge_id`, `store_status`, `created_at`, `updated_at`) VALUES
(9, '2d7e78d7ae13517015e2a4cc51c720c8', 'techtoolindia.myshopify.com', '8808376b17a6237a876a2751e9fa21c18751091082005f48c43822aa5a9d945b', '1531387720', 'ad00d7dc7d0334d15c80fba547fef301', NULL, 0, '2018-07-12 03:59:15', '2018-07-12 03:59:15');

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` int(11) NOT NULL,
  `shopify_domain` varchar(255) NOT NULL,
  `shop_id` int(255) DEFAULT NULL,
  `charge_id` bigint(20) DEFAULT NULL,
  `activated` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shops`
--

INSERT INTO `shops` (`id`, `shopify_domain`, `shop_id`, `charge_id`, `activated`, `created_at`, `updated_at`) VALUES
(9, 'techtoolindia.myshopify.com', 9, 3217915961, 1, '2018-07-12 04:26:54', '2018-07-12 04:26:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shopify_stores`
--
ALTER TABLE `shopify_stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shopify_stores`
--
ALTER TABLE `shopify_stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
