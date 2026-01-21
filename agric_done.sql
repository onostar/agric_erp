-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2026 at 04:09 PM
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
  `asset_no` varchar(100) NOT NULL,
  `location` int(11) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `cost` decimal(12,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` float NOT NULL,
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
  `posted_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`asset_id`, `asset`, `asset_no`, `location`, `supplier`, `cost`, `quantity`, `size`, `accum_dep`, `useful_life`, `salvage_value`, `book_value`, `ledger`, `specification`, `asset_status`, `purchase_date`, `deployment_date`, `store`, `post_date`, `posted_by`, `updated_by`, `updated_at`) VALUES
(7, 'OBITUGBO LAND', 'DAVIDORLAH/Dav/2024/07/7', 1, 'OBOJO', 50000000.00, 1, 50, 0.00, 10, 2000000.00, 50000000.00, 1020524, 'Land', 0, '2024-07-30', '2025-12-17', 0, '2026-01-20 15:31:36', 1, 0, NULL);

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
  `field_size` decimal(10,2) NOT NULL,
  `latitude` decimal(12,9) NOT NULL,
  `longitude` decimal(12,9) NOT NULL,
  `contract_duration` int(11) NOT NULL,
  `payment_duration` float NOT NULL,
  `rent_percentage` decimal(12,2) NOT NULL,
  `annual_rent` decimal(12,2) NOT NULL,
  `installment` decimal(12,2) NOT NULL,
  `purchase_cost` decimal(12,2) NOT NULL,
  `discount` decimal(12,2) NOT NULL,
  `total_due` decimal(12,2) NOT NULL,
  `documentation` decimal(12,2) NOT NULL,
  `documentation_status` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `contract_status` int(11) NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `assigned_date` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assigned_fields`
--

INSERT INTO `assigned_fields` (`assigned_id`, `field`, `customer`, `field_size`, `latitude`, `longitude`, `contract_duration`, `payment_duration`, `rent_percentage`, `annual_rent`, `installment`, `purchase_cost`, `discount`, `total_due`, `documentation`, `documentation_status`, `start_date`, `due_date`, `contract_status`, `assigned_by`, `assigned_date`, `updated_by`, `updated_at`) VALUES
(26, 18, 15, 4.00, 0.000000000, 0.000000000, 3, 1, 36.00, 1080000.00, 3000000.00, 3000000.00, 0.00, 3000000.00, 350000.00, 1, '2026-01-20', '2026-01-20', 2, 1, '2026-01-21 15:59:35', 0, NULL);

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
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(10,8) NOT NULL,
  `accuracy` int(11) NOT NULL,
  `ip_address` varchar(55) NOT NULL,
  `location` varchar(255) NOT NULL,
  `remark` text NOT NULL,
  `flagged` int(11) NOT NULL,
  `marked_by` int(11) NOT NULL,
  `marked_date` datetime DEFAULT NULL,
  `checked_out_by` int(11) NOT NULL,
  `checked_out` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `staff`, `store`, `attendance_date`, `time_in`, `time_out`, `attendance_status`, `latitude`, `longitude`, `accuracy`, `ip_address`, `location`, `remark`, `flagged`, `marked_by`, `marked_date`, `checked_out_by`, `checked_out`) VALUES
(35, 99910, 1, '2026-01-20', '08:53:00', NULL, 0, 0.00000000, 0.00000000, 0, '', 'Head Office', '', 0, 1, '2026-01-20 11:53:03', 0, NULL),
(36, 99910, 1, '2026-01-21', '14:57:19', NULL, 0, 6.42854400, 3.58896600, 178, '127.0.0.1', 'Okun-Ajah, Eti Osa, Lagos, 101245, Nigeria', '', 0, 34, '2026-01-21 14:57:19', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `attendance_flagging`
--

CREATE TABLE `attendance_flagging` (
  `attendance_id` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `staff` int(11) NOT NULL,
  `attendance_date` date DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `latitude` decimal(12,9) NOT NULL,
  `longitude` decimal(12,9) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `location` varchar(255) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `flagged_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance_flagging`
--

INSERT INTO `attendance_flagging` (`attendance_id`, `store`, `staff`, `attendance_date`, `time_in`, `latitude`, `longitude`, `ip_address`, `location`, `post_date`, `flagged_by`) VALUES
(2, 1, 99910, '2026-01-20', '11:48:09', 6.438334610, 3.513942260, '127.0.0.1', 'Lekki-Epe Expressway, Ikota, Jakande, Eti Osa, Lagos, 101001, Nigeria', '2026-01-20 11:49:00', 1);

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
(180, 1, 29, 'purchase', 0, 30, 1, '2026-01-20 13:14:56'),
(181, 1, 30, 'purchase', 0, 5, 1, '2026-01-20 13:18:21'),
(182, 1, 30, 'purchase', 5, 2, 1, '2026-01-20 13:20:33'),
(183, 1, 30, 'issue', 7, 3, 1, '2026-01-20 13:39:26');

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
-- Table structure for table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `beneficiary_id` int(11) NOT NULL,
  `staff` int(11) NOT NULL,
  `beneficiary` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `entitlement` decimal(5,2) NOT NULL,
  `relation` varchar(50) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `date_added` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `briquette_production`
--

CREATE TABLE `briquette_production` (
  `production_id` int(11) NOT NULL,
  `production_num` varchar(50) NOT NULL,
  `store` int(11) NOT NULL,
  `briquette` decimal(12,2) NOT NULL,
  `leaves` decimal(12,2) NOT NULL,
  `pineapple_crown` decimal(12,2) NOT NULL,
  `pineapple_peel` decimal(12,2) NOT NULL,
  `leave_cost` decimal(12,2) NOT NULL,
  `pineapple_crown_cost` decimal(12,2) NOT NULL,
  `pineapple_peel_cost` decimal(12,2) NOT NULL,
  `total_leave_cost` decimal(12,2) NOT NULL,
  `total_crown_cost` decimal(12,2) NOT NULL,
  `total_peel_cost` decimal(12,2) NOT NULL,
  `trx_number` decimal(50,0) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `date_produced` datetime DEFAULT NULL
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
(85, 1010228, 0, 'Investment Payment', 'TR415200126122752', 0.00, 'inflow', 'financing', 1, '2026-01-20 12:27:52', 1),
(86, 1010228, 0, 'Field Purchase Payment', 'TR241210126055927', 2000000.00, 'inflow', 'investing', 1, '2026-01-21 05:59:27', 1),
(87, 1010228, 0, 'Field Purchase Payment', 'TR923210126061150', 1000000.00, 'inflow', 'investing', 1, '2026-01-21 06:11:50', 1),
(88, 1010228, 0, 'Field Documentation Payment', 'TR526210126061436', 350000.00, 'inflow', 'operating', 1, '2026-01-21 06:14:36', 1),
(89, 1010228, 0, 'Loan Repayment', 'TR621210126064747', 750000.00, 'outflow', 'operating', 1, '2026-01-21 06:47:47', 1);

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
(37, '12', 'PRODUCT', 0),
(40, '1', 'FARM INPUT', 0);

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
(1, 'Davidorlah Nigeria Limited', 'davidorlah.png', 1500000, '2025-08-28 14:46:39');

-- --------------------------------------------------------

--
-- Table structure for table `concentrate_production`
--

CREATE TABLE `concentrate_production` (
  `production_id` int(11) NOT NULL,
  `production_num` varchar(50) NOT NULL,
  `store` int(11) NOT NULL,
  `pineapple` decimal(12,2) NOT NULL,
  `concentrate` decimal(12,2) NOT NULL,
  `pineapple_crown` decimal(12,2) NOT NULL,
  `pineapple_peel` decimal(12,2) NOT NULL,
  `pineapple_cost` decimal(12,2) NOT NULL,
  `pineapple_crown_value` decimal(12,2) NOT NULL,
  `pineapple_peel_value` decimal(12,2) NOT NULL,
  `total_pineapple_cost` decimal(12,2) NOT NULL,
  `total_crown_value` decimal(12,2) NOT NULL,
  `total_peel_value` decimal(12,2) NOT NULL,
  `others` decimal(12,2) NOT NULL,
  `trx_number` decimal(50,0) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `date_produced` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(15, 'KELLY IKPEFUA', 106, 10104106, 'Investor', '$2y$10$WDTnvDQxMSXCpr.7z87AfeysAchTpZH2pM5c7IGF3ttjOBOCBCS22', '07068897068', 'Benin', 'onostarkels@gmail.com', 0, 0, '2026-01-19 11:50:21', 1, NULL),
(16, 'MERCY', 107, 10104107, 'Investor', '$2y$10$lz1E2RrKf88KB1DX/RTcLOQ7UACq2dN7zJ4t7/unCmOyJE0x.pugS', '0900', 'Lagos', 'mercyoteje97@gmail.com', 0, 0, '2026-01-20 12:05:58', 1, NULL);

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
(76, 15, '', 'Investment Payment', 1000.00, 'TR415200126122752', 1, 1, '2026-01-20 12:27:52'),
(77, 15, '', 'Field Purchase Payment', 2000000.00, 'TR241210126055927', 1, 1, '2026-01-21 05:59:27'),
(78, 15, '', 'Field Purchase Payment', 1000000.00, 'TR923210126061150', 1, 1, '2026-01-21 06:11:50'),
(79, 15, '', 'Field Documentation Payment', 350000.00, 'TR526210126061436', 1, 1, '2026-01-21 06:14:36'),
(80, 15, '', 'Loan Repayment', 750000.00, 'TR621210126064747', 1, 1, '2026-01-21 06:47:47'),
(81, 15, '', 'Field purchase payment', 3000000.00, 'TR774210126110006', 1, 1, '2026-01-21 11:00:06'),
(82, 15, '', 'Field purchase payment', 4000000.00, 'TR236210126112522', 1, 1, '2026-01-21 11:25:21'),
(83, 15, '', 'Field purchase payment', 3000000.00, 'TR985210126112842', 1, 1, '2026-01-21 11:28:42'),
(84, 15, '', 'Field purchase payment', 3000000.00, 'TR388210126035936', 1, 1, '2026-01-21 15:59:35');

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
(78, 1, 15, 2000.00, 'Cash', 0, 'TR386190126115254', 'TR386190126115254', 'Investment Payment', 'Investment payment', '2026-01-05', '2026-01-19 11:52:54', 1),
(79, 1, 15, 2000.00, 'Cash', 0, 'TR960190126122238', 'TR960190126122238', 'Investment Payment', 'Investment payment', '2026-01-14', '2026-01-19 12:22:38', 1),
(80, 1, 16, 2000.00, 'Cash', 0, 'TR829200126120638', 'TR829200126120638', 'Investment Payment', 'Investment payment', '2026-01-19', '2026-01-20 12:06:38', 1),
(81, 1, 16, 2000.00, 'Cash', 0, 'TR649200126120826', 'TR649200126120826', 'Investment Payment', 'Investment payment', '2026-01-12', '2026-01-20 12:08:26', 1),
(82, 1, 15, 1000.00, 'Cash', 0, 'INV2001261227011123', 'TR415200126122752', 'Investment Payment', 'Investment payment', '2026-01-20', '2026-01-20 12:27:52', 1),
(83, 1, 15, 2000000.00, 'Cash', 0, 'LP2101260558112011168', 'TR241210126055927', 'Field Purchase Payment', 'Field purchase payment', '2026-01-21', '2026-01-21 05:59:27', 1),
(84, 1, 15, 1000000.00, 'Cash', 0, 'LP2101260611223311167', 'TR923210126061150', 'Field Purchase Payment', 'Field purchase payment', '2026-01-21', '2026-01-21 06:11:50', 1),
(85, 1, 15, 350000.00, 'Cash', 0, 'LP210126061400002118', 'TR526210126061436', 'Field Documentation Payment', 'Documentation payment', '2026-01-21', '2026-01-21 06:14:36', 1),
(86, 1, 15, 750000.00, 'Cash', 0, 'LP2101260646102331171', 'TR621210126064747', 'Loan Repayment', 'Rent Payment', '2026-01-21', '2026-01-21 06:47:47', 1),
(87, 1, 15, 3000000.00, 'Cash', 0, 'TR774210126110006', 'TR774210126110006', 'Field purchase payment', 'Field purchase payment', '2023-12-21', '2026-01-21 11:00:06', 1),
(88, 1, 15, 4000000.00, 'Cash', 0, 'TR236210126112522', 'TR236210126112522', 'Field purchase payment', 'Field purchase payment', '2023-01-21', '2026-01-21 11:25:21', 1),
(89, 1, 15, 3000000.00, 'Cash', 0, 'TR985210126112842', 'TR985210126112842', 'Field purchase payment', 'Field purchase payment', '2024-12-11', '2026-01-21 11:28:42', 1),
(90, 1, 15, 3000000.00, 'Cash', 0, 'TR388210126035936', 'TR388210126035936', 'Field purchase payment', 'Field purchase payment', '2026-01-20', '2026-01-21 15:59:35', 1);

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
  `store` int(11) NOT NULL,
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
(19, 'LABOURER'),
(20, 'HR MANAGER');

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
  `store` int(11) NOT NULL,
  `disposed_date` datetime DEFAULT NULL,
  `disposed_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documentation_fees`
--

CREATE TABLE `documentation_fees` (
  `payment_id` int(11) NOT NULL,
  `assigned_id` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `client` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_mode` varchar(50) NOT NULL,
  `bank` int(11) NOT NULL,
  `trx_date` date DEFAULT NULL,
  `trx_number` varchar(50) NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `store` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documentation_fees`
--

INSERT INTO `documentation_fees` (`payment_id`, `assigned_id`, `field`, `client`, `amount`, `payment_mode`, `bank`, `trx_date`, `trx_number`, `invoice`, `store`, `post_date`, `posted_by`) VALUES
(8, 18, 18, 15, 350000.00, 'Cash', 0, '2026-01-21', 'TR526210126061436', 'LP210126061400002118', 1, '2026-01-21 06:14:36', 1),
(9, 26, 18, 15, 350000.00, '', 0, '2026-01-21', 'TR388210126035936', 'TR388210126035936', 1, '2026-01-21 15:59:35', 1);

-- --------------------------------------------------------

--
-- Table structure for table `document_uploads`
--

CREATE TABLE `document_uploads` (
  `document_id` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `doc_type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `document` varchar(1024) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `upload_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exchange_rates`
