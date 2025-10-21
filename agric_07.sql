-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2025 at 05:36 PM
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
-- Database: `agric`
--

-- --------------------------------------------------------

--
-- Table structure for table `accept_po`
--

CREATE TABLE `accept_po` (
  `accept_id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `balance_qty` int(11) NOT NULL,
  `supplied` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `accepted_by` int(11) NOT NULL,
  `accept_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accept_po`
--

INSERT INTO `accept_po` (`accept_id`, `purchase_id`, `item`, `balance_qty`, `supplied`, `store`, `accepted_by`, `accept_date`) VALUES
(1, 1, 18, 23, 3, 1, 1, '2025-10-15 11:25:10'),
(2, 1, 18, 20, 5, 1, 1, '2025-10-15 11:25:56'),
(3, 1, 18, 15, 15, 1, 1, '2025-10-15 11:26:51');

-- --------------------------------------------------------

--
-- Table structure for table `account_class`
--

CREATE TABLE `account_class` (
  `class_id` int(11) NOT NULL,
  `account_group` int(11) NOT NULL,
  `sub_group` int(11) NOT NULL,
  `class` varchar(1024) NOT NULL,
  `class_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_class`
--

INSERT INTO `account_class` (`class_id`, `account_group`, `sub_group`, `class`, `class_code`) VALUES
(1, 1, 1, 'Bank', 0),
(2, 1, 1, 'Cash', 0),
(3, 1, 1, 'Inventories', 0),
(4, 1, 1, 'Trade Receivables', 0),
(5, 1, 2, 'Properties, Plants And Equipment', 0),
(6, 1, 2, 'Accum Depre Properties, Plant And Equipment', 0),
(7, 2, 3, 'Account Payables', 0),
(8, 2, 3, 'Tax Payables', 0),
(9, 2, 3, 'Other Payables', 0),
(10, 2, 4, 'Loans', 0),
(11, 3, 5, 'Revenue', 0),
(12, 4, 6, 'Admin And General Expense', 0),
(13, 4, 6, 'Other Expense', 0),
(14, 4, 7, 'Cost Of Sales', 0),
(15, 4, 8, 'Depreciation', 0),
(16, 5, 9, 'Share Capital', 0),
(17, 5, 9, 'Retained Earnings', 0),
(18, 4, 10, 'Loss On Disposal Of Asset', 0),
(19, 3, 5, 'Other Revenue', 0);

-- --------------------------------------------------------

--
-- Table structure for table `account_groups`
--

CREATE TABLE `account_groups` (
  `account_id` int(11) NOT NULL,
  `account_group` varchar(50) NOT NULL,
  `account_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_groups`
--

INSERT INTO `account_groups` (`account_id`, `account_group`, `account_code`) VALUES
(1, 'Assets', 0),
(2, 'Liabilities', 0),
(3, 'Income', 0),
(4, 'Expenses', 0),
(5, 'Equity', 0);

-- --------------------------------------------------------

--
-- Table structure for table `account_sub_groups`
--

CREATE TABLE `account_sub_groups` (
  `sub_group_id` int(11) NOT NULL,
  `account_group` int(11) NOT NULL,
  `sub_group` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_sub_groups`
--

INSERT INTO `account_sub_groups` (`sub_group_id`, `account_group`, `sub_group`) VALUES
(1, 1, 'Current Asset'),
(2, 1, 'Fixed Assets'),
(3, 2, 'Current Liabilities'),
(4, 2, 'Non Current Liabilities'),
(5, 3, 'Income'),
(6, 4, 'Operating Expenses'),
(7, 4, 'Cost Of Sales'),
(8, 4, 'Depreciation'),
(9, 5, 'Share Capital'),
(10, 4, 'Disposal Of Asset');

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `asset_id` int(11) NOT NULL,
  `asset` varchar(255) NOT NULL,
  `asset_no` varchar(50) NOT NULL,
  `location` int(11) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `cost` decimal(12,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `accum_dep` decimal(12,2) NOT NULL,
  `useful_life` float NOT NULL,
  `salvage_value` decimal(12,2) NOT NULL,
  `book_value` decimal(12,2) NOT NULL,
  `ledger` int(50) NOT NULL,
  `specification` text NOT NULL,
  `asset_status` int(11) NOT NULL,
  `purchase_date` date DEFAULT NULL,
  `deployment_date` date DEFAULT NULL,
  `store` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_locations`
--

CREATE TABLE `asset_locations` (
  `location_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asset_locations`
--

INSERT INTO `asset_locations` (`location_id`, `location`) VALUES
(1, 'SECURITY POST'),
(2, 'FRONT DESK'),
(3, 'ICT'),
(4, 'PROCUREMENT'),
(5, 'HEAD OFFICE');

-- --------------------------------------------------------

--
-- Table structure for table `asset_postings`
--

CREATE TABLE `asset_postings` (
  `asset_id` int(11) NOT NULL,
  `asset` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `asset_ledger` int(11) NOT NULL,
  `contra_ledger` int(11) NOT NULL,
  `details` varchar(1024) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `trans_date` date DEFAULT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assigned_fields`
--

CREATE TABLE `assigned_fields` (
  `assigned_id` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `assigned_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assigned_fields`
--

INSERT INTO `assigned_fields` (`assigned_id`, `field`, `customer`, `assigned_by`, `assigned_date`) VALUES
(2, 1, 1, 1, '2025-10-03 07:39:01');

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `audit_id` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `transaction` varchar(255) NOT NULL,
  `previous_qty` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_trail`
--

INSERT INTO `audit_trail` (`audit_id`, `store`, `item`, `transaction`, `previous_qty`, `quantity`, `posted_by`, `post_date`) VALUES
(1, 1, 18, 'purchase', 0, 30, 1, '2025-09-18 17:41:09'),
(2, 1, 18, 'task', 30, 1, 1, '2025-09-18 17:56:44'),
(3, 1, 15, 'harvest', 0, 20, 1, '2025-09-18 18:46:55'),
(4, 1, 15, 'sales', 20, 3, 1, '2025-09-18 21:37:26'),
(5, 1, 15, 'sales', 17, 1, 1, '2025-09-19 10:26:23'),
(6, 1, 18, 'task', 29, 2, 1, '2025-09-19 10:41:25'),
(7, 1, 16, 'harvest', 0, 10, 1, '2025-09-19 10:44:23'),
(8, 1, 18, 'purchase', 27, 400, 1, '2025-09-19 13:16:56'),
(9, 1, 18, 'task', 427, 10, 1, '2025-10-04 13:45:12'),
(10, 1, 18, 'task', 417, 7, 1, '2025-10-04 13:49:29'),
(11, 1, 18, 'task', 410, 3, 1, '2025-10-04 13:53:40'),
(12, 1, 18, 'task', 407, 3, 1, '2025-10-05 10:17:04'),
(13, 1, 18, 'task', 404, 3, 1, '2025-10-05 10:55:06'),
(14, 1, 18, 'task return', 401, 3, 1, '2025-10-05 10:55:14'),
(15, 1, 18, 'task', 404, 1, 1, '2025-10-05 10:55:32'),
(16, 1, 18, 'task return', 403, 1, 1, '2025-10-05 10:55:43'),
(17, 1, 18, 'task', 404, 3, 1, '2025-10-05 11:33:02'),
(18, 1, 18, 'task', 401, 2, 1, '2025-10-05 12:25:50'),
(19, 1, 18, 'task return', 399, 2, 1, '2025-10-05 12:38:40'),
(20, 1, 18, 'task', 401, 3, 1, '2025-10-05 14:19:30'),
(21, 1, 15, 'task', 16, 2, 1, '2025-10-06 14:45:00'),
(22, 1, 18, 'task', 398, 3, 1, '2025-10-06 14:45:14'),
(23, 1, 15, 'harvest', 14, 200, 1, '2025-10-06 14:47:00'),
(24, 1, 15, 'harvest', 214, 1, 1, '2025-10-06 15:15:32'),
(25, 1, 15, 'harvest', 215, 2, 1, '2025-10-06 17:35:15'),
(26, 1, 18, 'task', 395, 3, 1, '2025-10-06 18:33:04'),
(27, 1, 18, 'purchase', 392, 30, 1, '2025-10-08 09:00:18'),
(28, 1, 15, 'purchase', 217, 10, 1, '2025-10-08 09:01:30'),
(29, 1, 15, 'purchase delete', 227, 10, 1, '2025-10-08 09:01:39'),
(30, 1, 18, 'purchase delete', 422, 30, 1, '2025-10-08 09:01:54'),
(31, 1, 18, 'purchase', 392, 8, 1, '2025-10-08 09:02:03'),
(32, 1, 16, 'purchase', 10, 30, 1, '2025-10-08 09:33:40'),
(33, 1, 18, 'purchase', 400, 2, 1, '2025-10-08 09:33:57'),
(34, 1, 18, 'purchase', 402, 25, 1, '2025-10-08 16:21:32'),
(35, 1, 18, 'purchase', 427, 4, 1, '2025-10-08 16:25:54'),
(36, 1, 15, 'purchase', 217, 10, 1, '2025-10-08 16:26:12'),
(37, 1, 18, 'purchase', 431, 12, 1, '2025-10-08 16:29:32'),
(38, 1, 18, 'purchase', 443, 3, 1, '2025-10-15 11:25:10'),
(39, 1, 18, 'purchase', 3, 5, 1, '2025-10-15 11:25:56'),
(40, 1, 18, 'purchase', 8, 15, 1, '2025-10-15 11:26:51'),
(41, 1, 18, 'production', 23, 10, 1, '2025-10-17 07:00:19'),
(42, 1, 18, 'production', 23, 2, 1, '2025-10-17 13:14:16'),
(43, 1, 18, 'production', 23, 5, 1, '2025-10-17 13:14:34'),
(44, 1, 18, 'production delete', 18, 5, 1, '2025-10-18 06:46:02'),
(45, 1, 18, 'production', 23, 3, 1, '2025-10-18 06:46:16'),
(46, 1, 18, 'production delete', 20, 3, 1, '2025-10-18 14:00:56'),
(47, 1, 18, 'production', 23, 4, 1, '2025-10-19 10:12:03'),
(48, 1, 15, 'production', 227, 10, 1, '2025-10-19 10:12:08'),
(49, 1, 18, 'production', 19, 2, 1, '2025-10-19 10:13:57'),
(50, 1, 15, 'production', 237, 5, 1, '2025-10-19 10:14:00'),
(51, 1, 18, 'production', 17, 1, 1, '2025-10-19 10:15:41'),
(52, 1, 15, 'production', 242, 3, 1, '2025-10-19 10:15:44'),
(53, 1, 18, 'sales', 16, 1, 1, '2025-10-19 11:26:01'),
(54, 1, 15, 'sales', 245, 2, 1, '2025-10-19 11:26:01'),
(55, 1, 18, 'sales', 15, 1, 1, '2025-10-19 14:56:03'),
(56, 1, 15, 'sales', 243, 1, 1, '2025-10-19 14:56:03'),
(57, 1, 15, 'sales', 242, 3, 1, '2025-10-19 14:56:39'),
(58, 1, 15, 'sales', 239, 5, 1, '2025-10-19 14:58:41'),
(59, 1, 18, 'sales', 14, 1, 1, '2025-10-20 07:11:17'),
(60, 1, 18, 'sales', 13, 3, 1, '2025-10-20 07:58:51'),
(61, 1, 15, 'sales', 234, 10, 1, '2025-10-20 08:05:41'),
(62, 1, 18, 'sales', 10, 1, 1, '2025-10-20 08:05:41');

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `bank_id` int(11) NOT NULL,
  `bank` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_flows`
--

CREATE TABLE `cash_flows` (
  `fow_id` int(11) NOT NULL,
  `account` int(20) NOT NULL,
  `destination` int(20) NOT NULL,
  `details` varchar(1024) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `trans_type` varchar(50) NOT NULL,
  `activity` varchar(50) NOT NULL,
  `store` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_flows`
--

INSERT INTO `cash_flows` (`fow_id`, `account`, `destination`, `details`, `trx_number`, `amount`, `trans_type`, `activity`, `store`, `post_date`, `posted_by`) VALUES
(16, 1010228, 1010483, 'Net Income', 'TR048201025075851', 10500.00, 'inflow', 'operating', 1, '2025-10-20 07:58:51', 1),
(17, 1010228, 0, 'Net Income', 'TR311201025091421', 20000.00, 'inflow', 'operating', 0, '2025-10-20 09:14:21', 1),
(18, 1010228, 0, 'Net Income', 'TR621201025094251', 30000.00, 'inflow', 'operating', 0, '2025-10-20 09:42:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `department` varchar(255) NOT NULL,
  `category` varchar(1024) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `department`, `category`, `price`) VALUES
(32, '13', 'CONSUMABLE', 0),
(33, '11', 'SEEDLINGS', 0),
(34, '11', 'FERTILIZERS', 0),
(35, '11', 'FEEDS', 0),
(36, '12', 'LIVESTOCK', 0),
(37, '12', 'CROPS', 0),
(38, '1', 'FERTILIZER', 0),
(39, '1', 'SEEDLINGS', 0);

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `company_id` int(11) NOT NULL,
  `company` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `amount` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`company_id`, `company`, `logo`, `amount`, `date_created`) VALUES
(1, 'Demo Farms', 'icon.png', 200000, '2025-08-28 14:46:39');

-- --------------------------------------------------------

--
-- Table structure for table `cost_of_sales`
--

CREATE TABLE `cost_of_sales` (
  `cost_of_sales_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `details` varchar(1024) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `store` int(11) NOT NULL,
  `trans_date` date DEFAULT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cost_of_sales`
--

INSERT INTO `cost_of_sales` (`cost_of_sales_id`, `amount`, `details`, `trx_number`, `store`, `trans_date`, `post_date`, `posted_by`) VALUES
(8, 9000.00, 'cost of sales', 'TR048201025075851', 1, '2025-10-20', '2025-10-20 07:58:51', 1),
(9, 13000.00, 'cost of sales', 'TR084201025080541', 1, '2025-10-20', '2025-10-20 08:05:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `crop_cycles`
--

CREATE TABLE `crop_cycles` (
  `cycle_id` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `farm` int(11) NOT NULL,
  `crop` int(11) NOT NULL,
  `variety` varchar(255) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `expected_harvest` datetime DEFAULT NULL,
  `expected_yield` float NOT NULL,
  `area_used` decimal(10,2) NOT NULL,
  `cycle_status` int(11) NOT NULL,
  `notes` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `ended_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crop_cycles`
--

INSERT INTO `crop_cycles` (`cycle_id`, `field`, `farm`, `crop`, `variety`, `start_date`, `expected_harvest`, `expected_yield`, `area_used`, `cycle_status`, `notes`, `created_at`, `created_by`, `end_date`, `ended_by`) VALUES
(2, 1, 1, 15, 'yoruba maize', '2025-09-18 00:00:00', '2025-12-18 00:00:00', 0, 24.00, -1, '', '2025-09-18 12:35:31', 1, '2025-10-03 10:52:52', 1),
(3, 2, 1, 16, 'foreign rice', '2025-09-18 00:00:00', '2025-09-26 00:00:00', 0, 25.00, 1, '', '2025-09-18 12:39:28', 1, '2025-10-04 10:16:20', 1),
(4, 3, 1, 16, 'foreign rice', '2025-09-19 00:00:00', '2026-05-30 00:00:00', 35, 10.00, 0, 'Nil', '2025-09-19 10:38:04', 1, NULL, 0),
(5, 2, 1, 16, 'foreign rice', '2025-10-06 00:00:00', '2025-10-30 00:00:00', 60, 10.00, 0, 'Started', '2025-10-06 14:40:08', 1, NULL, 0),
(6, 2, 1, 15, 'yoruba maize', '2025-10-07 00:00:00', '2025-11-06 00:00:00', 200, 15.00, 1, 'Platned', '2025-10-06 14:41:23', 1, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `customer` varchar(50) NOT NULL,
  `ledger_id` int(11) NOT NULL,
  `acn` int(50) NOT NULL,
  `customer_type` varchar(20) NOT NULL,
  `phone_numbers` varchar(20) NOT NULL,
  `customer_address` varchar(100) NOT NULL,
  `customer_email` varchar(50) NOT NULL,
  `wallet_balance` int(11) NOT NULL,
  `amount_due` int(11) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `customer`, `ledger_id`, `acn`, `customer_type`, `phone_numbers`, `customer_address`, `customer_email`, `wallet_balance`, `amount_due`, `reg_date`) VALUES
(1, 'TEST CUSTOMER', 83, 1010483, '', '08100653788', 'Benin', '', 0, 0, '2025-09-18 21:36:17');

-- --------------------------------------------------------

--
-- Table structure for table `customer_trail`
--

CREATE TABLE `customer_trail` (
  `id` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `store` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_trail`
--

INSERT INTO `customer_trail` (`id`, `customer`, `invoice`, `description`, `amount`, `trx_number`, `store`, `posted_by`, `post_date`) VALUES
(2, 1, 'TO119102502571521', 'Credit Sales', 17500.00, 'TR217191025025841', 1, 1, '2025-10-19 14:58:41'),
(3, 1, 'TO120102507101664', 'Credit Sales', 2000.00, 'TR876201025071117', 1, 1, '2025-10-20 07:11:17'),
(4, 1, '', 'Deposit', 10000.00, '', 1, 1, '2025-10-20 07:46:25'),
(5, 1, 'TO120102508051540', 'Credit Sales', 50000.00, 'TR084201025080541', 1, 1, '2025-10-20 08:05:41'),
(6, 1, '', 'Deposit', 20000.00, '', 1, 1, '2025-10-20 08:14:21'),
(7, 1, '', 'Deposit', 30000.00, '', 1, 1, '2025-10-20 08:42:51');

-- --------------------------------------------------------

--
-- Table structure for table `cycle_changes`
--

CREATE TABLE `cycle_changes` (
  `change_id` int(11) NOT NULL,
  `cycle` int(11) NOT NULL,
  `old_field` int(11) NOT NULL,
  `new_field` int(11) NOT NULL,
  `old_crop` int(11) NOT NULL,
  `new_crop` int(11) NOT NULL,
  `old_variety` varchar(255) NOT NULL,
  `new_variety` varchar(255) NOT NULL,
  `old_area` float NOT NULL,
  `new_area` float NOT NULL,
  `old_start` date NOT NULL,
  `new_start` date NOT NULL,
  `old_harvest` date NOT NULL,
  `new_harvest` date NOT NULL,
  `old_yield` float NOT NULL,
  `new_yield` float NOT NULL,
  `old_notes` text NOT NULL,
  `new_notes` text NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cycle_changes`
--

INSERT INTO `cycle_changes` (`change_id`, `cycle`, `old_field`, `new_field`, `old_crop`, `new_crop`, `old_variety`, `new_variety`, `old_area`, `new_area`, `old_start`, `new_start`, `old_harvest`, `new_harvest`, `old_yield`, `new_yield`, `old_notes`, `new_notes`, `updated_by`, `updated_at`) VALUES
(1, 4, 3, 3, 16, 16, 'foreign rice', 'foreign rice', 10, 10, '2025-09-19', '2025-09-19', '2026-05-30', '2026-05-30', 50, 35, 'Nil', 'Nil', 1, '2025-10-06 17:30:25'),
(2, 5, 1, 2, 16, 16, 'foreign rice', 'foreign rice', 10, 10, '2025-10-06', '2025-10-06', '2025-10-30', '2025-10-30', 0, 60, 'Started', 'Started', 1, '2025-10-06 17:31:13'),
(3, 5, 2, 1, 16, 16, 'foreign rice', 'foreign rice', 10, 10, '2025-10-06', '2025-10-06', '2025-10-30', '2025-10-30', 60, 60, 'Started', 'Started', 1, '2025-10-06 17:36:14'),
(4, 5, 1, 2, 16, 16, 'foreign rice', 'foreign rice', 10, 10, '2025-10-06', '2025-10-06', '2025-10-30', '2025-10-30', 60, 60, 'Started', 'Started', 1, '2025-10-06 17:36:57');

-- --------------------------------------------------------

--
-- Table structure for table `debtors`
--

CREATE TABLE `debtors` (
  `debtor_id` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `store` int(11) NOT NULL,
  `debt_status` int(11) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `debtors`
--

INSERT INTO `debtors` (`debtor_id`, `customer`, `invoice`, `amount`, `store`, `debt_status`, `trx_number`, `posted_by`, `post_date`) VALUES
(4, 1, 'TO120102508051540', 50000.00, 1, 1, 'TR084201025080541', 1, '2025-10-20 08:05:41');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department`) VALUES
(1, 'Farm inputs'),
(12, 'Products'),
(13, 'Consumables');

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `deposit_id` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_mode` varchar(50) NOT NULL,
  `bank` int(11) NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `details` varchar(255) NOT NULL,
  `trans_date` date DEFAULT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deposits`
--

INSERT INTO `deposits` (`deposit_id`, `store`, `customer`, `amount`, `payment_mode`, `bank`, `invoice`, `trx_number`, `details`, `trans_date`, `post_date`, `posted_by`) VALUES
(2, 1, 1, 20000.00, 'Cash', 0, 'DEP20102508131031011', 'TR311201025091421', 'For Invoice', '2025-10-20', '2025-10-20 09:14:21', 1),
(3, 1, 1, 30000.00, 'Cash', 0, 'DEP20102508421011201', 'TR621201025094251', 'Jknjnm,n', '2025-10-20', '2025-10-20 09:42:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `depreciation`
--

CREATE TABLE `depreciation` (
  `depreciation_id` int(11) NOT NULL,
  `asset` int(11) NOT NULL,
  `cost` decimal(12,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `useful_life` float NOT NULL,
  `salvage_value` decimal(12,2) NOT NULL,
  `dr_ledger` int(11) NOT NULL,
  `contra_ledger` int(11) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `accum_dep` decimal(12,2) NOT NULL,
  `book_value` decimal(12,2) NOT NULL,
  `details` varchar(1024) NOT NULL,
  `trx_date` date NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `designation_id` int(11) NOT NULL,
  `designation` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`designation_id`, `designation`) VALUES
(15, 'CASHIER'),
(16, 'HEAD ICT'),
(17, 'GENERAL MANAGER'),
(18, 'ACCOUNT OFFICER'),
(19, 'LABOURER');

-- --------------------------------------------------------

--
-- Table structure for table `director_posting`
--

CREATE TABLE `director_posting` (
  `director_id` int(11) NOT NULL,
  `financier` int(11) NOT NULL,
  `contra_ledger` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `trans_type` varchar(50) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `details` varchar(1024) NOT NULL,
  `store` int(11) NOT NULL,
  `trans_date` date DEFAULT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disciplines`
--

CREATE TABLE `disciplines` (
  `discipline_id` int(11) NOT NULL,
  `discipline` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `disciplines`
--

INSERT INTO `disciplines` (`discipline_id`, `discipline`) VALUES
(11, 'ACCOUNTANT'),
(12, 'HR GENERALIST'),
(13, 'SCIENTIST'),
(14, 'ICT ENGINEER');

-- --------------------------------------------------------

--
-- Table structure for table `disposed_assets`
--

CREATE TABLE `disposed_assets` (
  `disposed_id` int(11) NOT NULL,
  `asset` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `accum_dep` decimal(12,2) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `reason` varchar(1024) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `disposed_date` datetime DEFAULT NULL,
  `disposed_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `expense_head` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `contra` int(11) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `details` text NOT NULL,
  `expense_date` datetime NOT NULL,
  `post_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_heads`
--

CREATE TABLE `expense_heads` (
  `exp_head_id` int(11) NOT NULL,
  `expense_head` varchar(255) NOT NULL,
  `ledger_id` int(11) NOT NULL,
  `acn` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_heads`
--

INSERT INTO `expense_heads` (`exp_head_id`, `expense_head`, `ledger_id`, `acn`) VALUES
(4, 'UTILITY BILLS', 37, 40601237),
(5, 'TRANSPORTATION', 38, 40601238),
(6, 'SALARIES AND WAGES', 39, 40601239),
(7, 'DEPRECIATION', 40, 40801540),
(8, 'AMORTIZATION - SOFTWARE COST', 41, 40801541),
(9, 'LOSS ON DISPOSAL OF ASSET', 60, 401001860),
(10, 'COST OF SALES', 63, 40701463),
(11, 'COMPANY INCOME TAX', 67, 40601267),
(12, 'LOAN FEES', 68, 40601368),
(13, 'BANK CHARGES', 69, 40601369),
(14, 'LOSS DUE TO DAMAGE', 81, 40601381),
(15, 'FARM INPUTS', 84, 40601384),
(16, 'FARM LABOUR', 85, 40601385),
(17, 'COST OF LABOUR', 86, 40601286),
(18, 'DELIVERY &AMP; LOGISTICS EXPENSE', 88, 40601388),
(19, 'PRODUCTION INPUT', 89, 40601389);

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

CREATE TABLE `fields` (
  `field_id` int(11) NOT NULL,
  `farm` int(11) NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `customer` int(11) NOT NULL,
  `field_size` decimal(10,2) NOT NULL,
  `soil_type` varchar(255) NOT NULL,
  `soil_ph` decimal(10,2) NOT NULL,
  `topography` varchar(1024) NOT NULL,
  `field_status` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fields`
--

INSERT INTO `fields` (`field_id`, `farm`, `field_name`, `customer`, `field_size`, `soil_type`, `soil_ph`, `topography`, `field_status`, `created_at`, `created_by`) VALUES
(1, 1, 'OKUN-AJAH RICE FIELD', 1, 50.00, 'Sandy Clay', 6.00, 'Gentle Slope, Non-irrigated', 0, '2025-09-18 10:45:38', 1),
(2, 1, 'LOWLAND BLOCK C', 0, 65.00, 'Alluvial soil', 7.20, 'elevated, well-drained', 1, '2025-09-18 10:49:31', 1),
(3, 1, 'RICE FIELD', 0, 25.00, 'sandy loamy', 7.10, 'irrigated dry land', 1, '2025-09-19 10:31:58', 1);

-- --------------------------------------------------------

--
-- Table structure for table `finance_cost`
--

CREATE TABLE `finance_cost` (
  `finance_id` int(11) NOT NULL,
  `financier` int(11) NOT NULL,
  `contra_ledger` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `trans_type` varchar(50) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `details` varchar(1024) NOT NULL,
  `store` int(11) NOT NULL,
  `trans_date` date DEFAULT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `harvests`
--

CREATE TABLE `harvests` (
  `harvest_id` int(11) NOT NULL,
  `cycle` int(11) NOT NULL,
  `crop` int(11) NOT NULL,
  `farm` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `harvests`
--

INSERT INTO `harvests` (`harvest_id`, `cycle`, `crop`, `farm`, `field`, `quantity`, `unit_cost`, `posted_by`, `post_date`) VALUES
(3, 6, 15, 1, 2, 200, 0.00, 1, '2025-10-06 14:47:00'),
(4, 6, 15, 1, 2, 1, 191.54, 1, '2025-10-06 15:15:32'),
(5, 6, 15, 1, 2, 2, 189.66, 1, '2025-10-06 17:35:15');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventory_id` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `item_type` varchar(50) DEFAULT NULL,
  `store` int(11) NOT NULL,
  `cost_price` float NOT NULL,
  `quantity` float NOT NULL,
  `batch_number` int(11) NOT NULL,
  `expiration_date` date DEFAULT NULL,
  `reorder_level` float NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `item`, `item_type`, `store`, `cost_price`, `quantity`, `batch_number`, `expiration_date`, `reorder_level`, `post_date`) VALUES
(1, 18, 'Farm Input', 1, 3000, 9, 0, NULL, 5, NULL),
(2, 15, 'Crop', 1, 1000, 224, 0, NULL, 10, NULL),
(3, 16, 'Crop', 1, 2000, 40, 0, NULL, 10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `issue_items`
--

CREATE TABLE `issue_items` (
  `issue_id` int(11) NOT NULL,
  `from_store` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `issue_status` int(11) NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `cost_price` int(11) DEFAULT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `department` varchar(255) NOT NULL,
  `category` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_type` varchar(50) DEFAULT NULL,
  `cost_price` float NOT NULL,
  `sales_price` float NOT NULL,
  `pack_size` float NOT NULL,
  `pack_price` float NOT NULL,
  `wholesale` float NOT NULL,
  `wholesale_pack` float NOT NULL,
  `reorder_level` float NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `item_status` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `department`, `category`, `item_name`, `item_type`, `cost_price`, `sales_price`, `pack_size`, `pack_price`, `wholesale`, `wholesale_pack`, `reorder_level`, `barcode`, `item_status`, `date_created`) VALUES
(15, '12', 37, 'MAIZE', 'Crop', 1000, 5000, 0, 0, 0, 0, 10, '', 0, '2025-09-18 12:23:46'),
(16, '12', 37, 'RICE', 'Crop', 2000, 120000, 0, 0, 0, 0, 10, '', 0, '2025-09-18 12:23:59'),
(17, '12', 36, 'COW', 'Livestock', 0, 0, 0, 0, 0, 0, 10, '', 0, '2025-09-18 12:24:10'),
(18, '1', 38, 'NPK FERTILIZER', 'Farm Input', 3000, 5000, 0, 0, 0, 0, 5, '', 0, '2025-09-18 15:04:22');

-- --------------------------------------------------------

--
-- Table structure for table `item_transfers`
--

CREATE TABLE `item_transfers` (
  `transfer_id` int(11) NOT NULL,
  `item_from` int(11) NOT NULL,
  `item_to` int(11) NOT NULL,
  `removed_qty` int(11) NOT NULL,
  `added_qty` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `labour_payments`
--

CREATE TABLE `labour_payments` (
  `labour_id` int(11) NOT NULL,
  `task` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `exp_head` int(11) NOT NULL,
  `contra` int(11) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `trans_date` date NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `leaves_id` int(11) NOT NULL,
  `employee` int(11) NOT NULL,
  `leave_type` int(11) NOT NULL,
  `max_days` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` int(11) NOT NULL,
  `reason` text NOT NULL,
  `leave_status` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `applied` datetime NOT NULL,
  `posted_by` int(11) NOT NULL,
  `approved_by` int(11) NOT NULL,
  `approved_at` datetime NOT NULL,
  `returned` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`leaves_id`, `employee`, `leave_type`, `max_days`, `start_date`, `end_date`, `total_days`, `reason`, `leave_status`, `store`, `applied`, `posted_by`, `approved_by`, `approved_at`, `returned`) VALUES
(2, 99896, 2, 7, '2025-10-22', '2025-10-28', 7, 'Currently down with fever, need time off proper treatment', 0, 1, '2025-10-21 16:22:44', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `leave_id` int(11) NOT NULL,
  `leave_title` varchar(255) NOT NULL,
  `max_days` int(11) NOT NULL,
  `description` text NOT NULL,
  `leave_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`leave_id`, `leave_title`, `max_days`, `description`, `leave_status`) VALUES
(1, 'ANNUAL LEAVE', 31, 'Annual leave for all staffs once a year', 0),
(2, 'SICK LEAVE', 7, 'For staffs who fall sick', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ledgers`
--

CREATE TABLE `ledgers` (
  `ledger_id` int(11) NOT NULL,
  `account_group` int(11) NOT NULL,
  `sub_group` int(11) NOT NULL,
  `class` int(11) NOT NULL,
  `ledger` varchar(1024) NOT NULL,
  `acn` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ledgers`
--

INSERT INTO `ledgers` (`ledger_id`, `account_group`, `sub_group`, `class`, `ledger`, `acn`) VALUES
(24, 1, 2, 5, 'LAND AND BUILDING', 1020524),
(25, 1, 2, 5, 'OFFICE EQUIPMENT', 1020525),
(28, 1, 1, 2, 'CASH ACCOUNT', 1010228),
(29, 1, 1, 3, 'INVENTORIES', 1010329),
(30, 3, 5, 11, 'GENERAL REVENUE', 30501130),
(37, 4, 6, 12, 'UTILITY BILLS', 40601237),
(38, 4, 6, 12, 'TRANSPORTATION', 40601238),
(39, 4, 6, 12, 'SALARIES AND WAGES', 40601239),
(40, 4, 8, 15, 'DEPRECIATION EXPENSE', 40801540),
(41, 4, 8, 15, 'AMORTIZATION - SOFTWARE COST', 40801541),
(42, 1, 2, 6, 'ACCUM DEPRE - LAND AND BUILDING', 1020642),
(44, 1, 2, 6, 'ACCUM DEPRE - OFFICE EQUIPMENT', 1020644),
(45, 5, 9, 16, 'EQUITY CAPITAL', 50901645),
(46, 5, 9, 17, 'RETAINED EARNINGS', 50901746),
(47, 1, 2, 5, 'MOTOR VEHICLES', 1020547),
(48, 1, 2, 5, 'FURNITURE AND FITTINGS', 1020548),
(49, 5, 9, 16, 'DIRECTOR CURRENT ACCOUNT', 50901649),
(60, 4, 10, 18, 'LOSS ON DISPOSAL OF ASSET', 401001860),
(61, 3, 5, 19, 'GAIN ON DISPOSAL OF ASSET', 30501961),
(62, 1, 2, 6, 'ACCUM DEPRE - MOTOR VEHICLES', 1020662),
(63, 4, 7, 14, 'COST OF SALES', 40701463),
(67, 4, 6, 13, 'COMPANY INCOME TAX', 40601267),
(68, 4, 6, 13, 'LOAN FEES', 40601368),
(69, 4, 6, 13, 'BANK CHARGES', 40601369),
(70, 2, 3, 9, 'SHORT TERM LOANS', 2030970),
(71, 2, 4, 10, 'LONG TERM LOANS', 20401071),
(81, 4, 6, 13, 'INVENTORY ADJUSTMENT', 40601381),
(82, 2, 3, 7, 'ABC LIMITED', 2030782),
(83, 1, 1, 4, 'TEST CUSTOMER', 1010483),
(84, 4, 6, 13, 'FARM INPUTS', 40601384),
(85, 4, 6, 13, 'PRODUCTION LABOUR', 40601385),
(86, 4, 6, 12, 'GENERAL FIELD MAINTENANCE', 40601286),
(87, 2, 3, 7, 'AXXON MOBIL', 2030787),
(88, 4, 6, 12, 'DELIVERY &AMP; LOGISTICS EXPENSE', 40601388),
(89, 4, 6, 13, 'PRODUCTION INPUTS', 40601389);

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `loan_id` int(11) NOT NULL,
  `financier` int(11) NOT NULL,
  `loan_account` int(11) NOT NULL,
  `contra_ledger` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `details` varchar(1024) NOT NULL,
  `trans_type` varchar(50) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `trans_date` date DEFAULT NULL,
  `store` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `menu_id` int(11) NOT NULL,
  `menu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menu_id`, `menu`) VALUES
(1, 'Admin menu'),
(2, 'Sales menu'),
(3, 'Inventory'),
(4, 'Financial mgt'),
(5, 'Reports'),
(6, 'Financial reports'),
(7, 'Production'),
(9, 'Chart Of Account'),
(10, 'Asset Management'),
(11, 'Farm Management'),
(12, 'Human Resource');

-- --------------------------------------------------------

--
-- Table structure for table `multiple_payments`
--

CREATE TABLE `multiple_payments` (
  `id` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `invoice` varchar(255) NOT NULL,
  `cash` decimal(12,2) NOT NULL,
  `transfer` decimal(12,2) NOT NULL,
  `pos` decimal(12,2) NOT NULL,
  `bank` int(11) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `observations`
--

CREATE TABLE `observations` (
  `observation_id` int(11) NOT NULL,
  `farm` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `cycle` int(11) NOT NULL,
  `description` text NOT NULL,
  `done_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `observations`
--

INSERT INTO `observations` (`observation_id`, `farm`, `field`, `cycle`, `description`, `done_by`, `post_date`) VALUES
(2, 1, 3, 4, 'Rain Destriyed Part Of The Crop', 1, '2025-09-19 10:42:12');

-- --------------------------------------------------------

--
-- Table structure for table `opening_balance`
--

CREATE TABLE `opening_balance` (
  `balance_id` int(11) NOT NULL,
  `ledger` int(11) NOT NULL,
  `trans_type` varchar(50) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `trans_date` date DEFAULT NULL,
  `store` int(11) NOT NULL,
  `details` varchar(1024) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `opening_balance`
--

INSERT INTO `opening_balance` (`balance_id`, `ledger`, `trans_type`, `amount`, `trx_number`, `trans_date`, `store`, `details`, `post_date`, `posted_by`) VALUES
(1, 45, 'Credit', 1000000.00, 'TR887281024093309', '2024-01-01', 1, 'Opening Balance As At &quot;01-Jan-2024&quot;', '2024-10-28 09:33:09', 1);

-- --------------------------------------------------------

--
-- Table structure for table `other_income`
--

CREATE TABLE `other_income` (
  `income_id` int(11) NOT NULL,
  `income_head` varchar(255) NOT NULL,
  `activity` varchar(50) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `details` varchar(1024) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_payments`
--

CREATE TABLE `other_payments` (
  `payment_id` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_mode` varchar(20) NOT NULL,
  `store` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `other_payments`
--

INSERT INTO `other_payments` (`payment_id`, `customer`, `invoice`, `amount`, `payment_mode`, `store`, `posted_by`, `post_date`) VALUES
(1, 1, 'TO120102508051540', 50000.00, 'Wallet', 1, 1, '2025-10-20 09:39:00');

-- --------------------------------------------------------

--
-- Table structure for table `other_transactions`
--

CREATE TABLE `other_transactions` (
  `trx_id` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `ledger` int(11) NOT NULL,
  `contra_ledger` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `details` varchar(1024) NOT NULL,
  `trans_type` varchar(50) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `trans_date` date DEFAULT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `outstanding`
--

CREATE TABLE `outstanding` (
  `id` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `sales_type` varchar(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `amount_due` decimal(12,2) NOT NULL,
  `store` int(11) NOT NULL,
  `amount_paid` decimal(12,2) NOT NULL,
  `discount` decimal(12,2) NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `bank` int(11) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL,
  `invoice` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `sales_type`, `customer`, `amount_due`, `store`, `amount_paid`, `discount`, `payment_mode`, `bank`, `trx_number`, `post_date`, `posted_by`, `invoice`) VALUES
(8, 'Wholesale', 1, 10500.00, 1, 10500.00, 0.00, 'Cash', 0, 'TR048201025075851', '2025-10-20 07:58:51', 1, 'TO120102507581245'),
(9, 'Wholesale', 1, 50000.00, 1, 0.00, 0.00, 'Credit', 0, 'TR084201025080541', '2025-10-20 08:05:41', 1, 'TO120102508051540');

-- --------------------------------------------------------

--
-- Table structure for table `production`
--

CREATE TABLE `production` (
  `product_id` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `product_number` varchar(50) DEFAULT NULL,
  `product_qty` int(11) NOT NULL,
  `raw_material` int(11) NOT NULL,
  `raw_quantity` int(11) NOT NULL,
  `unit_cost` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `product_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `purchase_id` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `invoice` varchar(255) NOT NULL,
  `item` int(11) NOT NULL,
  `cost_price` decimal(12,2) NOT NULL,
  `sales_price` int(255) NOT NULL,
  `vendor` int(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `waybill` decimal(12,2) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `expiration_date` date NOT NULL,
  `purchase_status` int(11) NOT NULL,
  `purchase_date` date DEFAULT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE `purchase_order` (
  `purchase_id` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `item` int(11) NOT NULL,
  `cost_price` decimal(12,2) NOT NULL,
  `vendor` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `supplied` float NOT NULL,
  `order_status` int(11) NOT NULL,
  `delivery_status` int(11) NOT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `posted_by` int(11) DEFAULT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_payments`
--

CREATE TABLE `purchase_payments` (
  `payment_id` int(11) NOT NULL,
  `vendor` int(11) NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `product_cost` decimal(12,2) NOT NULL,
  `waybill` decimal(12,2) NOT NULL,
  `amount_due` decimal(12,2) NOT NULL,
  `amount_paid` decimal(12,2) NOT NULL,
  `payment_mode` varchar(20) NOT NULL,
  `store` int(11) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `trans_date` date DEFAULT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `remove_items`
--

CREATE TABLE `remove_items` (
  `remove_id` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `previous_qty` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `cost` decimal(12,2) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `removed_by` int(11) NOT NULL,
  `removed_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `remove_reasons`
--

CREATE TABLE `remove_reasons` (
  `remove_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `remove_reasons`
--

INSERT INTO `remove_reasons` (`remove_id`, `reason`) VALUES
(2, 'Damages');

-- --------------------------------------------------------

--
-- Table structure for table `renewals`
--

CREATE TABLE `renewals` (
  `renewal_id` int(11) NOT NULL,
  `company` int(11) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `package` varchar(255) NOT NULL,
  `amount` float NOT NULL,
  `processing_fee` float NOT NULL,
  `total_due` float NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `previous_date` date DEFAULT NULL,
  `new_due_date` date DEFAULT NULL,
  `renew_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rights`
--

CREATE TABLE `rights` (
  `right_id` int(11) NOT NULL,
  `menu` int(11) NOT NULL,
  `sub_menu` int(11) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rights`
--

INSERT INTO `rights` (`right_id`, `menu`, `sub_menu`, `user`) VALUES
(1, 1, 1, 20),
(2, 1, 2, 20),
(3, 1, 3, 20),
(4, 1, 4, 20),
(5, 1, 5, 20),
(6, 1, 6, 20),
(7, 1, 7, 20),
(8, 1, 8, 20),
(9, 1, 10, 20),
(10, 1, 11, 20),
(11, 1, 51, 20),
(12, 1, 62, 20),
(13, 1, 76, 20),
(14, 1, 79, 20),
(15, 1, 87, 20),
(16, 1, 115, 20),
(17, 2, 15, 20),
(18, 2, 16, 20),
(19, 2, 96, 20),
(20, 3, 17, 20),
(21, 3, 19, 20),
(22, 3, 20, 20),
(23, 3, 21, 20),
(24, 3, 22, 20),
(25, 3, 23, 20),
(26, 3, 90, 20),
(27, 3, 99, 20),
(28, 3, 101, 20),
(29, 4, 25, 20),
(30, 4, 69, 20),
(31, 4, 80, 20),
(32, 4, 106, 20),
(33, 4, 107, 20),
(34, 4, 129, 20),
(35, 4, 143, 20),
(36, 4, 151, 20),
(37, 4, 152, 20),
(38, 4, 153, 20),
(39, 5, 26, 20),
(40, 5, 27, 20),
(41, 5, 28, 20),
(42, 5, 29, 20),
(43, 5, 30, 20),
(44, 5, 31, 20),
(45, 5, 33, 20),
(46, 5, 34, 20),
(47, 5, 35, 20),
(48, 5, 36, 20),
(49, 5, 37, 20),
(50, 5, 38, 20),
(51, 5, 39, 20),
(52, 5, 40, 20),
(53, 5, 49, 20),
(54, 5, 50, 20),
(55, 5, 63, 20),
(56, 5, 85, 20),
(57, 5, 91, 20),
(58, 5, 94, 20),
(59, 5, 97, 20),
(60, 5, 100, 20),
(61, 5, 102, 20),
(62, 6, 41, 20),
(63, 6, 42, 20),
(64, 6, 43, 20),
(65, 6, 44, 20),
(66, 6, 45, 20),
(67, 6, 46, 20),
(68, 6, 47, 20),
(69, 6, 48, 20),
(70, 6, 66, 20),
(71, 6, 67, 20),
(72, 6, 68, 20),
(73, 6, 70, 20),
(74, 6, 104, 20),
(75, 6, 108, 20),
(76, 6, 109, 20),
(77, 6, 119, 20),
(78, 6, 120, 20),
(79, 6, 121, 20),
(80, 6, 122, 20),
(81, 6, 130, 20),
(82, 6, 131, 20),
(83, 6, 132, 20),
(84, 6, 133, 20),
(85, 6, 134, 20),
(86, 6, 135, 20),
(87, 6, 136, 20),
(88, 6, 137, 20),
(89, 6, 138, 20),
(90, 6, 139, 20),
(91, 6, 140, 20),
(92, 6, 141, 20),
(93, 6, 142, 20),
(94, 7, 92, 20),
(95, 7, 93, 20),
(96, 9, 123, 20),
(97, 9, 124, 20),
(98, 9, 125, 20),
(99, 9, 126, 20),
(100, 9, 127, 20),
(101, 9, 128, 20),
(102, 10, 144, 20),
(103, 10, 145, 20),
(104, 10, 146, 20),
(105, 10, 147, 20),
(106, 10, 148, 20),
(107, 10, 149, 20),
(108, 10, 150, 20),
(109, 10, 144, 21),
(110, 10, 145, 21),
(111, 10, 146, 21),
(112, 10, 147, 21),
(113, 10, 148, 21),
(114, 10, 149, 21),
(115, 10, 150, 21),
(116, 9, 123, 21),
(117, 9, 124, 21),
(118, 9, 125, 21),
(119, 9, 126, 21),
(120, 9, 127, 21),
(121, 9, 128, 21),
(122, 6, 41, 21),
(123, 6, 42, 21),
(124, 6, 43, 21),
(125, 6, 44, 21),
(126, 6, 45, 21),
(127, 6, 46, 21),
(128, 6, 47, 21),
(129, 6, 48, 21),
(130, 6, 66, 21),
(131, 6, 67, 21),
(132, 6, 68, 21),
(133, 6, 70, 21),
(134, 6, 104, 21),
(135, 6, 108, 21),
(136, 6, 109, 21),
(137, 6, 119, 21),
(138, 6, 120, 21),
(139, 6, 121, 21),
(140, 6, 122, 21),
(141, 6, 130, 21),
(142, 6, 131, 21),
(143, 6, 132, 21),
(144, 6, 133, 21),
(145, 6, 134, 21),
(146, 6, 135, 21),
(147, 6, 136, 21),
(148, 6, 137, 21),
(149, 6, 138, 21),
(150, 6, 139, 21),
(151, 6, 140, 21),
(152, 6, 141, 21),
(153, 6, 142, 21),
(154, 5, 26, 21),
(155, 5, 33, 21),
(156, 5, 39, 21),
(157, 5, 40, 21),
(158, 5, 49, 21),
(159, 5, 50, 21),
(160, 5, 85, 21),
(161, 5, 63, 21),
(162, 5, 94, 21),
(163, 5, 29, 21),
(164, 5, 30, 21),
(165, 5, 27, 21),
(166, 5, 28, 21),
(167, 4, 25, 21),
(168, 4, 69, 21),
(169, 4, 80, 21),
(170, 4, 106, 21),
(171, 4, 107, 21),
(172, 4, 129, 21),
(173, 4, 143, 21),
(174, 4, 151, 21),
(175, 4, 152, 21),
(176, 4, 153, 21),
(177, 3, 90, 21),
(178, 2, 15, 21),
(179, 2, 16, 21),
(180, 1, 2, 21),
(181, 1, 4, 21),
(182, 1, 115, 21),
(183, 3, 19, 22);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `sales_id` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `sales_type` varchar(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `invoice` varchar(255) NOT NULL,
  `quantity` float NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `discount` decimal(12,2) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `cost` decimal(12,2) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `sales_status` int(11) NOT NULL,
  `post_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`sales_id`, `item`, `store`, `sales_type`, `customer`, `invoice`, `quantity`, `price`, `discount`, `total_amount`, `cost`, `trx_number`, `posted_by`, `sales_status`, `post_date`) VALUES
(13, 18, 1, 'Wholesale', 1, 'TO120102507581245', 3, 3500.00, 1500.00, 10500.00, 9000.00, 'TR048201025075851', 1, 2, '2025-10-20 07:58:51'),
(14, 15, 1, 'Wholesale', 1, 'TO120102508051540', 10, 4500.00, 500.00, 45000.00, 10000.00, 'TR084201025080541', 1, 2, '2025-10-20 08:05:41'),
(15, 18, 1, 'Wholesale', 1, 'TO120102508051540', 1, 5000.00, 0.00, 5000.00, 3000.00, 'TR084201025080541', 1, 2, '2025-10-20 08:05:41');

-- --------------------------------------------------------

--
-- Table structure for table `sales_returns`
--

CREATE TABLE `sales_returns` (
  `return_id` int(11) NOT NULL,
  `invoice` varchar(255) NOT NULL,
  `store` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `reason` varchar(1024) NOT NULL,
  `returned_by` int(11) NOT NULL,
  `return_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staffs`
--

CREATE TABLE `staffs` (
  `staff_id` int(11) NOT NULL,
  `staff_number` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `other_names` varchar(255) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `marital_status` varchar(50) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `home_address` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `nok` varchar(255) NOT NULL,
  `nok_relation` varchar(50) NOT NULL,
  `nok_phone` varchar(50) NOT NULL,
  `employed` date NOT NULL,
  `department` int(11) NOT NULL,
  `staff_group` varchar(50) NOT NULL,
  `staff_category` varchar(50) NOT NULL,
  `designation` int(11) NOT NULL,
  `discipline` int(11) NOT NULL,
  `bank` int(11) NOT NULL,
  `account_num` int(11) NOT NULL,
  `pension` varchar(50) NOT NULL,
  `pension_num` varchar(50) NOT NULL,
  `photo` varchar(1024) NOT NULL,
  `signature` varchar(1024) NOT NULL,
  `staff_status` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `resigned` datetime DEFAULT NULL,
  `resigned_by` int(11) NOT NULL,
  `reg_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staffs`
--

INSERT INTO `staffs` (`staff_id`, `staff_number`, `last_name`, `other_names`, `gender`, `title`, `dob`, `marital_status`, `religion`, `phone`, `home_address`, `email_address`, `nok`, `nok_relation`, `nok_phone`, `employed`, `department`, `staff_group`, `staff_category`, `designation`, `discipline`, `bank`, `account_num`, `pension`, `pension_num`, `photo`, `signature`, `staff_status`, `store`, `user_id`, `resigned`, `resigned_by`, `reg_date`, `posted_by`) VALUES
(99896, '001', 'HEINZE', 'GABRIEL', 'Male', 'Eng.', '1993-08-30', 'Single', 'Christian', '08100123456', '23 Ajah Way, Lagos', 'heinzegabriel@mail.com', 'PAUL HEINZE', 'BROTHER', '08012345678', '2025-09-30', 3, 'core staff', 'senior staff', 16, 14, 0, 0, '', '', 'user.png', '', 0, 1, 0, '2025-10-21 09:34:55', 1, '2025-10-20 12:31:00', 1),
(99897, '003', 'EJIOFOR', 'HELEN NKECHI', 'Female', 'Mrs', '1978-08-30', 'Married', 'Christian', '08087654321', 'Lekki Gardens', 'ejiofor@mail.com', 'EJIOFOR EMEKA', 'SON', '09012341234', '2025-08-01', 1, 'core staff', 'managment staff', 18, 11, 0, 0, '', '', 'user.png', '', 0, 1, 0, NULL, 0, '2025-10-21 08:46:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `staff_departments`
--

CREATE TABLE `staff_departments` (
  `department_id` int(11) NOT NULL,
  `department` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff_departments`
--

INSERT INTO `staff_departments` (`department_id`, `department`) VALUES
(1, 'ACCOUNTS'),
(2, 'ADMINISTRATION'),
(3, 'ICT');

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustments`
--

CREATE TABLE `stock_adjustments` (
  `adjust_id` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `adjusted_by` int(11) NOT NULL,
  `previous_qty` int(11) NOT NULL,
  `new_qty` int(11) NOT NULL,
  `adjust_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `store_id` int(11) NOT NULL,
  `company` int(11) NOT NULL,
  `store` varchar(124) NOT NULL,
  `store_address` varchar(255) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`store_id`, `company`, `store`, `store_address`, `phone_number`, `date_created`) VALUES
(1, 1, 'Davidorlah Farm', 'Lekki', '', '2025-09-18 19:54:43');

-- --------------------------------------------------------

--
-- Table structure for table `sub_menus`
--

CREATE TABLE `sub_menus` (
  `sub_menu_id` int(11) NOT NULL,
  `menu` int(11) NOT NULL,
  `sub_menu` varchar(255) NOT NULL,
  `url` varchar(50) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_menus`
--

INSERT INTO `sub_menus` (`sub_menu_id`, `menu`, `sub_menu`, `url`, `status`) VALUES
(1, 1, 'Add Users', 'add_user', 0),
(2, 1, 'Disable User', 'disable_user', 0),
(3, 1, 'Activate User', 'activate_user', 0),
(4, 1, 'Reset Password', 'reset_password', 0),
(5, 1, 'Add Category', 'add_department', 0),
(6, 1, 'Add Sub-category', 'add_category', 0),
(7, 1, 'Add Items', 'add_item', 0),
(8, 1, 'Modify Item Name', 'modify_item', 0),
(9, 1, 'Add Bank', 'add_bank', 1),
(10, 1, 'Manage Prices', 'item_price', 0),
(11, 1, 'Add Remove Reasons', 'add_remove_reasons', 0),
(12, 2, 'Direct Sales', 'direct_sales', 1),
(13, 2, 'Sales Order', 'sales_order', 1),
(14, 2, 'Post Sales Order', 'post_sales_order', 1),
(15, 2, 'Sales Return', 'sales_return', 0),
(16, 2, 'Reprint Receipt', 'print_receipt', 0),
(17, 3, 'Set Reorder Level', 'reorder_level', 0),
(18, 3, 'Product Balance', 'stock_balance', 1),
(19, 3, 'Receive Purchases', 'stockin_purchase', 0),
(20, 3, 'Add Supplier', 'add_vendor', 0),
(21, 3, 'Adjust Quantity', 'stock_adjustment', 0),
(22, 3, 'Remove Item', 'remove_item', 0),
(23, 3, 'Adjust Expiration', 'adjust_expiration', 0),
(24, 4, 'Add Expense Head', 'add_exp_head', 1),
(25, 4, 'Post Expense', 'post_expense', 0),
(26, 5, 'Item List', 'item_list', 0),
(27, 5, 'Bank List', 'bank_list', 0),
(28, 5, 'List Of Suppliers', 'vendor_list', 0),
(29, 5, 'Sales Return Report', 'sales_return_report', 0),
(30, 5, 'Stock Adjustment Report', 'stock_adjustment_report', 0),
(31, 5, 'Item Removed Report', 'item_removed_report', 0),
(33, 5, 'Purchase Reports', 'purchase_reports', 0),
(34, 5, 'Out Of Stock', 'out_of_stock', 0),
(35, 5, 'Soon To Expire', 'expire_soon', 0),
(36, 5, 'Expired Items', 'expired_items', 0),
(37, 5, 'Reached Reorder Level', 'reached_reorder', 0),
(38, 5, 'Item History', 'item_history', 0),
(39, 5, 'Purchase By Item', 'purchase_by_item', 0),
(40, 5, 'Purchase Per Vendor', 'purchase_per_vendor', 0),
(41, 6, 'Sales Report', 'sales_report', 0),
(42, 6, 'Cash Payments', 'cash_list', 0),
(43, 6, 'POS Payments', 'pos_list', 0),
(44, 6, 'Transfer Payments', 'transfer_list', 0),
(45, 6, 'Cashier Report', 'cashier_report', 0),
(46, 6, 'Revenue By Category', 'revenue_by_category', 0),
(47, 6, 'Daily Income Statement', 'profit_and_loss', 0),
(48, 6, 'Expense Report', 'expense_report', 0),
(49, 5, 'Highest Selling Items', 'highest_selling', 0),
(50, 5, 'Fast Selling Items', 'fast_selling', 0),
(51, 1, 'Change Category', 'change_category', 0),
(52, 1, 'Update Item Barcode', 'update_barcode', 1),
(53, 3, 'Transfer Items', 'transfer_item', 1),
(54, 3, 'Pending Transfer', 'pending_transfer', 1),
(55, 3, 'Accept Items', 'accept_items', 1),
(56, 3, 'Returned Transfer', 'returned_transfer', 1),
(57, 5, 'Transferred Items Report', 'transfer_report', 1),
(58, 5, 'Accept Items Report', 'accept_report', 1),
(59, 3, 'All Store Balance', 'all_store_balance', 1),
(60, 2, 'Wholesale', 'wholesale', 1),
(62, 1, 'Add Customer', 'add_customer', 0),
(63, 5, 'Customer List', 'customer_list', 0),
(64, 6, 'Retail Sales', 'retail_sales', 1),
(65, 6, 'Wholesale Report', 'wholesale_report', 1),
(66, 6, 'Customer Statement', 'customer_statement', 0),
(67, 6, 'Credit Sales List', 'credit_sales_list', 0),
(68, 6, 'Debtors Report', 'debtors_list', 0),
(69, 4, 'Clear Unpaid Invoice', 'pay_debt', 0),
(70, 6, 'Debt Payment Report', 'debt_payment_report', 0),
(71, 1, 'Add Menu', 'add_menu', 1),
(72, 1, 'Add Sub-menu', 'add_sub_menu', 1),
(73, 1, 'Edit Sub Menu', 'edit_sub_menu', 1),
(74, 1, 'Manage Profile', 'manage_profile', 1),
(75, 11, 'Add Farm', 'add_store', 0),
(76, 1, 'Update Store Details', 'update_store', 0),
(77, 1, 'Add User Rights', 'add_rights', 1),
(78, 1, 'Delete Rights', 'delete_right', 1),
(79, 1, 'Edit Customer Info', 'edit_customer_info', 0),
(80, 4, 'Customer Payments', 'fund_wallet', 0),
(82, 1, 'Adjust Expiration', 'adjust_expiration', 1),
(83, 3, 'Transfer Qty Btw Items', 'transfer_qty', 1),
(85, 5, 'List Of Users', 'user_list', 0),
(86, 3, 'Reprint Transfer Receipt', 'reprint_transfer', 1),
(87, 1, 'Give Rights', 'give_user_right', 0),
(89, 1, 'Manage Cost Price', 'raw_material_price', 1),
(90, 3, 'Stock Balance', 'raw_material_balance', 0),
(91, 5, 'Raw Materials', 'raw_material_list', 0),
(92, 7, 'Make Product', 'make_product', 0),
(93, 7, 'Pending Production', 'pending_production', 0),
(94, 5, 'Production Report', 'production_report', 0),
(96, 2, 'Make Sales', 'wholesale', 0),
(97, 5, 'Production Statistics', 'production_statistics', 0),
(98, 3, 'Consumables', 'consumables', 1),
(99, 3, 'Issue Items', 'issue_items', 0),
(100, 5, 'Issued Items Report', 'issue_report', 0),
(101, 3, 'Pending Issued Items', 'pending_issued', 0),
(102, 5, 'Issued Item History', 'issued_item_stats', 0),
(104, 6, 'Customer Payment Reports', 'deposit_report', 0),
(105, 4, 'Post Debt Balance', 'post_debt', 1),
(106, 4, 'Post Purchases', 'post_purchase', 0),
(107, 4, 'Vendor Payments', 'post_vendor_payments', 0),
(108, 6, 'Vendor Payment Report', 'vendor_payments', 0),
(109, 6, 'Vendor Statement', 'vendor_statement', 0),
(110, 4, 'Post Vendor Balance', 'post_vendor_balance', 1),
(111, 5, 'Transfer Qty Bwt Reports', 'transfer_qty_btw_reports', 1),
(113, 5, 'Ice Cream Productions', 'ice_cream_production', 1),
(114, 6, 'Outstanding Debts Posting', 'outstanding_debts', 1),
(115, 1, 'Merge Customer Files', 'merge_files', 0),
(119, 6, 'Cash Sales Report', 'cash_sales_list', 0),
(120, 6, 'Pos Sales Report', 'pos_sales_report', 0),
(121, 6, 'Transfer Sales Report', 'transfer_sales', 0),
(122, 6, 'Vendor Payables', 'payables', 0),
(123, 9, 'Account Group', 'account_group', 0),
(124, 9, 'Account Sub-group', 'account_sub_group', 0),
(125, 9, 'Account Class', 'account_class', 0),
(126, 9, 'Account Ledgers', 'account_ledger', 0),
(127, 9, 'View Chart Of Account', 'chart_of_account', 0),
(128, 9, 'Update Ledger', 'update_ledger', 0),
(129, 4, 'Post Opening Balance', 'post_opening_balance', 0),
(130, 6, 'Trial Balance', 'trial_balance', 0),
(131, 6, 'Asset Postings', 'asset_posting', 0),
(132, 6, 'Depreciation Report', 'depreciation_report', 0),
(133, 6, 'Monthly Financial Position', 'monthly_financial_position', 0),
(134, 6, 'Yearly Financial Position', 'yearly_financial_position', 0),
(135, 6, 'Cash Flow', 'cash_flow', 0),
(136, 6, 'Account Statement', 'account_statement', 0),
(137, 6, 'Loans Received', 'loans', 0),
(138, 6, 'Loan Transactions', 'loan_postings', 0),
(139, 6, 'Director Transactions', 'director_postings', 0),
(140, 6, 'Finance Costs Trx', 'other_postings', 0),
(141, 6, 'Yearly Income Statement', 'yearly_income_statement', 0),
(142, 6, 'Other Transactions', 'other_transactions', 0),
(143, 4, 'Reverse Transaction', 'reverse_transactions', 0),
(144, 10, 'Add Asset Location', 'add_asset_location', 0),
(145, 10, 'Add New Asset', 'add_asset', 0),
(146, 10, 'Asset Register', 'asset_register', 0),
(147, 10, 'Allocate Asset', 'allocate_asset', 0),
(148, 10, 'Dispose Asset', 'dispose_asset', 0),
(149, 10, 'View Disposed Assets', 'disposed_assets', 0),
(150, 10, 'Delete Asset', 'delete_asset', 0),
(151, 4, 'Post Fixed Asset', 'post_fixed_asset', 0),
(152, 4, 'Post Depreciation', 'post_depreciation', 0),
(153, 4, 'Post Other Transactions', 'post_other_trx', 0),
(154, 11, 'Farm Fields', 'farm_fields', 0),
(155, 11, 'Crop Cycle', 'crop_cycle', 0),
(156, 11, 'General Tasks', 'general_task', 0),
(158, 5, 'Crop Cycle Reports', 'crop_cycle_report', 0),
(159, 5, 'Tasks Done', 'tasks_done', 0),
(161, 4, 'Post Labour Cost', 'post_labour', 0),
(162, 6, 'Labour Postings', 'labour_postings', 0),
(163, 5, 'Harvest Report', 'harvest_report', 0),
(164, 1, 'Update Vendor Details', 'edit_supplier_info', 0),
(165, 3, 'Raise PO', 'raise_purchase_order', 0),
(166, 3, 'Update PO', 'update_po', 0),
(167, 3, 'Receive Items By PO', 'receive_po', 0),
(168, 5, 'Purchase Orders', 'purchase_order_report', 0),
(169, 12, 'Add Staff', 'add_staff', 0),
(170, 12, 'Staff_list', 'staff_list', 0),
(171, 12, 'Add Designation', 'add_designation', 0),
(172, 12, 'Add Discipline', 'add_discipline', 0),
(173, 12, 'Add Department', 'add_staff_department', 0),
(174, 12, 'Suspend Staff', 'suspend_staff', 0),
(175, 12, 'Recall Staff', 'recall_staff', 0),
(176, 12, 'Resign Employee', 'resign_employee', 0),
(177, 12, 'Leave Types', 'leave_types', 0),
(178, 5, 'Staff Suspensions', 'suspension_report', 0),
(179, 5, 'Staff Resignations', 'resignation_report', 0),
(180, 12, 'Leave Request', 'apply_for_leave', 0),
(181, 12, 'Approve Leave', 'approve_leave', 0);

-- --------------------------------------------------------

--
-- Table structure for table `suspensions`
--

CREATE TABLE `suspensions` (
  `suspension_id` int(11) NOT NULL,
  `staff` int(11) NOT NULL,
  `suspension_date` datetime DEFAULT NULL,
  `suspension_status` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `recall_date` datetime DEFAULT NULL,
  `recalled_by` int(11) NOT NULL,
  `suspended_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suspensions`
--

INSERT INTO `suspensions` (`suspension_id`, `staff`, `suspension_date`, `suspension_status`, `store`, `recall_date`, `recalled_by`, `suspended_by`) VALUES
(3, 99896, '2025-10-20 13:59:09', 1, 1, '2025-10-20 13:59:15', 1, 1),
(4, 99897, '2025-10-21 08:53:49', 1, 1, '2025-10-21 08:58:17', 1, 1),
(5, 99896, '2025-10-21 08:55:04', 0, 1, NULL, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `task_number` varchar(50) NOT NULL,
  `farm` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `cycle` int(11) NOT NULL,
  `title` varchar(1024) NOT NULL,
  `description` text NOT NULL,
  `workers` varchar(1024) NOT NULL,
  `labour_cost` decimal(12,2) NOT NULL,
  `task_type` varchar(50) NOT NULL,
  `payment_status` int(11) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `done_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_items`
--

CREATE TABLE `task_items` (
  `task_item_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `farm` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `cycle` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `account_type` int(11) NOT NULL,
  `sub_group` int(11) NOT NULL,
  `class` int(11) NOT NULL,
  `account` int(50) NOT NULL,
  `debit` decimal(12,3) NOT NULL,
  `credit` decimal(12,3) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `details` varchar(1024) NOT NULL,
  `trx_status` int(11) NOT NULL,
  `trans_date` date DEFAULT NULL,
  `store` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `account_type`, `sub_group`, `class`, `account`, `debit`, `credit`, `trx_number`, `details`, `trx_status`, `trans_date`, `store`, `post_date`, `posted_by`) VALUES
(101, 3, 5, 11, 30501130, 0.000, 10500.000, 'TR048201025075851', 'Sales of product', 0, '2025-10-20', 1, '2025-10-20 07:58:51', 1),
(102, 4, 7, 14, 40701463, 9000.000, 0.000, 'TR048201025075851', 'Cost of sales', 0, '2025-10-20', 1, '2025-10-20 07:58:51', 1),
(103, 1, 1, 3, 1010329, 0.000, 9000.000, 'TR048201025075851', 'Cost of sales', 0, '2025-10-20', 1, '2025-10-20 07:58:51', 1),
(104, 1, 1, 4, 1010483, 10500.000, 0.000, 'TR048201025075851', 'Goods purchased', 0, '2025-10-20', 1, '2025-10-20 07:58:51', 1),
(105, 1, 1, 2, 1010228, 10500.000, 0.000, 'TR048201025075851', 'Payment for goods sold', 0, '2025-10-20', 1, '2025-10-20 07:58:51', 1),
(106, 1, 1, 4, 1010483, 0.000, 10500.000, 'TR048201025075851', 'Goods purchased', 0, '2025-10-20', 1, '2025-10-20 07:58:51', 1),
(107, 3, 5, 11, 30501130, 0.000, 50000.000, 'TR084201025080541', 'Sales of product', 0, '2025-10-20', 1, '2025-10-20 08:05:41', 1),
(108, 4, 7, 14, 40701463, 13000.000, 0.000, 'TR084201025080541', 'Cost of sales', 0, '2025-10-20', 1, '2025-10-20 08:05:41', 1),
(109, 1, 1, 3, 1010329, 0.000, 13000.000, 'TR084201025080541', 'Cost of sales', 0, '2025-10-20', 1, '2025-10-20 08:05:41', 1),
(110, 1, 1, 4, 1010483, 50000.000, 0.000, 'TR084201025080541', 'Goods purchased', 0, '2025-10-20', 1, '2025-10-20 08:05:41', 1),
(111, 1, 1, 2, 1010228, 20000.000, 0.000, 'TR311201025091421', 'For Invoice', 0, '2025-10-20', 0, '2025-10-20 09:14:21', 1),
(112, 1, 1, 4, 1010483, 0.000, 20000.000, 'TR311201025091421', 'For Invoice', 0, '2025-10-20', 1, '2025-10-20 09:14:21', 1),
(113, 1, 1, 2, 1010228, 30000.000, 0.000, 'TR621201025094251', 'Jknjnm,n', 0, '2025-10-20', 0, '2025-10-20 09:42:51', 1),
(114, 1, 1, 4, 1010483, 0.000, 30000.000, 'TR621201025094251', 'Jknjnm,n', 0, '2025-10-20', 1, '2025-10-20 09:42:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `transfer_id` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `from_store` int(11) NOT NULL,
  `to_store` int(11) NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `cost_price` int(11) NOT NULL,
  `sales_price` int(11) NOT NULL,
  `expiration` date NOT NULL,
  `transfer_status` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `accept_by` int(11) NOT NULL,
  `post_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(1024) NOT NULL,
  `username` varchar(255) NOT NULL,
  `user_role` varchar(255) NOT NULL,
  `user_password` varchar(1024) NOT NULL,
  `status` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `username`, `user_role`, `user_password`, `status`, `store`, `reg_date`) VALUES
(1, 'Administrator', 'Sysadmin', 'Admin', '$2y$10$dcUrnR/.PvfK7XeYcP60hOyW2qnPSSvEq/Wxee6lv5DETW8pbGXYu', 0, 1, '2022-09-27 13:47:21'),
(20, 'Godwin Alabi', 'Godwin', 'Accountant', '$2y$10$MxYFDweEJ6AHKeAF79cvOe.Js21/y5mDBTSVX2LpVOSu0V5MKkDy6', 0, 1, '2024-10-21 03:59:17'),
(21, 'Helen Kalams Udodirim', 'Helen', 'Accountant', '$2y$10$CIYVML3NBcRpfYZWVRHT0.OJ/5yjeABqOnj6wo3EZVb.OBvymDwZa', 0, 1, '2024-10-21 04:01:55'),
(22, 'James John', 'John', 'Inventory Officer', '$2y$10$iczDHrQ0fNdFBh/p79aheurXCShSCALfL6aoqd0hRkW/SSI0pGREa', 0, 9, '2025-09-19 10:51:58');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vendor_id` int(11) NOT NULL,
  `vendor` varchar(1024) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `biz_address` varchar(1024) NOT NULL,
  `balance` int(11) NOT NULL,
  `account_no` int(11) NOT NULL,
  `ledger_id` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`vendor_id`, `vendor`, `contact_person`, `phone`, `email_address`, `biz_address`, `balance`, `account_no`, `ledger_id`, `created_date`, `added_by`) VALUES
(1, 'ABC LIMITED', 'Abc', '0-90808', 'kk', '', 0, 2030782, 82, '2025-09-18 17:40:42', 0),
(2, 'AXXON MOBIL', 'James Kano', '09087766788', '', 'ajah', 0, 2030787, 87, '2025-10-08 07:45:59', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payments`
--

CREATE TABLE `vendor_payments` (
  `payment_id` int(11) NOT NULL,
  `vendor` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `payment_mode` varchar(20) NOT NULL,
  `contra` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `trans_date` date DEFAULT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waybills`
--

CREATE TABLE `waybills` (
  `waybill_id` int(11) NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `vendor` int(11) NOT NULL,
  `invoice_amount` decimal(12,2) NOT NULL,
  `waybill` decimal(12,2) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `store` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accept_po`
--
ALTER TABLE `accept_po`
  ADD PRIMARY KEY (`accept_id`);

--
-- Indexes for table `account_class`
--
ALTER TABLE `account_class`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `account_groups`
--
ALTER TABLE `account_groups`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `account_sub_groups`
--
ALTER TABLE `account_sub_groups`
  ADD PRIMARY KEY (`sub_group_id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`asset_id`);

--
-- Indexes for table `asset_locations`
--
ALTER TABLE `asset_locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `asset_postings`
--
ALTER TABLE `asset_postings`
  ADD PRIMARY KEY (`asset_id`);

--
-- Indexes for table `assigned_fields`
--
ALTER TABLE `assigned_fields`
  ADD PRIMARY KEY (`assigned_id`);

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`audit_id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`bank_id`);

--
-- Indexes for table `cash_flows`
--
ALTER TABLE `cash_flows`
  ADD PRIMARY KEY (`fow_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `cost_of_sales`
--
ALTER TABLE `cost_of_sales`
  ADD PRIMARY KEY (`cost_of_sales_id`);

--
-- Indexes for table `crop_cycles`
--
ALTER TABLE `crop_cycles`
  ADD PRIMARY KEY (`cycle_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `customer_trail`
--
ALTER TABLE `customer_trail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cycle_changes`
--
ALTER TABLE `cycle_changes`
  ADD PRIMARY KEY (`change_id`);

--
-- Indexes for table `debtors`
--
ALTER TABLE `debtors`
  ADD PRIMARY KEY (`debtor_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`deposit_id`);

--
-- Indexes for table `depreciation`
--
ALTER TABLE `depreciation`
  ADD PRIMARY KEY (`depreciation_id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`designation_id`);

--
-- Indexes for table `director_posting`
--
ALTER TABLE `director_posting`
  ADD PRIMARY KEY (`director_id`);

--
-- Indexes for table `disciplines`
--
ALTER TABLE `disciplines`
  ADD PRIMARY KEY (`discipline_id`);

--
-- Indexes for table `disposed_assets`
--
ALTER TABLE `disposed_assets`
  ADD PRIMARY KEY (`disposed_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `expense_heads`
--
ALTER TABLE `expense_heads`
  ADD PRIMARY KEY (`exp_head_id`);

--
-- Indexes for table `fields`
--
ALTER TABLE `fields`
  ADD PRIMARY KEY (`field_id`);

--
-- Indexes for table `finance_cost`
--
ALTER TABLE `finance_cost`
  ADD PRIMARY KEY (`finance_id`);

--
-- Indexes for table `harvests`
--
ALTER TABLE `harvests`
  ADD PRIMARY KEY (`harvest_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_id`);

--
-- Indexes for table `issue_items`
--
ALTER TABLE `issue_items`
  ADD PRIMARY KEY (`issue_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `item_transfers`
--
ALTER TABLE `item_transfers`
  ADD PRIMARY KEY (`transfer_id`);

--
-- Indexes for table `labour_payments`
--
ALTER TABLE `labour_payments`
  ADD PRIMARY KEY (`labour_id`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`leaves_id`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`leave_id`);

--
-- Indexes for table `ledgers`
--
ALTER TABLE `ledgers`
  ADD PRIMARY KEY (`ledger_id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `multiple_payments`
--
ALTER TABLE `multiple_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `observations`
--
ALTER TABLE `observations`
  ADD PRIMARY KEY (`observation_id`);

--
-- Indexes for table `opening_balance`
--
ALTER TABLE `opening_balance`
  ADD PRIMARY KEY (`balance_id`);

--
-- Indexes for table `other_income`
--
ALTER TABLE `other_income`
  ADD PRIMARY KEY (`income_id`);

--
-- Indexes for table `other_payments`
--
ALTER TABLE `other_payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `other_transactions`
--
ALTER TABLE `other_transactions`
  ADD PRIMARY KEY (`trx_id`);

--
-- Indexes for table `outstanding`
--
ALTER TABLE `outstanding`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `production`
--
ALTER TABLE `production`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`purchase_id`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`purchase_id`);

--
-- Indexes for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `remove_items`
--
ALTER TABLE `remove_items`
  ADD PRIMARY KEY (`remove_id`);

--
-- Indexes for table `remove_reasons`
--
ALTER TABLE `remove_reasons`
  ADD PRIMARY KEY (`remove_id`);

--
-- Indexes for table `renewals`
--
ALTER TABLE `renewals`
  ADD PRIMARY KEY (`renewal_id`);

--
-- Indexes for table `rights`
--
ALTER TABLE `rights`
  ADD PRIMARY KEY (`right_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sales_id`);

--
-- Indexes for table `sales_returns`
--
ALTER TABLE `sales_returns`
  ADD PRIMARY KEY (`return_id`);

--
-- Indexes for table `staffs`
--
ALTER TABLE `staffs`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `staff_departments`
--
ALTER TABLE `staff_departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD PRIMARY KEY (`adjust_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`store_id`);

--
-- Indexes for table `sub_menus`
--
ALTER TABLE `sub_menus`
  ADD PRIMARY KEY (`sub_menu_id`);

--
-- Indexes for table `suspensions`
--
ALTER TABLE `suspensions`
  ADD PRIMARY KEY (`suspension_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `task_items`
--
ALTER TABLE `task_items`
  ADD PRIMARY KEY (`task_item_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`transfer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendor_id`);

--
-- Indexes for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `waybills`
--
ALTER TABLE `waybills`
  ADD PRIMARY KEY (`waybill_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accept_po`
--
ALTER TABLE `accept_po`
  MODIFY `accept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `account_class`
--
ALTER TABLE `account_class`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `account_groups`
--
ALTER TABLE `account_groups`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `account_sub_groups`
--
ALTER TABLE `account_sub_groups`
  MODIFY `sub_group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_locations`
--
ALTER TABLE `asset_locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `asset_postings`
--
ALTER TABLE `asset_postings`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `assigned_fields`
--
ALTER TABLE `assigned_fields`
  MODIFY `assigned_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `bank_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_flows`
--
ALTER TABLE `cash_flows`
  MODIFY `fow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cost_of_sales`
--
ALTER TABLE `cost_of_sales`
  MODIFY `cost_of_sales_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `crop_cycles`
--
ALTER TABLE `crop_cycles`
  MODIFY `cycle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_trail`
--
ALTER TABLE `customer_trail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cycle_changes`
--
ALTER TABLE `cycle_changes`
  MODIFY `change_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `debtors`
--
ALTER TABLE `debtors`
  MODIFY `debtor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `deposit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `depreciation`
--
ALTER TABLE `depreciation`
  MODIFY `depreciation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `designation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `director_posting`
--
ALTER TABLE `director_posting`
  MODIFY `director_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disciplines`
--
ALTER TABLE `disciplines`
  MODIFY `discipline_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `disposed_assets`
--
ALTER TABLE `disposed_assets`
  MODIFY `disposed_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `expense_heads`
--
ALTER TABLE `expense_heads`
  MODIFY `exp_head_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `fields`
--
ALTER TABLE `fields`
  MODIFY `field_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `finance_cost`
--
ALTER TABLE `finance_cost`
  MODIFY `finance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `harvests`
--
ALTER TABLE `harvests`
  MODIFY `harvest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `issue_items`
--
ALTER TABLE `issue_items`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `item_transfers`
--
ALTER TABLE `item_transfers`
  MODIFY `transfer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `labour_payments`
--
ALTER TABLE `labour_payments`
  MODIFY `labour_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `leaves_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ledgers`
--
ALTER TABLE `ledgers`
  MODIFY `ledger_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `multiple_payments`
--
ALTER TABLE `multiple_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `observations`
--
ALTER TABLE `observations`
  MODIFY `observation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `opening_balance`
--
ALTER TABLE `opening_balance`
  MODIFY `balance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `other_income`
--
ALTER TABLE `other_income`
  MODIFY `income_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other_payments`
--
ALTER TABLE `other_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `other_transactions`
--
ALTER TABLE `other_transactions`
  MODIFY `trx_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outstanding`
--
ALTER TABLE `outstanding`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `production`
--
ALTER TABLE `production`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `remove_items`
--
ALTER TABLE `remove_items`
  MODIFY `remove_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `remove_reasons`
--
ALTER TABLE `remove_reasons`
  MODIFY `remove_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `renewals`
--
ALTER TABLE `renewals`
  MODIFY `renewal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rights`
--
ALTER TABLE `rights`
  MODIFY `right_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sales_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `sales_returns`
--
ALTER TABLE `sales_returns`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staffs`
--
ALTER TABLE `staffs`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99898;

--
-- AUTO_INCREMENT for table `staff_departments`
--
ALTER TABLE `staff_departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  MODIFY `adjust_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `store_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sub_menus`
--
ALTER TABLE `sub_menus`
  MODIFY `sub_menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT for table `suspensions`
--
ALTER TABLE `suspensions`
  MODIFY `suspension_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `task_items`
--
ALTER TABLE `task_items`
  MODIFY `task_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `transfer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `waybills`
--
ALTER TABLE `waybills`
  MODIFY `waybill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
