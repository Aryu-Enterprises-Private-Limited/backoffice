-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 08, 2023 at 08:21 AM
-- Server version: 8.0.34-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `backoffice`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `user_name` varchar(55) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(55) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('1','0') COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `user_name`, `email`, `password`, `status`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$8u8a0ITX.egeoMg4cvZh4unGLFUBao0dDhor4El3LqP/JPvOaJnY6', '1');

-- --------------------------------------------------------

--
-- Table structure for table `client_details`
--

CREATE TABLE `client_details` (
  `id` int NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `address` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `is_deleted` int NOT NULL,
  `status` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_details`
--

INSERT INTO `client_details` (`id`, `first_name`, `last_name`, `email`, `phone`, `address`, `is_deleted`, `status`, `created_at`) VALUES
(1, 'vijay', 'k', 'vj@yopmail.com', '9658748596', 'No.13, First floor, Shivan Kovil North Car Street,\r\nNear Dr.Sundararajan SMS Hospital,\r\nPalayamkottai Market, Tirunelveli – 627002,Tamilnadu, India.', 0, 1, '2023-09-08 06:22:47');

-- --------------------------------------------------------

--
-- Table structure for table `crm`
--

CREATE TABLE `crm` (
  `id` int NOT NULL,
  `lead` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `project_details` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `price` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `document_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL,
  `is_deleted` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department_details`
--

CREATE TABLE `department_details` (
  `id` int NOT NULL,
  `department_name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL,
  `is_deleted` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department_details`
--

INSERT INTO `department_details` (`id`, `department_name`, `status`, `is_deleted`, `created_at`) VALUES
(1, 'operation', 1, 0, '2023-09-04 14:06:31'),
(2, 'housing Keeping', 1, 0, '2023-09-04 15:02:23'),
(3, 'market', 1, 0, '2023-09-04 15:02:40'),
(4, 'finance', 1, 0, '2023-09-04 15:03:10'),
(5, 'development', 1, 0, '2023-09-04 15:03:29');

-- --------------------------------------------------------

--
-- Table structure for table `employee_attendance`
--

CREATE TABLE `employee_attendance` (
  `id` int NOT NULL,
  `employee_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `employee_email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `employee_id` int NOT NULL,
  `att_current_date` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `att_current_time` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `reason` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_details`
--

CREATE TABLE `employee_details` (
  `id` int NOT NULL,
  `first_name` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `role_id` int NOT NULL,
  `department_id` int NOT NULL,
  `dob` date NOT NULL,
  `phone` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `address` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `pin_code` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `city` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `state` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `blood_grp` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `aadhar_no` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `pan_no` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `relationship` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `r_name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `r_phone` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `r_email` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `r_address` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `work_exp` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `resume` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `notes` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `is_deleted` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_role`
--

CREATE TABLE `employee_role` (
  `id` int NOT NULL,
  `role_name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL,
  `is_deleted` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_role`
--

INSERT INTO `employee_role` (`id`, `role_name`, `status`, `is_deleted`, `created_at`) VALUES
(1, 'php Programmer', 1, 0, '2023-09-04 13:28:21'),
(2, 'admin', 1, 0, '2023-09-04 13:32:17');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_details`
--

CREATE TABLE `invoice_details` (
  `id` int NOT NULL,
  `from_name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `to_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `invoice_no` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `invoice_date` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `amount` text COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lms`
--

CREATE TABLE `lms` (
  `id` int NOT NULL,
  `first_name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `address` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `lead_source` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `linked_in` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `twitter` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `facebook` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('1','0') COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `follow_up_alert` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `is_deleted` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int NOT NULL,
  `lms_id` int NOT NULL,
  `note` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_hours`
--

CREATE TABLE `schedule_hours` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL,
  `employee_email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `daily_working_hrs` json NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_details`
--
ALTER TABLE `client_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crm`
--
ALTER TABLE `crm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department_details`
--
ALTER TABLE `department_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_details`
--
ALTER TABLE `employee_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_role`
--
ALTER TABLE `employee_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lms`
--
ALTER TABLE `lms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule_hours`
--
ALTER TABLE `schedule_hours`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `client_details`
--
ALTER TABLE `client_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `crm`
--
ALTER TABLE `crm`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department_details`
--
ALTER TABLE `department_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_details`
--
ALTER TABLE `employee_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_role`
--
ALTER TABLE `employee_role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoice_details`
--
ALTER TABLE `invoice_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lms`
--
ALTER TABLE `lms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule_hours`
--
ALTER TABLE `schedule_hours`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
