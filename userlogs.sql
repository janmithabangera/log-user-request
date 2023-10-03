-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2023 at 02:47 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `userlogs`
--

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`) VALUES
(1, 'MES'),
(2, 'ARF'),
(3, 'CDS');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `name`) VALUES
(1, 'CE PKT'),
(2, 'CE UDH'),
(3, 'CE JALANDHAR'),
(4, 'CE CHANDIGARH'),
(5, 'CE LEH'),
(6, 'CE NC & CE 31 ZONE'),
(7, 'CE AF UDHAMPUR'),
(8, 'CESC AND CE (AF) NAGPUR'),
(9, 'CE SWC AND CE JAIPUR'),
(10, 'CE BAREILLY'),
(11, 'CE (FY)');

-- --------------------------------------------------------

--
-- Table structure for table `tenders`
--

CREATE TABLE `tenders` (
  `id` int(11) NOT NULL,
  `due_date` date DEFAULT NULL,
  `tenderID` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tenders`
--

INSERT INTO `tenders` (`id`, `due_date`, `tenderID`, `department_id`) VALUES
(1, '2022-09-23', '2022_MES_234876_3', 1),
(2, '2022-04-13', '2022_MES_985674_1', 1),
(3, '2022-11-19', '2022_ARF_985777_1', 2),
(4, '2021-04-07', '2021_ARF_157865_1', 2),
(5, '2022-01-11', '2022_CDS_985777_1', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'janmitha28', 'janmithabangera@gmail.com', '$2y$10$SgXjFyiGgbGonRaetneIQ.V0EK6FzH7W651Dt7q3kVmeMXCFrVLr.', '2023-09-28 19:14:07'),
(2, 'priya', 'priyaraj@gmail.com', '$2y$10$y9hIMBAUHSi5Wdc.GRSX4O2BOycn.kMV3tkqxHXuKSZ.RPum9D7Ya', '2023-09-29 11:50:19'),
(3, 'test_user', 'test@gmail.com', '$2y$10$mOg6igea0P.PHvNWqZh1vOwPeisNgYnIpMEazjhqBXY0t97uKhdbe', '2023-09-29 12:12:19');

-- --------------------------------------------------------

--
-- Table structure for table `user_tender_requests`
--

CREATE TABLE `user_tender_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tender_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(15) NOT NULL,
  `tender_no` varchar(20) DEFAULT NULL,
  `name_of_work` varchar(255) DEFAULT NULL,
  `file_name` varchar(40) DEFAULT NULL,
  `reference_code` varchar(20) DEFAULT NULL,
  `section_id` varchar(20) NOT NULL,
  `sent_at` datetime DEFAULT NULL,
  `edit_user_id` int(11) DEFAULT NULL,
  `allotted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reminder_days` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_tender_requests`
--

INSERT INTO `user_tender_requests` (`id`, `user_id`, `tender_id`, `created_at`, `status`, `tender_no`, `name_of_work`, `file_name`, `reference_code`, `section_id`, `sent_at`, `edit_user_id`, `allotted_at`, `updated_at`, `reminder_days`) VALUES
(1, 1, 4, '2023-10-02 19:33:36', 'Allotted', 'jhxbscb', 'construction', '169044613763.pdf', '2256789', '9', '2023-10-03 06:39:36', 2, '2023-10-03 13:44:58', '2023-10-03 16:09:22', 10),
(2, 1, 2, '2023-10-03 05:48:11', 'Allotted', 'cm1/ui/567/89', 'machine works', '168968479935.pdf', '2256789', '6', '2023-10-03 07:52:23', 3, '2023-10-03 17:16:07', '2023-10-03 17:16:07', 0),
(3, 1, 5, '2023-10-03 05:48:54', 'Sent', 'cm1/ui/567/hu8.kk', '', 'iphone12.jpg', 'promo123', '11', '2023-10-03 17:53:41', NULL, NULL, '2023-10-03 17:53:41', NULL),
(4, 2, 5, '2023-10-03 11:41:27', 'Allotted', 'cm1/ui/567', '', '16896847993776.pdf', '', '6', '2023-10-03 17:12:12', 3, '2023-10-03 17:13:36', '2023-10-03 17:13:36', 0),
(5, 2, 5, '2023-10-03 12:13:58', 'Requested', NULL, '', NULL, '', '', NULL, NULL, NULL, '2023-10-03 18:13:41', NULL),
(6, 2, 2, '2023-10-03 12:37:49', 'Requested', NULL, '', NULL, '', '', NULL, NULL, NULL, '2023-10-03 18:13:52', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenders`
--
ALTER TABLE `tenders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tender_id` (`tenderID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_tender_requests`
--
ALTER TABLE `user_tender_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tenders`
--
ALTER TABLE `tenders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_tender_requests`
--
ALTER TABLE `user_tender_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
