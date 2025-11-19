-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2025 at 08:29 AM
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
(4, 3, 18, 40, 20, 1, 1, '2025-10-29 13:36:03');

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
  `contract_duration` int(11) NOT NULL,
  `payment_duration` float NOT NULL,
  `rent_percentage` decimal(12,2) NOT NULL,
  `annual_rent` decimal(12,2) NOT NULL,
  `installment` decimal(12,2) NOT NULL,
  `purchase_cost` decimal(12,2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `contract_status` int(11) NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `assigned_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assigned_fields`
--

INSERT INTO `assigned_fields` (`assigned_id`, `field`, `customer`, `contract_duration`, `payment_duration`, `rent_percentage`, `annual_rent`, `installment`, `purchase_cost`, `start_date`, `due_date`, `contract_status`, `assigned_by`, `assigned_date`) VALUES
(8, 8, 9, 2, 0, 0.00, 5000000.00, 5000000.00, 10000000.00, '2025-10-30', '2027-10-30', 1, 1, '2025-10-29 13:51:17'),
(9, 7, 10, 3, 0, 0.00, 3000000.00, 3000000.00, 9000000.00, '2025-11-01', '2028-11-01', 1, 1, '2025-10-31 12:00:55'),
(12, 9, 10, 3, 1, 25.00, 3750000.00, 15000000.00, 15000000.00, '2025-11-14', '2025-11-14', 2, 1, '2025-11-13 14:34:42'),
(14, 10, 12, 5, 3, 36.00, 10800000.00, 10000000.00, 30000000.00, '2025-11-21', '2026-01-21', 2, 1, '2025-11-14 12:10:08');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `staff` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `attendance_date` date DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `attendance_status` int(11) NOT NULL,
  `remark` text NOT NULL,
  `marked_by` int(11) NOT NULL,
  `marked_date` datetime DEFAULT NULL,
  `checked_out_by` int(11) NOT NULL,
  `checked_out` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `staff`, `store`, `attendance_date`, `time_in`, `time_out`, `attendance_status`, `remark`, `marked_by`, `marked_date`, `checked_out_by`, `checked_out`) VALUES