--

CREATE TABLE `exchange_rates` (
  `exchange_id` int(11) NOT NULL,
  `rate` decimal(12,2) NOT NULL,
  `added_at` datetime DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL
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
(19, 'PRODUCTION INPUT', 89, 40601389),
(20, 'SALARY AND WAGES', 105, 406012105);

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

CREATE TABLE `fields` (
  `field_id` int(11) NOT NULL,
  `farm` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `customer` int(11) NOT NULL,
  `field_size` decimal(10,2) NOT NULL,
  `soil_type` varchar(255) NOT NULL,
  `soil_ph` decimal(10,2) NOT NULL,
  `topography` varchar(1024) NOT NULL,
  `location` varchar(1024) NOT NULL,
  `latitude` decimal(12,9) NOT NULL,
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

INSERT INTO `fields` (`field_id`, `farm`, `asset_id`, `field_name`, `customer`, `field_size`, `soil_type`, `soil_ph`, `topography`, `location`, `latitude`, `longitude`, `purchase_cost`, `field_status`, `created_at`, `created_by`, `updated_by`, `updated_at`) VALUES
(18, 1, 7, 'OBITUGBO LAND', 0, 50.00, 'alluvial', 0.00, '', 'obitugbo', 0.000000000, 0.000000000, 50000000.00, 0, '2026-01-20 15:31:36', 1, 1, '2026-01-20 15:41:34');

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
  `trx_date` date DEFAULT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `trx_number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `field_payments`
--

INSERT INTO `field_payments` (`payment_id`, `customer`, `store`, `loan`, `schedule`, `amount`, `interest`, `processing_fee`, `penalty`, `payment_mode`, `details`, `invoice`, `bank`, `trx_date`, `posted_by`, `post_date`, `trx_number`) VALUES
(32, 15, 1, 23, 173, 3000000, 0, 0, 0.00, 'Cash', 'Field Purchase Payment', 'TR774210126110006', 0, '2023-12-21', 1, '2026-01-21 11:00:06', 'TR774210126110006'),
(33, 15, 1, 24, 174, 4000000, 0, 0, 0.00, 'Cash', 'Field Purchase Payment', 'TR236210126112522', 0, '2023-01-21', 1, '2026-01-21 11:25:21', 'TR236210126112522'),
(34, 15, 1, 25, 175, 3000000, 0, 0, 0.00, 'Cash', 'Field Purchase Payment', 'TR985210126112842', 0, '2024-12-11', 1, '2026-01-21 11:28:42', 'TR985210126112842'),
(35, 15, 1, 26, 176, 3000000, 0, 0, 0.00, 'Cash', 'Field Purchase Payment', 'TR388210126035936', 0, '2026-01-20', 1, '2026-01-21 15:59:35', 'TR388210126035936');

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
(173, 23, 18, 15, 3000000.00, 3000000.00, 0.00, 1, '2023-12-21', 1, '2026-01-21 11:00:06', 1),
(174, 24, 18, 15, 4000000.00, 4000000.00, 0.00, 1, '2023-01-21', 1, '2026-01-21 11:25:21', 1),
(175, 25, 18, 15, 5000000.00, 3000000.00, 0.00, 1, '2024-12-11', 0, '2026-01-21 11:28:42', 1),
(176, 26, 18, 15, 3000000.00, 3000000.00, 0.00, 1, '2026-01-20', 1, '2026-01-21 15:59:35', 1);

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
  `quantity` decimal(12,2) NOT NULL,
  `batch_number` int(11) NOT NULL,
  `expiration_date` date DEFAULT NULL,
  `reorder_level` float NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `item`, `item_type`, `store`, `cost_price`, `quantity`, `batch_number`, `expiration_date`, `reorder_level`, `post_date`) VALUES
(30, 29, 'Consumable', 1, 10000, 30.00, 0, NULL, 10, NULL),
(31, 30, 'Consumable', 1, 2500, 4.00, 0, NULL, 10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `investment_id` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `currency` varchar(50) NOT NULL,
  `units` float NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `total_in_dollar` decimal(12,2) NOT NULL,
  `exchange_rate` decimal(12,2) NOT NULL,
  `contract_status` int(11) NOT NULL,
  `principal` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `modified_by` int(11) NOT NULL,
  `date_modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investments`
--

INSERT INTO `investments` (`investment_id`, `customer`, `store`, `duration`, `currency`, `units`, `amount`, `total_in_dollar`, `exchange_rate`, `contract_status`, `principal`, `posted_by`, `post_date`, `start_date`, `modified_by`, `date_modified`) VALUES
(22, 16, 1, 3, 'Dollar', 1, 2000.00, 2000.00, 0.00, 1, 0, 1, '2026-01-20 12:08:26', '2026-01-12 00:00:00', 0, NULL),
(23, 15, 1, 3, 'Dollar', 0.5, 1000.00, 1000.00, 0.00, 1, 0, 1, '2026-01-20 12:09:07', '2025-12-18 12:27:52', 1, '2026-01-20 12:42:10');

-- --------------------------------------------------------

--
-- Table structure for table `investment_payments`
--

CREATE TABLE `investment_payments` (
  `payment_id` int(11) NOT NULL,
  `investment` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `payment_mode` varchar(50) NOT NULL,
  `bank` int(11) NOT NULL,
  `currency` varchar(50) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `amount_in_dollar` decimal(12,2) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `trx_date` date DEFAULT NULL,
  `store` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investment_payments`
--

INSERT INTO `investment_payments` (`payment_id`, `investment`, `customer`, `invoice`, `payment_mode`, `bank`, `currency`, `amount`, `amount_in_dollar`, `trx_number`, `trx_date`, `store`, `posted_by`, `post_date`) VALUES
(19, 22, 16, 'TR649200126120826', 'Cash', 0, 'Dollar', 2000.00, 2000.00, 'TR649200126120826', '2026-01-12', 1, 1, '2026-01-20 12:08:26'),
(20, 23, 15, 'INV2001261227011123', 'Cash', 0, 'Dollar', 1000.00, 1000.00, 'TR415200126122752', '2026-01-20', 1, 1, '2026-01-20 12:27:52');

-- --------------------------------------------------------

--
-- Table structure for table `investment_returns`
--

CREATE TABLE `investment_returns` (
  `schedule_id` int(11) NOT NULL,
  `investment_id` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `due_date` date DEFAULT NULL,
  `payment_status` int(11) NOT NULL,
  `percentage` float NOT NULL,
  `amount_due` decimal(12,2) NOT NULL,
  `amount_paid` decimal(12,2) NOT NULL,
  `store` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investment_returns`
--

INSERT INTO `investment_returns` (`schedule_id`, `investment_id`, `customer`, `due_date`, `payment_status`, `percentage`, `amount_due`, `amount_paid`, `store`, `posted_by`, `post_date`) VALUES
(82, 22, 16, '2027-01-12', 0, 30, 600.00, 0.00, 1, 1, '2026-01-20 12:08:26'),
(83, 22, 16, '2027-07-12', 0, 15, 300.00, 0.00, 1, 1, '2026-01-20 12:08:26'),
(84, 22, 16, '2028-01-12', 0, 15, 300.00, 0.00, 1, 1, '2026-01-20 12:08:26'),
(85, 22, 16, '2028-07-12', 0, 15, 300.00, 0.00, 1, 1, '2026-01-20 12:08:26'),
(86, 22, 16, '2029-01-12', 0, 15, 300.00, 0.00, 1, 1, '2026-01-20 12:08:26'),
(92, 23, 15, '2026-12-18', 0, 30, 300.00, 0.00, 1, 1, '2026-01-20 12:42:10'),
(93, 23, 15, '2027-06-18', 0, 15, 150.00, 0.00, 1, 1, '2026-01-20 12:42:10'),
(94, 23, 15, '2027-12-18', 0, 15, 150.00, 0.00, 1, 1, '2026-01-20 12:42:10'),
(95, 23, 15, '2028-06-18', 0, 15, 150.00, 0.00, 1, 1, '2026-01-20 12:42:10'),
(96, 23, 15, '2028-12-18', 0, 15, 150.00, 0.00, 1, 1, '2026-01-20 12:42:10');

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
  `department` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `issued_by` int(11) NOT NULL,
  `date_issued` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `issue_items`
--

INSERT INTO `issue_items` (`issue_id`, `from_store`, `item`, `quantity`, `issue_status`, `invoice`, `cost_price`, `department`, `posted_by`, `post_date`, `issued_by`, `date_issued`) VALUES
(25, 1, 30, 3, 2, 'ISS226120012601351', 2500, 3, 1, '2026-01-20 13:36:02', 1, '2026-01-20 13:39:26'),
(26, 1, 29, 2, 1, 'ISS226120012601351', 10000, 3, 1, '2026-01-20 13:36:11', 0, NULL);

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
(1, '12', 37, 'PINEAPPLE', 'Crop', 0, 0, 0, 0, 0, 0, 10, '', 0, '2025-09-18 12:23:46'),
(18, '1', 40, 'NPK FERTILIZER', 'Farm Input', 0, 0, 0, 0, 0, 0, 5, '', 0, '2025-09-18 15:04:22'),
(20, '1', 40, 'SUCKER', 'Farm Input', 0, 0, 0, 0, 0, 0, 10, '', 0, '2025-11-10 07:01:16'),
(21, '12', 37, 'LEAVES', 'Product', 0, 0, 0, 0, 0, 0, 10, '', 0, '2025-11-19 07:49:08'),
(22, '12', 37, 'CONCENTRATE', 'Product', 0, 0, 0, 0, 0, 0, 10, '', 0, '2025-11-24 12:27:25'),
(23, '12', 37, 'PINEAPPLE CROWN', 'Product', 0, 0, 0, 0, 0, 0, 10, '', 0, '2025-12-17 18:21:07'),
(24, '12', 37, 'PINEAPPLE PEEL', 'Product', 0, 0, 0, 0, 0, 0, 10, '', 0, '2025-12-17 18:21:17'),
(25, '12', 37, 'BRIQUETTE', 'Product', 0, 0, 0, 0, 0, 0, 10, '', 0, '2025-12-18 21:09:19'),
(29, '13', 32, 'HAND GLOVES', 'Consumable', 0, 0, 0, 0, 0, 0, 10, '', 0, '2026-01-20 13:10:03'),
(30, '13', 32, 'A4 PAPER', 'Consumable', 0, 0, 0, 0, 0, 0, 10, '', 0, '2026-01-20 13:10:15');

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
(84, 4, 6, 13, 'FARM INPUTS', 40601384),
(85, 4, 6, 13, 'PRODUCTION LABOUR', 40601385),
(86, 4, 6, 12, 'GENERAL FIELD MAINTENANCE', 40601286),
(88, 4, 6, 12, 'DELIVERY &AMP; LOGISTICS EXPENSE', 40601388),
(89, 4, 6, 13, 'PRODUCTION INPUTS', 40601389),
(103, 2, 3, 7, 'DAVIDORLAH FARMS', 20307103),
(105, 4, 6, 12, 'SALARY AND WAGES', 406012105),
(106, 1, 1, 4, 'KELLY IKPEFUA', 10104106),
(107, 1, 1, 4, 'MERCY', 10104107),
(108, 2, 3, 7, 'ABC LIMITED', 20307108);

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
(14, 'Customer Menu'),
(15, 'Concentrate'),
(16, 'Briquette');

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
(184, 15, 'Your Concentrate Investment Has Been Created', 'Dear KELLY IKPEFUA, your Concentrate Production Investment has been successfully created. Please proceed with the investment payment to activate your 3-year contract. You will earn 30% after the first 12 months and 15% every 6 months thereafter.', 0, '2026-01-19 11:52:54'),
(185, 15, 'Your Concentrate Investment Has Been Created', 'Dear KELLY IKPEFUA, your Concentrate Production Investment has been successfully created. Please proceed with the investment payment to activate your 3-year contract. You will earn 30% after the first 12 months and 15% every 6 months thereafter.', 0, '2026-01-19 12:22:38'),
(186, 15, 'Your Concentrate Investment Has Been Created', 'Dear KELLY IKPEFUA, your Concentrate Production Investment has been successfully created. Please proceed with the investment payment to activate your 3-year contract. You will earn 30% after the first 12 months and 15% every 6 months thereafter.', 0, '2026-01-20 12:02:30'),
(187, 16, 'Your Concentrate Investment Has Been Created', 'Dear MERCY, your Concentrate Production Investment has been successfully created. Please proceed with the investment payment to activate your 3-year contract. You will earn 30% after the first 12 months and 15% every 6 months thereafter.', 0, '2026-01-20 12:06:38'),
(188, 16, 'Your Concentrate Investment Has Been Created', 'Dear MERCY, your Concentrate Production Investment has been successfully created. Please proceed with the investment payment to activate your 3-year contract. You will earn 30% after the first 12 months and 15% every 6 months thereafter.', 0, '2026-01-20 12:08:26'),
(189, 15, 'Your Concentrate Investment Has Been Created', 'Dear KELLY IKPEFUA, your Concentrate Production Investment has been successfully created. Please proceed with the investment payment to activate your 3-year contract. You will earn 30% after the first 12 months and 15% every 6 months thereafter.', 0, '2026-01-20 12:09:07'),
(190, 15, 'Concentrate Investment Payment Update', 'Dear KELLY IKPEFUA, your investment payment has been received. Total paid: ₦1,000.00 | Remaining: ₦1,000.00', 0, '2026-01-20 12:27:52'),
(191, 15, 'Investment Restructured & Returns Updated', 'Your investment was restructured due to incomplete payment within 30 days. A new returns schedule has been generated based on your actual amount paid.', 0, '2026-01-20 12:42:10'),
(192, 15, 'Your Field Purchase Contract is Active', 'Dear KELLY IKPEFUA, your field (OBITUGBO LAND - 4 Hectares) located at obitugbo has been successfully assigned for purchase. Once installments are completed, you will start receiving annual returns of ₦750,000.00 (25%) for 3 year(s).', 0, '2026-01-20 16:22:37'),
(193, 15, 'Field Purchase Payment Update', 'Dear KELLY IKPEFUA, your payment for OBITUGBO LAND (50.00 plot (25000 m²)) has been received. Total paid so far: ₦1,000,000.00, remaining balance: ₦2,000,000.00. Once fully paid, your annual rent of ₦750,000.00 will begin.', 0, '2026-01-21 05:59:27'),
(194, 15, 'Field purchase Payment Confirmation', 'Dear KELLY IKPEFUA,\r\n            We confirm the receipt of your payment of ₦2,000,000.00 on 21st January 2026, 05:59am towards your Field purchase.\r\n            Transaction ID: LP2101260558112011168\r\n            Your account has been updated accordingly. Thank you for your commitment.\r\n            \r\n            If you have any questions or need a receipt, feel free to contact us\r\n\r\n            Warm regards,\r\n            Davidorlah Nigeria Limited\r\n            Customer Support', 0, '2026-01-21 05:59:27'),
(195, 15, 'Field Purchase Completed - Annual Rent Activated', 'Dear KELLY IKPEFUA, your field (OBITUGBO LAND - 4.00 plot(2000 m²)) has been fully paid. Your annual rent of ₦750,000.00 will commence now for the next 3 year(s).', 0, '2026-01-21 06:11:50'),
(196, 15, 'Field purchase Payment Confirmation', 'Dear KELLY IKPEFUA,\r\n            We confirm the receipt of your payment of ₦1,000,000.00 on 21st January 2026, 06:11am towards your Field purchase.\r\n            Transaction ID: LP2101260611223311167\r\n            Your account has been updated accordingly. Thank you for your commitment.\r\n            \r\n            If you have any questions or need a receipt, feel free to contact us\r\n\r\n            Warm regards,\r\n            Davidorlah Nigeria Limited\r\n            Customer Support', 0, '2026-01-21 06:11:50'),
(197, 15, 'Field Documentation payment Completed', 'Dear KELLY IKPEFUA, Documentation for your field (OBITUGBO LAND - 50.00 plot (25000m²)) has been fully paid. ', 0, '2026-01-21 06:14:36'),
(198, 15, 'Field Documentation Payment Confirmation', 'Dear KELLY IKPEFUA,\r\n            We confirm the receipt of your payment of ₦350,000.00 on 21st January 2026, 06:14am towards your Field documentaion.\r\n            Transaction ID: LP210126061400002118\r\n            Your account has been updated accordingly. Thank you for your commitment.\r\n            \r\n            If you have any questions or need a receipt, feel free to contact us\r\n\r\n            Warm regards,\r\n            Davidorlah Nigeria Limited\r\n            Customer Support', 0, '2026-01-21 06:14:36'),
(199, 15, 'Rent Payment Confirmation', 'Dear KELLY IKPEFUA, Your annual rent return of ₦750000.00 has been paid for your field (OBITUGBO LAND).\r\n\r\nPayment Date: 2026-01-21 06:47:47\r\n\r\nThank you for investing with Davidorlah Nigeria Ltd', 1, '2026-01-21 06:47:47'),
(200, 15, 'Your Field Purchase Contract is Active', 'Dear KELLY IKPEFUA, your field (OBITUGBO LAND - 4 Plot) located at obitugbo has been successfully assigned for purchase. Once installments are completed, you will start receiving annual returns of ₦750,000.00 (25%) for 5 year(s).', 0, '2026-01-21 11:00:06'),
(201, 15, 'Your Field Purchase Contract is Active', 'Dear KELLY IKPEFUA, your field (OBITUGBO LAND - 7 Plot) located at obitugbo has been successfully assigned for purchase. Once installments are completed, you will start receiving annual returns of ₦1,000,000.00 (25%) for 3 year(s).', 0, '2026-01-21 11:25:21'),
(202, 15, 'Your Field Purchase Contract is Active', 'Dear KELLY IKPEFUA, your field (OBITUGBO LAND - 2 Plot) located at obitugbo has been successfully assigned for purchase. Once installments are completed, you will start receiving annual returns of ₦1,250,000.00 (25%) for 3 year(s).', 0, '2026-01-21 11:28:42'),
(203, 15, 'Your Field Purchase Contract is Active', 'Dear KELLY IKPEFUA, your field (OBITUGBO LAND - 4 Plot) located at obitugbo has been successfully assigned for purchase. Once installments are completed, you will start receiving annual returns of ₦1,080,000.00 (36%) for 3 year(s).', 0, '2026-01-21 15:59:35');

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
  `store` int(11) NOT NULL,
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
-- Table structure for table `payment_evidence`
--

CREATE TABLE `payment_evidence` (
  `payment_id` int(11) NOT NULL,
  `assigned_id` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `field` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `evidence` varchar(1024) NOT NULL,
  `remark` text NOT NULL,
  `payment_status` int(11) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `upload_date` datetime DEFAULT NULL
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
  `late_days` float NOT NULL,
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
  `daily_pay` decimal(12,2) NOT NULL,
  `net_after_tax` decimal(12,2) NOT NULL,
  `net_pay` decimal(12,2) NOT NULL,
  `payroll_status` int(11) NOT NULL,
  `prepared_by` int(11) NOT NULL,
  `date_generated` datetime DEFAULT NULL,
  `approved` datetime DEFAULT NULL,
  `approved_by` int(11) NOT NULL,
  `disbursed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`payroll_id`, `staff`, `store`, `payroll_date`, `working_days`, `days_at_work`, `leave_days`, `suspension_days`, `absent_days`, `late_days`, `basic_salary`, `housing`, `medical`, `transport`, `utility`, `other_allowance`, `gross_pay`, `tax`, `tax_rate`, `taxable_income`, `pension`, `lateness_penalty`, `absence_penalty`, `loan_repayment`, `other_deductions`, `daily_pay`, `net_after_tax`, `net_pay`, `payroll_status`, `prepared_by`, `date_generated`, `approved`, `approved_by`, `disbursed`) VALUES
(33, 99910, 1, '2026-01-20', 22, 1, 0, 0, 21, 1, 200000.00, 20000.00, 20000.00, 10000.00, 0.00, 0.00, 250000.00, 34500.00, 15.00, 2760000.00, 18400.00, 0.00, 188140.89, 0.00, 3000.00, 8959.09, 197100.00, 5959.11, 0, 1, '2026-01-20 11:54:17', NULL, 0, NULL);

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
(30, 99910, 32, 1, 230000, 18400.00, 23000.00, 41400.00, '2026-01-20', 1, '2026-01-20 11:45:20'),
(31, 99910, 33, 1, 230000, 18400.00, 23000.00, 41400.00, '2026-01-20', 1, '2026-01-20 11:54:17');

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE `prices` (
  `price_id` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `cost` decimal(12,2) NOT NULL,
  `sales_price` decimal(12,2) NOT NULL,
  `other_price` decimal(12,2) NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_at` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prices`
--

INSERT INTO `prices` (`price_id`, `item`, `store`, `cost`, `sales_price`, `other_price`, `added_by`, `added_at`, `updated_by`, `updated_at`) VALUES
(13, 29, 1, 10000.00, 0.00, 0.00, 1, '2026-01-20 13:14:56', 0, NULL),
(14, 30, 1, 2500.00, 0.00, 0.00, 1, '2026-01-20 13:18:21', 1, '2026-01-20 13:20:33');

-- --------------------------------------------------------

--
-- Table structure for table `principal_returns`
--

CREATE TABLE `principal_returns` (
  `payment_id` int(11) NOT NULL,
  `investment` int(11) NOT NULL,
  `client` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `value_in_naira` decimal(12,2) NOT NULL,
  `payment_mode` varchar(50) NOT NULL,
  `bank` int(11) NOT NULL,
  `trx_date` date DEFAULT NULL,
  `trx_number` varchar(50) NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `currency` varchar(50) NOT NULL,
  `store` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production`
--
-- Error reading structure for table agric.production: #1932 - Table &#039;agric.production&#039; doesn&#039;t exist in engine
-- Error reading data for table agric.production: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `agric`.`production`&#039; at line 1

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
  `quantity` decimal(12,2) NOT NULL,
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
(31, 1, '7867tyu', 29, 10000.00, 0, 5, 30.00, 0.00, '', '0000-00-00', 0, NULL, 1, '2026-01-20 13:14:56'),
(32, 1, '7867tyu', 30, 3000.00, 0, 5, 5.00, 0.00, '', '0000-00-00', 0, NULL, 1, '2026-01-20 13:18:21'),
(33, 1, '8yuih', 30, 2500.00, 0, 5, 2.00, 500.00, '', '0000-00-00', 0, NULL, 1, '2026-01-20 13:20:33');

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
  `trx_date` date DEFAULT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `trx_number` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rent_payments`
--

INSERT INTO `rent_payments` (`payment_id`, `customer`, `store`, `loan`, `schedule`, `amount`, `interest`, `processing_fee`, `penalty`, `payment_mode`, `details`, `invoice`, `bank`, `trx_date`, `posted_by`, `post_date`, `trx_number`) VALUES
(125, 15, 1, 24, 179, 1000000, 0, 0, 0.00, '', '', '', 0, '2024-01-21', 1, '2026-01-21 11:25:21', ''),
(126, 15, 1, 24, 180, 1000000, 0, 0, 0.00, '', '', '', 0, '2025-01-21', 1, '2026-01-21 11:25:21', ''),
(127, 15, 1, 25, 182, 1250000, 0, 0, 0.00, '', '', 'TR985210126112842', 0, '2025-12-11', 1, '2026-01-21 11:28:42', 'TR985210126112842');

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
(174, 23, 18, 15, 750000.00, 0.00, 0.00, 1, '2024-12-21', 1, '2026-01-21 11:00:06', 1),
(175, 23, 18, 15, 750000.00, 0.00, 0.00, 1, '2025-12-21', 1, '2026-01-21 11:00:06', 1),
(176, 23, 18, 15, 750000.00, 0.00, 0.00, 1, '2026-12-21', 0, '2026-01-21 11:00:06', 1),
(177, 23, 18, 15, 750000.00, 0.00, 0.00, 1, '2027-12-21', 0, '2026-01-21 11:00:06', 1),
(178, 23, 18, 15, 750000.00, 0.00, 0.00, 1, '2028-12-21', 0, '2026-01-21 11:00:06', 1),
(179, 24, 18, 15, 1000000.00, 0.00, 0.00, 1, '2024-01-21', 1, '2026-01-21 11:25:21', 1),
(180, 24, 18, 15, 1000000.00, 0.00, 0.00, 1, '2025-01-21', 1, '2026-01-21 11:25:21', 1),
(181, 24, 18, 15, 1000000.00, 0.00, 0.00, 1, '2026-01-21', 0, '2026-01-21 11:25:21', 1),
(182, 25, 18, 15, 1250000.00, 0.00, 0.00, 1, '2025-12-11', 1, '2026-01-21 11:28:42', 1),
(183, 25, 18, 15, 1250000.00, 0.00, 0.00, 1, '2026-12-11', 0, '2026-01-21 11:28:42', 1),
(184, 25, 18, 15, 1250000.00, 0.00, 0.00, 1, '2027-12-11', 0, '2026-01-21 11:28:42', 1),
(185, 26, 18, 15, 1080000.00, 0.00, 0.00, 1, '2027-01-20', 0, '2026-01-21 15:59:35', 1),
(186, 26, 18, 15, 1080000.00, 0.00, 0.00, 1, '2028-01-20', 0, '2026-01-21 15:59:35', 1),
(187, 26, 18, 15, 1080000.00, 0.00, 0.00, 1, '2029-01-20', 0, '2026-01-21 15:59:35', 1);

-- --------------------------------------------------------

--
-- Table structure for table `return_payments`
--

CREATE TABLE `return_payments` (
  `payment_id` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `investment` int(11) NOT NULL,
  `schedule` int(11) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `interest` decimal(10,0) NOT NULL,
  `processing_fee` decimal(10,0) NOT NULL,
  `penalty` decimal(10,2) NOT NULL,
  `payment_mode` varchar(50) NOT NULL,
  `currency` varchar(50) NOT NULL,
  `amount_in_dollar` decimal(12,2) NOT NULL,
  `details` text NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `bank` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `trx_number` varchar(50) NOT NULL,
  `trx_date` date DEFAULT NULL
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

-- --------------------------------------------------------

--
-- Table structure for table `salary_disbursement`
--

CREATE TABLE `salary_disbursement` (
  `salary_id` int(11) NOT NULL,
  `payroll_date` date NOT NULL,
  `store` int(11) NOT NULL,
  `payment_mode` varchar(50) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `details` varchar(255) NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `trx_number` varchar(50) NOT NULL,
  `bank` int(11) NOT NULL,
  `trans_date` date NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
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
(10, 1, 99910, 200000.00, 20000.00, 10000.00, 0.00, 20000.00, 0.00, 250000.00, 1, '2026-01-20 11:43:17', NULL, 0);

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
  `quantity` decimal(10,2) NOT NULL,
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
  `spouse` varchar(255) NOT NULL,
  `spouse_phone` varchar(50) NOT NULL,
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

INSERT INTO `staffs` (`staff_id`, `staff_number`, `last_name`, `other_names`, `gender`, `title`, `dob`, `marital_status`, `religion`, `phone`, `home_address`, `email_address`, `nok`, `nok_relation`, `nok_phone`, `spouse`, `spouse_phone`, `employed`, `department`, `staff_group`, `staff_category`, `designation`, `discipline`, `bank`, `account_num`, `pension`, `pension_num`, `photo`, `signature`, `staff_status`, `store`, `user_id`, `resigned`, `resigned_by`, `reg_date`, `posted_by`) VALUES
(99910, '009', 'IKPEFUA', 'KELLY', 'Male', 'Mr.', '1994-08-08', 'Divorced', 'Muslim', '07068897068', 'Ben', 'kellyikpefua@gmail.com', 'PAL', 'BRO', '09012345678', '', '', '2026-01-01', 3, 'core staff', 'junior staff', 16, 14, 0, 0, '', '', 'user.png', '', 0, 1, 34, NULL, 0, '2026-01-20 11:42:32', 1);

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
(3, 'ICT'),
(4, 'SECURITY');

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
(1, 1, 'Davidorlah Farm', 'Lekki', '', '2025-09-18 19:54:43'),
(10, 1, 'Davidorlah Concentrate', 'yutyrtr', '8979', '2025-11-19 14:25:23'),
(11, 1, 'Davidorlah Briquette', 'Lagos', '0', '2025-12-18 15:19:11');

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
(75, 1, 'Add Location', 'add_store', 1),
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
(101, 3, 'Pending Item Requests', 'pending_issued', 0),
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
(145, 10, 'Add New Asset', 'add_asset', 0),
(146, 10, 'Asset Register', 'asset_register', 0),
(147, 10, 'Allocate Asset', 'allocate_asset', 0),
(148, 10, 'Dispose Asset', 'dispose_asset', 0),
(149, 10, 'View Disposed Assets', 'disposed_assets', 0),
(150, 10, 'Delete Asset', 'delete_asset', 0),
(151, 4, 'Post Fixed Asset', 'post_fixed_asset', 0),
(152, 4, 'Post Depreciation', 'post_depreciation', 0),
(153, 4, 'Post Other Transactions', 'post_other_trx', 0),
(154, 11, 'Farm Lands', 'farm_fields', 0),
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
(201, 4, 'FIeld Payments Due', 'payments_due', 0),
(203, 14, 'Upload Receipt', 'upload_payment', 0),
(204, 14, 'Receipt Upload Reports', 'receipt_upload_report', 0),
(205, 4, 'Approve Field Payment', 'approve_customer_payment', 0),
(206, 14, 'My Field Payments', 'customer_field_payments', 0),
(207, 4, 'Documentation Fee', 'documentation_fee', 0),
(208, 6, 'Field Payments', 'field_payment_report', 0),
(209, 6, 'Documentation Payments', 'documentation_payments', 0),
(210, 14, 'My Documentation Payments', 'customer_documentaion_payments', 0),
(211, 15, 'Production', 'concentrate_production', 0),
(212, 15, 'Start Investment', 'concentrate_investment', 0),
(213, 1, 'Exchange Rate', 'exchange_rate', 0),
(214, 4, 'Post Investment', 'post_investment_payment', 0),
(215, 14, 'Concentrate Investments', 'my_investments', 0),
(216, 14, 'Investment Deposits', 'investment_deposits', 0),
(217, 6, 'Investment Payments', 'investment_payment_report', 0),
(218, 15, 'View Investments', 'view_concentrate_investments', 0),
(219, 4, 'Pay Returns', 'pay_investment_returns', 0),
(220, 6, 'Concentrate Returns', 'returns_payment_report', 0),
(221, 6, 'Rent Payments', 'rent_payment_report', 0),
(222, 4, 'Return Principal', 'return_principal', 0),
(223, 15, 'Production Report', 'concentrate_report', 0),
(224, 15, 'Make Sales', 'wholesale', 0),
(225, 15, 'Manage Prices', 'concentrate_price', 0),
(226, 16, 'Production', 'briquette_production', 0),
(227, 16, 'Manage Prices', 'briquette_price', 0),
(228, 16, 'Production Report', 'briquette_production_report', 0),
(229, 16, 'Make Sales', 'briquette_sales', 0),
(230, 12, 'Apply For Leave', 'apply_staff_leave', 0),
(231, 13, 'Upload Documents', 'client_document_upload', 0),
(232, 13, 'View Uploads', 'view_uploads', 0),
(233, 4, 'Returns Due', 'investment_returns_due', 0),
(234, 14, 'Investment Returns', 'investment_returns', 0),
(235, 15, 'Investments Due', 'investment_due', 0),
(236, 6, 'Principal Returned', 'principal_returned', 0),
(237, 14, 'View Documents', 'view_customer_docx', 0),
(238, 14, 'Rent Received', 'rent_received', 0),
(239, 5, 'Flagged Attendance', 'flagged_attendance', 0),
(240, 4, 'Disburse Salary', 'disburse_salary', 0),
(241, 6, 'Salary Disbursement', 'salary_disbursement', 0),
(242, 3, 'Item Requisition', 'item_requisition', 0),
(243, 15, 'Onboard Existing Cients', 'onboard_conc_investors', 0),
(244, 11, 'Customer Lands', 'customer_lands', 0),
(245, 11, 'Onboard Existing Clients', 'onboard_land_owners', 0),
(246, 5, 'Onboarded Clients', 'client_created', 0),
(247, 5, 'Lands Assigned', 'land_assigned_report', 0),
(248, 5, 'Staff Attendance Report', 'staff_attendance_report', 0);

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
(1, 'DEFAULT COMPANY TAX', 0.00, 800000.00, 0.00, 1, '2025-10-24 08:13:21', 1, '2026-01-07 11:06:01'),
(2, 'PAYEE TAX', 800001.00, 3000000.00, 15.00, 1, '2026-01-07 11:06:50', 0, NULL),
(3, 'PAYEE UPPER', 3000001.00, 10000000.00, 20.00, 1, '2026-01-16 07:18:28', 0, NULL);

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
(372, 1, 1, 2, 1010228, 0.000, 0.000, 'TR415200126122752', 'Investment payment', 0, '2026-01-20', 1, '2026-01-20 12:27:52', 1),
(373, 1, 1, 4, 10104106, 0.000, 0.000, 'TR415200126122752', 'Investment Payment', 0, '2026-01-20', 1, '2026-01-20 12:27:52', 1),
(374, 1, 1, 2, 1010228, 2000000.000, 0.000, 'TR241210126055927', 'Field Purchase payment', 0, '2026-01-21', 1, '2026-01-21 05:59:27', 1),
(375, 1, 1, 4, 10104106, 0.000, 2000000.000, 'TR241210126055927', 'Field Purchase Payment', 0, '2026-01-21', 1, '2026-01-21 05:59:27', 1),
(376, 1, 1, 2, 1010228, 1000000.000, 0.000, 'TR923210126061150', 'Field Purchase payment', 0, '2026-01-21', 1, '2026-01-21 06:11:50', 1),
(377, 1, 1, 4, 10104106, 0.000, 1000000.000, 'TR923210126061150', 'Field Purchase Payment', 0, '2026-01-21', 1, '2026-01-21 06:11:50', 1),
(378, 1, 1, 2, 1010228, 350000.000, 0.000, 'TR526210126061436', 'Field Documentation payment', 0, '2026-01-21', 1, '2026-01-21 06:14:36', 1),
(379, 1, 1, 4, 10104106, 0.000, 350000.000, 'TR526210126061436', 'Field Documentation Payment', 0, '2026-01-21', 1, '2026-01-21 06:14:36', 1),
(380, 1, 1, 4, 10104106, 750000.000, 0.000, 'TR621210126064747', 'Rent payment', 0, '2026-01-21', 1, '2026-01-21 06:47:47', 1),
(381, 1, 1, 2, 1010228, 0.000, 750000.000, 'TR621210126064747', 'Rent payment', 0, '2026-01-21', 1, '2026-01-21 06:47:47', 1);

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
  `staff_id` int(11) NOT NULL,
  `reg_date` datetime DEFAULT NULL,
  `posted_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `username`, `user_role`, `user_password`, `status`, `store`, `staff_id`, `reg_date`, `posted_by`) VALUES
(1, 'Administrator', 'Sysadmin', 'Admin', '$2y$10$08jBS1q4/vn/hT7dfRc2Peqh.y.jQRiWanS/VKxMbRpFn5XEX45/y', 0, 1, 0, NULL, 1),
(2, 'Admin', 'Admin', 'Admin', '123', 0, 1, 0, '2026-01-19 06:21:55', 1),
(34, 'IKPEFUA KELLY', '07068897068', 'Staff', '$2y$10$oNo3yMzXRwFCNSfNgoMc7uSopic/rXGK8JVuJ4HSKNrhRAlOOOi6m', 0, 1, 99910, '2026-01-20 11:42:32', 1);

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
(4, 'DAVIDORLAH FARMS', 'Jjhk', '908797897', '', '', 0, 20307103, 103, '2025-12-17 18:18:28', 1),
(5, 'ABC LIMITED', 'John', '097867', '', 'jhgh', 0, 20307108, 108, '2026-01-20 13:12:21', 1);

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
(17, '7867tyu', 5, 315000.00, 0.00, '', 1, '2026-01-20 13:18:25', 1),
(18, '8yuih', 5, 5000.00, 500.00, '', 1, '2026-01-20 13:20:41', 1);

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
-- Indexes for table `attendance_flagging`
--
ALTER TABLE `attendance_flagging`
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
-- Indexes for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`beneficiary_id`);

--
-- Indexes for table `briquette_production`
--
ALTER TABLE `briquette_production`
  ADD PRIMARY KEY (`production_id`);

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
-- Indexes for table `concentrate_production`
--
ALTER TABLE `concentrate_production`
  ADD PRIMARY KEY (`production_id`);

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
-- Indexes for table `documentation_fees`
--
ALTER TABLE `documentation_fees`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `document_uploads`
--
ALTER TABLE `document_uploads`
  ADD PRIMARY KEY (`document_id`);

--
-- Indexes for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  ADD PRIMARY KEY (`exchange_id`);

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
-- Indexes for table `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`investment_id`);

--
-- Indexes for table `investment_payments`
--
ALTER TABLE `investment_payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `investment_returns`
--
ALTER TABLE `investment_returns`
  ADD PRIMARY KEY (`schedule_id`);

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
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `payment_evidence`
--
ALTER TABLE `payment_evidence`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`payroll_id`);

--
-- Indexes for table `pensions`
--
ALTER TABLE `pensions`
  ADD PRIMARY KEY (`pension_id`);

--
-- Indexes for table `prices`
--
ALTER TABLE `prices`
  ADD PRIMARY KEY (`price_id`);

--
-- Indexes for table `principal_returns`
--
ALTER TABLE `principal_returns`
  ADD PRIMARY KEY (`payment_id`);

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
-- Indexes for table `return_payments`
--
ALTER TABLE `return_payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `rights`
--
ALTER TABLE `rights`
  ADD PRIMARY KEY (`right_id`);

--
-- Indexes for table `salary_disbursement`
--
ALTER TABLE `salary_disbursement`
  ADD PRIMARY KEY (`salary_id`);

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
  MODIFY `accept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `asset_locations`
--
ALTER TABLE `asset_locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `asset_postings`
--
ALTER TABLE `asset_postings`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `assigned_fields`
--
ALTER TABLE `assigned_fields`
  MODIFY `assigned_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `attendance_flagging`
--
ALTER TABLE `attendance_flagging`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `bank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `beneficiary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `briquette_production`
--
ALTER TABLE `briquette_production`
  MODIFY `production_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cash_flows`
--
ALTER TABLE `cash_flows`
  MODIFY `fow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

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
-- AUTO_INCREMENT for table `concentrate_production`
--
ALTER TABLE `concentrate_production`
  MODIFY `production_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cost_of_sales`
--
ALTER TABLE `cost_of_sales`
  MODIFY `cost_of_sales_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `customer_trail`
--
ALTER TABLE `customer_trail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `cycle_changes`
--
ALTER TABLE `cycle_changes`
  MODIFY `change_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `debtors`
--
ALTER TABLE `debtors`
  MODIFY `debtor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `deposit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `depreciation`
--
ALTER TABLE `depreciation`
  MODIFY `depreciation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `designation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
-- AUTO_INCREMENT for table `documentation_fees`
--
ALTER TABLE `documentation_fees`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `document_uploads`
--
ALTER TABLE `document_uploads`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  MODIFY `exchange_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `expense_heads`
--
ALTER TABLE `expense_heads`
  MODIFY `exp_head_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `fields`
--
ALTER TABLE `fields`
  MODIFY `field_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `field_payments`
--
ALTER TABLE `field_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `field_payment_schedule`
--
ALTER TABLE `field_payment_schedule`
  MODIFY `repayment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

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
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `investment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `investment_payments`
--
ALTER TABLE `investment_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `investment_returns`
--
ALTER TABLE `investment_returns`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `issue_items`
--
ALTER TABLE `issue_items`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `labour_payments`
--
ALTER TABLE `labour_payments`
  MODIFY `labour_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `leaves_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ledgers`
--
ALTER TABLE `ledgers`
  MODIFY `ledger_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `multiple_payments`
--
ALTER TABLE `multiple_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

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
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payment_evidence`
--
ALTER TABLE `payment_evidence`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `payroll_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `pensions`
--
ALTER TABLE `pensions`
  MODIFY `pension_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `prices`
--
ALTER TABLE `prices`
  MODIFY `price_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `principal_returns`
--
ALTER TABLE `principal_returns`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pruning`
--
ALTER TABLE `pruning`
  MODIFY `sucker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `rent_schedule`
--
ALTER TABLE `rent_schedule`
  MODIFY `repayment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT for table `return_payments`
--
ALTER TABLE `return_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rights`
--
ALTER TABLE `rights`
  MODIFY `right_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `salary_disbursement`
--
ALTER TABLE `salary_disbursement`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `salary_structure`
--
ALTER TABLE `salary_structure`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sales_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `sales_returns`
--
ALTER TABLE `sales_returns`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staffs`
--
ALTER TABLE `staffs`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99911;

--
-- AUTO_INCREMENT for table `staff_departments`
--
ALTER TABLE `staff_departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  MODIFY `adjust_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `store_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sub_menus`
--
ALTER TABLE `sub_menus`
  MODIFY `sub_menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;

--
-- AUTO_INCREMENT for table `sucker_removal`
--
ALTER TABLE `sucker_removal`
  MODIFY `sucker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `suspensions`
--
ALTER TABLE `suspensions`
  MODIFY `suspension_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `tax_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=382;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `transfer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `waybills`
--
ALTER TABLE `waybills`
  MODIFY `waybill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
