-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2023 at 08:57 AM
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
-- Table structure for table `userrequestlogs`
--

CREATE TABLE `userrequestlogs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tender_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `userrequestlogs`
--

INSERT INTO `userrequestlogs` (`id`, `user_id`, `tender_id`, `created_at`) VALUES
(1, 1, 1, '2023-09-28 18:54:05'),
(2, 1, 1, '2023-09-28 19:09:28'),
(3, 1, 2, '2023-09-28 19:10:15'),
(4, 1, 2, '2023-09-28 19:10:37'),
(5, 1, 4, '2023-09-29 04:25:48'),
(6, 1, 4, '2023-09-29 05:57:06'),
(7, 2, 4, '2023-09-29 06:20:45'),
(8, 3, 5, '2023-09-29 06:45:39'),
(9, 3, 2, '2023-09-29 06:52:37');

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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenders`
--
ALTER TABLE `tenders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tender_id` (`tenderID`);

--
-- Indexes for table `userrequestlogs`
--
ALTER TABLE `userrequestlogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
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
-- AUTO_INCREMENT for table `tenders`
--
ALTER TABLE `tenders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `userrequestlogs`
--
ALTER TABLE `userrequestlogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
