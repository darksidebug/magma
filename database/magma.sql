-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2020 at 01:24 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `magma`
--

CREATE DATABASE IF NOT EXISTS `magma`;

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `id` int(11) NOT NULL,
  `re` varchar(150) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `date` varchar(50) NOT NULL,
  `content` longtext NOT NULL,
  `chapter` varchar(150) NOT NULL,
  `closing` varchar(150) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `announcement`
--

-- --------------------------------------------------------

--
-- Table structure for table `billings`
--

CREATE TABLE `billings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_call_sign` varchar(50) NOT NULL,
  `bill_month` varchar(50) NOT NULL,
  `bill_amount` decimal(10,0) NOT NULL,
  `bill_status` varchar(50) NOT NULL,
  `date_bill_paid` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `billings`
--


-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_call_sign` varchar(50) NOT NULL,
  `chapter` varchar(150) NOT NULL,
  `date_commented` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_call_sign` varchar(50) NOT NULL,
  `hf_radio` int(11) NOT NULL,
  `vhf_radio` int(11) NOT NULL,
  `uhf_radio` int(11) NOT NULL,
  `aerial_antenna` int(11) NOT NULL,
  `spare_battery` int(11) NOT NULL,
  `generator` int(11) NOT NULL,
  `solar_panel` int(11) NOT NULL,
  `battery` int(11) NOT NULL,
  `mobile_set_up` int(11) NOT NULL,
  `drone` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `equipment`
--


-- --------------------------------------------------------

--
-- Table structure for table `members_siblings_childrens`
--

CREATE TABLE `members_siblings_childrens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_call_sign` varchar(50) NOT NULL,
  `siblings_childrens_name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `members_siblings_childrens`
--


-- --------------------------------------------------------

--
-- Table structure for table `other_info`
--

CREATE TABLE `other_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_call_sign` varchar(50) NOT NULL,
  `emergency_contact_person` varchar(150) NOT NULL,
  `emergency_contact_num` varchar(150) NOT NULL,
  `relation_to_member` varchar(150) NOT NULL,
  `name_of_father` varchar(150) NOT NULL,
  `fathers_occupation` varchar(150) NOT NULL,
  `name_of_mother` varchar(150) NOT NULL,
  `mothers_occupation` varchar(150) NOT NULL,
  `sign_at` varchar(150) NOT NULL,
  `date_register` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `other_info`
--


-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_call_sign` varchar(50) NOT NULL,
  `payment_amount` decimal(10,0) NOT NULL,
  `bill_month` varchar(50) NOT NULL,
  `date_paid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--


-- --------------------------------------------------------

--
-- Table structure for table `personnal_informations`
--

CREATE TABLE `personnal_informations` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `user_call_sign` varchar(50) NOT NULL,
  `chapter` varchar(150) NOT NULL,
  `contact` varchar(13) NOT NULL,
  `present_employment` varchar(250) NOT NULL,
  `home_address` varchar(250) NOT NULL,
  `date_of_birth` varchar(50) NOT NULL,
  `place_of_birth` varchar(150) NOT NULL,
  `citizenship` varchar(50) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `height` varchar(50) DEFAULT NULL,
  `weight` varchar(50) DEFAULT NULL,
  `blood_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `personnal_informations`
--

-- --------------------------------------------------------

--
-- Table structure for table `school_attended`
--

CREATE TABLE `school_attended` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_call_sign` varchar(50) NOT NULL,
  `school` varchar(250) NOT NULL,
  `date_graduate` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `school_attended`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

CREATE TABLE `user_account` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `user_pass` varchar(250) NOT NULL,
  `user_call_sign` varchar(150) NOT NULL,
  `user_type` varchar(10) NOT NULL,
  `chapter` varchar(50) DEFAULT NULL,
  `image_name` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`id`, `user_id`, `username`, `user_pass`, `user_call_sign`, `user_type`, `chapter`, `image_name`) VALUES
(8, 1, 'darksidebug', '$2y$10$RYdJjtfFM2EGd.khrfx5/OLRNHxwh2ZhTAcy6rP2hREnzuBM//k.K', 'darksidebug_09', 'Admin', '', 'nhobey.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `billings`
--
ALTER TABLE `billings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members_siblings_childrens`
--
ALTER TABLE `members_siblings_childrens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_info`
--
ALTER TABLE `other_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personnal_informations`
--
ALTER TABLE `personnal_informations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_call_sign` (`user_call_sign`);

--
-- Indexes for table `school_attended`
--
ALTER TABLE `school_attended`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `billings`
--
ALTER TABLE `billings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `members_siblings_childrens`
--
ALTER TABLE `members_siblings_childrens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `other_info`
--
ALTER TABLE `other_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `personnal_informations`
--
ALTER TABLE `personnal_informations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `school_attended`
--
ALTER TABLE `school_attended`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user_account`
--
ALTER TABLE `user_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