(16, 99898, 1, '2025-11-14', '15:50:00', '15:51:00', 0, '', 1, '2025-11-14 15:50:41', 1, '2025-11-14 15:51:15');

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
(63, 1, 15, 'harvest', 0, 20, 1, '2025-10-29 13:19:27'),
(64, 1, 15, 'harvest', 20, 30, 1, '2025-10-29 13:20:26'),
(65, 1, 18, 'purchase', 0, 300, 1, '2025-10-29 13:30:20'),
(66, 1, 18, 'purchase', 300, 20, 1, '2025-10-29 13:36:03'),
(67, 1, 18, 'task', 320, 1, 1, '2025-10-29 13:40:28'),
(68, 1, 20, 'purchase', 0, 3000, 1, '2025-11-10 07:01:50'),
(69, 1, 18, 'task', 319, 2, 1, '2025-11-10 07:21:06'),
(70, 1, 18, 'task', 317, 2, 1, '2025-11-10 07:21:49'),
(71, 1, 18, 'task return', 315, 2, 1, '2025-11-10 07:22:00'),
(72, 1, 18, 'task return', 317, 2, 1, '2025-11-10 07:22:02'),
(73, 1, 18, 'task', 319, 2, 1, '2025-11-10 07:23:57'),
(74, 1, 18, 'task return', 317, 2, 1, '2025-11-10 07:25:00'),
(75, 1, 18, 'task', 319, 1, 1, '2025-11-10 07:25:22'),
(76, 1, 18, 'task return', 318, 1, 1, '2025-11-10 07:26:34'),
(77, 1, 18, 'task', 319, 1, 1, '2025-11-10 07:26:42'),
(78, 1, 18, 'task return', 318, 1, 1, '2025-11-10 07:27:41'),
(79, 1, 18, 'task', 319, 2, 1, '2025-11-10 07:27:54'),
(80, 1, 18, 'task', 317, 1, 1, '2025-11-10 07:34:14'),
(81, 1, 20, 'task', 3000, 1, 1, '2025-11-10 07:35:55'),
(82, 1, 20, 'task return', 2999, 1, 1, '2025-11-10 07:46:04'),
(83, 1, 18, 'task return', 316, 1, 1, '2025-11-10 07:46:06'),
(84, 1, 18, 'task return', 317, 2, 1, '2025-11-10 07:46:08'),
(85, 1, 18, 'task', 319, 2, 1, '2025-11-10 08:28:24'),
(86, 1, 18, 'task', 317, 2, 1, '2025-11-10 08:35:48'),
(87, 1, 18, 'task', 315, 1, 1, '2025-11-10 08:38:33'),
(88, 1, 20, 'task', 3000, 1, 1, '2025-11-10 08:40:42'),
(89, 1, 20, 'task return', 2999, 1, 1, '2025-11-10 08:40:47'),
(90, 1, 20, 'task', 3000, 1, 1, '2025-11-10 08:41:37'),
(91, 1, 20, 'task return', 2999, 1, 1, '2025-11-10 08:41:39'),
(92, 1, 20, 'task', 3000, 1, 1, '2025-11-10 08:42:04'),
(93, 1, 20, 'task return', 2999, 1, 1, '2025-11-10 08:42:07'),
(94, 1, 20, 'task', 3000, 10, 1, '2025-11-11 15:12:28'),
(95, 1, 20, 'task', 2990, 300, 1, '2025-11-11 16:18:02'),
(96, 1, 18, 'task', 314, 2, 1, '2025-11-11 16:37:07'),
(97, 1, 1, 'harvest', 0, 250, 1, '2025-11-11 20:44:13'),
(98, 1, 1, 'harvest', 250, 30, 1, '2025-11-11 20:46:41'),
(99, 1, 1, 'harvest', 280, 20, 1, '2025-11-11 20:48:02'),
(100, 1, 20, 'sucker_removal', 2690, 40, 1, '2025-11-12 13:15:05'),
(101, 1, 20, 'sucker_removal', 2730, 10, 1, '2025-11-12 13:15:51'),
(102, 1, 20, 'sucker_removal', 2740, 12, 1, '2025-11-12 13:16:20'),
(103, 1, 18, 'task', 312, 2, 1, '2025-11-12 15:43:46'),
(104, 1, 18, 'task return', 310, 2, 1, '2025-11-12 15:44:13'),
(105, 1, 18, 'task', 312, 2, 1, '2025-11-12 15:44:24'),
(106, 1, 18, 'task', 310, 1, 1, '2025-11-14 11:18:22'),
(107, 1, 20, 'task', 2752, 300, 1, '2025-11-14 11:20:49'),
(108, 1, 1, 'harvest', 300, 40, 1, '2025-11-14 11:34:27'),
(109, 1, 20, 'sucker_removal', 2452, 300, 1, '2025-11-14 11:38:18'),
(110, 1, 21, 'pruning', 0, 30, 1, '2025-11-19 07:50:04'),
(111, 1, 20, 'sucker_removal', 2752, 10, 1, '2025-11-19 08:20:51');

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
(19, 1010228, 0, 'Loan Repayment', 'TR542131125051847', 5000000.00, 'inflow', 'operating', 1, '2025-11-13 17:18:47', 1),
(20, 1010228, 0, 'Loan Repayment', 'TR773131125062428', 4000.00, 'inflow', 'operating', 1, '2025-11-13 18:24:28', 1),
(21, 1010228, 0, 'Field Purchase Payment', 'TR239131125083233', 30000.00, 'inflow', 'operating', 1, '2025-11-13 20:32:33', 1),
(22, 1010228, 0, 'Field Purchase Payment', 'TR376131125083544', 3000000.00, 'inflow', 'operating', 1, '2025-11-13 20:35:44', 1),
(23, 1010228, 0, 'Field Purchase Payment', 'TR436131125083904', 3000000.00, 'inflow', 'operating', 1, '2025-11-13 20:39:04', 1),
(24, 1010228, 0, 'Field Purchase Payment', 'TR625131125084605', 300000.00, 'inflow', 'operating', 1, '2025-11-13 20:46:05', 1),
(25, 1010228, 0, 'Field Purchase Payment', 'TR045131125085317', 666000.00, 'inflow', 'operating', 1, '2025-11-13 20:53:17', 1),
(26, 1010228, 0, 'Field Purchase Payment', 'TR224141125123710', 50000.00, 'inflow', 'operating', 1, '2025-11-14 12:37:10', 1),
(27, 1010228, 0, 'Field Purchase Payment', 'TR551141125124220', 20000000.00, 'inflow', 'operating', 1, '2025-11-14 12:42:20', 1);

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
(35, '11', 'FEEDS', 0),
(37, '12', 'CROPS', 0),
(40, '1', 'FERTILIZERS', 0),
(41, '1', 'SEEDLINGS', 0);

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
(1, 'Davidorlah Farm', 'davidorlah.png', 200000, '2025-08-28 14:46:39');

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
(8, 8, 1, 0, '', '2025-11-04 00:00:00', '2026-04-17 00:00:00', 310, 100.00, 0, '', '2025-11-04 17:00:51', 1, NULL, 0),
(9, 8, 1, 0, '', '2025-11-13 00:00:00', '2025-11-14 00:00:00', 300, 250.00, 0, '', '2025-11-11 15:23:49', 1, NULL, 0),
(10, 8, 1, 0, '', '2025-11-25 00:00:00', NULL, 0, 40.00, 0, '', '2025-11-11 16:59:15', 1, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `crop_removal`
--

CREATE TABLE `crop_removal` (
  `removal_id` int(11) NOT NULL,
  `crop` int(11) NOT NULL,
  `cycle` int(11) NOT NULL,
  `farm` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `quantity` float NOT NULL,
  `reason` varchar(1024) NOT NULL,
  `other_notes` text NOT NULL,
  `removed_by` int(11) NOT NULL,
  `date_removed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crop_removal`
--

INSERT INTO `crop_removal` (`removal_id`, `crop`, `cycle`, `farm`, `field`, `trx_number`, `quantity`, `reason`, `other_notes`, `removed_by`, `date_removed`) VALUES
(1, 1, 8, 1, 8, '', 3, 'Boss Requested', 'Jkahsjjkjkchakj', 1, '2025-11-12 09:57:12'),
(2, 1, 8, 1, 8, '', 2, 'Disease Outbreak', 'Some Crops Were Damaged By Ebola', 1, '2025-11-12 10:13:38'),
(3, 1, 9, 1, 8, 'TR272141125113351', 30, 'Pest Infestation', 'Kljwfklsdjlkjf', 1, '2025-11-14 11:33:51');

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
  `user_password` varchar(1024) NOT NULL,
  `phone_numbers` varchar(20) NOT NULL,
  `customer_address` varchar(100) NOT NULL,
  `customer_email` varchar(50) NOT NULL,
  `wallet_balance` int(11) NOT NULL,
  `amount_due` int(11) NOT NULL,
  `reg_date` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `customer`, `ledger_id`, `acn`, `customer_type`, `user_password`, `phone_numbers`, `customer_address`, `customer_email`, `wallet_balance`, `amount_due`, `reg_date`, `created_by`, `updated_at`) VALUES
(9, 'JIMOH IBRAHIM', 97, 1010497, 'Landowner', '$2y$10$gzJXM2rqk1kUYcN/S2zIEuiD0E//9V7Plxj2Xxi/rTMrs/09DncJG', '091234567898', 'Wehgfhjcbn', 'onostarkels@gmail.com', 0, 0, '2025-10-29 13:49:16', 1, NULL),
(10, 'KELLY IKPEFUA', 98, 1010498, 'Landowner', '$2y$10$sKGhHvnYNPfci8kraHKu..u7lIm/F.SAUVGccIPcJovenJ4VvHyTa', '07068897968', 'Lagos', 'onostarmedia@gmail.com', 0, 0, '2025-10-29 13:56:57', 1, NULL),
(12, 'MERCY', 100, 10104100, 'Landowner', '$2y$10$4hSTTjsKSgIVNgxLemYcXuZAw0iqe9JXcNxEOFhF0/CY6LBSqYUsu', '9878686', 'Lagos', 'mercyoteje97@gmail.com', 0, 0, '2025-11-14 12:06:54', 1, NULL);

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
(8, 10, '', 'Field Purchase Payment', 3000000.00, 'TR474131125051708', 1, 1, '2025-11-13 17:17:08'),
(9, 10, '', 'Field Purchase Payment', 5000000.00, 'TR542131125051847', 1, 1, '2025-11-13 17:18:47'),
(10, 10, '', 'Field Purchase Payment', 4000.00, 'TR773131125062428', 1, 1, '2025-11-13 18:24:28'),
(11, 10, '', 'Field Purchase Payment', 30000.00, 'TR239131125083233', 1, 1, '2025-11-13 20:32:33'),
(12, 10, '', 'Field Purchase Payment', 3000000.00, 'TR376131125083544', 1, 1, '2025-11-13 20:35:44'),
(13, 10, '', 'Field Purchase Payment', 3000000.00, 'TR436131125083904', 1, 1, '2025-11-13 20:39:04'),
(14, 10, '', 'Field Purchase Payment', 300000.00, 'TR625131125084605', 1, 1, '2025-11-13 20:46:05'),
(15, 10, '', 'Field Purchase Payment', 666000.00, 'TR045131125085317', 1, 1, '2025-11-13 20:53:17'),
(16, 12, '', 'Field Purchase Payment', 20000000.00, 'TR668141125122601', 1, 1, '2025-11-14 12:26:01'),
(17, 12, '', 'Field Purchase Payment', 50000.00, 'TR224141125123710', 1, 1, '2025-11-14 12:37:10'),
(18, 12, '', 'Field Purchase Payment', 19950000.00, 'TR693141125123840', 1, 1, '2025-11-14 12:38:40'),
(19, 12, '', 'Field Purchase Payment', 20000000.00, 'TR551141125124220', 1, 1, '2025-11-14 12:42:20');

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
(5, 7, 7, 7, 15, 15, 'nil', 'nil', 30, 30, '2025-10-31', '2025-10-29', '2026-03-01', '2026-03-01', 100, 100, 'Nil', 'Nil', 1, '2025-10-29 12:34:42'),
(6, 8, 8, 8, 0, 0, '', '', 100, 100, '2025-11-04', '2025-11-04', '2025-11-28', '2025-11-28', 0, 0, '', '', 1, '2025-11-04 17:08:22'),
(7, 8, 8, 8, 0, 0, '', '', 100, 100, '2025-11-04', '2025-11-04', '2025-11-28', '2025-11-28', 0, 0, '', '', 1, '2025-11-11 15:11:47');

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
  `trx_type` varchar(255) NOT NULL,
  `details` varchar(255) NOT NULL,
  `trans_date` date DEFAULT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deposits`
--

INSERT INTO `deposits` (`deposit_id`, `store`, `customer`, `amount`, `payment_mode`, `bank`, `invoice`, `trx_number`, `trx_type`, `details`, `trans_date`, `post_date`, `posted_by`) VALUES
(4, 1, 10, 3000000.00, 'Cash', 0, 'LP1311250516333301150', 'TR474131125051708', 'Field Purchase Payment', 'Dfasf', '2025-11-13', '2025-11-13 17:17:08', 1),
(5, 1, 10, 5000000.00, 'Cash', 0, 'LP1311250518110311150', 'TR542131125051847', 'Field Purchase Payment', 'Klqjkljd', '2025-11-13', '2025-11-13 17:18:47', 1),
(6, 1, 10, 4000.00, 'Cash', 0, 'LP1311250623133031150', 'TR773131125062428', 'Field Purchase Payment', 'Gh', '2025-11-13', '2025-11-13 18:24:28', 1),
(7, 1, 10, 30000.00, 'Cash', 0, 'LP1311250828220031150', 'TR239131125083233', 'Field Purchase Payment', 'Adfdf', '2025-11-13', '2025-11-13 20:32:33', 1),
(8, 1, 10, 3000000.00, 'Cash', 0, 'LP1311250835021011150', 'TR376131125083544', 'Field Purchase Payment', 'Jkhjkhk', '2025-11-13', '2025-11-13 20:35:44', 1),
(9, 1, 10, 3000000.00, 'Cash', 0, 'LP1311250838232011150', 'TR436131125083904', 'Field Purchase Payment', 'Da,sc', '2025-11-13', '2025-11-13 20:39:04', 1),
(10, 1, 10, 300000.00, 'Cash', 0, 'LP1311250845330011150', 'TR625131125084605', 'Field Purchase Payment', 'Nmwdsfm,', '2025-11-13', '2025-11-13 20:46:05', 1),
(11, 1, 10, 666000.00, 'Cash', 0, 'LP1311250853222031150', 'TR045131125085317', 'Field Purchase Payment', 'Nghhj', '2025-11-13', '2025-11-13 20:53:17', 1),
(12, 1, 12, 20000000.00, 'Cash', 0, 'LP1411251225101311154', 'TR668141125122601', 'Field Purchase Payment', 'Ghfghfgh', '2025-11-14', '2025-11-14 12:26:01', 1),
(13, 1, 12, 50000.00, 'Cash', 0, 'LP1411251236002131155', 'TR224141125123710', 'Field Purchase Payment', 'Hjghjg', '2025-11-14', '2025-11-14 12:37:10', 1),
(14, 1, 12, 19950000.00, 'Cash', 0, 'LP1411251238123121155', 'TR693141125123840', 'Field Purchase Payment', 'Jhghj', '2025-11-14', '2025-11-14 12:38:40', 1),
(15, 1, 12, 20000000.00, 'Cash', 0, 'LP1411251242000301155', 'TR551141125124220', 'Field Purchase Payment', 'Ghfgh', '2025-11-14', '2025-11-14 12:42:20', 1);

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
(14, 'ICT ENGINEER'),
(15, 'TREKKER'),
(16, 'EATER');

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
  `location` varchar(1024) NOT NULL,
  `latitude` decimal(11,9) NOT NULL,
  `longitude` decimal(12,9) NOT NULL,
  `purchase_cost` decimal(12,2) NOT NULL,
  `field_status` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fields`
--

INSERT INTO `fields` (`field_id`, `farm`, `field_name`, `customer`, `field_size`, `soil_type`, `soil_ph`, `topography`, `location`, `latitude`, `longitude`, `purchase_cost`, `field_status`, `created_at`, `created_by`, `updated_by`, `updated_at`) VALUES
(7, 1, 'OKUN-AJAH PINEAPPLE FIELD', 10, 300.00, 'alluvial soil', 7.30, 'Irrigated', 'okun-ajah community', 6.429665498, 3.592732338, 3000000.00, 1, '2025-10-29 12:24:19', 1, 0, NULL),
(8, 1, 'LEKKIE GARDEN', 9, 500.00, 'loamy soil', 6.50, 'Sloapy', 'marualekki', 6.439890894, 3.524611036, 5000000.00, 0, '2025-10-29 12:31:16', 1, 0, NULL),
(9, 1, 'IGANDO FARM FIELD', 10, 900.00, 'clay soil', 7.20, 'Irrigated', 'igando, lagos', 0.100000000, 0.100000000, 15000000.00, 0, '2025-11-13 10:55:57', 1, 1, '2025-11-13 11:03:13'),
(10, 1, 'OKUN AJAH FARM', 12, 50.00, 'clay soild', 6.50, 'Irrigated', 'okun-ajah community', 0.766660000, 0.877660000, 30000000.00, 0, '2025-11-14 11:45:31', 1, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `field_payments`
--

CREATE TABLE `field_payments` (
  `payment_id` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `loan` int(11) NOT NULL,
  `schedule` int(11) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `interest` decimal(10,0) NOT NULL,
  `processing_fee` decimal(10,0) NOT NULL,
  `penalty` decimal(10,2) NOT NULL,
  `payment_mode` varchar(50) NOT NULL,
  `details` text NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `bank` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `trx_number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `field_payments`
--

INSERT INTO `field_payments` (`payment_id`, `customer`, `store`, `loan`, `schedule`, `amount`, `interest`, `processing_fee`, `penalty`, `payment_mode`, `details`, `invoice`, `bank`, `posted_by`, `post_date`, `trx_number`) VALUES
(1, 10, 1, 12, 150, 5000000, 0, 0, 0.00, 'Cash', 'Klqjkljd', 'LP1311250518110311150', 0, 1, '2025-11-13 17:18:47', 'TR542131125051847'),
(2, 10, 1, 12, 150, 4000, 0, 0, 0.00, 'Cash', 'Gh', 'LP1311250623133031150', 0, 1, '2025-11-13 18:24:28', 'TR773131125062428'),
(3, 10, 1, 12, 150, 30000, 0, 0, 0.00, 'Cash', 'Adfdf', 'LP1311250828220031150', 0, 1, '2025-11-13 20:32:33', 'TR239131125083233'),
(4, 10, 1, 12, 150, 3000000, 0, 0, 0.00, 'Cash', 'Jkhjkhk', 'LP1311250835021011150', 0, 1, '2025-11-13 20:35:44', 'TR376131125083544'),
(5, 10, 1, 12, 150, 3000000, 0, 0, 0.00, 'Cash', 'Da,sc', 'LP1311250838232011150', 0, 1, '2025-11-13 20:39:04', 'TR436131125083904'),
(6, 10, 1, 12, 150, 300000, 0, 0, 0.00, 'Cash', 'Nmwdsfm,', 'LP1311250845330011150', 0, 1, '2025-11-13 20:46:05', 'TR625131125084605'),
(7, 10, 1, 12, 150, 666000, 0, 0, 0.00, 'Cash', 'Nghhj', 'LP1311250853222031150', 0, 1, '2025-11-13 20:53:17', 'TR045131125085317'),
(8, 12, 1, 14, 154, 10000000, 0, 0, 0.00, 'Cash', 'Ghfghfgh', 'LP1411251225101311154', 0, 1, '2025-11-14 12:26:01', 'TR668141125122601'),
(9, 12, 1, 14, 155, 50000, 0, 0, 0.00, 'Cash', 'Hjghjg', 'LP1411251236002131155', 0, 1, '2025-11-14 12:37:10', 'TR224141125123710'),
(10, 12, 1, 14, 155, 9950000, 0, 0, 0.00, 'Cash', 'Jhghj', 'LP1411251238123121155', 0, 1, '2025-11-14 12:38:40', 'TR693141125123840'),
(11, 12, 1, 14, 155, 10000000, 0, 0, 0.00, 'Cash', 'Ghfgh', 'LP1411251242000301155', 0, 1, '2025-11-14 12:42:20', 'TR551141125124220'),
(12, 12, 1, 14, 156, 10000000, 0, 0, 0.00, 'Cash', 'Excess from previous', 'LP1411251242000301155', 0, 1, '2025-11-14 12:42:20', 'TR551141125124220');

-- --------------------------------------------------------

--
-- Table structure for table `field_payment_schedule`
--

CREATE TABLE `field_payment_schedule` (
  `repayment_id` int(11) NOT NULL,
  `assigned_id` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `amount_due` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `penalty` decimal(10,2) NOT NULL,
  `store` int(11) NOT NULL,
  `due_date` date DEFAULT NULL,
  `payment_status` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `field_payment_schedule`
--

INSERT INTO `field_payment_schedule` (`repayment_id`, `assigned_id`, `field`, `customer`, `amount_due`, `amount_paid`, `penalty`, `store`, `due_date`, `payment_status`, `post_date`, `posted_by`) VALUES
(150, 12, 9, 10, 15000000.00, 15000000.00, 0.00, 1, '2025-11-14', 1, '2025-11-13 14:34:42', 1),
(154, 14, 10, 12, 10000000.00, 10000000.00, 0.00, 1, '2025-11-21', 1, '2025-11-14 12:10:08', 1),
(155, 14, 10, 12, 10000000.00, 10000000.00, 0.00, 1, '2025-12-21', 1, '2025-11-14 12:10:08', 1),
(156, 14, 10, 12, 10000000.00, 10000000.00, 0.00, 1, '2026-01-21', 1, '2025-11-14 12:10:08', 1);

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
(8, 8, 1, 1, 8, 250, 780.00, 1, '2025-11-11 20:44:13'),
(9, 8, 1, 1, 8, 30, 696.43, 1, '2025-11-11 20:46:41'),
(10, 8, 1, 1, 8, 20, 650.00, 1, '2025-11-11 20:48:02'),
(11, 9, 1, 1, 8, 40, 4525.00, 1, '2025-11-14 11:34:27');

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
(5, 18, 'Farm Input', 1, 1000, 309, 0, NULL, 5, NULL),
(6, 20, 'Farm Input', 1, 500, 2762, 0, NULL, 10, NULL),
(7, 1, 'Crop', 1, 4525, 340, 0, NULL, 10, NULL),
(8, 21, 'Crop', 1, 0, 30, 0, NULL, 10, NULL);

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
(1, '12', 37, 'PINEAPPLE', 'Crop', 4525, 5000, 0, 0, 0, 0, 10, '', 0, '2025-09-18 12:23:46'),
(18, '1', 40, 'NPK FERTILIZER', 'Farm Input', 1000, 5000, 0, 0, 0, 0, 5, '', 0, '2025-09-18 15:04:22'),
(20, '1', 41, 'SUCKER', 'Farm Input', 500, 0, 0, 0, 0, 0, 10, '', 0, '2025-11-10 07:01:16'),
(21, '12', 37, 'LEAVES', 'Crop', 0, 0, 0, 0, 0, 0, 10, '', 0, '2025-11-19 07:49:08');

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
  `applied` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL,
  `approved_by` int(11) NOT NULL,
  `approved_at` datetime DEFAULT NULL,
  `returned` datetime DEFAULT NULL,
  `ended_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`leaves_id`, `employee`, `leave_type`, `max_days`, `start_date`, `end_date`, `total_days`, `reason`, `leave_status`, `store`, `applied`, `posted_by`, `approved_by`, `approved_at`, `returned`, `ended_by`) VALUES
(5, 99898, 2, 7, '2025-11-15', '2025-11-19', 5, 'staff have ebola virus', -1, 1, '2025-11-14 15:30:48', 1, 1, '2025-11-14 15:31:26', NULL, 0);

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
(1, 'ANNUAL LEAVE', 30, 'Annual leave for all staffs once a year', 0),
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
(84, 4, 6, 13, 'FARM INPUTS', 40601384),
(85, 4, 6, 13, 'PRODUCTION LABOUR', 40601385),
(86, 4, 6, 12, 'GENERAL FIELD MAINTENANCE', 40601286),
(87, 2, 3, 7, 'AXXON MOBIL', 2030787),
(88, 4, 6, 12, 'DELIVERY &AMP; LOGISTICS EXPENSE', 40601388),
(89, 4, 6, 13, 'PRODUCTION INPUTS', 40601389),
(96, 2, 3, 7, 'AXY LIMITED', 2030796),
(97, 1, 1, 4, 'JIMOH IBRAHIM', 1010497),
(98, 1, 1, 4, 'KELLY IKPEFUA', 1010498),
(100, 1, 1, 4, 'MERCY', 10104100);

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
(12, 'Human Resource'),
(13, 'Uploads'),
(14, 'Customer Menu');

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
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `client` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `not_status` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `client`, `subject`, `message`, `not_status`, `post_date`) VALUES
(84, 9, 'Your Field Has Been Assigned', 'Dear JIMOH IBRAHIM, your field (LEKKIE GARDEN => 500.00 Hectares) located at marualekki has been successfully assigned under our rental program. Please log in to your client portal to view your rent schedule and payment dates.', 0, '2025-10-29 13:51:17'),
(85, 10, 'Your Field Has Been Assigned', 'Dear KELLY IKPEFUA, your field (OKUN-AJAH PINEAPPLE FIELD => 300.00 Hectares) located at okun-ajah community has been successfully assigned under our rental program. Please log in to your client portal to view your rent schedule and payment dates.', 1, '2025-10-31 12:00:55'),
(86, 10, 'Your Field Purchase Contract is Active', 'Dear KELLY IKPEFUA, your field (IGANDO FARM FIELD - 900.00 Hectares) located at igando, lagos has been successfully assigned for purchase. Once installments are completed, you will start receiving annual returns of ₦3,750,000.00 (25%) for 3 year(s).', 1, '2025-11-13 14:34:42'),
(87, 10, 'Field Purchase Payment Update', 'Dear KELLY IKPEFUA, your payment for  ( hectares) has been received. Total paid so far: ₦, remaining balance: ₦. Once fully paid, your annual rent of ₦ will begin.', 0, '2025-11-13 17:18:47'),
(88, 10, 'Field purchase Payment Confirmation', 'Dear KELLY IKPEFUA,\r\n            We confirm the receipt of your payment of ₦5,000,000.00 on 13th November 2025, 05:18pm towards your Field purchase.\r\n            Transaction ID: LP1311250518110311150\r\n            Your account has been updated accordingly. Thank you for your commitment.\r\n            \r\n            If you have any questions or need a receipt, feel free to contact us\r\n\r\n            Warm regards,\r\n            Davidorlah Farm\r\n            Customer Support', 0, '2025-11-13 17:18:47'),
(89, 10, 'Field Purchase Payment Update', 'Dear KELLY IKPEFUA, your payment for  (900.00 hectares) has been received. Total paid so far: ₦, remaining balance: ₦. Once fully paid, your annual rent of ₦ will begin.', 0, '2025-11-13 18:24:28'),
(90, 10, 'Field purchase Payment Confirmation', 'Dear KELLY IKPEFUA,\r\n            We confirm the receipt of your payment of ₦4,000.00 on 13th November 2025, 06:24pm towards your Field purchase.\r\n            Transaction ID: LP1311250623133031150\r\n            Your account has been updated accordingly. Thank you for your commitment.\r\n            \r\n            If you have any questions or need a receipt, feel free to contact us\r\n\r\n            Warm regards,\r\n            Davidorlah Farm\r\n            Customer Support', 0, '2025-11-13 18:24:28'),
(91, 10, 'Field Purchase Payment Update', 'Dear KELLY IKPEFUA, your payment for IGANDO FARM FIELD (900.00 hectares) has been received. Total paid so far: ₦8,034,000.00, remaining balance: ₦6,966,000.00. Once fully paid, your annual rent of ₦3,750,000.00 will begin.', 0, '2025-11-13 20:32:33'),
(92, 10, 'Field purchase Payment Confirmation', 'Dear KELLY IKPEFUA,\r\n            We confirm the receipt of your payment of ₦30,000.00 on 13th November 2025, 08:32pm towards your Field purchase.\r\n            Transaction ID: LP1311250828220031150\r\n            Your account has been updated accordingly. Thank you for your commitment.\r\n            \r\n            If you have any questions or need a receipt, feel free to contact us\r\n\r\n            Warm regards,\r\n            Davidorlah Farm\r\n            Customer Support', 0, '2025-11-13 20:32:33'),
(93, 10, 'Field Purchase Payment Update', 'Dear KELLY IKPEFUA, your payment for IGANDO FARM FIELD (900.00 hectares) has been received. Total paid so far: ₦11,034,000.00, remaining balance: ₦3,966,000.00. Once fully paid, your annual rent of ₦3,750,000.00 will begin.', 0, '2025-11-13 20:35:44'),
(94, 10, 'Field purchase Payment Confirmation', 'Dear KELLY IKPEFUA,\r\n            We confirm the receipt of your payment of ₦3,000,000.00 on 13th November 2025, 08:35pm towards your Field purchase.\r\n            Transaction ID: LP1311250835021011150\r\n            Your account has been updated accordingly. Thank you for your commitment.\r\n            \r\n            If you have any questions or need a receipt, feel free to contact us\r\n\r\n            Warm regards,\r\n            Davidorlah Farm\r\n            Customer Support', 0, '2025-11-13 20:35:44'),
(95, 10, 'Field Purchase Payment Update', 'Dear KELLY IKPEFUA, your payment for IGANDO FARM FIELD (900.00 hectares) has been received. Total paid so far: ₦14,034,000.00, remaining balance: ₦966,000.00. Once fully paid, your annual rent of ₦3,750,000.00 will begin.', 0, '2025-11-13 20:39:04'),
(96, 10, 'Field purchase Payment Confirmation', 'Dear KELLY IKPEFUA,\r\n            We confirm the receipt of your payment of ₦3,000,000.00 on 13th November 2025, 08:39pm towards your Field purchase.\r\n            Transaction ID: LP1311250838232011150\r\n            Your account has been updated accordingly. Thank you for your commitment.\r\n            \r\n            If you have any questions or need a receipt, feel free to contact us\r\n\r\n            Warm regards,\r\n            Davidorlah Farm\r\n            Customer Support', 0, '2025-11-13 20:39:04'),
(97, 10, 'Field Purchase Payment Update', 'Dear KELLY IKPEFUA, your payment for IGANDO FARM FIELD (900.00 hectares) has been received. Total paid so far: ₦14,334,000.00, remaining balance: ₦666,000.00. Once fully paid, your annual rent of ₦3,750,000.00 will begin.', 1, '2025-11-13 20:46:05'),
(98, 10, 'Field purchase Payment Confirmation', 'Dear KELLY IKPEFUA,\r\n            We confirm the receipt of your payment of ₦300,000.00 on 13th November 2025, 08:46pm towards your Field purchase.\r\n            Transaction ID: LP1311250845330011150\r\n            Your account has been updated accordingly. Thank you for your commitment.\r\n            \r\n            If you have any questions or need a receipt, feel free to contact us\r\n\r\n            Warm regards,\r\n            Davidorlah Farm\r\n            Customer Support', 0, '2025-11-13 20:46:05'),
(99, 10, 'Field Purchase Completed - Annual Rent Activated', 'Dear KELLY IKPEFUA, your field (IGANDO FARM FIELD - 900.00 hectares) has been fully paid. Your annual rent of ₦3,750,000.00 will commence now for the next 3 year(s).', 0, '2025-11-13 20:53:17'),
(100, 10, 'Field purchase Payment Confirmation', 'Dear KELLY IKPEFUA,\r\n            We confirm the receipt of your payment of ₦666,000.00 on 13th November 2025, 08:53pm towards your Field purchase.\r\n            Transaction ID: LP1311250853222031150\r\n            Your account has been updated accordingly. Thank you for your commitment.\r\n            \r\n            If you have any questions or need a receipt, feel free to contact us\r\n\r\n            Warm regards,\r\n            Davidorlah Farm\r\n            Customer Support', 1, '2025-11-13 20:53:17'),
(101, 11, 'Your Field Purchase Contract is Active', 'Dear MERCY, your field (OKUN AJAH FARM - 50.00 Hectares) located at okun-ajah community has been successfully assigned for purchase. Once installments are completed, you will start receiving annual returns of ₦7,500,000.00 (25%) for 5 year(s).', 1, '2025-11-14 11:56:31'),
(102, 12, 'Your Field Purchase Contract is Active', 'Dear MERCY, your field (OKUN AJAH FARM - 50.00 Hectares) located at okun-ajah community has been successfully assigned for purchase. Once installments are completed, you will start receiving annual returns of ₦10,800,000.00 (36%) for 5 year(s).', 0, '2025-11-14 12:10:08'),
(103, 12, 'Field Purchase Payment Update', 'Dear MERCY, your payment for OKUN AJAH FARM (50.00 hectares) has been received. Total paid so far: ₦50,000.00, remaining balance: ₦29,950,000.00. Once fully paid, your annual rent of ₦10,800,000.00 will begin.', 0, '2025-11-14 12:37:10'),
(104, 12, 'Field purchase Payment Confirmation', 'Dear MERCY,\r\n            We confirm the receipt of your payment of ₦50,000.00 on 14th November 2025, 12:37pm towards your Field purchase.\r\n            Transaction ID: LP1411251236002131155\r\n            Your account has been updated accordingly. Thank you for your commitment.\r\n            \r\n            If you have any questions or need a receipt, feel free to contact us\r\n\r\n            Warm regards,\r\n            Davidorlah Farm\r\n            Customer Support', 0, '2025-11-14 12:37:10'),
(105, 12, 'Field Purchase Completed - Annual Rent Activated', 'Dear MERCY, your field (OKUN AJAH FARM - 50.00 hectares) has been fully paid. Your annual rent of ₦10,800,000.00 will commence now for the next 5 year(s).', 0, '2025-11-14 12:42:20'),
(106, 12, 'Field purchase Payment Confirmation', 'Dear MERCY,\r\n            We confirm the receipt of your payment of ₦20,000,000.00 on 14th November 2025, 12:42pm towards your Field purchase.\r\n            Transaction ID: LP1411251242000301155\r\n            Your account has been updated accordingly. Thank you for your commitment.\r\n            \r\n            If you have any questions or need a receipt, feel free to contact us\r\n\r\n            Warm regards,\r\n            Davidorlah Farm\r\n            Customer Support', 0, '2025-11-14 12:42:20');

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
(3, 1, 7, 7, 'Water Logged Soil', 1, '2025-10-29 13:18:36'),
(4, 1, 8, 8, 'Planting Started', 1, '2025-11-11 15:30:11'),
(5, 1, 8, 8, 'Planting Started', 1, '2025-11-11 15:32:23'),
(6, 1, 8, 8, 'Some Suckers Are Bad', 1, '2025-11-11 15:35:02'),
(7, 1, 8, 9, 'Jhesjkfdh', 1, '2025-11-14 11:38:58');

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

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `payroll_id` int(11) NOT NULL,
  `staff` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `payroll_date` date DEFAULT NULL,
  `working_days` int(11) NOT NULL,
  `days_at_work` int(11) NOT NULL,
  `leave_days` int(11) NOT NULL,
  `suspension_days` int(11) NOT NULL,
  `absent_days` int(11) NOT NULL,
  `basic_salary` decimal(12,2) NOT NULL,
  `housing` decimal(12,2) NOT NULL,
  `medical` decimal(12,2) NOT NULL,
  `transport` decimal(12,2) NOT NULL,
  `utility` decimal(12,2) NOT NULL,
  `other_allowance` decimal(12,2) NOT NULL,
  `gross_pay` decimal(12,2) NOT NULL,
  `tax` decimal(12,2) NOT NULL,
  `tax_rate` decimal(5,2) NOT NULL,
  `taxable_income` decimal(12,2) NOT NULL,
  `pension` decimal(12,2) NOT NULL,
  `lateness_penalty` decimal(12,2) NOT NULL,
  `absence_penalty` decimal(12,2) NOT NULL,
  `loan_repayment` decimal(12,2) NOT NULL,
  `other_deductions` decimal(12,2) NOT NULL,
  `net_pay` decimal(12,2) NOT NULL,
  `payroll_status` int(11) NOT NULL,
  `prepared_by` int(11) NOT NULL,
  `date_generated` datetime DEFAULT NULL,
  `approved` datetime DEFAULT NULL,
  `approved_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`payroll_id`, `staff`, `store`, `payroll_date`, `working_days`, `days_at_work`, `leave_days`, `suspension_days`, `absent_days`, `basic_salary`, `housing`, `medical`, `transport`, `utility`, `other_allowance`, `gross_pay`, `tax`, `tax_rate`, `taxable_income`, `pension`, `lateness_penalty`, `absence_penalty`, `loan_repayment`, `other_deductions`, `net_pay`, `payroll_status`, `prepared_by`, `date_generated`, `approved`, `approved_by`) VALUES
(24, 99898, 1, '2025-11-14', 30, 1, 0, 1, 29, 200000.00, 50000.00, 30000.00, 50000.00, 0.00, 0.00, 330000.00, 22500.00, 7.50, 300000.00, 24000.00, 2000.00, 58000.00, 0.00, 0.00, 223500.00, 1, 1, '2025-11-14 16:02:07', '2025-11-14 16:03:06', 1);

-- --------------------------------------------------------

--
-- Table structure for table `penalty_fees`
--

CREATE TABLE `penalty_fees` (
  `penalty_id` int(11) NOT NULL,
  `penalty` varchar(50) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penalty_fees`
--

INSERT INTO `penalty_fees` (`penalty_id`, `penalty`, `amount`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'LATENESS', 1000.00, '2025-10-24 09:33:04', 1, '2025-10-24 09:35:07', 1),
(2, 'ABSENT', 2000.00, '2025-10-24 09:35:23', 1, '2025-10-24 09:35:32', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pensions`
--

CREATE TABLE `pensions` (
  `pension_id` int(11) NOT NULL,
  `staff` int(11) NOT NULL,
  `payroll_id` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `pensionable_income` int(11) NOT NULL,
  `employee_contribution` decimal(12,2) NOT NULL,
  `employer_contribution` decimal(12,2) NOT NULL,
  `total_contribution` decimal(12,2) NOT NULL,
  `payroll_date` date DEFAULT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pensions`
--

INSERT INTO `pensions` (`pension_id`, `staff`, `payroll_id`, `store`, `pensionable_income`, `employee_contribution`, `employer_contribution`, `total_contribution`, `payroll_date`, `posted_by`, `post_date`) VALUES
(22, 99898, 24, 1, 300000, 24000.00, 30000.00, 54000.00, '2025-11-14', 1, '2025-11-14 16:02:07');

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
-- Table structure for table `pruning`
--

CREATE TABLE `pruning` (
  `sucker_id` int(11) NOT NULL,
  `farm` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `cycle` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `crop` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `removed_by` int(11) NOT NULL,
  `date_removed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pruning`
--

INSERT INTO `pruning` (`sucker_id`, `farm`, `field`, `cycle`, `task_id`, `crop`, `quantity`, `trx_number`, `removed_by`, `date_removed`) VALUES
(5, 1, 8, 8, 46, 21, 30, 'TR426191125075004', 1, '2025-11-19 07:50:04');

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

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`purchase_id`, `store`, `invoice`, `item`, `cost_price`, `sales_price`, `vendor`, `quantity`, `waybill`, `trx_number`, `expiration_date`, `purchase_status`, `purchase_date`, `posted_by`, `post_date`) VALUES
(15, 1, '897897ioui', 18, 1000.00, 0, 3, 300, 0.00, 'TR582291025013228', '0000-00-00', 1, NULL, 1, '2025-10-29 13:30:20'),
(16, 1, 'PO129102501333021', 18, 900.00, 0, 1, 20, 0.00, '', '0000-00-00', 0, NULL, 1, '2025-10-29 13:36:03'),
(17, 1, '098ojkl', 20, 500.00, 0, 1, 3000, 0.00, '', '0000-00-00', 0, NULL, 1, '2025-11-10 07:01:50');

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

--
-- Dumping data for table `purchase_order`
--

INSERT INTO `purchase_order` (`purchase_id`, `store`, `invoice`, `item`, `cost_price`, `vendor`, `quantity`, `supplied`, `order_status`, `delivery_status`, `delivery_date`, `posted_by`, `post_date`) VALUES
(3, 1, 'PO129102501333021', 18, 900.00, 1, 40, 20, 1, 0, NULL, 1, '2025-10-29 13:34:04');

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

--
-- Dumping data for table `purchase_payments`
--

INSERT INTO `purchase_payments` (`payment_id`, `vendor`, `invoice`, `product_cost`, `waybill`, `amount_due`, `amount_paid`, `payment_mode`, `store`, `trx_number`, `trans_date`, `posted_by`, `post_date`) VALUES
(8, 3, '897897ioui', 300000.00, 0.00, 300000.00, 0.00, 'Credit', 1, 'TR582291025013228', '2025-10-29', 1, '2025-10-29 13:32:28');

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
-- Table structure for table `rent_payments`
--

CREATE TABLE `rent_payments` (
  `payment_id` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `loan` int(11) NOT NULL,
  `schedule` int(11) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `interest` decimal(10,0) NOT NULL,
  `processing_fee` decimal(10,0) NOT NULL,
  `penalty` decimal(10,2) NOT NULL,
  `payment_mode` varchar(50) NOT NULL,
  `details` text NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `bank` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `trx_number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rent_schedule`
--

CREATE TABLE `rent_schedule` (
  `repayment_id` int(11) NOT NULL,
  `assigned_id` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `amount_due` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `penalty` decimal(10,2) NOT NULL,
  `store` int(11) NOT NULL,
  `due_date` date DEFAULT NULL,
  `payment_status` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rent_schedule`
--

INSERT INTO `rent_schedule` (`repayment_id`, `assigned_id`, `field`, `customer`, `amount_due`, `amount_paid`, `penalty`, `store`, `due_date`, `payment_status`, `post_date`, `posted_by`) VALUES
(145, 8, 8, 9, 5000000.00, 0.00, 0.00, 1, '2026-10-30', 0, '2025-10-29 13:51:17', 1),
(146, 8, 8, 9, 5000000.00, 0.00, 0.00, 1, '2027-10-30', 0, '2025-10-29 13:51:17', 1),
(147, 9, 7, 10, 3000000.00, 0.00, 0.00, 1, '2026-11-01', 0, '2025-10-31 12:00:55', 1),
(148, 9, 7, 10, 3000000.00, 0.00, 0.00, 1, '2027-11-01', 0, '2025-10-31 12:00:55', 1),
(149, 9, 7, 10, 3000000.00, 0.00, 0.00, 1, '2028-11-01', 0, '2025-10-31 12:00:55', 1),
(150, 12, 9, 10, 3750000.00, 0.00, 0.00, 1, '2026-11-13', 0, '2025-11-13 20:53:17', 1),
(151, 12, 9, 10, 3750000.00, 0.00, 0.00, 1, '2027-11-13', 0, '2025-11-13 20:53:17', 1),
(152, 12, 9, 10, 3750000.00, 0.00, 0.00, 1, '2028-11-13', 0, '2025-11-13 20:53:17', 1),
(153, 14, 10, 12, 10800000.00, 0.00, 0.00, 1, '2026-11-14', 0, '2025-11-14 12:42:20', 1),
(154, 14, 10, 12, 10800000.00, 0.00, 0.00, 1, '2027-11-14', 0, '2025-11-14 12:42:20', 1),
(155, 14, 10, 12, 10800000.00, 0.00, 0.00, 1, '2028-11-14', 0, '2025-11-14 12:42:20', 1),
(156, 14, 10, 12, 10800000.00, 0.00, 0.00, 1, '2029-11-14', 0, '2025-11-14 12:42:20', 1),
(157, 14, 10, 12, 10800000.00, 0.00, 0.00, 1, '2030-11-14', 0, '2025-11-14 12:42:20', 1);

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

-- --------------------------------------------------------

--
-- Table structure for table `salary_structure`
--

CREATE TABLE `salary_structure` (
  `salary_id` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `staff` int(11) NOT NULL,
  `basic_salary` decimal(12,2) NOT NULL,
  `housing_allowance` decimal(12,2) NOT NULL,
  `transport_allowance` decimal(12,2) NOT NULL,
  `utility_allowance` decimal(12,2) NOT NULL,
  `medical_allowance` decimal(12,2) NOT NULL,
  `other_allowance` decimal(12,2) NOT NULL,
  `total_earnings` decimal(12,2) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_structure`
--

INSERT INTO `salary_structure` (`salary_id`, `store`, `staff`, `basic_salary`, `housing_allowance`, `transport_allowance`, `utility_allowance`, `medical_allowance`, `other_allowance`, `total_earnings`, `created_by`, `created_at`, `updated_at`, `updated_by`) VALUES
(5, 1, 99898, 200000.00, 50000.00, 50000.00, 0.00, 30000.00, 0.00, 330000.00, 1, '2025-11-14 15:36:03', '2025-11-14 16:16:42', 1);

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
(99898, '003', 'TINUBU', 'BUHARI', 'Male', 'Mr.', '1998-04-04', 'Married', 'Christian', '08012345678', 'Lagos', 'mail#mail.com', 'PAUL', 'BRO', '08012345678', '2025-11-14', 1, 'core staff', 'senior staff', 18, 11, 0, 0, '', '', 'user.png', '', 0, 1, 0, NULL, 0, '2025-11-14 15:25:15', 1);

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
(181, 12, 'Approve Leave', 'approve_leave', 0),
(182, 12, 'End Leave', 'end_leave', 0),
(183, 5, 'Leave Reports', 'leave_report', 0),
(184, 12, 'Attendance', 'attendance', 0),
(185, 12, 'Check Out Staff', 'check_out', 0),
(186, 5, 'Attendance Report', 'attendance_report', 0),
(187, 12, 'Salary Structure', 'salary_structure', 0),
(188, 12, 'Generate Payroll', 'generate_payroll', 0),
(189, 1, 'Tax Rules', 'tax_rules', 0),
(190, 1, 'Penalty Fees', 'penalty_fees', 0),
(191, 12, 'Approve Payroll', 'approve_payroll', 0),
(192, 6, 'Monthly Payroll', 'payroll_report', 0),
(193, 11, 'Assign Fields', 'assign_field', 0),
(194, 14, 'View Fields', 'customer_field', 0),
(195, 14, 'Update Details', 'update_customer_info', 0),
(197, 4, 'Post Rent Payment', 'rent_payments', 0),
(198, 4, 'Rents Due', 'invoices_due', 0),
(199, 11, 'View General Tasks', 'view_general_tasks', 0),
(200, 4, 'Post Field Payment', 'post_field_payment', 0),
(201, 4, 'Payments Due', 'payments_due', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sucker_removal`
--

CREATE TABLE `sucker_removal` (
  `sucker_id` int(11) NOT NULL,
  `farm` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `cycle` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `crop` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `removed_by` int(11) NOT NULL,
  `date_removed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sucker_removal`
--

INSERT INTO `sucker_removal` (`sucker_id`, `farm`, `field`, `cycle`, `task_id`, `crop`, `quantity`, `trx_number`, `removed_by`, `date_removed`) VALUES
(1, 1, 8, 8, 0, 20, 40, 'TR103121125011505', 1, '2025-11-12 13:15:05'),
(2, 1, 8, 8, 0, 20, 10, 'TR426121125011551', 1, '2025-11-12 13:15:51'),
(3, 1, 8, 8, 0, 20, 12, 'TR520121125011620', 1, '2025-11-12 13:16:20'),
(4, 1, 8, 9, 0, 20, 300, 'TR216141125113818', 1, '2025-11-14 11:38:18'),
(5, 1, 8, 8, 47, 20, 10, 'TR024191125082051', 1, '2025-11-19 08:20:51');

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
(7, 99898, '2025-11-14 15:26:30', 1, 1, '2025-11-14 15:26:51', 1, 1);

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
  `task_status` int(11) NOT NULL,
  `done_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `ended_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `task_number`, `farm`, `field`, `cycle`, `title`, `description`, `workers`, `labour_cost`, `task_type`, `payment_status`, `start_date`, `end_date`, `task_status`, `done_by`, `post_date`, `updated_at`, `updated_by`, `end_time`, `ended_by`) VALUES
(34, 'TK1051125092691', 1, 8, 8, 'LAND PREPARATION', 'There Are Some Stony Part Of The Land That Can&#039;t Be Cleared.\n\nAlso No Tools', 'JAMES AWUYI, NNAMDI PETER, EMMANUEL NDUKA', 20000.00, 'Crop Cycle', 0, '2025-11-05 09:21:03', '2025-11-10 10:27:00', 1, 1, '2025-11-05 09:21:26', '2025-11-05 11:41:24', 1, '2025-11-10 10:27:50', 1),
(35, 'TK1111125020271', 1, 8, 8, 'PLANTING', 'Planting Started', 'JAMES BRAIMOH', 10000.00, 'Crop Cycle', 0, '2025-11-11 14:40:00', '2025-11-11 16:24:00', 1, 1, '2025-11-11 14:40:55', '2025-11-11 14:49:07', 1, '2025-11-11 16:24:28', 1),
(36, 'TK1111125042481', 1, 8, 8, 'FERTILIZER APPLICATION', '', 'JAMES AKPABIO', 3000.00, 'Crop Cycle', 0, '2025-11-11 16:36:00', '2025-11-11 16:37:00', 1, 1, '2025-11-11 16:36:55', NULL, 0, '2025-11-11 16:37:28', 1),
(37, 'TK1111125057561', 1, 8, 8, 'INDUCTION', '', 'FEMI BISHOP', 0.00, 'Crop Cycle', 1, '2025-11-11 17:06:00', '2025-11-11 17:07:00', 1, 1, '2025-11-11 17:06:20', NULL, 0, '2025-11-11 17:07:19', 1),
(38, 'TK1121125114521', 1, 8, 8, 'PRUNING', '', 'CLEANING FIELD', 0.00, 'Crop Cycle', 1, '2025-11-12 11:51:00', '2025-11-12 11:51:00', 1, 1, '2025-11-12 11:51:44', NULL, 0, '2025-11-12 11:51:54', 1),
(39, 'TK1121125021631', 1, 7, 0, 'SOIL TESTING', 'Testing The Soil For Issues', 'JIMOH JAMES', 5000.00, 'General Maintenance', 0, '2025-11-12 14:51:00', '2025-11-12 15:48:00', 1, 1, '2025-11-12 14:52:17', '2025-11-12 15:30:25', 1, '2025-11-12 15:48:33', 1),
(40, 'TK1141125119101', 1, 8, 9, 'LAND PREPARATION', 'Hkgjhgk', 'JOHN JAMES', 30000.00, 'Crop Cycle', 0, '2025-11-14 11:16:58', '2025-11-14 11:18:00', 1, 1, '2025-11-14 11:17:28', '2025-11-14 11:17:50', 1, '2025-11-14 11:19:12', 1),
(41, 'TK1141125115541', 1, 8, 9, 'PLANTING', '', 'BRYAN JAMES, JKLJ', 0.00, 'Crop Cycle', 1, '2025-11-14 11:19:00', '2025-11-14 11:23:00', 1, 1, '2025-11-14 11:20:22', NULL, 0, '2025-11-14 11:23:29', 1),
(42, 'TK1141125119451', 1, 8, 9, 'INDUCTION', 'Jgjhgjh', 'GRTY', 0.00, 'Crop Cycle', 1, '2025-11-14 11:26:00', '2025-11-14 11:26:00', 1, 1, '2025-11-14 11:26:16', '2025-11-14 11:26:28', 1, '2025-11-14 11:26:42', 1),
(43, 'TK1141125113331', 1, 8, 9, 'INDUCTION', '', 'GHFH', 0.00, 'Crop Cycle', 1, '2025-11-14 11:27:00', '2025-11-14 11:27:00', 1, 1, '2025-11-14 11:27:20', NULL, 0, '2025-11-14 11:27:32', 1),
(44, 'TK1141125119381', 1, 8, 9, 'PRUNING', '', 'KWEJILRJL', 0.00, 'Crop Cycle', 1, '2025-11-14 11:36:00', '2025-11-14 11:37:00', 1, 1, '2025-11-14 11:37:39', NULL, 0, '2025-11-14 11:37:49', 1),
(45, 'TK1171125012921', 1, 8, 8, 'INDUCTION', '', 'DFSAD', 0.00, 'Crop Cycle', 1, '2025-11-17 13:12:00', '2025-11-17 13:28:00', 1, 1, '2025-11-17 13:12:39', NULL, 0, '2025-11-17 13:28:45', 1),
(46, 'TK1191125073801', 1, 8, 8, 'PRUNING', '', 'ADAMS', 0.00, 'Crop Cycle', 1, '2025-11-19 07:23:00', '2025-11-19 08:02:00', 1, 1, '2025-11-19 07:23:58', NULL, 0, '2025-11-19 08:02:49', 1),
(47, 'TK1191125088211', 1, 8, 8, 'SUCKER REMOVAL', '', 'NIL', 0.00, 'Crop Cycle', 1, '2025-11-19 08:07:00', '2025-11-19 08:21:00', 1, 1, '2025-11-19 08:08:23', NULL, 0, '2025-11-19 08:21:05', 1);

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
  `invoice` varchar(50) NOT NULL,
  `item` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_items`
--

INSERT INTO `task_items` (`task_item_id`, `task_id`, `farm`, `field`, `cycle`, `invoice`, `item`, `quantity`, `unit_cost`, `total_cost`, `trx_number`, `posted_by`, `post_date`) VALUES
(22, 34, 1, 8, 8, '', 18, 2, 1000.00, 2000.00, 'TR304101125082824', 1, '2025-11-10 08:28:24'),
(23, 34, 1, 8, 8, 'IN7678101125083541', 18, 2, 1000.00, 2000.00, 'TR635101125083548', 1, '2025-11-10 08:35:48'),
(24, 34, 1, 8, 8, 'IN1068101125083826', 18, 1, 1000.00, 1000.00, 'TR315101125083833', 1, '2025-11-10 08:38:33'),
(28, 35, 1, 8, 8, 'IN0498111125031220', 20, 10, 500.00, 5000.00, 'TR944111125031228', 1, '2025-11-11 15:12:28'),
(29, 35, 1, 8, 8, 'IN6168111125041755', 20, 300, 500.00, 150000.00, 'TR705111125041802', 1, '2025-11-11 16:18:02'),
(30, 36, 1, 8, 8, 'IN7268111125043700', 18, 2, 1000.00, 2000.00, 'TR259111125043707', 1, '2025-11-11 16:37:07'),
(32, 39, 1, 7, 0, 'IN80012121125034418', 18, 2, 1000.00, 2000.00, 'TR432121125034424', 1, '2025-11-12 15:44:24'),
(33, 40, 1, 8, 9, 'IN5209141125111809', 18, 1, 1000.00, 1000.00, 'TR716141125111822', 1, '2025-11-14 11:18:22'),
(34, 41, 1, 8, 9, 'IN8499141125112036', 20, 300, 500.00, 150000.00, 'TR981141125112049', 1, '2025-11-14 11:20:49');

-- --------------------------------------------------------

--
-- Table structure for table `tax_rules`
--

CREATE TABLE `tax_rules` (
  `tax_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `min_income` decimal(12,2) NOT NULL,
  `max_income` decimal(12,2) NOT NULL,
  `tax_rate` decimal(5,2) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_rules`
--

INSERT INTO `tax_rules` (`tax_id`, `title`, `min_income`, `max_income`, `tax_rate`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'DEFAULT COMPANY TAX', 70000.00, 2000000.00, 7.50, 1, '2025-10-24 08:13:21', 1, '2025-10-25 13:33:50');

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
(115, 1, 1, 3, 1010329, 2000.000, 0.000, 'TR107291025011927', 'Harvested 20 of PINEAPPLE from OKUN-AJAH PINEAPPLE FIELD field', 0, '2025-10-29', 1, '2025-10-29 13:19:27', 1),
(116, 4, 6, 13, 40601384, 0.000, 0.000, 'TR107291025011927', 'Harvested 20 of PINEAPPLE from OKUN-AJAH PINEAPPLE FIELD field', 0, '2025-10-29', 1, '2025-10-29 13:19:27', 1),
(117, 4, 6, 13, 40601385, 0.000, 2000.000, 'TR107291025011927', 'Harvested 20 of PINEAPPLE from OKUN-AJAH PINEAPPLE FIELD field', 0, '2025-10-29', 1, '2025-10-29 13:19:27', 1),
(118, 1, 1, 3, 1010329, 3000.000, 0.000, 'TR457291025012026', 'Harvested 30 of PINEAPPLE from OKUN-AJAH PINEAPPLE FIELD field', 0, '2025-10-29', 1, '2025-10-29 13:20:26', 1),
(119, 4, 6, 13, 40601384, 0.000, 0.000, 'TR457291025012026', 'Harvested 30 of PINEAPPLE from OKUN-AJAH PINEAPPLE FIELD field', 0, '2025-10-29', 1, '2025-10-29 13:20:26', 1),
(120, 4, 6, 13, 40601385, 0.000, 3000.000, 'TR457291025012026', 'Harvested 30 of PINEAPPLE from OKUN-AJAH PINEAPPLE FIELD field', 0, '2025-10-29', 1, '2025-10-29 13:20:26', 1),
(121, 1, 1, 3, 1010329, 300000.000, 0.000, 'TR582291025013228', 'Inventory Purchase', 0, '2025-10-29', 1, '2025-10-29 13:32:28', 1),
(122, 2, 3, 7, 2030796, 0.000, 300000.000, 'TR582291025013228', 'Inventory Purchase', 0, '2025-10-29', 1, '2025-10-29 13:32:28', 1),
(123, 4, 6, 12, 40601286, 1000.000, 0.000, 'TR342291025014028', 'Item used for task', 0, '2025-10-29', 1, '2025-10-29 13:40:28', 1),
(124, 1, 1, 3, 1010329, 0.000, 1000.000, 'TR342291025014028', 'Item used for task', 0, '2025-10-29', 1, '2025-10-29 13:40:28', 1),
(141, 4, 6, 13, 40601384, 2000.000, 0.000, 'TR304101125082824', 'Item used for task', 0, '2025-11-10', 1, '2025-11-10 08:28:24', 1),
(142, 1, 1, 3, 1010329, 0.000, 2000.000, 'TR304101125082824', 'Item used for task', 0, '2025-11-10', 1, '2025-11-10 08:28:24', 1),
(143, 4, 6, 13, 40601384, 2000.000, 0.000, 'TR635101125083548', 'Item used for task', 0, '2025-11-10', 1, '2025-11-10 08:35:48', 1),
(144, 1, 1, 3, 1010329, 0.000, 2000.000, 'TR635101125083548', 'Item used for task', 0, '2025-11-10', 1, '2025-11-10 08:35:48', 1),
(145, 4, 6, 13, 40601384, 1000.000, 0.000, 'TR315101125083833', 'Item used for task', 0, '2025-11-10', 1, '2025-11-10 08:38:33', 1),
(146, 1, 1, 3, 1010329, 0.000, 1000.000, 'TR315101125083833', 'Item used for task', 0, '2025-11-10', 1, '2025-11-10 08:38:33', 1),
(153, 4, 6, 13, 40601384, 5000.000, 0.000, 'TR944111125031228', 'Item used for task', 0, '2025-11-11', 1, '2025-11-11 15:12:28', 1),
(154, 1, 1, 3, 1010329, 0.000, 5000.000, 'TR944111125031228', 'Item used for task', 0, '2025-11-11', 1, '2025-11-11 15:12:28', 1),
(155, 4, 6, 13, 40601384, 150000.000, 0.000, 'TR705111125041802', 'Item used for task', 0, '2025-11-11', 1, '2025-11-11 16:18:02', 1),
(156, 1, 1, 3, 1010329, 0.000, 150000.000, 'TR705111125041802', 'Item used for task', 0, '2025-11-11', 1, '2025-11-11 16:18:02', 1),
(157, 4, 6, 13, 40601384, 2000.000, 0.000, 'TR259111125043707', 'Item used for task', 0, '2025-11-11', 1, '2025-11-11 16:37:07', 1),
(158, 1, 1, 3, 1010329, 0.000, 2000.000, 'TR259111125043707', 'Item used for task', 0, '2025-11-11', 1, '2025-11-11 16:37:07', 1),
(159, 1, 1, 3, 1010329, 157258.065, 0.000, 'TR734111125084413', 'Harvested 250 of Pineapples from LEKKIE GARDEN field', 0, '2025-11-11', 1, '2025-11-11 20:44:13', 1),
(160, 4, 6, 13, 40601384, 0.000, 130645.161, 'TR734111125084413', 'Harvested 250 of Pineapples from LEKKIE GARDEN field', 0, '2025-11-11', 1, '2025-11-11 20:44:13', 1),
(161, 4, 6, 13, 40601385, 0.000, 26612.903, 'TR734111125084413', 'Harvested 250 of Pineapples from LEKKIE GARDEN field', 0, '2025-11-11', 1, '2025-11-11 20:44:13', 1),
(162, 1, 1, 3, 1010329, 18870.968, 0.000, 'TR259111125084641', 'Harvested 30 of Pineapples from LEKKIE GARDEN field', 0, '2025-11-11', 1, '2025-11-11 20:46:41', 1),
(163, 4, 6, 13, 40601384, 0.000, 15677.419, 'TR259111125084641', 'Harvested 30 of Pineapples from LEKKIE GARDEN field', 0, '2025-11-11', 1, '2025-11-11 20:46:41', 1),
(164, 4, 6, 13, 40601385, 0.000, 3193.548, 'TR259111125084641', 'Harvested 30 of Pineapples from LEKKIE GARDEN field', 0, '2025-11-11', 1, '2025-11-11 20:46:41', 1),
(165, 1, 1, 3, 1010329, 12580.645, 0.000, 'TR738111125084802', 'Harvested 20 of Pineapples from LEKKIE GARDEN field', 0, '2025-11-11', 1, '2025-11-11 20:48:02', 1),
(166, 4, 6, 13, 40601384, 0.000, 10451.613, 'TR738111125084802', 'Harvested 20 of Pineapples from LEKKIE GARDEN field', 0, '2025-11-11', 1, '2025-11-11 20:48:02', 1),
(167, 4, 6, 13, 40601385, 0.000, 2129.032, 'TR738111125084802', 'Harvested 20 of Pineapples from LEKKIE GARDEN field', 0, '2025-11-11', 1, '2025-11-11 20:48:02', 1),
(168, 1, 1, 3, 1010329, 20000.000, 0.000, 'TR103121125011505', 'Suckers removed from LEKKIE GARDEN', 0, '2025-11-12', 1, '2025-11-12 13:15:05', 1),
(169, 4, 6, 13, 40601384, 0.000, 20000.000, 'TR103121125011505', 'Suckers removed from LEKKIE GARDEN', 0, '2025-11-12', 1, '2025-11-12 13:15:05', 1),
(170, 1, 1, 3, 1010329, 5000.000, 0.000, 'TR426121125011551', 'Suckers removed from LEKKIE GARDEN', 0, '2025-11-12', 1, '2025-11-12 13:15:51', 1),
(171, 4, 6, 13, 40601384, 0.000, 5000.000, 'TR426121125011551', 'Suckers removed from LEKKIE GARDEN', 0, '2025-11-12', 1, '2025-11-12 13:15:51', 1),
(172, 1, 1, 3, 1010329, 6000.000, 0.000, 'TR520121125011620', 'Suckers removed from LEKKIE GARDEN', 0, '2025-11-12', 1, '2025-11-12 13:16:20', 1),
(173, 4, 6, 13, 40601384, 0.000, 6000.000, 'TR520121125011620', 'Suckers removed from LEKKIE GARDEN', 0, '2025-11-12', 1, '2025-11-12 13:16:20', 1),
(176, 4, 6, 12, 40601286, 2000.000, 0.000, 'TR432121125034424', 'Item used for task', 0, '2025-11-12', 1, '2025-11-12 15:44:24', 1),
(177, 1, 1, 3, 1010329, 0.000, 2000.000, 'TR432121125034424', 'Item used for task', 0, '2025-11-12', 1, '2025-11-12 15:44:24', 1),
(178, 1, 1, 2, 1010228, 5000000.000, 0.000, 'TR542131125051847', 'Field Purchase payment', 0, '2025-11-13', 1, '2025-11-13 17:18:47', 1),
(179, 1, 1, 4, 1010498, 0.000, 5000000.000, 'TR542131125051847', 'Field Purchase Payment', 0, '2025-11-13', 1, '2025-11-13 17:18:47', 1),
(180, 1, 1, 2, 1010228, 4000.000, 0.000, 'TR773131125062428', 'Field Purchase payment', 0, '2025-11-13', 1, '2025-11-13 18:24:28', 1),
(181, 1, 1, 4, 1010498, 0.000, 4000.000, 'TR773131125062428', 'Field Purchase Payment', 0, '2025-11-13', 1, '2025-11-13 18:24:28', 1),
(182, 1, 1, 2, 1010228, 30000.000, 0.000, 'TR239131125083233', 'Field Purchase payment', 0, '2025-11-13', 1, '2025-11-13 20:32:33', 1),
(183, 1, 1, 4, 1010498, 0.000, 30000.000, 'TR239131125083233', 'Field Purchase Payment', 0, '2025-11-13', 1, '2025-11-13 20:32:33', 1),
(184, 1, 1, 2, 1010228, 3000000.000, 0.000, 'TR376131125083544', 'Field Purchase payment', 0, '2025-11-13', 1, '2025-11-13 20:35:44', 1),
(185, 1, 1, 4, 1010498, 0.000, 3000000.000, 'TR376131125083544', 'Field Purchase Payment', 0, '2025-11-13', 1, '2025-11-13 20:35:44', 1),
(186, 1, 1, 2, 1010228, 3000000.000, 0.000, 'TR436131125083904', 'Field Purchase payment', 0, '2025-11-13', 1, '2025-11-13 20:39:04', 1),
(187, 1, 1, 4, 1010498, 0.000, 3000000.000, 'TR436131125083904', 'Field Purchase Payment', 0, '2025-11-13', 1, '2025-11-13 20:39:04', 1),
(188, 1, 1, 2, 1010228, 300000.000, 0.000, 'TR625131125084605', 'Field Purchase payment', 0, '2025-11-13', 1, '2025-11-13 20:46:05', 1),
(189, 1, 1, 4, 1010498, 0.000, 300000.000, 'TR625131125084605', 'Field Purchase Payment', 0, '2025-11-13', 1, '2025-11-13 20:46:05', 1),
(190, 1, 1, 2, 1010228, 666000.000, 0.000, 'TR045131125085317', 'Field Purchase payment', 0, '2025-11-13', 1, '2025-11-13 20:53:17', 1),
(191, 1, 1, 4, 1010498, 0.000, 666000.000, 'TR045131125085317', 'Field Purchase Payment', 0, '2025-11-13', 1, '2025-11-13 20:53:17', 1),
(192, 4, 6, 13, 40601384, 1000.000, 0.000, 'TR716141125111822', 'Item used for task', 0, '2025-11-14', 1, '2025-11-14 11:18:22', 1),
(193, 1, 1, 3, 1010329, 0.000, 1000.000, 'TR716141125111822', 'Item used for task', 0, '2025-11-14', 1, '2025-11-14 11:18:22', 1),
(194, 4, 6, 13, 40601384, 150000.000, 0.000, 'TR981141125112049', 'Item used for task', 0, '2025-11-14', 1, '2025-11-14 11:20:49', 1),
(195, 1, 1, 3, 1010329, 0.000, 150000.000, 'TR981141125112049', 'Item used for task', 0, '2025-11-14', 1, '2025-11-14 11:20:49', 1),
(196, 1, 1, 3, 1010329, 24133.333, 0.000, 'TR235141125113427', 'Harvested 40 of Pineapples from LEKKIE GARDEN field', 0, '2025-11-14', 1, '2025-11-14 11:34:27', 1),
(197, 4, 6, 13, 40601384, 0.000, 20133.333, 'TR235141125113427', 'Harvested 40 of Pineapples from LEKKIE GARDEN field', 0, '2025-11-14', 1, '2025-11-14 11:34:27', 1),
(198, 4, 6, 13, 40601385, 0.000, 4000.000, 'TR235141125113427', 'Harvested 40 of Pineapples from LEKKIE GARDEN field', 0, '2025-11-14', 1, '2025-11-14 11:34:27', 1),
(199, 1, 1, 3, 1010329, 150000.000, 0.000, 'TR216141125113818', 'Suckers removed from LEKKIE GARDEN', 0, '2025-11-14', 1, '2025-11-14 11:38:18', 1),
(200, 4, 6, 13, 40601384, 0.000, 150000.000, 'TR216141125113818', 'Suckers removed from LEKKIE GARDEN', 0, '2025-11-14', 1, '2025-11-14 11:38:18', 1),
(201, 1, 1, 2, 1010228, 50000.000, 0.000, 'TR224141125123710', 'Field Purchase payment', 0, '2025-11-14', 1, '2025-11-14 12:37:10', 1),
(202, 1, 1, 4, 10104100, 0.000, 50000.000, 'TR224141125123710', 'Field Purchase Payment', 0, '2025-11-14', 1, '2025-11-14 12:37:10', 1),
(203, 1, 1, 2, 1010228, 20000000.000, 0.000, 'TR551141125124220', 'Field Purchase payment', 0, '2025-11-14', 1, '2025-11-14 12:42:20', 1),
(204, 1, 1, 4, 10104100, 0.000, 20000000.000, 'TR551141125124220', 'Field Purchase Payment', 0, '2025-11-14', 1, '2025-11-14 12:42:20', 1),
(205, 1, 1, 3, 1010329, 0.000, 0.000, 'TR426191125075004', 'Leaves removed from LEKKIE GARDEN', 0, '2025-11-19', 1, '2025-11-19 07:50:04', 1),
(206, 4, 6, 13, 40601384, 0.000, 0.000, 'TR426191125075004', 'Leaves removed from LEKKIE GARDEN', 0, '2025-11-19', 1, '2025-11-19 07:50:04', 1),
(207, 1, 1, 3, 1010329, 5000.000, 0.000, 'TR024191125082051', 'Suckers removed from LEKKIE GARDEN', 0, '2025-11-19', 1, '2025-11-19 08:20:51', 1),
(208, 4, 6, 13, 40601384, 0.000, 5000.000, 'TR024191125082051', 'Suckers removed from LEKKIE GARDEN', 0, '2025-11-19', 1, '2025-11-19 08:20:51', 1);

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
  `reg_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `username`, `user_role`, `user_password`, `status`, `store`, `reg_date`) VALUES
(1, 'Administrator', 'Sysadmin', 'Admin', '$2y$10$dcUrnR/.PvfK7XeYcP60hOyW2qnPSSvEq/Wxee6lv5DETW8pbGXYu', 0, 1, '2022-09-27 13:47:21');

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
(2, 'AXXON MOBIL', 'James Kano', '09087766788', '', 'ajah', 0, 2030787, 87, '2025-10-08 07:45:59', 1),
(3, 'AXY LIMITED', 'Jhohn', '0989789', '', 'bjbhg', 0, 2030796, 96, '2025-10-29 13:29:26', 1);

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
-- Dumping data for table `waybills`
--

INSERT INTO `waybills` (`waybill_id`, `invoice`, `vendor`, `invoice_amount`, `waybill`, `trx_number`, `store`, `post_date`, `posted_by`) VALUES
(8, '897897ioui', 3, 300000.00, 0.00, 'TR582291025013228', 1, '2025-10-29 13:30:36', 1),
(9, '098ojkl', 1, 1500000.00, 0.00, '', 1, '2025-11-10 07:01:52', 1);

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
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`);

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
-- Indexes for table `crop_removal`
--
ALTER TABLE `crop_removal`
  ADD PRIMARY KEY (`removal_id`);

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
-- Indexes for table `field_payments`
--
ALTER TABLE `field_payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `field_payment_schedule`
--
ALTER TABLE `field_payment_schedule`
  ADD PRIMARY KEY (`repayment_id`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

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
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`payroll_id`);

--
-- Indexes for table `penalty_fees`
--
ALTER TABLE `penalty_fees`
  ADD PRIMARY KEY (`penalty_id`);

--
-- Indexes for table `pensions`
--
ALTER TABLE `pensions`
  ADD PRIMARY KEY (`pension_id`);

--
-- Indexes for table `production`
--
ALTER TABLE `production`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `pruning`
--
ALTER TABLE `pruning`
  ADD PRIMARY KEY (`sucker_id`);

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
-- Indexes for table `rent_payments`
--
ALTER TABLE `rent_payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `rent_schedule`
--
ALTER TABLE `rent_schedule`
  ADD PRIMARY KEY (`repayment_id`);

--
-- Indexes for table `rights`
--
ALTER TABLE `rights`
  ADD PRIMARY KEY (`right_id`);

--
-- Indexes for table `salary_structure`
--
ALTER TABLE `salary_structure`
  ADD PRIMARY KEY (`salary_id`);

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
-- Indexes for table `sucker_removal`
--
ALTER TABLE `sucker_removal`
  ADD PRIMARY KEY (`sucker_id`);

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
-- Indexes for table `tax_rules`
--
ALTER TABLE `tax_rules`
  ADD PRIMARY KEY (`tax_id`);

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
  MODIFY `accept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `assigned_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `bank_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_flows`
--
ALTER TABLE `cash_flows`
  MODIFY `fow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

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
  MODIFY `cycle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `crop_removal`
--
ALTER TABLE `crop_removal`
  MODIFY `removal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `customer_trail`
--
ALTER TABLE `customer_trail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `cycle_changes`
--
ALTER TABLE `cycle_changes`
  MODIFY `change_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `deposit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  MODIFY `discipline_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
  MODIFY `field_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `field_payments`
--
ALTER TABLE `field_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `field_payment_schedule`
--
ALTER TABLE `field_payment_schedule`
  MODIFY `repayment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `finance_cost`
--
ALTER TABLE `finance_cost`
  MODIFY `finance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `harvests`
--
ALTER TABLE `harvests`
  MODIFY `harvest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `issue_items`
--
ALTER TABLE `issue_items`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
  MODIFY `leaves_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ledgers`
--
ALTER TABLE `ledgers`
  MODIFY `ledger_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `multiple_payments`
--
ALTER TABLE `multiple_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `observations`
--
ALTER TABLE `observations`
  MODIFY `observation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `payroll_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `penalty_fees`
--
ALTER TABLE `penalty_fees`
  MODIFY `penalty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pensions`
--
ALTER TABLE `pensions`
  MODIFY `pension_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `production`
--
ALTER TABLE `production`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pruning`
--
ALTER TABLE `pruning`
  MODIFY `sucker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
-- AUTO_INCREMENT for table `rent_payments`
--
ALTER TABLE `rent_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `rent_schedule`
--
ALTER TABLE `rent_schedule`
  MODIFY `repayment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `rights`
--
ALTER TABLE `rights`
  MODIFY `right_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT for table `salary_structure`
--
ALTER TABLE `salary_structure`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99899;

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
  MODIFY `sub_menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `sucker_removal`
--
ALTER TABLE `sucker_removal`
  MODIFY `sucker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `suspensions`
--
ALTER TABLE `suspensions`
  MODIFY `suspension_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `task_items`
--
ALTER TABLE `task_items`
  MODIFY `task_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `tax_rules`
--
ALTER TABLE `tax_rules`
  MODIFY `tax_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `transfer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `waybills`
--
ALTER TABLE `waybills`
  MODIFY `waybill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
