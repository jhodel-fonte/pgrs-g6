-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2025 at 07:10 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unity_service_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `report_type` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `date_reported` timestamp NOT NULL DEFAULT current_timestamp(),
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `name`, `report_type`, `description`, `location`, `latitude`, `longitude`, `status`, `date_reported`, `photo`) VALUES
(1, 'jay', 'Others', 'dasd', '13.83974, 121.20465', '13.839738907394935', '121.2046480178833', 'Pending', '2025-10-07 18:47:03', NULL),
(3, 'asd', 'Fire', 'adasd', 'Cheap PC Shop, J. B. Zuño Street, Poblacion E, Rosario, Batangas, Calabarzon, 4225, Philippines', '13.840600942584565', '121.20529174804689', 'Pending', '2025-10-10 11:20:20', 'uploads/68e8ebf42a552_ce2ed14f-bc38-4cbf-bc3a-04074ae977cf.jpg'),
(4, 'das', 'Fire', 'asdas', 'Namuco, Rosario, Batangas, Calabarzon, 4225, Philippines', '13.839527', '121.204181', 'Pending', '2025-10-10 11:21:39', 'uploads/68e8ec43af3cb_feaab9fc-461f-4aff-a4a5-565034b11407.jpg'),
(5, 'asd', 'Accident', 'asdasd', 'Namuco, Rosario, Batangas, Calabarzon, 4225, Philippines', '13.839527', '121.204181', 'Pending', '2025-10-10 11:24:03', 'uploads/68e8ecd38f1c3_feaab9fc-461f-4aff-a4a5-565034b11407.jpg'),
(6, 'asdsaasdasdas', 'Rescue', 'asdsadcsa', 'Unable to get address', '13.839527', '121.204181', 'Pending', '2025-10-10 11:38:02', 'uploads/68e8f01ab964b_feaab9fc-461f-4aff-a4a5-565034b11407.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `staff_accounts`
--

CREATE TABLE `staff_accounts` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('Admin','Staff') DEFAULT 'Staff',
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_accounts`
--
ALTER TABLE `staff_accounts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `staff_accounts`
--
ALTER TABLE `staff_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
